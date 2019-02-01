<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::namespace('API')->group(function  () {
    Route::get('/customers', 'CustomerController@index');

    Route::get('/customer-products/{customer_id}', 'CustomerProductController@get_products_by_customer_id');
    
    Route::get('/products', 'ProductController@index');

    Route::get('/orders', 'OrderController@index');
});
