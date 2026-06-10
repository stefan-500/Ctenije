<?php

use App\Models\DiscountCode;
use App\Models\DiscountCodeRedemption;
use App\Models\Porudzbina;
use App\Models\User;
use Illuminate\Support\Carbon;

function discountManagementAdminUser(): User
{
    return User::factory()->create(['ovlascenje' => 'Administrator']);
}

function discountManagementManagerUser(): User
{
    return User::factory()->create(['ovlascenje' => 'Menadzer']);
}

it('redirects guests and forbids normal users from discount management', function () {
    $this->get('/admin/popusti/index')->assertRedirect('/login');

    $user = User::factory()->create(['ovlascenje' => 'Korisnik']);

    $this->actingAs($user)
        ->get('/admin/popusti/index')
        ->assertForbidden();
});

it('allows administrators and managers to list create edit and view discount codes', function () {
    $discountCode = DiscountCode::factory()->create(['code' => 'USTEDI10']);

    $this->actingAs(discountManagementAdminUser())
        ->get('/admin/popusti/index')
        ->assertOk()
        ->assertSee('USTEDI10');

    $this->actingAs(discountManagementManagerUser())
        ->get('/admin/popusti/dodaj')
        ->assertOk();

    $this->actingAs(discountManagementManagerUser())
        ->get('/admin/popusti/izmijeni/' . $discountCode->id)
        ->assertOk()
        ->assertSee('USTEDI10');

    $this->actingAs(discountManagementAdminUser())
        ->get('/admin/popusti/' . $discountCode->id)
        ->assertOk()
        ->assertSee('USTEDI10');
});

it('stores created codes uppercase with integer minor unit amounts', function () {
    $this->actingAs(discountManagementAdminUser())
        ->post('/admin/popusti/dodaj', [
            'code' => 'popust500',
            'type' => 'fixed',
            'value' => '5.00',
            'is_active' => '1',
            'starts_at' => '2026-06-01 10:00',
            'expires_at' => '2026-07-01 10:00',
            'minimum_order_total' => '30.00',
            'max_uses' => '10',
            'max_uses_per_email' => '1',
        ])
        ->assertRedirect('/admin/popusti/index');

    $this->assertDatabaseHas('discount_codes', [
        'code' => 'POPUST500',
        'type' => 'fixed',
        'value' => 500,
        'is_active' => true,
        'minimum_order_total' => 3000,
        'max_uses' => 10,
        'max_uses_per_email' => 1,
    ]);

    $discountCode = DiscountCode::where('code', 'POPUST500')->first();

    expect($discountCode->starts_at->timezone(config('app.timezone'))->format('Y-m-d H:i:s'))->toBe('2026-06-01 08:00:00')
        ->and($discountCode->expires_at->timezone(config('app.timezone'))->format('Y-m-d H:i:s'))->toBe('2026-07-01 08:00:00');
});

it('validates uniqueness on update while allowing the current code', function () {
    DiscountCode::factory()->create(['code' => 'POSTOJECI']);
    $discountCode = DiscountCode::factory()->create(['code' => 'EMAIL1']);

    $this->actingAs(discountManagementAdminUser())
        ->put('/admin/popusti/izmijeni/' . $discountCode->id, [
            'code' => 'postojeci',
            'type' => 'percent',
            'value' => '10',
            'is_active' => '1',
        ])
        ->assertSessionHasErrors('code');

    $this->actingAs(discountManagementAdminUser())
        ->put('/admin/popusti/izmijeni/' . $discountCode->id, [
            'code' => 'email1',
            'type' => 'percent',
            'value' => '15',
            'is_active' => '1',
        ])
        ->assertRedirect('/admin/popusti/index');

    $this->assertDatabaseHas('discount_codes', [
        'id' => $discountCode->id,
        'code' => 'EMAIL1',
        'value' => 15,
    ]);
});

it('rejects invalid discount code values', function () {
    $admin = discountManagementAdminUser();

    $this->actingAs($admin)
        ->post('/admin/popusti/dodaj', [
            'code' => 'los kod',
            'type' => 'unknown',
            'value' => '10',
            'starts_at' => '2026-07-01 10:00',
            'expires_at' => '2026-06-01 10:00',
            'minimum_order_total' => '-1',
            'max_uses' => '-5',
            'max_uses_per_email' => '0',
        ])
        ->assertSessionHasErrors([
            'code',
            'type',
            'expires_at',
            'minimum_order_total',
            'max_uses',
            'max_uses_per_email',
        ]);

    $this->actingAs($admin)
        ->post('/admin/popusti/dodaj', [
            'code' => 'PREVELIKPROCENAT',
            'type' => 'percent',
            'value' => '101',
        ])
        ->assertSessionHasErrors('value');

    $this->actingAs($admin)
        ->post('/admin/popusti/dodaj', [
            'code' => 'MALIFIKSNI',
            'type' => 'fixed',
            'value' => '0',
        ])
        ->assertSessionHasErrors('value');
});

it('validates max uses per email limits on create and update', function () {
    $admin = discountManagementAdminUser();

    foreach (['101010', '101', '0', '-1', '1.5', 'abc'] as $invalidLimit) {
        $this->actingAs($admin)
            ->post('/admin/popusti/dodaj', [
                'code' => 'EMAILLIMIT' . preg_replace('/[^A-Z0-9]/', '', strtoupper($invalidLimit)),
                'type' => 'percent',
                'value' => '10',
                'is_active' => '1',
                'max_uses_per_email' => $invalidLimit,
            ])
            ->assertSessionHasErrors('max_uses_per_email');
    }

    $this->actingAs($admin)
        ->post('/admin/popusti/dodaj', [
            'code' => 'EMAILTOOHIGH',
            'type' => 'percent',
            'value' => '10',
            'is_active' => '1',
            'max_uses_per_email' => '101',
        ])
        ->assertSessionHasErrors([
            'max_uses_per_email' => 'Maksimalni broj korištenja po email adresi ne moze biti veci od 100.',
        ]);

    $this->actingAs($admin)
        ->post('/admin/popusti/dodaj', [
            'code' => 'EMAILVALID',
            'type' => 'percent',
            'value' => '10',
            'is_active' => '1',
            'max_uses_per_email' => '100',
        ])
        ->assertRedirect('/admin/popusti/index');

    $discountCode = DiscountCode::where('code', 'EMAILVALID')->firstOrFail();

    expect($discountCode->max_uses_per_email)->toBe(100);

    $this->actingAs($admin)
        ->put('/admin/popusti/izmijeni/' . $discountCode->id, [
            'code' => 'EMAILVALID',
            'type' => 'percent',
            'value' => '10',
            'is_active' => '1',
            'max_uses_per_email' => '101',
        ])
        ->assertSessionHasErrors('max_uses_per_email');

    $this->actingAs($admin)
        ->put('/admin/popusti/izmijeni/' . $discountCode->id, [
            'code' => 'EMAILVALID',
            'type' => 'percent',
            'value' => '10',
            'is_active' => '1',
            'max_uses_per_email' => '25',
        ])
        ->assertRedirect('/admin/popusti/index');

    expect($discountCode->fresh()->max_uses_per_email)->toBe(25);
});

it('toggles discount codes enabled and disabled', function () {
    $discountCode = DiscountCode::factory()->create(['is_active' => true]);

    $this->actingAs(discountManagementAdminUser())
        ->patch('/admin/popusti/' . $discountCode->id . '/toggle')
        ->assertRedirect();

    expect($discountCode->fresh()->is_active)->toBeFalse();

    $this->actingAs(discountManagementAdminUser())
        ->patch('/admin/popusti/' . $discountCode->id . '/toggle')
        ->assertRedirect();

    expect($discountCode->fresh()->is_active)->toBeTrue();
});

it('deactivates discount codes instead of hard deleting them', function () {
    $discountCode = DiscountCode::factory()->create(['is_active' => true]);

    $this->actingAs(discountManagementManagerUser())
        ->delete('/admin/popusti/' . $discountCode->id)
        ->assertRedirect('/admin/popusti/index');

    $this->assertDatabaseHas('discount_codes', [
        'id' => $discountCode->id,
        'is_active' => false,
    ]);
});

it('displays usage counts remaining uses and recent redemption details', function () {
    $discountCode = DiscountCode::factory()->create([
        'code' => 'EMAIL1',
        'type' => 'fixed',
        'value' => 500,
        'uses_count' => 1,
        'max_uses' => 3,
    ]);
    $porudzbina = Porudzbina::factory()->create();

    DiscountCodeRedemption::factory()->create([
        'discount_code_id' => $discountCode->id,
        'porudzbina_id' => $porudzbina->id,
        'email' => 'kupac@example.com',
        'discount_amount' => 500,
    ]);

    $this->actingAs(discountManagementAdminUser())
        ->get('/admin/popusti/' . $discountCode->id)
        ->assertOk()
        ->assertSee('EMAIL1')
        ->assertSee('kupac@example.com')
        ->assertSee('#' . $porudzbina->id)
        ->assertSee('2')
        ->assertSee('5,00');
});

it('displays discount dates in Europe Belgrade 24 hour format', function () {
    $discountCode = DiscountCode::factory()->create([
        'code' => 'DATUM24',
        'starts_at' => Carbon::parse('2026-06-01 08:00:00', 'UTC'),
        'expires_at' => Carbon::parse('2026-07-01 08:00:00', 'UTC'),
    ]);

    $admin = discountManagementAdminUser();

    $this->actingAs($admin)
        ->get('/admin/popusti/index')
        ->assertOk()
        ->assertSee('01.06.2026. 10:00')
        ->assertSee('01.07.2026. 10:00')
        ->assertDontSee('fa-ban', false)
        ->assertDontSee('value="DELETE"', false);

    $this->actingAs($admin)
        ->get('/admin/popusti/' . $discountCode->id)
        ->assertOk()
        ->assertSee('01.06.2026. 10:00')
        ->assertSee('01.07.2026. 10:00');

    $this->actingAs($admin)
        ->get('/admin/popusti/izmijeni/' . $discountCode->id)
        ->assertOk()
        ->assertSee('01.06.2026. 10:00')
        ->assertSee('01.07.2026. 10:00')
        ->assertSee('2026-06-01T10:00')
        ->assertSee('2026-07-01T10:00');
});
