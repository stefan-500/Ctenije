<?php

namespace App\Http\Controllers;

use App\Mail\PotvrdaPorudzbine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Porudzbina;
use Illuminate\Support\Facades\Mail;
use App\Services\DiscountService;
use App\Services\StripePaymentService;
use Illuminate\Support\Facades\DB;

class PlacanjeController extends Controller
{
    public function prikazFormePlacanja()
    {
        $discountService = app(DiscountService::class);

        if (Auth::check()) {
            $porudzbina = Porudzbina::where('user_id', Auth::id())
                ->where('status', 'neobradjeno')
                ->with('stavkePorudzbine.artikal')
                ->firstOrFail();

            $discountService->recalculateOrder($porudzbina, Auth::user()->email);
            $porudzbina->refresh()->load('stavkePorudzbine.artikal');
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

            $discountService->recalculateOrder($porudzbina, $porudzbina->guestDeliveryData?->email);
            $porudzbina->refresh()->load('stavkePorudzbine.artikal', 'guestDeliveryData');
        }

        $paymentIntent = app(StripePaymentService::class)->createPaymentIntent($porudzbina->ukupno);

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
        $discountService = app(DiscountService::class);

        try {
            $intent = app(StripePaymentService::class)->retrievePaymentIntent($paymentIntentId);

            if ($intent->status == 'succeeded') {
                if (Auth::check()) {
                    // Prijavljeni korisnik
                    $porudzbina = Porudzbina::where('user_id', Auth::id())
                        ->where('status', 'neobradjeno')
                        ->with('stavkePorudzbine.artikal', 'guestDeliveryData')
                        ->firstOrFail();
                    $customerEmail = Auth::user()->email;
                    $customerId = Auth::id();
                } else {
                    // Neprijavljeni korisnik
                    $paymentToken = $request->payment_token;

                    if (!$paymentToken) {
                        return response()->json(['error' => 'Nevažeći token plaćanja.'], 400);
                    }

                    $porudzbina = Porudzbina::where('payment_token', $paymentToken)
                        ->where('status', 'neobradjeno')
                        ->with('stavkePorudzbine.artikal', 'guestDeliveryData')
                        ->firstOrFail();
                    $customerEmail = $porudzbina->guestDeliveryData?->email;
                    $customerId = null;
                }

                DB::transaction(function () use ($porudzbina, $intent, $discountService, $customerEmail, $customerId) {
                    $discountService->recalculateOrder($porudzbina, $customerEmail);
                    $porudzbina->refresh()->load('stavkePorudzbine.artikal');

                    foreach ($porudzbina->stavkePorudzbine as $stavka) {
                        //TODO potrebna provjera dostupne kolicine artikla jer vise korisnika moze poruciti istovremeno
                        $stavka->artikal->dostupna_kolicina -= $stavka->kolicina;
                        $stavka->artikal->save();
                    }

                    $porudzbina->status = 'zakljuceno';
                    $porudzbina->stripe_payment_intent_id = $intent->id;

                    if (!$porudzbina->user_id) {
                        $porudzbina->payment_token = null;
                    }

                    $porudzbina->save();
                    $discountService->redeem($porudzbina->refresh(), $customerEmail, $customerId);
                });

                $porudzbina->refresh()->load('stavkePorudzbine.artikal', 'guestDeliveryData', 'user');

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

        try {
            app(StripePaymentService::class)->cancelPaymentIntent($paymentIntentId);

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
