<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\StateTax;
use App\CountryTax;

class TaxController extends Controller
{
    public function chargeTaxes(Request $request)
    {
        $countryTax = CountryTax::where('country_id', $request->country_id)->first();
        $stateTax = StateTax::where('state_id', $request->state_id)->first();
        $total_tax_percentage = ($countryTax ? $countryTax->tax_percentage : 0) + ($stateTax ? $stateTax->tax_percentage : 0);
        $total_handling_charges = ($countryTax ? $countryTax->other_charges : 0) + ($stateTax ? $stateTax->other_charges : 0);
        $total_tax_amount = ($request->total * $total_tax_percentage) / 100;
        $data = [
            'total_handling_charges' => $total_handling_charges,
            'total_tax_percentage' => $total_tax_percentage,
            'total_tax_amount' => $total_tax_amount,
        ];

        return response()->json(['data' => $data, 'status' => 200]);
    }
}