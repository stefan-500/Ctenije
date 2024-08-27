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
        $korisnici = User::factory(10)->state(new Sequence(
            ['ovlascenje' => 'Korisnik'],
            ['ovlascenje' => 'Menadzer']
        ))->create();

        $porudzbine = Porudzbina::factory(10)->make()->each(function ($porudzbina) use ($korisnici) {
            $porudzbina->user_id = $korisnici->random()->id;
            $porudzbina->save();
        });

        $nadVrsteArtikala = VrstaArtikala::factory(5)->create();

        // Kreiranje podvrsta za neke od $nadVrsteArtikala
        $podVrsteArtikala = VrstaArtikala::factory(6)->make()->each(function ($podVrsta) use ($nadVrsteArtikala) {
            $podVrsta->nadvrsta_id = $nadVrsteArtikala->random()->id;
            $podVrsta->save();
        });

        $vrsteArtikala = $nadVrsteArtikala->merge($podVrsteArtikala);

        $knjige = Knjiga::factory(20)
            ->create()
            ->each(function ($knjiga) use ($vrsteArtikala) {
                // Za svaki instancu Knjige kreira se ArtikalSlika
                // sa istim artikal_id  
                ArtikalSlika::factory()->create([
                    'artikal_id' => $knjiga->artikal_id,
                ]);

                // Dodavanje redova u pivot tabelu
                // Za svaki artikal upisuje se 1-3 vrsti artikla
                $knjiga->artikal->vrsteArtikla()->attach(
                    $vrsteArtikala->random(rand(1, 3))->pluck('id')->toArray()
                );
            });

        // Dodjela nasumicnih postojecih artikal_id i porudzbina_id vrijednosti stavci porudzbine
        StavkaPorudzbine::factory(30)->make()->each(function ($stavka) use ($knjige, $porudzbine) {

            $stavka->artikal_id = $knjige->random()->artikal_id;

            $stavka->porudzbina_id = $porudzbine->random()->id;

            $stavka->save();
        });

    }
}
