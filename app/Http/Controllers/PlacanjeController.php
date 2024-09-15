<?php

namespace App\Http\Controllers;

use App\Mail\PotvrdaPorudzbine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Porudzbina;
use Illuminate\Support\Facades\Mail;

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

            // Maybe there is no paymentToken?? From which request is it get?
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

        // Create a PaymentIntent
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $intent = \Stripe\PaymentIntent::create([
            'amount' => $porudzbina->ukupno, // amount saved in cents in database
            'currency' => 'eur',
        ]);

        // Pass the client secret and payment token to the view
        return view('placanje.placanje-form', [
            'porudzbina' => $porudzbina,
            'stripeKey' => config('services.stripe.key'),
            'clientSecret' => $intent->client_secret,
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

                    // Optionally, you can update the order status or take other actions
                    // For example, set a flag on the order indicating email sending failed

                    // Moguce obavijestiti korisnika da je porudzbina uspijesna ali da mejl nije poslat
                    return response()->json([
                        'success' => true,
                        'message' => 'Plaćanje je uspiješno, ali trenutno nije moguće poslati email sa potvrdom.'
                    ]);
                }

                // Clear session data if necessary
                session()->forget('nastavak_na_dostavu');

                return response()->json(['success' => true]);

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
        return view('placanje.uspijeh');
    }

    public function placanjeOtkazano()
    {
        return view('placanje.otkazano');
    }


}
