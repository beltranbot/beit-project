<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CustomerProduct;

class CustomerProductController extends Controller
{
    public function get_products_by_customer_id(int $customer_id)
    {
        $customer_products = CustomerProduct::where('customer_id', '=', $customer_id)
            ->with('product')
            ->get();

        return response()->json([
            'customer_products' => $customer_products
        ]);
    }
}
