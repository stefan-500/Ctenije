<?php

namespace App\Http\Controllers;

use App\Models\Knjiga;
use App\Models\VrstaArtikala;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KnjigaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Uzima prvu knjigu sa artiklom i slikom artikla
        $knjigaGodine = Knjiga::with(['artikal.artikalSlike'])->where('artikal_id', 4)->first();

        $cijena = round($knjigaGodine->artikal->cijena / 100, 2);
        $formatiranaCijena = number_format($cijena, 2, ',', '');

        $akcijskaCijena = round(($knjigaGodine->artikal->akcijska_cijena / 100), 2);
        $formatiranaAkcijskaCijena = number_format($akcijskaCijena, 2, ',', '');
        // Opis artikla je ogranicen na 100 rijeci posle kojih se dodaje '...'
        $opis = Str::words($knjigaGodine->artikal->opis, 130);

        //compact('vrsteArtikala', 'knjigaGodine', 'cijena', 'formatiranaCijena', 'opis')
        return view('index', compact('knjigaGodine', 'cijena', 'formatiranaCijena', 'formatiranaAkcijskaCijena', 'opis'));
    }

    public function listaKnjiga(Request $request, $vrstaArtikla = null)
    {
        if ($vrstaArtikla) {
            // Trazi vrstu artikala po unesenom id-u
            $vrstaArtikla = VrstaArtikala::where('id', $vrstaArtikla)->firstOrFail();

            // Trazi knjige koje su artiklom povezane sa vrstom artikla
            $knjige = Knjiga::whereHas('artikal.vrsteArtikla', function ($query) use ($vrstaArtikla) {
                $query->where('vrsta_artikalas.id', $vrstaArtikla->id);
            })->with('artikal.artikalSlike')->simplePaginate(8);
        } else {
            $knjige = Knjiga::with('artikal.artikalSlike')->simplePaginate(8);
        }

        // Formatiranje cijene i opisa za svaku knjigu u kolekciji
        foreach ($knjige as $knjiga) {
            $cijena = round($knjiga->artikal->cijena / 100, 2);
            $knjiga->artikal->formatiranaCijena = number_format($cijena, 2, ',', '');

            $knjiga->artikal->kratkiOpis = Str::words($knjiga->artikal->opis, 35);
        }

        return view('knjige.index', compact('knjige', 'vrstaArtikla'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public function show($artikal_id)
    {
        // Trazi knjigu po artikal_id iz request-a
        $knjiga = Knjiga::with('artikal.artikalSlike')->where('artikal_id', $artikal_id)->firstOrFail();

        $cijena = round($knjiga->artikal->cijena / 100, 2);
        $knjiga->artikal->formatiranaCijena = number_format($cijena, 2, ',', '');

        $akcijskaCijena = round($knjiga->artikal->akcijska_cijena / 100, 2);
        $knjiga->artikal->formatiranaAkcijskaCijena = number_format($akcijskaCijena, 2, ',', '');

        return view('knjige.knjiga', compact('knjiga'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Knjiga $knjiga)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Knjiga $knjiga)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Knjiga $knjiga)
    {
        //
    }
}
