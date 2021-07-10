<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Country;
use Mockery\Exception;

class CountryController extends Controller
{
    public function allCountries(Request $request)
    {
        $countries = Country::where('status', 1)->get();

        return response()->json(['status' => 200, 'data' => $countries]);
    }

    public function show($id)
    {
        $country = Country::find($id);

        return response()->json(['status' => 200, 'data' => $country]);
    }
}
