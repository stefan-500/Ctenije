<?php

namespace App\Services;

use Stripe\PaymentIntent;

class StripePaymentService
{
    public function createPaymentIntent(int $amount, string $currency = 'eur')
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        return PaymentIntent::create([
            'amount' => $amount,
            'currency' => $currency,
        ]);
    }

    public function retrievePaymentIntent(string $paymentIntentId)
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        return PaymentIntent::retrieve($paymentIntentId);
    }

    public function cancelPaymentIntent(string $paymentIntentId)
    {
        $paymentIntent = $this->retrievePaymentIntent($paymentIntentId);
        $paymentIntent->cancel();

        return $paymentIntent;
    }
}
