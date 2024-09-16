<?php

namespace App\Http\Controllers;

use App\Mail\PotvrdaPorudzbine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Porudzbina;
use Illuminate\Support\Facades\Mail;
use Stripe\PaymentIntent;

class PlacanjeController extends Controller
{
    public function prikazFormePlacanja()
    {
        if (Auth::check()) {
            $porudzbina = Porudzbina::where('user_id', Auth::id())
                ->where('status', 'neobradjeno')
                ->with('stavkePorudzbine.artikal')
                ->firstOrFail();

            $paymentToken = null; // Prijavljeni korisnik ne koristi payment_token
        } else {
            //Neprijavljeni korisnik

            $paymentToken = request()->query('payment_token');

            if (!$paymentToken) {
                return redirect('/')->with('error', 'Nevažeći token plaćanja.');
            }

            // Aktivna porudzbina
            $porudzbina = Porudzbina::where('payment_token', $paymentToken)
                ->where('status', 'neobradjeno')
                ->with('stavkePorudzbine.artikal', 'guestDeliveryData')
                ->firstOrFail();
        }

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $paymentIntent = PaymentIntent::create([
            'amount' => $porudzbina->ukupno,
            'currency' => 'eur',
        ]);

        return view('placanje.placanje-form', [
            'porudzbina' => $porudzbina,
            'stripeKey' => config('services.stripe.key'),
            'clientSecret' => $paymentIntent->client_secret,
            'paymentIntentId' => $paymentIntent->id,
            'paymentToken' => $paymentToken,
        ]);
    }

    public function obradaPlacanja(Request $request)
    {
        $paymentIntentId = $request->payment_intent_id;

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $intent = \Stripe\PaymentIntent::retrieve($paymentIntentId);

            if ($intent->status == 'succeeded') {
                if (Auth::check()) {
                    // Prijavljeni korisnik
                    $porudzbina = Porudzbina::where('user_id', Auth::id())
                        ->where('status', 'neobradjeno')
                        ->with('stavkePorudzbine.artikal')
                        ->firstOrFail();
                } else {
                    // Neprijavljeni korisnik
                    $paymentToken = $request->payment_token;

                    if (!$paymentToken) {
                        return response()->json(['error' => 'Nevažeći token plaćanja.'], 400);
                    }

                    $porudzbina = Porudzbina::where('payment_token', $paymentToken)
                        ->where('status', 'neobradjeno')
                        ->with('stavkePorudzbine.artikal')
                        ->firstOrFail();
                }

                foreach ($porudzbina->stavkePorudzbine as $stavka) {
                    //TODO potrebna provjera dostupne kolicine artikla jer vise korisnika moze poruciti istovremeno

                    // Umanjivanje dostupne kolicine artikla za porucenu kolicinu svake stavke porudzbine
                    $stavka->artikal->dostupna_kolicina -= $stavka->kolicina;
                    $stavka->artikal->save();
                }
                // Azuriranje statusa i cuvanje Stripe Intent ID
                $porudzbina->status = 'zakljuceno';
                $porudzbina->stripe_payment_intent_id = $intent->id; // ID transakcije

                // Ponistavanje payment_token-a za neprijavljenog korisnika
                if (!$porudzbina->user_id) {
                    $porudzbina->payment_token = null;
                }

                $porudzbina->save();

                // Slanje potvrde porudzbine mejlom
                $primalac = Auth::check() ? Auth::user()->email : $porudzbina->guestDeliveryData->email;

                try {
                    Mail::to($primalac)->send(new PotvrdaPorudzbine($porudzbina));

                    $porudzbina->email_poslat = true;
                    $porudzbina->save();

                } catch (\Exception $e) {
                    // Logovanje greske
                    \Log::error('Neuspiješno slanje mejla potvrde porudžbine: ' . $e->getMessage());

                    // Moguce obavijestiti korisnika da je porudzbina uspijesna ali da mejl nije poslat
                    return response()->json([
                        'success' => true,
                        'message' => 'Plaćanje je uspiješno, ali trenutno nije moguće poslati email sa potvrdom.'
                    ]);
                }

                session()->forget('cart');
                session()->forget('nastavak_na_dostavu');
                session()->forget('guest_delivery_data');

                session()->put('payment_success', true);
                // Ogranicavanje pristupa za /placanje/otkazano
                session()->forget('payment_canceled');

                return response()->json(['success' => true]);

            } else if ($intent->status == 'requires_payment_method' || $intent->status == 'canceled') {
                // Ako korisnik otkaze placanje
                session()->put('payment_canceled', true);
                session()->forget('payment_success');
            } else {

                return response()->json(['error' => 'Plaćanje neuspiješno.'], 400);
            }
        } catch (\Exception $e) {
            \Log::error('Neuspiješna obrada plaćanja: ' . $e->getMessage());
            return response()->json(['error' => 'Došlo je do greške prilikom obrade plaćanja.'], 500);
        }
    }

    public function placanjeUspijeh()
    {
        if (!session()->has('payment_success')) {
            return redirect('/')->with('error', 'Neovlašćen pristup.');
        }

        // Brisanje sesije nakon posjete stranice
        session()->forget('payment_success');

        return view('placanje.uspijeh');
    }



    public function setPlacanjeOtkazano(Request $request)
    {
        $paymentIntentId = $request->payment_intent_id;
        \Log::info('Received payment_intent_id for cancellation: ' . $paymentIntentId);

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        try {
            // Trazenje instance PaymentIntent-a
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
            $paymentIntent->cancel();

            session()->put('payment_canceled', true);
            session()->forget('payment_success');

            \Log::info('PaymentIntent canceled successfully: ' . $paymentIntentId);

            return response()->json(['status' => 'Plaćanje otkazano.']);
        } catch (\Exception $e) {
            \Log::error('Neuspješna obrada otkazivanja plaćanja: ' . $e->getMessage());
            return response()->json(['error' => 'Došlo je do greške prilikom otkazivanja plaćanja.'], 500);
        }
    }



    public function placanjeOtkazano()
    {

        \Log::info('Accessing placanjeOtkazano. Session flags:');
        \Log::info('payment_canceled: ' . session()->has('payment_canceled'));
        \Log::info('payment_success: ' . session()->has('payment_success'));

        // Check if the user came from a canceled payment
        if (!session()->has('payment_canceled')) {
            return redirect('/')->with('error', 'Neovlašćen pristup.');
        }

        // Brisanje sesije nakon posjete stranice
        session()->forget('payment_canceled');
        // session()->forget('payment_success'); 

        return view('placanje.otkazano');
    }

}
