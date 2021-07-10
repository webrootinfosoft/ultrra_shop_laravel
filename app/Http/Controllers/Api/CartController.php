<?php

namespace App\Http\Controllers\Api;

use App\Cart;
use App\CartProduct;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
//    public function store(Request $request)
//    {
//        if ($request->has('cart_id'))
//        {
//            $cart_product = CartProduct::updateOrCreate(
//                ['cart_id' => $request->cart_id, 'product_id' => $request->product_id],
//                ['quantity' => DB::raw('quantity + 1')]
//            );
//        }
//        elseif ($request->has('create_cart') && Cart::where('user_id', $request->user_id)->count() == 0)
//        {
//            Cart::create(['user_id' => $request->user_id]);
//        }
//
//        return response()->json(['status' => 200, 'message' => 'success']);
//    }

    public function store(Request $request)
    {
        //  return response()->json(['data'=>$request->usertype]);
        if ($request->has('cart_id'))
        {
            $cart_product = CartProduct::updateOrCreate(
                ['cart_id' => $request->cart_id, 'product_id' => $request->product_id],
                ['quantity' => DB::raw('quantity + 1')]
            );
        }
        elseif ($request->has('create_cart') && Cart::where('user_id', $request->user_id)->count() == 0)
        {
            $cart = Cart::create(['user_id' => $request->user_id]);
            return response()->json(['status' => 200, 'data' => $cart]);
        }
        elseif ($request->has('new_cart') && !isset($request->user_id))
        {
            $cart = Cart::create(['user_id' => $request->user_id]);
            if ($request->usertype == "pc")
            {
                $product = Product::find(84);
                CartProduct::updateOrCreate(['cart_id' => $cart->id, 'product_id' => $product->id], ['quantity' => 1]);
            }
            elseif ($request->usertype == "dc")
            {
                $product = Product::find(83);
                CartProduct::updateOrCreate(['cart_id' => $cart->id, 'product_id' => $product->id], ['quantity' => 1]);
            }
            $cart = Cart::with('products')->find($cart->id);
            return response()->json(['status' => 200, 'data' => $cart]);
        }

        return response()->json(['status' => 200, 'message' => 'success', 'usertype'=>$request->usertype]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cart = Cart::with(['products.product' => function($q) {
            $q->with('productType', 'productCountries', 'productImages', 'productCategories', 'productAdditionalFields', 'productComponents');
        }])->find($id);

        return response()->json(['status' => 200, 'data' => $cart, 'message' => 'success']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $cart_product = CartProduct::find($id);
        if ($cart_product->product->maximum_quantity < $request->quantity)
        {
            return response()->json(['status' => 2, 'message' => 'You have reached the maximum of item quantity for this product']);
        }
        $cart_product->update($request->all());

        return response()->json(['status' => 200, 'message' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        CartProduct::destroy($id);

        return response()->json(['status' => 200, 'message' => 'success']);
    }

    public function userCart($user_id)
    {
        $cart = Cart::with('products.product.productCountries')->where('user_id', $user_id)->orderBy('id', 'desc')->first();

        return response()->json(['data' => $cart, 'status' => 200]);
    }
}
