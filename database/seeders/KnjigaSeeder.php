<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Knjiga;

class KnjigaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $knjige = [
            // ISBN se dodaje dinamicki iz KnjigaFactory-ja
            [
                'izdanje' => 2018,
                'autor' => 'Episkop žički Sava Dečanac',
                'izdavac' => 'Centar za istraživanje pravoslavnog monarhizma, Beograd',
                'br_stranica' => 252,
                'pismo' => 'ćirilica',
                'artikal_id' => 7,
            ],
            [
                'izdanje' => 2007,
                'autor' => 'Prezviter Oliver Subotić',
                'izdavac' => 'Bernar',
                'br_stranica' => 564,
                'pismo' => 'ćirilica',
                'artikal_id' => 6,
            ],
            [
                'izdanje' => 2020,
                'autor' => 'Sveti Makarije Optinski',
                'izdavac' => 'Pravoslavna misionarska škola pri hramu Svetog Aleksandra Nevskog, Beograd',
                'br_stranica' => 316,
                'pismo' => 'ćirilica',
                'artikal_id' => 5,
            ],
            [
                'izdanje' => 2023,
                'autor' => 'Sveti vladika Nikolaj Velimirović',
                'izdavac' => 'Manastir Lelić, Valjevo',
                'br_stranica' => 127,
                'pismo' => 'ćirilica',
                'artikal_id' => 4,
            ],
            [
                'izdanje' => 2016,
                'autor' => 'Sveti vladika Nikolaj Velimirović',
                'izdavac' => 'Manastir Lelić, Valjevo',
                'br_stranica' => 482,
                'pismo' => 'ćirilica',
                'artikal_id' => 2,
            ],
            [
                'izdanje' => 2007,
                'autor' => 'Sveti Jefrem Sirijski',
                'izdavac' => 'Biblioteka „Očev dom“, Beograd',
                'br_stranica' => 967,
                'pismo' => 'ćirilica',
                'artikal_id' => 1,
            ],
            [
                'izdanje' => 2017,
                'autor' => 'Milovan Matić',
                'izdavac' => 'Centar za crkvene studije, Niš',
                'br_stranica' => 349,
                'pismo' => 'ćirilica',
                'artikal_id' => 3,
            ],
        ];

        foreach ($knjige as $knjigaData) {
            Knjiga::factory()->create($knjigaData);
        }
    }
}
