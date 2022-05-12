<?php

namespace App\Http\Controllers;

use App\Mail\OrderCompleted;
use App\Models\Order;
use App\Models\OrderStatus;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        if ($request->status) {
            $order = Order::with(['order_status' => function ($query) use ($request) {
                $query->where('order_status.status', $request->status);
            }])->paginate(20);
        } else {
            $order = Order::with('order_status')->paginate(20);
        }

        return response()
            ->json([
                'status' => 'success', 
                'message' => 'Successful', 
                'data' => $order
            ],
        200);
    }

    public function orderStatus(Request $request)
    {
        $validator = Validator::make($request->all(),  [
            "order_id" => "required|string",
            "zip_file" => "string"
        ]);

        if ($validator->fails()) return response()->json(['status' => "error", "message" => $validator->errors()->first()], 400);
        
        try {
            $order_status = OrderStatus::where('order_id', $request->order_id)->first();
            
            if (!$order_status) return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
            
            $result = $order_status->with('order')->first();
        
            Mail::to($result->order->email)->send(new OrderCompleted($result->order));
            
            $order_status->update(['status' => 'completed']);
            
            return response()->json(['status' => 'success', 'message' => 'Successful'], 200);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public function showWithStatus($id)
    {
        $order = Order::where('id', $id)->with('order_status')->first();
        if (!$order) return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);

        return response()->json(['status' => 'success', 'message' => 'successful', 'data' => $order], 200);
    }
}
