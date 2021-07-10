<?php

namespace App\Http\Controllers;

use App\State;
use Illuminate\Http\Request;

class StateController extends Controller
{
    public function statesByCountry($country_id)
    {
        $states = State::where('country_id', $country_id)->where('status', 1)->orderBy('name')->get();

        return response()->json(['status' => 200, 'data' => $states, 'message' => 'success']);
    }
}
