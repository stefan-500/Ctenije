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

        $formatiranaCijena = formatirajCijenu($knjigaGodine->artikal->cijena);
        $formatiranaAkcijskaCijena = formatirajCijenu($knjigaGodine->artikal->akcijska_cijena);

        $opis = Str::words($knjigaGodine->artikal->opis, 130);

        return view('index', compact('knjigaGodine', 'formatiranaCijena', 'formatiranaAkcijskaCijena', 'opis'));
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

        foreach ($knjige as $knjiga) {
            $knjiga->artikal->formatiranaCijena = formatirajCijenu($knjiga->artikal->cijena);
            $knjiga->artikal->formatiranaAkcijskaCijena = formatirajCijenu($knjiga->artikal->akcijska_cijena);

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

        $knjiga->artikal->formatiranaCijena = formatirajCijenu($knjiga->artikal->cijena);
        $knjiga->artikal->formatiranaAkcijskaCijena = formatirajCijenu($knjiga->artikal->akcijska_cijena);

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
