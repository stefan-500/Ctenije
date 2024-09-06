<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\VrstaArtikala;

class VrstaArtikalaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vrstaArtikala = [
            [
                'naziv' => 'Duhovnost',
                'nadvrsta_id' => null,
            ],
            [
                'naziv' => 'Istorija',
                'nadvrsta_id' => null,
            ],
            [
                'naziv' => 'Biografija',
                'nadvrsta_id' => null,
            ],
            [
                'naziv' => 'Srednji vijek',
                'nadvrsta_id' => 2,
            ],
        ];

        foreach ($vrstaArtikala as $vrstaPodaci) {
            VrstaArtikala::create($vrstaPodaci);
        }
    }
}
