<?php

namespace App\Http\Controllers;

use App\Models\GuestDeliveryData;
use App\Models\Porudzbina;
use Illuminate\Http\Request;
use App\Models\Artikal;
use Illuminate\Support\Facades\Auth;
use App\Models\StavkaPorudzbine;
use App\Services\CartService;
use App\Models\User;

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

        if ($artikal->dostupna_kolicina == 0) {
            return response()->json(['error' => __('Nema dostupnih količina za ovaj artikal')], 400);
        }

        if (Auth::check()) {
            // Ako je korisnik prijavljen, Porudzbina i StavkaPorudzbine se cuvaju u bazi podataka
            $porudzbina = Porudzbina::firstOrCreate(
                [
                    'user_id' => Auth::id(),
                    'status' => 'neobradjeno'
                ],
                [
                    'datum' => now(),
                    'ukupno' => 0,
                    'adresa_isporuke' => Auth::user()->adresa
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

            // Ako stavka vec postoji, provjerava se kolicina na lageru 
            if (!$stavkaPorudzbine->wasRecentlyCreated) {
                if ($stavkaPorudzbine->kolicina + 1 > $artikal->dostupna_kolicina) {
                    return response()->json(['error' => __('Ne možete dodati više, dostupna količina je ograničena')], 400);
                }
                // Kolicina i ukupna cijena se azuriraju
                $stavkaPorudzbine->increment('kolicina');
                $stavkaPorudzbine->ukupna_cijena = $stavkaPorudzbine->artikal->akcijska_cijena ?? $stavkaPorudzbine->artikal->cijena * $stavkaPorudzbine->kolicina;
                $stavkaPorudzbine->save();
            }

            $porudzbina->ukupno = $porudzbina->stavkePorudzbine->sum('ukupna_cijena');
            $porudzbina->save();

            $cartCount = $porudzbina->stavkePorudzbine->sum('kolicina');

        } else {
            // Korisnik nije prijavljen - podaci se cuvaju u sesiji

            $cart = session()->get('cart', []);

            // Ako artikal postoji u korpi kolicina se uvecava
            if (isset($cart[$artikal->id])) {
                // Provjera kolicine na lageru
                if ($cart[$artikal->id]['kolicina'] + 1 > $artikal->dostupna_kolicina) {
                    return response()->json(['error' => __('Ne možete dodati više, dostupna količina je ograničena')], 400);
                }

                $cart[$artikal->id]['kolicina']++;
            } else {
                $cart[$artikal->id] = [
                    'artikal_id' => $artikal->id,
                    'naziv' => $artikal->naziv,
                    'cijena' => $artikal->akcijska_cijena ?? $artikal->cijena,
                    'kolicina' => 1
                ];
            }

            $cartCount = array_sum(array_column($cart, 'kolicina'));

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
        $artikal = Artikal::findOrFail($artikalId);

        if (Auth::check()) {
            // Trenutna porudzbina prijavljenog korisnika
            $porudzbina = Porudzbina::where('user_id', Auth::id())
                ->where('status', 'neobradjeno')
                ->with('stavkePorudzbine')
                ->firstOrFail();

            $stavka = StavkaPorudzbine::where('porudzbina_id', $porudzbina->id)
                ->where('artikal_id', $artikalId)
                ->firstOrFail();

            // Provjera da li ima dovoljno kolicine na lageru
            if ($stavka->kolicina >= $stavka->artikal->dostupna_kolicina) {
                return response()->json(['error' => __('Ne možete dodati više, dostupna količina je ograničena.')], 400);
            }

            $stavka->increment('kolicina');
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
                if ($cart[$artikalId]['kolicina'] >= $artikal->dostupna_kolicina) {
                    return response()->json(['error' => __('Ne možete dodati više, dostupna količina je ograničena.')], 400);
                }

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

    public function removeFromCart(Request $request)
    {
        // artikal_id iz AJAX zahtijeva
        $artikalId = $request->input('artikal_id');

        if (Auth::check()) {
            $porudzbina = Porudzbina::where('user_id', Auth::id())
                ->where('status', 'neobradjeno')
                ->with('stavkePorudzbine')
                ->firstOrFail();

            $stavka = StavkaPorudzbine::where('porudzbina_id', $porudzbina->id)
                ->where('artikal_id', $artikalId)
                ->firstOrFail();

            $stavka->delete();

            // Ponovno ucitavanje stavki
            $porudzbina->load('stavkePorudzbine');

            // Ako su obrisane sve stavke porudzbine i porudzbina se brise
            if ($porudzbina->stavkePorudzbine->isEmpty()) {
                $porudzbina->delete();
                $cartCount = 0;
                $porudzbinaUkupno = formatirajCijenu(0);
            } else {
                $porudzbina->ukupno = $porudzbina->stavkePorudzbine->sum('ukupna_cijena');
                $porudzbina->save();

                $cartCount = $porudzbina->stavkePorudzbine->sum('kolicina');
                $porudzbinaUkupno = formatirajCijenu($porudzbina->ukupno);
            }
        } else {
            $cart = session()->get('cart', []);

            // Brisanje stavke iz sesije za neprijavljenog korisnika
            if (isset($cart[$artikalId])) {
                unset($cart[$artikalId]);
            }

            $cartCount = array_sum(array_column($cart, 'kolicina'));

            $cijenaPorudzbine = 0;
            foreach ($cart as $stavka) {
                $cijenaPorudzbine += $stavka['cijena'] * $stavka['kolicina'];
            }
            $porudzbinaUkupno = formatirajCijenu($cijenaPorudzbine);

            session()->put('cart', $cart);
        }

        return response()->json([
            'porudzbina_ukupno' => $porudzbinaUkupno,
            'cart_count' => $cartCount
        ]);
    }

    public function showDeliveryForm()
    {
        if (Auth::check()) {
            // Aktivna porudzbina
            $porudzbina = Porudzbina::where('user_id', Auth::id())
                ->where('status', 'neobradjeno')
                ->with('stavkePorudzbine')
                ->first();

            // Da li aktivna porudzbina postoji, da li porudzbina ima stavke(korisnik moze obrisati stavke)
            if (!$porudzbina || $porudzbina->stavkePorudzbine->isEmpty()) {
                return redirect('/cart')->with('error', __('Nema stavki u korpi za kupovinu.'));
            }

            $user = Auth::user();
        } else {
            // Neprijavljeni korisnik
            $porudzbina = session()->get('cart', []);

            if (empty($porudzbina)) {
                return redirect('/cart')->with('error', __('Nema stavki u korpi za kupovinu.'));
            }

            $user = null;
        }

        return view('dostava', compact('porudzbina', 'user'));
    }

    public function setDeliveryStep()
    {
        // Varijabla sesije koja daje info da je korisnik nastavio na korak porudzbine za dostavu
        session(['nastavak_na_dostavu' => true]);

        return redirect('/dostava');
    }

    public function sacuvajPodatkeDostave(Request $request)
    {
        if (Auth::check()) {
            $porudzbina = Porudzbina::where('user_id', Auth::id())
                ->where('status', 'neobradjeno')
                ->first();

            if (!$porudzbina) {
                return redirect('/cart')->with('error', __('Nema aktivne porudžbine.'));
            }

            return redirect('stripe-payment');
        } else {
            // Neprijavljeni korisnik

            $cart = session()->get('cart', []);

            if (empty($cart)) {
                return redirect('/cart')->with('error', __('Vaša korpa je prazna.'));
            }

            $data = $request->validate([
                'ime' => [
                    'required',
                    'string',
                    'regex:/^[A-Z]{1}[a-zA-Z]{3,15}$/'
                ],
                'prezime' => [
                    'required',
                    'string',
                    'regex:/^[A-Z]{1}[a-zA-Z]{3,15}$/'
                ],
                'email' => [
                    'required',
                    'string',
                    'lowercase',
                    'email',
                    'max:255',
                    'unique:' . User::class
                ],
                'adresa' => [
                    'required',
                    'string',
                    'regex:/^[a-zA-Z]{1,15}(\s[a-zA-Z]{1,15})?(\s[a-zA-Z]{1,12})?(\s[a-zA-Z0-9]{1,20})?\s[a-zA-Z0-9]{1,3}\,\s[0-9]{5}\s[a-zA-z]{3,13}$/'
                ],
                'tel' => [
                    'required',
                    'string',
                    'regex:/^\+3816([0-9]){6,9}$/'
                ],
            ]);

            // Cuvanje podataka za dostavu za neprijavljenog korisnika
            $guestDeliveryData = GuestDeliveryData::create($data);

            // Cuvanje porudzbine 
            $porudzbina = Porudzbina::create([
                'user_id' => null,
                'guest_delivery_data_id' => $guestDeliveryData->id,
                'datum' => now(),
                'adresa_isporuke' => $data['adresa'],
                'ukupno' => array_sum(array_column($cart, 'ukupna_cijena')),
                'status' => 'neobradjeno',
            ]);

            // Cuvanje stavci porudzbine
            foreach ($cart as $stavka) {
                StavkaPorudzbine::create([
                    'porudzbina_id' => $porudzbina->id,
                    'artikal_id' => $stavka['artikal_id'],
                    'kolicina' => $stavka['kolicina'],
                    'ukupna_cijena' => $stavka['kolicina'] * $stavka['cijena'],
                ]);
            }

            // Brisanje cart sesije
            session()->forget('cart');
            // session()->forget('cart_count');

            return redirect('/stripe-payment')->with('success', __('Podaci za dostavu su sačuvani.'));
        }
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
