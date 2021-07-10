<?php

namespace App\Http\Controllers\Api;

use App\Autoship;
use App\AutoshipCart;
use App\AutoshipCartProduct;
use App\AutoshipProduct;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Mockery\Exception;

class AutoshipController extends Controller
{
    public function index(Request $request)
    {
        $cart = Autoship::with('products.product', 'creditCard', 'shippingAddress.state', 'shippingAddress.country', 'shippingRate.shippingServiceSetting')->where('user_id', auth()->id())->get();

        return response()->json(['data' => $cart, 'status' => 200]);
    }

    public function create(Request $request)
    {
        $cart = Autoship::create(['user_id' => auth()->id()]);

        return response()->json(['data' => $cart->with('products'), 'status' => 200]);
    }

    public function show($id)
    {
        $cart = Autoship::with('products.product', 'creditCard', 'shippingAddress.state', 'shippingAddress.country', 'shippingRate.shippingServiceSetting')->find($id);

        return response()->json(['data' => $cart, 'status' => 200]);
    }

    public function update(Request $request, $id)
    {
        $date_array = explode('-', $request->autoship_next_run_date);
        $request->merge(['autoship_next_run_date' => $date_array[2].'-'.$date_array[0].'-'.$date_array[1]]);
        $cart = Autoship::with('products.product', 'creditCard', 'shippingAddress.state', 'shippingAddress.country', 'shippingRate.shippingServiceSetting')->find($id);
        $cart->update($request->all());

        return response()->json(['data' => $cart, 'status' => 200]);
    }

    public function userCart(Request $request)
    {
        $cart = AutoshipCart::with('products.product')->where('user_id', auth()->id())->first();

        return response()->json(['data' => $cart, 'status' => 200]);
    }

    public function addItem(Request $request, $id)
    {
        $cart_item = AutoshipProduct::create(['autoship_id' => $id, 'product_id' => $request->product_id, 'quantity' => 1]);

        return response()->json(['data' => Autoship::with('products.product')->find($id), 'status' => 200]);
    }

    public function incrementItem($autoship_id, $product_id)
    {
        $cart_item = AutoshipProduct::with('product')->find($product_id);
        $cart_item->increment('quantity');

        return response()->json(['data' => $cart_item, 'status' => 200]);
    }

    public function decrementItem($autoship_id, $product_id)
    {
        $cart_item = AutoshipProduct::with('product')->find($product_id);
        $cart_item->decrement('quantity');
        if ($cart_item->quantity == 0)
        {
            $cart_item->delete();
        }

        return response()->json(['data' => $cart_item, 'status' => 200]);
    }

    public function addCart(Request $request)
    {
        if (AutoshipCart::where('user_id', auth()->id())->count() == 0)
        {
            $cart = AutoshipCart::create(['user_id' => auth()->id()]);
            $cart = AutoshipCart::with('products.product')->find($cart->id);
        }
        else
        {
            $cart = AutoshipCart::where('user_id', auth()->id())->latest()->first();
        }

        return response()->json(['data' => $cart, 'status' => 200]);
    }

    public function addCartItem(Request $request, $id)
    {
        $cart_item = AutoshipCartProduct::create(['autoship_cart_id' => $id, 'product_id' => $request->product_id, 'quantity' => 1]);

        return response()->json(['data' => AutoshipCart::with('products.product')->find($id), 'status' => 200]);
    }

    public function incrementCartItem($cart_id, $product_id)
    {
        $cart_item = AutoshipCartProduct::with('product')->find($product_id);
        $cart_item->increment('quantity');

        return response()->json(['data' => $cart_item, 'status' => 200]);
    }

    public function decrementCartItem($cart_id, $product_id)
    {
        $cart_item = AutoshipCartProduct::with('product')->find($product_id);
        $cart_item->decrement('quantity');
        if ($cart_item->quantity == 0)
        {
            $cart_item->delete();
        }

        return response()->json(['data' => $cart_item, 'status' => 200]);
    }

    public function emptyCart($cart_id)
    {
        AutoshipCartProduct::where('autoship_cart_id', $cart_id)->delete();

        return response()->json(['data' => AutoshipCart::with('products.product')->find($cart_id), 'status' => 200]);
    }

    public function submit(Request $request)
    {
        $date_array = explode('-', $request->autoship_next_run_date);
        $date = $date_array[2].'-'.$date_array[0].'-'.$date_array[1];
        $autoship = Autoship::create(['user_id' => auth()->id(), 'credit_card_id' => $request->credit_card_id, 'autoship_next_run_date' => $date, 'shipping_rate_id' => $request->shipping_rate_id, 'shipping_address_id' => $request->address_id]);

        foreach ($request->autoship_cart['products'] as $product)
        {
            AutoshipProduct::create(['autoship_id' => $autoship->id, 'product_id' => $product['product_id'], 'quantity' => $product['quantity']]);
        }

        AutoshipCart::find($request->autoship_cart['id'])->delete();

        return response()->json(['data' => $autoship, 'status' => 200]);
    }

}
