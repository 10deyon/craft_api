<?php

namespace App\Services;

use App\Models\Transaction;

class StripeService
{
   const CURRENCY = 'usd', CENT = 100;

   public function handle ($request)
   {
      \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
      
      $order_amount = $request->amount * self::CENT;
      $stripeRequest = [
         'amount' => $order_amount,
         'currency' => self::CURRENCY,
         'automatic_payment_methods' => [
            'enabled' => true,
         ],
      ];

      $paymentIntent = \Stripe\PaymentIntent::create($stripeRequest);

      Transaction::create([
         'stripe_request' => json_encode($stripeRequest),
         'stripe_response' => json_encode($paymentIntent),
         'order_id' => $request->order_id
      ]);
      
      return $paymentIntent->client_secret;
   }
}
