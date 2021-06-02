<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderProduct;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function invoice(Request $request, $id)
    {
        $order = Order::with('orderStatus')->find($id);
        $order_status = !is_null($order->orderStatus) ? $order->orderStatus->name : '';
        $products = OrderProduct::where('order_id', $order->id)->get();

        if ($id > 34322)
        {
            return view('invoice', compact('order', 'order_status', 'products'));
        }
        else
        {
            return view('errors.maintenance');
        }
    }

}
