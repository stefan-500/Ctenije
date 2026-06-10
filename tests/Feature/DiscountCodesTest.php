<?php

use App\Models\Artikal;
use App\Models\DiscountCode;
use App\Models\DiscountCodeRedemption;
use App\Models\GuestDeliveryData;
use App\Models\Porudzbina;
use App\Models\StavkaPorudzbine;
use App\Models\User;
use App\Services\DiscountService;
use App\Services\StripePaymentService;
use Database\Seeders\DiscountCodeSeeder;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

function discountCodeUser(string $email = 'kupac@example.com'): User
{
    return User::factory()->create([
        'email' => $email,
        'ovlascenje' => 'Korisnik',
        'adresa' => 'Ulica Test 12, 11000 Beograd',
    ]);
}

function discountCodeArtikal(int $price = 10000, int $stock = 10): Artikal
{
    return Artikal::factory()->create([
        'cijena' => $price,
        'akcijska_cijena' => null,
        'dostupna_kolicina' => $stock,
    ]);
}

function discountCodeCartItem(Artikal $artikal, int $quantity = 1): array
{
    return [
        'artikal_id' => $artikal->id,
        'naziv' => $artikal->naziv,
        'cijena' => $artikal->akcijska_cijena ?? $artikal->cijena,
        'kolicina' => $quantity,
        'ukupna_cijena' => ($artikal->akcijska_cijena ?? $artikal->cijena) * $quantity,
    ];
}

function discountCodeOpenOrder(User $user, array $items): Porudzbina
{
    $porudzbina = Porudzbina::factory()->create([
        'user_id' => $user->id,
        'status' => 'neobradjeno',
        'adresa_isporuke' => $user->adresa,
        'subtotal' => 0,
        'ukupno' => 0,
    ]);

    foreach ($items as [$artikal, $quantity]) {
        StavkaPorudzbine::create([
            'porudzbina_id' => $porudzbina->id,
            'artikal_id' => $artikal->id,
            'kolicina' => $quantity,
            'ukupna_cijena' => ($artikal->akcijska_cijena ?? $artikal->cijena) * $quantity,
        ]);
    }

    app(DiscountService::class)->recalculateOrder($porudzbina, $user->email);

    return $porudzbina->refresh();
}

function discountCodeGuestDeliveryData(string $email = 'guest@example.com'): array
{
    return [
        'ime' => 'Marko',
        'prezime' => 'Markovic',
        'email' => $email,
        'adresa' => 'Ulica Test 12, 11000 Beograd',
        'tel' => '+38161234567',
    ];
}

class FakeDiscountStripePaymentService extends StripePaymentService
{
    public array $createdAmounts = [];

    public string $retrieveStatus = 'succeeded';

    public string $retrieveId = 'pi_test_finalized';

    public function createPaymentIntent(int $amount, string $currency = 'eur')
    {
        $this->createdAmounts[] = compact('amount', 'currency');

        return (object) [
            'id' => 'pi_test_created',
            'client_secret' => 'secret_test',
        ];
    }

    public function retrievePaymentIntent(string $paymentIntentId)
    {
        return (object) [
            'id' => $this->retrieveId ?: $paymentIntentId,
            'status' => $this->retrieveStatus,
        ];
    }
}

it('applies valid percent and fixed codes for guests and logged in users', function () {
    $artikal = discountCodeArtikal(10000);
    $percent = DiscountCode::factory()->create(['code' => 'USTEDI10', 'type' => 'percent', 'value' => 10]);
    $fixed = DiscountCode::factory()->fixed(500)->create(['code' => 'POPUST500']);

    $this->withSession(['cart' => [$artikal->id => discountCodeCartItem($artikal)]])
        ->postJson('/cart/discount/apply', ['code' => 'ustedi10'])
        ->assertOk()
        ->assertJsonPath('discount.discount_code', 'USTEDI10')
        ->assertJsonPath('subtotal', '100,00')
        ->assertJsonPath('discount_amount', '10,00')
        ->assertJsonPath('porudzbina_ukupno', '90,00');

    expect(session('discount.discount_code'))->toBe($percent->code);

    $user = discountCodeUser('fixed@example.com');
    $order = discountCodeOpenOrder($user, [[$artikal, 1]]);

    $this->actingAs($user)
        ->postJson('/cart/discount/apply', ['code' => $fixed->code])
        ->assertOk()
        ->assertJsonPath('discount.discount_code', 'POPUST500')
        ->assertJsonPath('subtotal', '100,00')
        ->assertJsonPath('discount_amount', '5,00')
        ->assertJsonPath('porudzbina_ukupno', '95,00');

    $order->refresh();

    expect($order->discount_code)->toBe('POPUST500')
        ->and($order->discount_amount)->toBe(500)
        ->and($order->ukupno)->toBe(9500);
});

it('rejects invalid unknown expired disabled and future-start codes', function () {
    $artikal = discountCodeArtikal();

    DiscountCode::factory()->expired()->create(['code' => 'ISTEKAO5']);
    DiscountCode::factory()->inactive()->create(['code' => 'NEAKTIVAN20']);
    DiscountCode::factory()->create(['code' => 'SJUTRA10', 'starts_at' => now()->addDay()]);

    $request = fn (?string $code) => $this
        ->withSession(['cart' => [$artikal->id => discountCodeCartItem($artikal)]])
        ->postJson('/cart/discount/apply', array_filter(['code' => $code], fn ($value) => $value !== null));

    $request(null)->assertStatus(422)->assertJsonValidationErrors('code');
    $request('NEPOSTOJI')->assertStatus(422)->assertJsonPath('error', __('Kod za popust nije važeći.'));
    $request('ISTEKAO5')->assertStatus(422)->assertJsonPath('error', __('Kod za popust je istekao.'));
    $request('NEAKTIVAN20')->assertStatus(422)->assertJsonPath('error', __('Kod za popust nije aktivan.'));
    $request('SJUTRA10')->assertStatus(422)->assertJsonPath('error', __('Kod za popust još nije aktivan.'));
});

it('handles minimum order pass fail and cart-change invalidation', function () {
    $first = discountCodeArtikal(2000);
    $second = discountCodeArtikal(2000);
    DiscountCode::factory()->create([
        'code' => 'MINIMUM3000',
        'type' => 'percent',
        'value' => 10,
        'minimum_order_total' => 3000,
    ]);

    $this->withSession(['cart' => [$first->id => discountCodeCartItem($first)]])
        ->postJson('/cart/discount/apply', ['code' => 'MINIMUM3000'])
        ->assertStatus(422)
        ->assertJsonPath('error', __('Iznos porudžbine je prenizak za ovaj kod.'));

    $session = [
        'cart' => [
            $first->id => discountCodeCartItem($first),
            $second->id => discountCodeCartItem($second),
        ],
    ];

    $this->withSession($session)
        ->postJson('/cart/discount/apply', ['code' => 'MINIMUM3000'])
        ->assertOk()
        ->assertJsonPath('discount_amount', '4,00')
        ->assertJsonPath('porudzbina_ukupno', '36,00');

    $this->postJson('/cart/remove', ['artikal_id' => $second->id])
        ->assertOk()
        ->assertJsonPath('discount', null)
        ->assertJsonPath('discount_amount', '0,00')
        ->assertJsonPath('porudzbina_ukupno', '20,00');

    expect(session('discount'))->toBeNull();
});

it('enforces global and per-email usage limits for guests and logged-in users', function () {
    $artikal = discountCodeArtikal(10000);
    $limited = DiscountCode::factory()->create([
        'code' => 'LIMIT1',
        'max_uses' => 1,
        'uses_count' => 1,
    ]);
    $perEmail = DiscountCode::factory()->create([
        'code' => 'EMAIL1',
        'max_uses_per_email' => 1,
    ]);
    $existingOrder = Porudzbina::factory()->create();
    $guestExistingOrder = Porudzbina::factory()->create();

    DiscountCodeRedemption::factory()->create([
        'discount_code_id' => $perEmail->id,
        'porudzbina_id' => $existingOrder->id,
        'email' => 'limit@example.com',
    ]);
    DiscountCodeRedemption::factory()->create([
        'discount_code_id' => $perEmail->id,
        'porudzbina_id' => $guestExistingOrder->id,
        'email' => 'guest-limit@example.com',
    ]);

    $this->withSession(['cart' => [$artikal->id => discountCodeCartItem($artikal)]])
        ->postJson('/cart/discount/apply', ['code' => $limited->code])
        ->assertStatus(422)
        ->assertJsonPath('error', __('Kod je već iskorišten maksimalan broj puta.'));

    $user = discountCodeUser('limit@example.com');
    discountCodeOpenOrder($user, [[$artikal, 1]]);

    $this->actingAs($user)
        ->postJson('/cart/discount/apply', ['code' => $limited->code])
        ->assertStatus(422)
        ->assertJsonPath('error', __('Kod je već iskorišten maksimalan broj puta.'));

    $this->actingAs($user)
        ->postJson('/cart/discount/apply', ['code' => $perEmail->code])
        ->assertStatus(422)
        ->assertJsonPath('error', __('Kod je već iskorišten.'));

    auth()->logout();

    $this->withSession(['cart' => [$artikal->id => discountCodeCartItem($artikal)]])
        ->postJson('/cart/discount/apply', ['code' => $perEmail->code])
        ->assertOk();

    $this->post('/dostava', discountCodeGuestDeliveryData('guest-limit@example.com'))
        ->assertRedirect();

    $guestOrder = Porudzbina::whereHas('guestDeliveryData', fn ($query) => $query->where('email', 'guest-limit@example.com'))->first();

    expect($guestOrder->discount_code)->toBeNull()
        ->and($guestOrder->discount_amount)->toBe(0)
        ->and($guestOrder->ukupno)->toBe(10000);
});

it('calculates subtotal discount amount and final ukupno with integer prices', function () {
    $service = app(DiscountService::class);
    $percent = DiscountCode::factory()->create(['type' => 'percent', 'value' => 15]);
    $fixed = DiscountCode::factory()->fixed(10000)->create();

    expect($service->cartSubtotal([
        ['cijena' => 3333, 'kolicina' => 3],
        ['cijena' => 1000, 'kolicina' => 1],
    ]))->toBe(10999);

    expect($service->totals($percent, 10999))->toBe([
        'subtotal' => 10999,
        'discount_amount' => 1649,
        'total' => 9350,
    ]);

    expect($service->totals($fixed, 7500))->toBe([
        'subtotal' => 7500,
        'discount_amount' => 7500,
        'total' => 0,
    ]);
});

it('recalculates discounts after quantity increment decrement and item removal', function () {
    $first = discountCodeArtikal(2000, 5);
    $second = discountCodeArtikal(1000, 5);
    DiscountCode::factory()->create(['code' => 'USTEDI10', 'type' => 'percent', 'value' => 10]);

    $this->withSession([
        'cart' => [
            $first->id => discountCodeCartItem($first, 2),
            $second->id => discountCodeCartItem($second, 1),
        ],
    ])->postJson('/cart/discount/apply', ['code' => 'USTEDI10'])->assertOk();

    $this->postJson('/cart/increment', ['artikal_id' => $first->id])
        ->assertOk()
        ->assertJsonPath('subtotal', '70,00')
        ->assertJsonPath('discount_amount', '7,00')
        ->assertJsonPath('porudzbina_ukupno', '63,00');

    $this->postJson('/cart/decrement', ['artikal_id' => $first->id])
        ->assertOk()
        ->assertJsonPath('subtotal', '50,00')
        ->assertJsonPath('discount_amount', '5,00')
        ->assertJsonPath('porudzbina_ukupno', '45,00');

    $this->postJson('/cart/remove', ['artikal_id' => $second->id])
        ->assertOk()
        ->assertJsonPath('subtotal', '40,00')
        ->assertJsonPath('discount_amount', '4,00')
        ->assertJsonPath('porudzbina_ukupno', '36,00');
});

it('lets customers remove an applied discount and excludes removed codes from final totals', function () {
    $artikal = discountCodeArtikal(10000);
    $code = DiscountCode::factory()->create(['code' => 'USTEDI10', 'type' => 'percent', 'value' => 10]);
    $user = discountCodeUser('remove@example.com');
    $order = discountCodeOpenOrder($user, [[$artikal, 1]]);

    $this->actingAs($user)
        ->postJson('/cart/discount/apply', ['code' => $code->code])
        ->assertOk()
        ->assertJsonPath('porudzbina_ukupno', '90,00');

    $this->actingAs($user)
        ->deleteJson('/cart/discount/remove')
        ->assertOk()
        ->assertJsonPath('discount', null)
        ->assertJsonPath('discount_amount', '0,00')
        ->assertJsonPath('porudzbina_ukupno', '100,00');

    $order->refresh();

    expect($order->discount_code)->toBeNull()
        ->and($order->discount_amount)->toBe(0)
        ->and($order->ukupno)->toBe(10000);
});

it('keeps guest session discounts across cart reload and persists them to delivery orders', function () {
    $artikal = discountCodeArtikal(10000);
    DiscountCode::factory()->create(['code' => 'USTEDI10', 'type' => 'percent', 'value' => 10]);

    $this->withSession(['cart' => [$artikal->id => discountCodeCartItem($artikal)]])
        ->postJson('/cart/discount/apply', ['code' => 'USTEDI10'])
        ->assertOk();

    $this->get('/cart')
        ->assertOk()
        ->assertSee('USTEDI10')
        ->assertSee('90,00');

    expect(session('discount.discount_code'))->toBe('USTEDI10');

    $this->post('/dostava', discountCodeGuestDeliveryData('guest-discount@example.com'))
        ->assertRedirect();

    $order = Porudzbina::whereHas('guestDeliveryData', fn ($query) => $query->where('email', 'guest-discount@example.com'))->first();

    expect($order->discount_code)->toBe('USTEDI10')
        ->and($order->subtotal)->toBe(10000)
        ->and($order->discount_amount)->toBe(1000)
        ->and($order->ukupno)->toBe(9000);
});

it('keeps order discount snapshots unchanged after editing the source code', function () {
    $discountCode = DiscountCode::factory()->create(['code' => 'SNAP10', 'type' => 'percent', 'value' => 10]);
    $order = Porudzbina::factory()->create([
        'subtotal' => 10000,
        'discount_code_id' => $discountCode->id,
        'discount_code' => 'SNAP10',
        'discount_type' => 'percent',
        'discount_value' => 10,
        'discount_amount' => 1000,
        'ukupno' => 9000,
        'status' => 'zakljuceno',
    ]);

    $discountCode->update(['code' => 'SNAP20', 'type' => 'fixed', 'value' => 5000]);

    $order->refresh();

    expect($order->discount_code)->toBe('SNAP10')
        ->and($order->discount_type)->toBe('percent')
        ->and($order->discount_value)->toBe(10)
        ->and($order->discount_amount)->toBe(1000)
        ->and($order->ukupno)->toBe(9000);
});

it('recalculates checkout totals server-side before creating Stripe payment intents', function () {
    $fakeStripe = new FakeDiscountStripePaymentService();
    app()->instance(StripePaymentService::class, $fakeStripe);

    $artikal = discountCodeArtikal(10000);
    $user = discountCodeUser('checkout@example.com');
    $order = discountCodeOpenOrder($user, [[$artikal, 1]]);
    $discountCode = DiscountCode::factory()->create(['code' => 'USTEDI10', 'type' => 'percent', 'value' => 10]);

    $order->update([
        'subtotal' => 1,
        'discount_code_id' => $discountCode->id,
        'discount_code' => 'USTEDI10',
        'discount_type' => 'percent',
        'discount_value' => 10,
        'discount_amount' => 0,
        'ukupno' => 1,
    ]);

    $this->actingAs($user)
        ->get('/placanje')
        ->assertOk()
        ->assertSee('secret_test');

    expect($fakeStripe->createdAmounts)->toBe([
        ['amount' => 9000, 'currency' => 'eur'],
    ]);
});

it('creates redemptions and increments uses only after successful order finalization', function () {
    Mail::fake();

    $fakeStripe = new FakeDiscountStripePaymentService();
    $fakeStripe->retrieveId = 'pi_success';
    app()->instance(StripePaymentService::class, $fakeStripe);

    $artikal = discountCodeArtikal(10000, 5);
    $user = discountCodeUser('finalize@example.com');
    $order = discountCodeOpenOrder($user, [[$artikal, 2]]);
    $discountCode = DiscountCode::factory()->create(['code' => 'USTEDI10', 'type' => 'percent', 'value' => 10]);

    $this->actingAs($user)
        ->postJson('/cart/discount/apply', ['code' => 'USTEDI10'])
        ->assertOk();

    expect($discountCode->fresh()->uses_count)->toBe(0)
        ->and(DiscountCodeRedemption::count())->toBe(0);

    $this->actingAs($user)
        ->postJson('/placanje', ['payment_intent_id' => 'pi_success'])
        ->assertOk()
        ->assertJsonPath('success', true);

    $order->refresh();

    expect($order->status)->toBe('zakljuceno')
        ->and($order->stripe_payment_intent_id)->toBe('pi_success')
        ->and($discountCode->fresh()->uses_count)->toBe(1)
        ->and(DiscountCodeRedemption::where('porudzbina_id', $order->id)->where('email', 'finalize@example.com')->exists())->toBeTrue();
});

it('keeps finalization and redemption data consistent when finalization fails', function () {
    Mail::fake();

    $fakeStripe = new FakeDiscountStripePaymentService();
    app()->instance(StripePaymentService::class, $fakeStripe);

    $artikal = discountCodeArtikal(10000, 5);
    $user = discountCodeUser('failure@example.com');
    $order = discountCodeOpenOrder($user, [[$artikal, 1]]);
    $discountCode = DiscountCode::factory()->create(['code' => 'USTEDI10', 'type' => 'percent', 'value' => 10]);

    app(DiscountService::class)->applyToOrder($order, 'USTEDI10', $user->email);

    DB::table('stavka_porudzbines')->where('porudzbina_id', $order->id)->update(['artikal_id' => null]);

    $this->actingAs($user)
        ->postJson('/placanje', ['payment_intent_id' => 'pi_failure'])
        ->assertStatus(500);

    $order->refresh();

    expect($order->status)->toBe('neobradjeno')
        ->and($order->stripe_payment_intent_id)->toBeNull()
        ->and($discountCode->fresh()->uses_count)->toBe(0)
        ->and(DiscountCodeRedemption::where('porudzbina_id', $order->id)->exists())->toBeFalse();
});

it('seeds expected sample codes and enforces database uniqueness and redemption lookup indexes', function () {
    $this->seed(DiscountCodeSeeder::class);

    foreach (['USTEDI10', 'POPUST500', 'ISTEKAO5', 'NEAKTIVAN20', 'MINIMUM3000', 'LIMIT1', 'EMAIL1'] as $code) {
        $this->assertDatabaseHas('discount_codes', ['code' => $code]);
    }

    expect(fn () => DiscountCode::factory()->create(['code' => 'USTEDI10']))->toThrow(QueryException::class);

    if (method_exists(Schema::getFacadeRoot(), 'getIndexes')) {
        $indexes = collect(Schema::getIndexes('discount_code_redemptions'))
            ->map(fn (array $index) => $index['columns'] ?? [])
            ->values();

        expect($indexes->contains(['discount_code_id', 'email']))->toBeTrue();
    }
});
