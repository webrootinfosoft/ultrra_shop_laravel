<?php

namespace App\Http\Controllers;

use App\Enquiry;
use App\Order;
use App\OrderProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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

    public function contactUsSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|regex:/^[a-zA-Z\s]+$/',
            'phone' => 'required|digits_between:10,15',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required'
        ]);

        $enquiry = Enquiry::create($request->except('_token'));

        if ($enquiry)
        {
            Mail::send('emails.contact', ['enquiry' => $enquiry], function ($m) use ($enquiry) {
                $m->to('cc@ultrra.com')->subject($enquiry->subject);
            });
        }
        return redirect()->back()->with('message', 'Your message has been received. Our contact support team will reach out to you shortly.');
    }
}
