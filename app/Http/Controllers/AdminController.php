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
        $checkPasscode = $this->checkPasscode($request);

        if (! $checkPasscode) {
            return response()->json(['status' => 'error', 'message' => 'Invalid passcode'], 400);
        }

        $validator = Validator::make($request->all(), [
            "status" => (isset($request->status)) ? "in:all,pending,completed" : "",
        ]);
        if ($validator->fails()) return response()->json(['status' => "error", "message" => $validator->errors()->first()], 400);
        
        $order = OrderStatus::query();
        $order->when(isset($request->status) && ($request->status != "all"), function ($query) use ($request) {
            $query->where("status", "=", $request->status);
        });
        
        $order = $order->with('order')->latest()->paginate(20);

        return response()
            ->json([
                'status' => 'success', 
                'message' => 'Successful', 
                'data' => $order
            ],
        200);
    }

    public function showWithStatus(Request $request, $id)
    {
        $checkPasscode = $this->checkPasscode($request);

        if (! $checkPasscode) {
            return response()->json(['status' => 'error', 'message' => 'Invalid passcode'], 400);
        }

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

    public function completeOrder(Request $request, $id)
    {
        $checkPasscode = $this->checkPasscode($request);
        if (! $checkPasscode) {
            return response()->json(['status' => 'error', 'message' => 'Invalid passcode'], 400);
        }

        $result = OrderStatus::where("order_id", $id)->first();
        if($result) {
            $result->update([
                "status" => "completed",
            ]);

            return response()->json(['status' => 'success', 'message' => 'successful'], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
    }

    private function checkPasscode($request) {
        $hashedPassword = Passcode::first()->passcode;
        $result = Hash::check($request->passcode, $hashedPassword);

        return $result;
    }
}
