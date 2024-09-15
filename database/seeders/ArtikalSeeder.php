<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Artikal;

class ArtikalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $artikli =
            [
                // Dostupna kolicina se dodaje dinamicki iz ArtikalFactory-ja

                [
                    'naziv' => 'Vladalac i narod',
                    'opis' => 'Sveobuhvatno teološko, antropološko i istoriografsko delo episkopa Save Dečanca jedan je od najznačajnijih naslova u istoriji pravoslavnog monarhizma. Nezaobilazna poveznica između Svetog Filareta Moskovskog i Svetog Nikolaja Ohridskog i Žičkog, dvojice savremenika sa početka i kraja njegovog života. Obnova krštene monarhije u Srbiji nezamisliva je bez ove knjige.',
                    'cijena' => 550,
                    'akcijska_cijena' => 450,
                    'vrste_artikala' => [1, 2], // Duhovnost, istorija
                ],
                [
                    'naziv' => 'Rečnik spasenja',
                    'opis' => 'U knjizi Rečnik spasenja svaki hrišćanin, i monah i mirjanin, za svaku priliku – od bolesti do radosti, može naći reči utehe i ohrabrenja, istinsku duhovnu pouku i brigu čiji je cilj spasenje ljudske duše. Pojmovnik pravoslavnog življenja.',
                    'cijena' => 1290,
                    'akcijska_cijena' => null,
                    'vrste_artikala' => 1,
                ],
                [
                    'naziv' => 'Tesla: Duhovni lik',
                    'opis' => 'Monografija Tesla: Duhovni lik predstavlja studiju rađenu u saradnji sa Muzejom Nikole Tesle u Beogradu, koji je suizdavač. U knjizi su obuhvaćene mnoge dimenzije ličnosti Nikole Tesle: njegovo poreklo i porodično okruženje, religijski i filozofski stavovi, životna etika i društveni pogledi, rodoljublje i mirotvorstvo.',
                    'cijena' => 6500,
                    'akcijska_cijena' => null,
                    'vrste_artikala' => [2, 3], // Istorija, biografija
                ],
                [
                    'naziv' => 'Ohridski Prolog',
                    'opis' => 'Otkako je Srpskog roda, nije bilo mudrije, i bogomudrije, srpske knjige od Ohridskog prologa Vladike Nikolaja. I zato: ni besmrtnije, ni važnije srpske knjige. I kroza sve to: ni korisnije, jer je od večne koristi za srpskog čoveka i njegova obadva života u obadva sveta.

                    Ohridski prolog je Srpsko Evanđelje, večno Srpsko Evanđelje. U njemu je sve što uvek treba srpskoj duši u obadva sveta, sve što nadživljuje naše zemaljske smrti, sve što savlađuje i umrtvljuje naše grehe, i strasti. Roj za rojem, sveti rojevi blagih vesti za tebe u vascelom životu tvom, koji počinje na zemlji i produžuje se kroza svu večnost u onome svetu. Eto, to je Ohridski prolog, to Srpsko Evanđelje.

                    Srpsko Evanđelje? – Da, jer te uči i nauči večnoj Istini.

                    Srpsko Evanđelje? – Da, jer te uči i nauči večnoj Pravdi.

                    Srpsko Evanđelje? – Da, jer te uči i nauči večnoj Dobroti, večnoj Ljubavi, večnoj Mudrosti, večnoj Lepoti, večnoj Radosti.

                    Srpsko Evanđelje? – Da, jer te uči i nauči večnom Životu. Da, jer te uči i nauči kako se svetim tajnama evanđeljskim i svetim vrlinama evanđeljskim savlađuje i pobeđuje: obmana, laž, srebroljublje, gordost, gnev, pohota, pakost, zloba, zavist, mržnja, zloćudnost, blud, lakomstvo, očajanje, neverje, bezverje, maloverje, poluverje: jednom rečju: svaki greh, svaka strast, svako zlo, svaka smrt, svaki đavo: i time – sav pakao zemaljski i sva iskušenja zemaljska.

                    Evanđelist među Evanđelistima, eto to je sveti pisac ovog Srpskog Evanđelja. Apostol među Apostolima, eto to je sveti pisac ovog Srpskog Evanđelja.

                    Mučenik među Mučenicima, eto to je sveti pisac ovog Srpskog Evanđelja.

                    Ispovednik među Ispovednicima, eto to je sveti pisac ovog Srpskog Evanđelja.

                    Svetitelj među Svetiteljima, eto to je sveti pisac ovog Srpskog Evanđelja...',
                    'cijena' => 1650,
                    'akcijska_cijena' => null,
                    'vrste_artikala' => 1
                ],
                [
                    'naziv' => 'Gospod Govori',
                    'opis' => 'Sveti Jefrem Sirijski je, uz Svetog Jovana Lestvičnika, bio jedan od najčitanijih duhovnih pisaca našeg Srednjeg veka. Ova knjiga pomaže savremenim Srbima da shvate Stari i Novi Zavet kao jedinstvenu celinu Otkrivenja Boga čoveku.',
                    'cijena' => 1400,
                    'akcijska_cijena' => 1250,
                    'vrste_artikala' => 1
                ],
                [
                    'naziv' => 'Vera svetih – Katihizis',
                    'opis' => 'Hrišćanska vera je Hristovo znanje o najvažnijim Tajnama bića i života, koje ljudi mogu shvatiti samo verovanjem u Njega. Ova knjiga pokriva tajne o Bogu, anđelima, ljudskoj duši, stvaranju sveta, grehu i spasenju.',
                    'cijena' => 300,
                    'akcijska_cijena' => null,
                    'vrste_artikala' => 1
                ],
                [
                    'naziv' => 'Srpski Prota Milutin Tesla',
                    'opis' => 'Prva knjiga o Milutinu Tesli donosi nova saznanja o presudnim uticajima oca Milutina na vaspitanje i obrazovanje sina Nikole. Knjiga istražuje životopis Milutina Tesle u svim njegovim aspektima i donosi priloge u originalnom obliku.',
                    'cijena' => 600,
                    'akcijska_cijena' => 500,
                    'vrste_artikala' => [2, 3],
                ],
            ];

        foreach ($artikli as $artikalPodaci) {
            $vrsteArtikala = $artikalPodaci['vrste_artikala'];
            unset($artikalPodaci['vrste_artikala']); // Micanje atributa iz niza podataka

            $artikal = Artikal::factory()->create($artikalPodaci);

            // Povezivanje vrsta artikala sa artiklom
            $artikal->vrsteArtikla()->attach($vrsteArtikala);
        }

    }
}
