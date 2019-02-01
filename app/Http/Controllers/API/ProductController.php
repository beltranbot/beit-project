<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = [];
        $products = Product::all();
        // if ($request->has('name')) {
        //     $name = strtolower(trim($request->name));

        //     if (strlen($name) >= 3) {
        //         $customers = Customer::where('name', 'like', "%$name%")->get();
        //     }
        // }

        return response()->json([
            'products' => $products
        ]);
    }
}
