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

        // Fetch four random recommended books, excluding soft-deleted ones
        $preporuceneKnjige = Knjiga::whereHas('artikal', function ($query) {
            $query->whereNull('deleted_at');
        })
            ->whereNull('knjigas.deleted_at')
            ->with('artikal.artikalSlike')
            ->inRandomOrder()
            ->take(4)
            ->get();

        // Process each book to format prices and descriptions
        foreach ($preporuceneKnjige as $knjiga) {
            $knjiga->artikal->formatiranaCijena = formatirajCijenu($knjiga->artikal->cijena);
            $knjiga->artikal->formatiranaAkcijskaCijena = $knjiga->artikal->akcijska_cijena ? formatirajCijenu($knjiga->artikal->akcijska_cijena) : null;
            $knjiga->artikal->kratkiOpis = Str::words($knjiga->artikal->opis, 35);
        }

        // Uzima prvu knjigu sa artiklom i slikom artikla
        $knjigaGodine = Knjiga::with(['artikal.artikalSlike'])->where('artikal_id', 4)->first();
        $formatiranaCijena = formatirajCijenu($knjigaGodine->artikal->cijena);
        $formatiranaAkcijskaCijena = formatirajCijenu($knjigaGodine->artikal->akcijska_cijena);
        $opis = Str::words($knjigaGodine->artikal->opis, 130);

        return view('index', compact(
            'knjigaGodine',
            'formatiranaCijena',
            'formatiranaAkcijskaCijena',
            'opis',
            'preporuceneKnjige'
        ));
    }

    public function listaKnjiga(Request $request, $vrstaArtikla = null)
    {
        if ($vrstaArtikla) {
            // Trazi vrstu artikala po unesenom id-u
            $vrstaArtikla = VrstaArtikala::findOrFail($vrstaArtikla);

            // Trazi knjige koje su artiklom povezane sa vrstom artikla
            // i dje artikal nije soft-deleted
            $knjige = Knjiga::whereHas('artikal', function ($query) {
                $query->whereNull('deleted_at');
            })
                ->whereNull('knjigas.deleted_at')
                ->whereHas('artikal.vrsteArtikla', function ($query) use ($vrstaArtikla) {
                    $query->where('vrsta_artikalas.id', $vrstaArtikla->id);
                })
                ->with('artikal.artikalSlike')
                ->simplePaginate(8);
        } else {
            // Trazi knjige ciji artikal nije soft-deleted
            $knjige = Knjiga::whereHas('artikal', function ($query) {
                $query->whereNull('deleted_at');
            })
                ->whereNull('knjigas.deleted_at')
                ->with('artikal.artikalSlike')
                ->simplePaginate(8);
        }

        foreach ($knjige as $knjiga) {
            $knjiga->artikal->formatiranaCijena = formatirajCijenu($knjiga->artikal->cijena);
            $knjiga->artikal->formatiranaAkcijskaCijena = formatirajCijenu($knjiga->artikal->akcijska_cijena);
            $knjiga->artikal->kratkiOpis = Str::words($knjiga->artikal->opis, 35);
        }

        return view('knjige.index', compact('knjige', 'vrstaArtikla'));
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
