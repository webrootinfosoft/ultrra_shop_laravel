<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ProductCategory;
use Mockery\Exception;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $product_categories = ProductCategory::where('status', 1)->where('name', '!=', 'membership')->where(function ($q) {
            if (isset(request()->country_id) && request()->country_id > 0)
            {
                $q->where('country_ids', 'like', '%'.request()->country_id.'%');
            }
        })->orderByDesc('sort_order')->get();

        return response()->json(['status' => 200, 'data' => $product_categories]);
    }

    public function productCategoriesByProductType($product_type_id)
    {
        $product_categories = ProductCategory::where('product_type_id', $product_type_id)->where('status', 1)->get();

        return response()->json(['status' => 200, 'data' => $product_categories]);
    }

}
