<?php

namespace App\Http\Controllers;

use App\Address;
use Illuminate\Http\Request;
use Mockery\Exception;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try
        {
            $address = Address::create($request->all());
            $address = Address::find($address->id);
            return response()->json(['status' => 200, 'data' => $address, 'message' => 'success']);
        }
        catch (Exception $exception)
        {
            return response()->json(['status' => $exception->getCode(),  'message' => $exception->getMessage().' at line '.$exception->getLine()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try
        {
            $address = Address::find($id);
            return response()->json(['status' => 200, 'data' => $address, 'message' => 'success']);
        }
        catch (Exception $exception)
        {
            return response()->json(['status' => $exception->getCode(),  'message' => $exception->getMessage().' at line '.$exception->getLine()]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'address_1' => 'required',
            'city' => 'required',
            'postcode' => 'required',
            'state_id' => 'required|numeric',
            'country_id' => 'required|numeric',
        ]);
        try
        {
            Address::find($id)->update($request->all());
            return response()->json(['status' => 200, 'data' => Address::find($id), 'message' => 'success']);
        }
        catch (Exception $exception)
        {
            return response()->json(['status' => $exception->getCode(),  'message' => $exception->getMessage().' at line '.$exception->getLine()]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
