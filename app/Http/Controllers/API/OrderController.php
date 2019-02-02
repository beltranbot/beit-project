<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\OrderGetIndexRequest;
use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    public function index(OrderGetIndexRequest $request)
    {
        $customer_id = $request->customer_id;
        $date_start = $request->date_start;
        $date_end = $request->date_end;

        $orders = Order::whereBetween('creation_date', [$date_start, $date_end])
            ->where('customer_id', $customer_id)
            ->with('order_details.product')
            ->get();

        return response()->json([
            'orders' => $orders
        ]);
    }
}
