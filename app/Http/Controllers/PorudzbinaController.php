<?php

namespace App\Http\Controllers;

use App\Models\Porudzbina;
use Illuminate\Http\Request;
use App\Models\Artikal;
use Illuminate\Support\Facades\Auth;
use App\Models\StavkaPorudzbine;
use App\Services\CartService;

class PorudzbinaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::check()) {
            // Za prijavljene korisnike trenutna porudzbina se uzima iz baze podataka
            $porudzbina = Porudzbina::where('user_id', Auth::id())
                ->where('status', 'neobradjeno')
                ->with('stavkePorudzbine.artikal')
                ->first();

            // Ako nema trenutne porudzbine cart ce biti prazan niz
            $stavkePorudzbine = $porudzbina ? $porudzbina->stavkePorudzbine : collect();

            foreach ($stavkePorudzbine as $stavka) {
                $stavka->artikal->cijena = formatirajCijenu($stavka->artikal->cijena);
                // Ako akcijska cijena postoji formatira se,
                $stavka->artikal->akcijska_cijena = $stavka->artikal->akcijska_cijena ? formatirajCijenu($stavka->artikal->akcijska_cijena) : $stavka->artikal->akcijska_cijena;
                $stavka->ukupna_cijena = formatirajCijenu($stavka->ukupna_cijena);
            }

            $cijenaPorudzbine = $porudzbina ? $porudzbina->ukupno : 0;
        } else {
            // Ukupna cijena porudzbine se obracunava pomocu stavci iz sesije.
            $stavkePorudzbine = session()->get('cart', []);

            // Ukupna cijena porudzbine
            $cijenaPorudzbine = array_reduce($stavkePorudzbine, function ($ukupno, $stavka) {
                return $ukupno + $stavka['kolicina'] * $stavka['cijena'];
            }, 0);

            foreach ($stavkePorudzbine as &$stavka) {
                $stavka['formatirana_cijena'] = formatirajCijenu($stavka['cijena']);
                $stavka['formatirana_ukupna_cijena'] = formatirajCijenu($stavka['kolicina'] * $stavka['cijena']);
            }

            $porudzbina = null;
        }

        $formatiranaCijenaPorudzbine = formatirajCijenu($cijenaPorudzbine);

        return view('cart', compact('porudzbina', 'stavkePorudzbine', 'formatiranaCijenaPorudzbine'));
    }

    public function addToCart(Request $request)
    {

        $artikal = Artikal::findOrFail($request->artikal_id);

        if (Auth::check()) {
            // Ako je korisnik prijavljen, Porudzbina i StavkaPorudzbine se cuvaju u bazi podataka
            $porudzbina = Porudzbina::firstOrCreate(
                [
                    'user_id' => Auth::id(),
                    'status' => 'neobradjeno'
                ],
                [
                    'datum' => now(),
                    'ukupno' => 0
                ]
            );

            // Ako stavka ne postoji za trenutnu porudzbinu kreira se
            $stavkaPorudzbine = StavkaPorudzbine::firstOrCreate(
                [
                    'porudzbina_id' => $porudzbina->id,
                    'artikal_id' => $artikal->id
                ],
                [
                    'kolicina' => 1,
                    'ukupna_cijena' => $artikal->akcijska_cijena ?? $artikal->cijena,
                ]
            );

            // Ako stavka vec postoji kolicina i ukupna cijena se azuriraju
            if (!$stavkaPorudzbine->wasRecentlyCreated) {
                $stavkaPorudzbine->increment('kolicina');
                $stavkaPorudzbine->ukupna_cijena = $stavkaPorudzbine->artikal->akcijska_cijena ?? $stavkaPorudzbine->artikal->cijena * $stavkaPorudzbine->kolicina;
                $stavkaPorudzbine->save();
            }

            $porudzbina->ukupno = $porudzbina->stavkePorudzbine->sum('ukupna_cijena');
            $porudzbina->save();

            $cartCount = $porudzbina->stavkePorudzbine->sum('kolicina');

        } else {
            // Ako korisnik nije prijavljen podaci se cuvaju u sesiji

            // Uzima trenutni cart iz sesije ili inicijalizuje niz;
            $cart = session()->get('cart', []);

            // Ako artikal postoji u korpi kolicina se uvecava
            if (isset($cart[$artikal->id])) {
                $cart[$artikal->id]['kolicina']++;
            } else {
                $cart[$artikal->id] = [
                    'artikal_id' => $artikal->id,
                    'naziv' => $artikal->naziv,
                    'cijena' => $artikal->akcijska_cijena ?? $artikal->cijena,
                    'kolicina' => 1
                ];
            }

            $cartCount = 0;
            // Azuriranje broja artikala u sesiji za prikaz u navigaciji
            foreach ($cart as $cartItem) {
                $cartCount += $cartItem['kolicina'];
            }

            session()->put('cart', $cart);
            session()->put('cart_count', $cartCount);
        }

        return response()->json(['cart_count' => $cartCount]);
    }

    public function getCartCount()
    {
        $cartService = new CartService();
        $cartCount = $cartService->getCartCount();

        // Vraca json podatak koji se prikazuje pomocu JS - cart.js
        return response()->json(['cart_count' => $cartCount]);
    }

    public function incrementQuantity(Request $request)
    {
        $artikalId = $request->input('artikal_id');

        if (Auth::check()) {
            // Trenutna porudzbina prijavljenog korisnika
            $porudzbina = Porudzbina::where('user_id', Auth::id())
                ->where('status', 'neobradjeno')
                ->with('stavkePorudzbine')
                ->firstOrFail();

            $stavka = StavkaPorudzbine::where('porudzbina_id', $porudzbina->id)
                ->where('artikal_id', $artikalId)
                ->firstOrFail();

            $stavka->kolicina++;
            $stavka->ukupna_cijena = $stavka->kolicina * ($stavka->artikal->akcijska_cijena ?? $stavka->artikal->cijena);
            $stavka->save();

            // Ponovno ucitavanje stavki porudzbine zbog azuriranja stavke
            $porudzbina->load('stavkePorudzbine');

            $porudzbina->ukupno = $porudzbina->stavkePorudzbine->sum('ukupna_cijena');
            $porudzbina->save();
            $cijenaPorudzbine = $porudzbina->ukupno;

            $cartCount = $porudzbina->stavkePorudzbine->sum('kolicina');
        } else {
            // Neprijavljeni korisnik
            $cart = session()->get('cart', []);

            $artikalId = $request->input('artikal_id');

            if (isset($cart[$artikalId])) {
                $cart[$artikalId]['kolicina']++;
                $cart[$artikalId]['ukupna_cijena'] = $cart[$artikalId]['kolicina'] * $cart[$artikalId]['cijena'];
                session()->put('cart', $cart);
            }

            $cijenaPorudzbine = array_sum(array_column($cart, 'ukupna_cijena'));

            $cartCount = array_sum(array_column($cart, 'kolicina'));
            $stavka = $cart[$artikalId];
        }

        $formatiranaUkupnaCijenaStavke = formatirajCijenu($stavka['ukupna_cijena']);
        $formatiranaCijenaPorudzbine = formatirajCijenu($cijenaPorudzbine);

        return response()->json([
            'stavka_ukupna_cijena' => $formatiranaUkupnaCijenaStavke,
            'porudzbina_ukupno' => $formatiranaCijenaPorudzbine,
            'cart_count' => $cartCount
        ]);
    }


    public function decrementQuantity(Request $request)
    {
        if (Auth::check()) {
            $porudzbina = Porudzbina::where('user_id', Auth::id())
                ->where('status', 'neobradjeno')
                ->with('stavkePorudzbine')
                ->firstOrFail();

            $stavka = StavkaPorudzbine::where('porudzbina_id', $porudzbina->id)
                ->where('artikal_id', $request->input('artikal_id'))
                ->firstOrFail();

            if ($stavka->kolicina > 1) {
                $stavka->kolicina--;

                $stavka->ukupna_cijena = $stavka->kolicina * ($stavka->artikal->akcijska_cijena ?? $stavka->artikal->cijena);
                $stavka->save();

                // Ponovno ucitavanje stavki porudzbine zbog azuriranja stavke
                $porudzbina->load('stavkePorudzbine');

                $porudzbina->ukupno = $porudzbina->stavkePorudzbine->sum('ukupna_cijena');
                $porudzbina->save();
            }

            $cijenaPorudzbine = $porudzbina->ukupno;
            $cartCount = $porudzbina->stavkePorudzbine->sum('kolicina');
        } else {
            // Neprijavljeni korisnik
            $cart = session()->get('cart', []);

            $artikalId = $request->input('artikal_id');

            if (isset($cart[$artikalId]) && $cart[$artikalId]['kolicina'] > 1) {
                $cart[$artikalId]['kolicina']--;
                $cart[$artikalId]['ukupna_cijena'] = $cart[$artikalId]['kolicina'] * $cart[$artikalId]['cijena'];
                session()->put('cart', $cart);
            }

            $cijenaPorudzbine = array_sum(array_column($cart, 'ukupna_cijena'));

            $cartCount = array_sum(array_column($cart, 'kolicina'));
            $stavka = $cart[$artikalId];
        }

        $formatiranaUkupnaCijenaStavke = formatirajCijenu($stavka['ukupna_cijena']);
        $formatiranaCijenaPorudzbine = formatirajCijenu($cijenaPorudzbine);

        return response()->json([
            'stavka_ukupna_cijena' => $formatiranaUkupnaCijenaStavke,
            'porudzbina_ukupno' => $formatiranaCijenaPorudzbine,
            'cart_count' => $cartCount
        ]);
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

    /**
     * Display the specified resource.
     */
    public function show(Porudzbina $porudzbina)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Porudzbina $porudzbina)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Porudzbina $porudzbina)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Porudzbina $porudzbina)
    {
        //
    }
}
