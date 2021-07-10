<?php

namespace App\Http\Controllers\Api;

use App\CreditCard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Address;

class AddressController extends Controller
{
    public function userAddresses()
    {
        $addresses = Address::with('country', 'state', 'user')->where('user_id', auth()->id())->get();

        return response()->json(['status' => 200, 'data' => $addresses]);

    }

    public function addAddress(Request $request)
    {
        $address = Address::create($request->all());

        return response()->json(['data' => $address, 'status' => 200]);
    }

    public function updateAddress(Request $request, $id)
    {
        $address = Address::find($id)->update($request->all());

        return response()->json(['data' => $address, 'status' => 200]);
    }

    public function deleteAddress($id)
    {
        if (CreditCard::where('billing_address_id', $id)->count() == 0)
        {
            $address = Address::find($id)->delete();
            return response()->json(['data' => $address, 'status' => 200]);
        }
        else
        {
            return response()->json(['message' => 'Cannot delete this address. It is linked to a Credit Card', 'status' => 300]);
        }

    }

    public function addressesByUserId($user_id)
    {
        $addresses = Address::with('country', 'state', 'user')->where('user_id', $user_id)->get();

        return response()->json(['status' => 200, 'data' => $addresses]);
    }

    public function show($id)
    {
        $address = Address::with('country', 'state', 'user')->find($id);

        return response()->json(['status' => 200, 'data' => $address]);
    }
}
