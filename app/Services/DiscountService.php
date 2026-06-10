<?php

namespace App\Services;

use App\Models\DiscountCode;
use App\Models\DiscountCodeRedemption;
use App\Models\Porudzbina;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DiscountService
{
    public function normalizeCode(?string $code): string
    {
        return strtoupper(trim((string) $code));
    }

    public function normalizeEmail(?string $email): ?string
    {
        $email = strtolower(trim((string) $email));

        return $email === '' ? null : $email;
    }

    public function cartSubtotal(array $cart): int
    {
        return array_reduce($cart, function (int $total, array $stavka) {
            return $total + ((int) $stavka['kolicina'] * (int) $stavka['cijena']);
        }, 0);
    }

    public function orderSubtotal(Porudzbina $porudzbina): int
    {
        $porudzbina->loadMissing('stavkePorudzbine');

        return (int) $porudzbina->stavkePorudzbine->sum('ukupna_cijena');
    }

    public function findValidCode(string $code, int $subtotal, ?string $email = null): DiscountCode
    {
        $normalizedCode = $this->normalizeCode($code);
        $email = $this->normalizeEmail($email);

        $discountCode = DiscountCode::where('code', $normalizedCode)->first();

        if (!$discountCode) {
            throw ValidationException::withMessages(['code' => __('Kod za popust nije važeći.')]);
        }

        $this->assertValid($discountCode, $subtotal, $email);

        return $discountCode;
    }

    public function assertValid(DiscountCode $discountCode, int $subtotal, ?string $email = null): void
    {
        $email = $this->normalizeEmail($email);
        $now = now();

        if (!$discountCode->is_active) {
            throw ValidationException::withMessages(['code' => __('Kod za popust nije aktivan.')]);
        }

        if ($discountCode->starts_at && $discountCode->starts_at->isFuture()) {
            throw ValidationException::withMessages(['code' => __('Kod za popust još nije aktivan.')]);
        }

        if ($discountCode->expires_at && $discountCode->expires_at->lte($now)) {
            throw ValidationException::withMessages(['code' => __('Kod za popust je istekao.')]);
        }

        if ($discountCode->minimum_order_total !== null && $subtotal < $discountCode->minimum_order_total) {
            throw ValidationException::withMessages(['code' => __('Iznos porudžbine je prenizak za ovaj kod.')]);
        }

        if ($discountCode->max_uses !== null && $discountCode->uses_count >= $discountCode->max_uses) {
            throw ValidationException::withMessages(['code' => __('Kod je već iskorišten maksimalan broj puta.')]);
        }

        if ($email && $discountCode->max_uses_per_email !== null) {
            $usesByEmail = DiscountCodeRedemption::where('discount_code_id', $discountCode->id)
                ->where('email', $email)
                ->count();

            if ($usesByEmail >= $discountCode->max_uses_per_email) {
                throw ValidationException::withMessages(['code' => __('Kod je već iskorišten.')]);
            }
        }
    }

    public function calculateDiscount(DiscountCode $discountCode, int $subtotal): int
    {
        if ($subtotal <= 0) {
            return 0;
        }

        $discount = $discountCode->type === 'percent'
            ? (int) floor($subtotal * ((int) $discountCode->value / 100))
            : (int) $discountCode->value;

        return max(0, min($subtotal, $discount));
    }

    public function totals(?DiscountCode $discountCode, int $subtotal): array
    {
        $discountAmount = $discountCode ? $this->calculateDiscount($discountCode, $subtotal) : 0;

        return [
            'subtotal' => $subtotal,
            'discount_amount' => $discountAmount,
            'total' => max(0, $subtotal - $discountAmount),
        ];
    }

    public function discountSnapshot(DiscountCode $discountCode, int $subtotal): array
    {
        $totals = $this->totals($discountCode, $subtotal);

        return [
            'subtotal' => $totals['subtotal'],
            'discount_code_id' => $discountCode->id,
            'discount_code' => $discountCode->code,
            'discount_type' => $discountCode->type,
            'discount_value' => $discountCode->value,
            'discount_amount' => $totals['discount_amount'],
            'ukupno' => $totals['total'],
        ];
    }

    public function clearSnapshot(): array
    {
        return [
            'discount_code_id' => null,
            'discount_code' => null,
            'discount_type' => null,
            'discount_value' => null,
            'discount_amount' => 0,
        ];
    }

    public function applyToSession(string $code, array $cart, ?string $email = null): array
    {
        $subtotal = $this->cartSubtotal($cart);
        $discountCode = $this->findValidCode($code, $subtotal, $email);
        $snapshot = $this->discountSnapshot($discountCode, $subtotal);

        session()->put('discount', [
            'discount_code_id' => $snapshot['discount_code_id'],
            'discount_code' => $snapshot['discount_code'],
            'discount_type' => $snapshot['discount_type'],
            'discount_value' => $snapshot['discount_value'],
        ]);

        return $snapshot;
    }

    public function removeFromSession(): void
    {
        session()->forget('discount');
    }

    public function recalculateSession(array $cart, ?string $email = null): array
    {
        $subtotal = $this->cartSubtotal($cart);
        $discount = session()->get('discount');

        if (!$discount || empty($discount['discount_code'])) {
            return [
                ...$this->totals(null, $subtotal),
                'discount' => null,
            ];
        }

        try {
            $discountCode = $this->findValidCode($discount['discount_code'], $subtotal, $email);
        } catch (ValidationException $exception) {
            $this->removeFromSession();

            return [
                ...$this->totals(null, $subtotal),
                'discount' => null,
                'discount_removed' => true,
                'message' => $exception->errors()['code'][0] ?? __('Kod za popust više nije važeći.'),
            ];
        }

        $snapshot = $this->discountSnapshot($discountCode, $subtotal);

        return [
            'subtotal' => $snapshot['subtotal'],
            'discount_amount' => $snapshot['discount_amount'],
            'total' => $snapshot['ukupno'],
            'discount' => [
                'discount_code_id' => $snapshot['discount_code_id'],
                'discount_code' => $snapshot['discount_code'],
                'discount_type' => $snapshot['discount_type'],
                'discount_value' => $snapshot['discount_value'],
            ],
        ];
    }

    public function applyToOrder(Porudzbina $porudzbina, string $code, ?string $email = null): Porudzbina
    {
        $subtotal = $this->orderSubtotal($porudzbina);
        $discountCode = $this->findValidCode($code, $subtotal, $email);

        $porudzbina->fill($this->discountSnapshot($discountCode, $subtotal))->save();

        return $porudzbina->refresh();
    }

    public function removeFromOrder(Porudzbina $porudzbina): Porudzbina
    {
        $subtotal = $this->orderSubtotal($porudzbina);

        $porudzbina->fill([
            'subtotal' => $subtotal,
            ...$this->clearSnapshot(),
            'ukupno' => $subtotal,
        ])->save();

        return $porudzbina->refresh();
    }

    public function recalculateOrder(Porudzbina $porudzbina, ?string $email = null): array
    {
        $subtotal = $this->orderSubtotal($porudzbina);

        if (!$porudzbina->discount_code_id || !$porudzbina->discount_code) {
            $porudzbina->fill([
                'subtotal' => $subtotal,
                ...$this->clearSnapshot(),
                'ukupno' => $subtotal,
            ])->save();

            return [
                ...$this->totals(null, $subtotal),
                'discount' => null,
            ];
        }

        $discountCode = DiscountCode::find($porudzbina->discount_code_id);

        try {
            if (!$discountCode) {
                throw ValidationException::withMessages(['code' => __('Kod za popust nije važeći.')]);
            }

            $this->assertValid($discountCode, $subtotal, $email);
        } catch (ValidationException $exception) {
            $porudzbina->fill([
                'subtotal' => $subtotal,
                ...$this->clearSnapshot(),
                'ukupno' => $subtotal,
            ])->save();

            return [
                ...$this->totals(null, $subtotal),
                'discount' => null,
                'discount_removed' => true,
                'message' => $exception->errors()['code'][0] ?? __('Kod za popust više nije važeći.'),
            ];
        }

        $snapshot = $this->discountSnapshot($discountCode, $subtotal);
        $porudzbina->fill($snapshot)->save();

        return [
            'subtotal' => $snapshot['subtotal'],
            'discount_amount' => $snapshot['discount_amount'],
            'total' => $snapshot['ukupno'],
            'discount' => [
                'discount_code_id' => $snapshot['discount_code_id'],
                'discount_code' => $snapshot['discount_code'],
                'discount_type' => $snapshot['discount_type'],
                'discount_value' => $snapshot['discount_value'],
            ],
        ];
    }

    public function persistSessionDiscountToOrder(Porudzbina $porudzbina, ?string $email = null): Porudzbina
    {
        $discount = session()->get('discount');

        if (!$discount || empty($discount['discount_code'])) {
            return $this->removeFromOrder($porudzbina);
        }

        return $this->applyToOrder($porudzbina, $discount['discount_code'], $email);
    }

    public function redeem(Porudzbina $porudzbina, ?string $email, ?int $userId = null): void
    {
        if (!$porudzbina->discount_code_id || !$porudzbina->discount_code || (int) $porudzbina->discount_amount <= 0) {
            return;
        }

        $email = $this->normalizeEmail($email);

        if (!$email) {
            return;
        }

        DB::transaction(function () use ($porudzbina, $email, $userId) {
            if (DiscountCodeRedemption::where('porudzbina_id', $porudzbina->id)->exists()) {
                return;
            }

            $discountCode = DiscountCode::whereKey($porudzbina->discount_code_id)->lockForUpdate()->firstOrFail();
            $this->assertValid($discountCode, (int) $porudzbina->subtotal, $email);

            DiscountCodeRedemption::create([
                'discount_code_id' => $discountCode->id,
                'porudzbina_id' => $porudzbina->id,
                'user_id' => $userId,
                'email' => $email,
                'discount_amount' => $porudzbina->discount_amount,
            ]);

            $discountCode->increment('uses_count');
        });
    }
}
