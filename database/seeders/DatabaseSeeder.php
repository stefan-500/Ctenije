<?php

namespace Database\Seeders;

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


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */


    public function run(): void
    {
        $this->call([
            VrstaArtikalaSeeder::class,
            ArtikalSeeder::class,
            ArtikalSlikaSeeder::class,
            KnjigaSeeder::class,
        ]);

        $korisnici = User::factory(10)->state(new Sequence(
            ['ovlascenje' => 'Korisnik'],
            ['ovlascenje' => 'Menadzer']
        ))->create();

        $porudzbine = Porudzbina::factory(10)->make()->each(function ($porudzbina) use ($korisnici) {
            $porudzbina->user_id = $korisnici->random()->id;
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
