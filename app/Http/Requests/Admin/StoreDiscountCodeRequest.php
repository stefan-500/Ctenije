<?php

namespace App\Http\Requests\Admin;

use App\Models\DiscountCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class StoreDiscountCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'code' => strtoupper(trim((string) $this->input('code'))),
            'is_active' => $this->boolean('is_active'),
            'value' => $this->normalizeDecimalInput($this->input('value')),
            'minimum_order_total' => $this->normalizeDecimalInput($this->input('minimum_order_total')),
        ]);
    }

    public function rules(): array
    {
        return [
            'code' => [
                'required',
                'string',
                'max:50',
                'regex:/^[A-Z0-9_-]+$/',
                Rule::unique(DiscountCode::class, 'code'),
            ],
            'type' => ['required', Rule::in(['percent', 'fixed'])],
            'value' => ['required', 'numeric'],
            'is_active' => ['boolean'],
            'starts_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after:starts_at'],
            'minimum_order_total' => ['nullable', 'numeric', 'min:0.01'],
            'max_uses' => ['nullable', 'integer', 'min:1'],
            'max_uses_per_email' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $type = $this->input('type');
            $value = $this->input('value');

            if ($type === 'percent') {
                if (filter_var($value, FILTER_VALIDATE_INT) === false || (int) $value < 1 || (int) $value > 100) {
                    $validator->errors()->add('value', 'Procenat mora biti cijeli broj od 1 do 100.');
                }
            }

            if ($type === 'fixed' && (float) $value < 0.01) {
                $validator->errors()->add('value', 'Fiksni popust mora biti najmanje 0.01 EUR.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Kod je obavezan.',
            'code.unique' => 'Kod vec postoji.',
            'code.regex' => 'Kod smije sadrzati samo velika slova, brojeve, donju crtu i crticu.',
            'type.required' => 'Tip popusta je obavezan.',
            'type.in' => 'Tip popusta mora biti procenat ili fiksni iznos.',
            'value.required' => 'Vrijednost popusta je obavezna.',
            'value.numeric' => 'Vrijednost popusta mora biti broj.',
            'starts_at.date' => 'Datum pocetka nije ispravan.',
            'expires_at.date' => 'Datum isteka nije ispravan.',
            'expires_at.after' => 'Datum isteka mora biti nakon datuma pocetka.',
            'minimum_order_total.numeric' => 'Minimalni iznos porudzbine mora biti broj.',
            'minimum_order_total.min' => 'Minimalni iznos porudzbine mora biti najmanje 0.01 EUR.',
            'max_uses.integer' => 'Globalni limit mora biti cijeli broj.',
            'max_uses.min' => 'Globalni limit mora biti najmanje 1.',
            'max_uses_per_email.integer' => 'Vrijednost mora biti biti cijeli broj.',
            'max_uses_per_email.min' => 'Najmanji broj korištenja po email adresi je 1.',
            'max_uses_per_email.max' => 'Najveći broj korištenja po email adresi je 100.',
        ];
    }

    public function discountCodeData(): array
    {
        $data = $this->validated();

        $data['value'] = $data['type'] === 'percent'
            ? (int) $data['value']
            : $this->toMinorUnits($data['value']);

        $data['is_active'] = (bool) ($data['is_active'] ?? false);
        $data['minimum_order_total'] = $this->filled('minimum_order_total')
            ? $this->toMinorUnits($data['minimum_order_total'])
            : null;
        $data['max_uses'] = $this->filled('max_uses') ? (int) $data['max_uses'] : null;
        $data['max_uses_per_email'] = $this->filled('max_uses_per_email') ? (int) $data['max_uses_per_email'] : null;
        $data['starts_at'] = $this->toStorageDateTime($data['starts_at'] ?? null);
        $data['expires_at'] = $this->toStorageDateTime($data['expires_at'] ?? null);

        return $data;
    }

    protected function uniqueRule()
    {
        return Rule::unique(DiscountCode::class, 'code');
    }

    private function normalizeDecimalInput($value): ?string
    {
        if ($value === null || $value === '') {
            return $value;
        }

        return str_replace(',', '.', trim((string) $value));
    }

    private function toMinorUnits($value): int
    {
        return (int) round(((float) $value) * 100);
    }

    /**
     * Admin datetime-local inputs have no timezone, so interpret them as
     * Europe/Belgrade local time and persist them using Laravel's app timezone.
     */
    private function toStorageDateTime(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        return Carbon::parse($value, DiscountCode::ADMIN_TIMEZONE)
            ->timezone(config('app.timezone'))
            ->format('Y-m-d H:i:s');
    }
}
