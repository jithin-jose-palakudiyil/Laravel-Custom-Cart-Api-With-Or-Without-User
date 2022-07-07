<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/
Route::group([ 'middleware' => 'api' ], function ($router) 
{
    //login the user and creae token
    Route::post('/login', [ App\Http\Controllers\LoginController::class, 'login']); 
    //create cart
    Route::apiResource('cart', '\App\Http\Controllers\CartController')->except(['update', 'show','destroy']); 
    //add products to the cart
    Route::post('/cart/products/{product_id}', [ App\Http\Controllers\CartController::class, 'addProducts']); 
     //create an order from the cart
    Route::post('/order', [ App\Http\Controllers\CartController::class, 'Order']);
    //retrieve a list of products in the order
    Route::get('/order/{order_id}', [ App\Http\Controllers\OrderController::class, 'show']); 
    

    Route::group(['middleware' =>  'custom_guard:custom_api'], function()
    {
        //logout the user and remove token
        Route::any('/logout', [ App\Http\Controllers\LoginController::class, 'logout']); 
    }); 
});
