<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Products;
use App\Models\CartItems;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; 

class CartController extends Controller
{ 
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [ 'cartKey' => 'required', ]);
        if ($validator->fails()) { return response()->json([ 'errors' => $validator->errors(), ], 422); } 
        
        $cart = Cart::where('key',$request->cartKey)->first();
        if ( $cart): 
            return response()->json([ 'cart' => $cart->id, 'items' => $this->getCartItems($cart->id)->toArray(), ], 200); 
        else: 
            return response()->json([ 'message' => 'The CarKey you provided does not match the Cart Key for this Cart.', ], 404);
        endif; 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (\Auth::guard('custom_api')->check()) {  $userID = auth('custom_api')->user()->id;  }
         
        $cart = Cart::create([ 
            'key' => md5(uniqid(rand(), true)),
            'userID' => isset($userID) ? $userID : null,

        ]);
        
        return response()->json([
            'Message' => 'A new cart have been created for you!',
            'cartId' => $cart->id,
            'cartKey' => $cart->key,
        ]);

    } 
 
    /**
     * Adds Products to the given Cart;
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cart  $cart
     * @return void
     */
    public function addProducts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cartKey' => 'required',
            'quantity' => 'required|numeric|min:1|max:10',
        ]); 
        if ($validator->fails()) { return response()->json([ 'errors' => $validator->errors(),  ], 400); }

        //Check if the Cart exist or return 404 not found.
        $cart = Cart::where('key',$request->cartKey)->first();
        if ( $cart):
            //Check if the proudct exist or return 404 not found.
            $products = Products::find($request->product_id);
            if($products):
                //check if the the same product is already in the Cart, if true update the quantity, if not create a new one.
                $cartItem = CartItems::where(['cart_id' => $cart->id, 'product_id' => $products->id])->first();
                if ($cartItem): 
                    CartItems::where(['cart_id' => $cart->id, 'product_id' => $products->id])->update(['quantity' => $request->quantity]);
                else:
                    CartItems::create(['cart_id' => $cart->id, 'product_id' => $products->id, 'quantity' => $request->quantity]);
                endif;  
                return response()->json(['message' => 'The Cart was updated with the given product information successfully'], 200);
            else:
                return response()->json([ 'message' => 'The Product you\'re trying to add does not exist.', ], 404);
            endif;  
        else: 
            return response()->json([ 'message' => 'The CarKey you provided does not match the Cart Key for this Cart.', ], 404);
        endif;    
         
    }
 

    /**
     * List the specified resource from storage.
     *
     * @param  \App\Models\Cart  $cart_id
     * @return \Illuminate\Http\Response
     */
    public function getCartItems($cart_id)
    {
        $items = CartItems:: with(['Product' => function ($query) {
            $query->select('id', 'category_id','label','type','DownloadURL','Weight','price');
            $query->with(['Category' => function ($qu) {
                $qu->select('id', 'label'); 
            }]); 
        }]) ->where('cart_id',$cart_id)->get();
        return $items;
    }

/**
     * checkout the cart Items and create and order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  String  cartKey
     * @return \Illuminate\Http\Response
     */
    public function Order(Request $request)
    {
        if (\Auth::guard('custom_api')->check()) {  $userID = auth('custom_api')->user()->id;  }
        $validator = Validator::make($request->all(), [
            'cartKey' => 'required', 'name' => 'required', 'address' => 'required', 'phone'=> 'required|numeric', 'email'=> 'required',
        ]); 
        if ($validator->fails()) { return response()->json([ 'errors' => $validator->errors(), ], 422); }

        $cart = Cart::where('key',$request->cartKey)->first();
        if ($cart): 
            $items = $this->getCartItems($cart->id);
            $order =  $products =  $price = [];
            foreach ($items as $key => $item): 
                if(isset($item->Product) && $item->Product != null):
                    $products[$key] = $item->Product->id;
                    $price[$key] = ($item->Product->price  *  $item->quantity);
                endif; 
            endforeach;
            
            $order = Order::create([ 
                'products'=>json_encode($products),
                'totalPrice'=>array_sum($price),
                'name'=>$request->name,
                'address'=>$request->address,
                'phone'=>$request->phone,
                'email'=>$request->email,
                'is_guest'=>isset($userID) ? $userID : 1, 
                'userID'=>isset($userID) ? $userID : null, 
            ]);
            $cart->delete(); // cart delete
            return response()->json([
                'message' => 'you\'re order has been completed succefully, thanks for shopping with us!',
                'orderID' => $order->id,
            ], 200);
        else: 
            return response()->json([ 'message' => 'The CarKey you provided does not match the Cart Key for this Cart.', ], 404);
        endif;
    }
}
