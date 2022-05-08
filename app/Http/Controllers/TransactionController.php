<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    const CURRENCY = 'usd', CENT = 100;
    
    public function makePayment (Request $request)
    {
        $validator = Validator::make($request->all(),  [
            "order_id" => "required|string",
            "amount" => "required|integer"
        ]);

        if ($validator->fails()) return response()->json(['status' => "error", "message" => $validator->errors()->first()], 400);

        $order = Order::find($request->order_id);
        if (!$order) return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);

        $order->update(['amount' => $request->amount]);
        return response()->json(['status' => 'success', 'message' => "Successful"], 200);
    }
    
    public function chargeCard(Request $request)
    {
        $validator = Validator::make($request->all(),  [
            "stripeToken" => "required|string",
            "order_id" => "required|string",
        ]);

        if ($validator->fails()) return response()->json(['status' => "error", "message" => $validator->errors()->first()], 400);

        $order = Order::find($request->order_id);
        if (!$order) return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
        
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $order_amount = $order->amount * self::CENT;
        $stripeRequest = [
            'amount' => $order_amount,
            'currency' => self::CURRENCY,
            'source' => $request->stripeToken,
            'description' => "Payment for {$order->name} ($order->tagline) logo"
        ];

        $stripeResponse = \Stripe\Charge::create($stripeRequest);

        if (! empty($stripeResponse)) {
            if ($stripeResponse->status  === "succeeded") {
                Transaction::create([
                    'payment_status' => $stripeResponse->paid,
                    'stripe_request' => json_encode($stripeRequest),
                    'stripe_response' => json_encode($stripeResponse),
                    'order_id' => $order->id
                ]);

                // if ($stripeResponse->amount < $order_amount) {
                //     $balance = $stripeResponse->amount - $order_amount;
                //     return response()->json(['status' => 'error', 'message' => 'Incomplete payment. Your remaining balance is ' . $balance], 400);
                // }
                return response()->json(['status' => 'success', 'message' => "Payment successful"], 200);
            }

            return response()->json(['status' => 'error', 'message' => 'Payment not successful'], 400);
        }

        return response()->json(['status' => 'error', 'message' => "Error processing payment"], 400);
    }
}
