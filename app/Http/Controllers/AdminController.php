<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Order;
use App\Models\OrderStatus;

class AdminController extends Controller
{
    public function index()
    {
        $order = Order::with('order_status')->paginate(20);

        return response()->json(['status' => 'success', 'message' => 'Successful', 'data' => $order], 200);
    }

    public function toggleOrderStatus($id)
    {
        $order = OrderStatus::where('order_uuid', $id)->first();
        if (!$order) return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);

        $order->update(['status' => $order->status === 'pending' ? 'completed' : 'pending']);
        return response()->json(['status' => 'success', 'message' => 'Successful'], 200);
    }
}
