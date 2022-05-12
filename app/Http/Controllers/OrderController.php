<?php

namespace App\Http\Controllers;

use App\Services\StripeService;
use App\Mail\OrderCreated;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    const LOGO_AMOUNT = 25;
    const CURRENCY = 'usd', CENT = 100;
    public $stripe;

    public function __construct(StripeService $stripe)
    {
        $this->stripe = $stripe;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),  [
            "name" => "required|string",
            "tagline" => "required|string",
            "phone" => "required|string",
            "email" => "required|confirmed",
            "amount" => "required|integer"
        ]);

        if ($validator->fails()) return response()->json(['status' => "error", "message" => $validator->errors()->first()], 400);

        DB::beginTransaction();
        
        try {
            $order = Order::create([
                "name" => $request->name,
                "tagline" => $request->tagline,
                "phone" => $request->phone,
                "email" => $request->email,
                "amount" => $request->amount
            ]);
    
            $order->order_status()->create(["status" => 'pending']);
            
            // Mail::to($request->email)->send(new OrderCreated($order));
    
            $request->request->add(['order_id' => $order->id]);
            $clientSecret = $this->stripe->handle($request);
            
            $order['clientSecret'] = $clientSecret;
            
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Created successfully', 'data' => $order], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'an error occured'], 500);
        }
        
    }

    private function stripePayment()
    {

    }

    public function show($order)
    {
        $order = Order::find($order);
        if (!$order) return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);

        return response()->json(['status' => 'success', 'message' => 'successful', 'data' => $order], 200);
    }

    // public function update(Request $request, $order)
    // {
    //     $validator = Validator::make($request->all(),  [
    //         "name" => "string",
    //         "tagline" => "string",
    //         "phone" => "string",
    //         "email" => "confirmed"
    //     ]);

    //     if ($validator->fails()) return response()->json(['status' => "error", "message" => $validator->errors()->first()], 400);

    //     $order = Order::where('id', $order)->update([
    //         "name" => $request->name,
    //         "tagline" => $request->tagline,
    //         "phone" => $request->phone,
    //         "email" => $request->email,
    //     ]);

    //     // Mail::to($request->email)->send(new OrderCreated($order));

    //     return response()->json(['status' => 'success', 'message' => "Updated successfully"], 200);
    // }
}
