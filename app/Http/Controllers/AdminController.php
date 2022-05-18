<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Passcode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

    public function showWithStatus($id)
    {
        $order = Order::where('id', $id)->with('order_status')->first();
        if (!$order) return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);

        return response()->json(['status' => 'success', 'message' => 'successful', 'data' => $order], 200);
    }

    public function verifyPasscode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "passcode" => "required|string",
        ]);
        if ($validator->fails()) return response()->json(['status' => "error", "message" => $validator->errors()->first()], 400);
        
        $hashedPassword = Passcode::first()->passcode;
        $result = Hash::check($request->passcode, $hashedPassword);
        
        if ($result) {
            return response()->json(['status' => 'success', 'message' => 'successful'], 200);
        }

        return response()->json(['status' => 'error', 'message' => 'Invalid passcode'], 400);
    }

    public function completeOrder($id)
    {
        $result = OrderStatus::where("order_id", $id)->first();
        if($result) {
            $result->update([
                "status" => "completed",
            ]);

            return response()->json(['status' => 'success', 'message' => 'successful'], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
    }
}
