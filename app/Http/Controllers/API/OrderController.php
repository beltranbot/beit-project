<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\OrderGetIndexRequest;
use App\Http\Requests\OrderStoreRequest;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;

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

    public function store(OrderStoreRequest $request)
    {
        $order = new Order;
        $order->customer_id = $request->customer_id;
        $order->creation_date = $request->creation_date;
        $order->delivery_address = $request->delivery_address;
        
        $order_details = [];
        $total = 0;

        foreach ($request->order_details as $order_line) {
            
            $product = Product::findOrFail($order_line['product_id']);

            $order_detail = new OrderDetail;
            $order_detail->product_id = $product->product_id;
            $order_detail->product_description = $product->product_description;
            $order_detail->price = $product->price;
            $order_detail->quantity = $order_line['quantity'];

            $total += $order_detail->price * $order_detail->quantity;
            $order_details[] = $order_detail;
        }

        $order->total = $total;
        
        $order->save();
        $order->order_details()->saveMany($order_details);

        return response()->json([
            'order' => $order
        ], 201);
    }

}
