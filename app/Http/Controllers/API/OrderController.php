<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $customer_id = $request->customer_id;
        $date_start = $request->date_start;
        $date_end = $request->date_end;

        $orders = Order::whereBetween('creation_date', [$date_start, $date_end])
            ->with('order_details.product')
            ->get();

        return response()->json([
            'orders' => $orders
        ]);
    }
}
