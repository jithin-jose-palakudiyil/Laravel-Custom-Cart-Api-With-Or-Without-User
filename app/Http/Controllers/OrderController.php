<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Cart;
use App\Models\Products;
use App\Models\CartItems;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{   
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $query = Order::where('id', $request->order_id);
        if (\Auth::guard('custom_api')->check()) { 
            $userID = auth('custom_api')->user()->id;
            $query->where('userID',$userID);
        }
        $order =$query->get(); 
        if ( $order): 
            foreach( $order as  $val):
                $product_ids = json_decode($val->products);
                $products = Products::whereIn('id',$product_ids)
                ->select('id', 'category_id','label','type','DownloadURL','Weight','price')
                ->with(['Category' => function ($qu) {
                    $qu->select('id', 'label'); 
                }])->get()->toArray(); 
                $val->products =$products;
            endforeach; 
            return response()->json([ 'order' =>  $order->toArray() ], 200); 
        else: 
            return response()->json([ 'message' => 'The order id you provided does not match the order list.', ], 404);
        endif;
    }

     
  
}
