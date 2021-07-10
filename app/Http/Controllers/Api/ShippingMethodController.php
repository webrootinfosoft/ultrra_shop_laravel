<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\ShippingMethod;
use App\ShippingRate;
use Illuminate\Http\Request;

class ShippingMethodController extends Controller
{
    public function index()
    {
        $shipping_methods = ShippingMethod::all();

        return response()->json(['data' => $shipping_methods, 'status' => 200]);
    }

    public function showShippingRate($id)
    {
        $shipping_rate = ShippingRate::with('shippingServiceSetting')->find($id);

        return response()->json(['data' => $shipping_rate, 'status' => 200]);
    }

    public function getShippingRates(Request $request)
    {
        $shipping_rates = ShippingRate::with('shippingServiceSetting')->where(function ($q) use ($request) {
            if ($request->is_membership_only == 1)
            {
                $q->where('start_range', '<=', (int)$request->price)->where('end_range', '>=', (int)$request->price)->whereHas('shippingServiceSetting', function ($q) use ($request) {
                    $q->where('country_id', $request->country_id)->where('shipping_method_type', 'membership_only');
                });
            }
            else
            {
                $q->where(function ($q) use ($request) {
                    $q->where('start_range', '<=', (int)$request->price)->where('end_range', '>=', (int)$request->price)->whereHas('shippingServiceSetting', function ($q) use ($request) {
                        $q->where('country_id', $request->country_id)->where('shipping_method_type', 'regular_shipping');
                    });
                })->orWhere(function ($q) use ($request) {
                    $q->where('start_range', '<=', (int)$request->fast_shipping_price)->where('end_range', '>=', (int)$request->fast_shipping_price)->whereHas('shippingServiceSetting', function ($q) use ($request) {
                        $q->where('country_id', $request->country_id)->where('shipping_method_type', 'fast_shipping');
                    });
                });
            }

        })->get();

        return response()->json(['data' => $shipping_rates, 'status' => 200]);
    }
}
