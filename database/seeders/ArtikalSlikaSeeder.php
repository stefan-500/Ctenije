<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ArtikalSlika;

class ArtikalSlikaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $slikeArtikala = [
            [
                'naziv_fajla' => 'vladalac_i_narod.jpg',
                'artikal_id' => 1,
            ],
            [
                'naziv_fajla' => 'recnik_spasenja.jpg',
                'artikal_id' => 2,
            ],
            [
                'naziv_fajla' => 'Tesla_duhovni_lik.jpg',
                'artikal_id' => 3,
            ],
            [
                'naziv_fajla' => 'prolog.jpg',
                'artikal_id' => 4,
            ],
            [
                'naziv_fajla' => 'Gospod_Govori_sveti_Jefrem_Sirijski.jpg',
                'artikal_id' => 5,
            ],
            [
                'naziv_fajla' => 'vera_svetih_katihizis_sveti_vladika_Nikolaj_Velimirovic.jpg',
                'artikal_id' => 6,
            ],
            [
                'naziv_fajla' => 'srpski_prota_Milutin_Tesla_otac_Nikole_Tesle.jpg',
                'artikal_id' => 7,
            ],
        ];

        foreach ($slikeArtikala as $slikaArtiklaPodaci) {
            ArtikalSlika::create($slikaArtiklaPodaci);
        }
    }
}
