<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;
use Mockery\Exception;

class ProductController extends Controller
{
    public function allProducts(Request $request)
    {
        $products = Product::with('productCountries')->where(function ($q) use ($request) {
            if ($request->has('product_category_id') && (int)$request->product_category_id > 0)
            {
                $q->whereHas('productCategories', function ($q) use ($request) {
                    $q->where('id', $request->product_category_id);
                });
            }
            if ($request->has('country_id') && (int)$request->country_id > 0)
            {
                $q->whereHas('productCountries', function ($q) use ($request) {
                    $q->where('country_id', $request->country_id);
                });
            }
            if ($request->has('usertype'))
            {
                $q->where('display_for', 'like', '%'.$request->usertype.'%');
            }
            $q->whereNotIn('id', [80, 83, 84])->where('status', 1);
        })->where('is_only_admin', 0)->get();

        return response()->json(['status' => 200, 'data' => $products]);
    }

    public function show($id)
    {
        $product = Product::with('productType', 'productCategories', 'productAdditionalFields')->find($id);

        return response()->json(['status' => 200, 'data' => $product]);
    }
}
