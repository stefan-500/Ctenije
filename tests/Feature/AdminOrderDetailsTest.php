<?php

use App\Models\Artikal;
use App\Models\Porudzbina;
use App\Models\StavkaPorudzbine;
use App\Models\User;

it('formats unit prices on the admin order detail page', function () {
    $admin = User::factory()->create(['ovlascenje' => 'Administrator']);
    $artikal = Artikal::factory()->create([
        'cijena' => 1250,
        'akcijska_cijena' => null,
        'dostupna_kolicina' => 5,
    ]);
    $porudzbina = Porudzbina::factory()->create([
        'ukupno' => 2500,
        'subtotal' => 2500,
        'discount_amount' => 0,
        'status' => 'zakljuceno',
    ]);

    StavkaPorudzbine::create([
        'porudzbina_id' => $porudzbina->id,
        'artikal_id' => $artikal->id,
        'kolicina' => 2,
        'ukupna_cijena' => 2500,
    ]);

    $this->actingAs($admin)
        ->get('/admin/porudzbine/' . $porudzbina->id)
        ->assertOk()
        ->assertSee('12,50')
        ->assertSee('25,00');
});
