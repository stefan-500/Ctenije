<?php

namespace Database\Seeders;

use App\Models\GuestDeliveryData;
use App\Models\Porudzbina;
use App\Models\User;
use App\Models\Artikal;
use App\Models\Knjiga;
use App\Models\ArtikalSlika;
use App\Models\VrstaArtikala;
use App\Models\StavkaPorudzbine;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */


    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            VrstaArtikalaSeeder::class,
            ArtikalSeeder::class,
            ArtikalSlikaSeeder::class,
            KnjigaSeeder::class,
        ]);

        $korisnici = User::factory(10)->state(new Sequence(
            ['ovlascenje' => 'Korisnik'],
            ['ovlascenje' => 'Menadzer']
        ))->create();

        $guestDeliveryData = GuestDeliveryData::factory(10)->create();

        $porudzbine = Porudzbina::factory(10)->make()->each(function ($porudzbina) use ($korisnici, $guestDeliveryData) {
            if (rand(1, 10) <= 7) {
                // 70% 
                $porudzbina->user_id = $korisnici->random()->id;
                $porudzbina->stripe_payment_intent_id = 'pi_' . Str::random(24);
                // $porudzbina->guest_delivery_data_id = null;
            } else {
                // 30% 
                $porudzbina->guest_delivery_data_id = $guestDeliveryData->random()->id;
                $porudzbina->payment_token = Str::random(64);
                $porudzbina->stripe_payment_intent_id = 'pi_' . Str::random(24);
                // $porudzbina->user_id = null;
            }

            $porudzbina->save();
        });

        $nadVrsteArtikala = VrstaArtikala::all(); // VrstaArtikalaSeeder podaci

        // Kreiranje podvrsta za neke od $nadVrsteArtikala
        $podVrsteArtikala = VrstaArtikala::factory(2)->make()->each(function ($podVrsta) use ($nadVrsteArtikala) {
            $podVrsta->nadvrsta_id = $nadVrsteArtikala->random()->id;
            $podVrsta->save();
        });

        $vrsteArtikala = $nadVrsteArtikala->merge($podVrsteArtikala);

        $knjige = Knjiga::factory(5)
            ->create()
            ->each(function ($knjiga) use ($vrsteArtikala) {
                // Nakon pozivanja ArtikalSlikaSeeder koriste se ti podaci
                ArtikalSlika::factory()->create([
                    'artikal_id' => $knjiga->artikal_id,
                ]);

                // Dodavanje redova u pivot tabelu
                $knjiga->artikal->vrsteArtikla()->attach(
                    $vrsteArtikala->random(rand(1, 3))->pluck('id')->toArray()
                );
            });

        StavkaPorudzbine::factory(15)->make()->each(function ($stavka) use ($knjige, $porudzbine) {
            $stavka->artikal_id = $knjige->random()->artikal_id;
            $stavka->porudzbina_id = $porudzbine->random()->id;
            $stavka->save();
        });
    }
}
