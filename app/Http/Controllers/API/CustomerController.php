<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerProduct;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = [];
        $customers = Customer::all();
        // if ($request->has('name')) {
        //     $name = strtolower(trim($request->name));

        //     if (strlen($name) >= 3) {
        //         $customers = Customer::where('name', 'like', "%$name%")->get();
        //     }
        // }

        return response()->json([
            'customers' => $customers
        ]);
    }


}
