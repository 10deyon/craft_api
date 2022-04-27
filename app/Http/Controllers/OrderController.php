<?php

namespace App\Http\Controllers;

use App\Mail\OrderCreated;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),  [
            "name" => "required|string",
            "tagline" => "required|string",
            "phone" => "required|string",
            "email" => "required|confirmed"
        ]);

        if ($validator->fails()) return response()->json(['status' => "error", "message" => $validator->errors()->first()], 400);
        
        $order = Order::create([
            "name" => $request->name,
            "tagline" => $request->tagline,
            "phone" => $request->phone,
            "email" => $request->email,
        ]);

        $order->order_status()->create(["status" => 'pending']);
        // Mail::to($request->email)->queue(new OrderCreated($order)); //->later(now()->addMinutes(10), new OrderCreated($order));
        // dispatch(Mail::to($request->email)->send(new OrderCreated($order)));

        return response()->json(['status' => 'success', 'message' => 'Created successfully', 'data' => $order], 201);
    }
    
    public function show($order)
    {
        $order = Order::find($order);
        if (!$order) return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);

        return response()->json(['status' => 'success', 'message' => 'successful', 'data' => $order], 200);
    }
    
    public function update(Request $request, $order)
    {
        $validator = Validator::make($request->all(),  [
            "name" => "string",
            "tagline" => "string",
            "phone" => "string",
            "email" => "confirmed"
        ]);
        
        if ($validator->fails()) return response()->json(['status' => "error", "message" => $validator->errors()->first()], 400);

        $order = Order::where('uuid', $order)->update([
            "name" => $request->name,
            "tagline" => $request->tagline,
            "phone" => $request->phone,
            "email" => $request->email,
        ]);

        // dispatch(Mail::to($request->email)->send(new OrderCreated($request->all())));

        return response()->json(['status' => 'success', 'message' => "Updated successfully"], 200);
    }
}
