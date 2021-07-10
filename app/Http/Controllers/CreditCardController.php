<?php

namespace App\Http\Controllers;

use App\Address;
use App\User;
use App\CreditCard;
use Illuminate\Http\Request;
use Mockery\Exception;

class CreditCardController extends Controller
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
        $request->validate([
            'card_number' => 'required',
            'expiry_month' => 'required',
            'expiry_year' => 'required',
            'cvv' => 'required|digits_between:3,4',
            'card_name' => 'required|string',
            'billing_address_1' => 'required_if:billing_same,false',
            'billing_city' => 'required_if:billing_same,false',
            'billing_state_id' => 'required_if:billing_same,false|numeric',
            'billing_postcode' => 'required_if:billing_same,false',
            'billing_country_id' => 'required_if:billing_same,false|numeric'
        ]);
        try
        {
            $last_day =  date('t', strtotime($request->expiry_year . '-' . $request->expiry_month . '-' .'01'));

            if (strtotime($request->expiry_year . '-' . $request->expiry_month . '-' . $last_day) > strtotime(date('y-m-01')))
            {
                $credit_card_data['user_id'] = $request->user_id;
                $credit_card_data['card_number'] = str_replace(' ', '', $request->card_number);
                $credit_card_data['card_name'] = $request->card_name;
                $credit_card_data['cvv'] = $request->cvv;
                $credit_card_data['expiry_month'] = $request->expiry_month;
                $credit_card_data['expiry_year'] = strlen($request->expiry_year) == 2 ? '20'.$request->expiry_year : $request->expiry_year;

                if ($request->has('billing_address_id'))
                {
                    $credit_card_data['billing_address_id'] = $request->billing_address_id;
                    $billing_address = Address::find($request->billing_address_id);
                    if (is_null($billing_address))
                    {
                        return response()->json(['status' => 200, 'data' => false, 'message' => 'This address does not exist in database.']);
                    }
                    $billing_address->update(['is_billing' => 1]);
                }
                else
                {
                    $billing_address_data['user_id'] = $request->user_id;
                    $billing_address_data['contact_name'] = $request->billing_contact_name;
                    $billing_address_data['contact_number'] = $request->billing_contact_number;
                    $billing_address_data['address_1'] = $request->billing_address_1;
                    $billing_address_data['address_2'] = $request->billing_address_2;
                    $billing_address_data['city'] = $request->billing_city;
                    $billing_address_data['postcode'] = $request->billing_postcode;
                    $billing_address_data['state_id'] = $request->billing_state_id;
                    $billing_address_data['country_id'] = $request->billing_country_id;
                    $billing_address_data['is_billing'] = 1;

                    $billing_address = Address::create($billing_address_data);

                    $credit_card_data['billing_address_id'] = $billing_address->id;
                }
                $user = User::find($request->user_id);
                if ($user->creditCards->count() == 0)
                {
                    $authorize_customer_profile = CreditCard::createAuthorizeCustomerProfile((object)$credit_card_data, $billing_address, $user);
                    if ($authorize_customer_profile['status'] == 1)
                    {
                        $credit_card_data['card_number'] = substr($credit_card_data['card_number'], -4);
                        $credit_card_data['payment_profile_id'] = $authorize_customer_profile['payment_profile_id'];
                        $credit_card = CreditCard::create($credit_card_data);
                        $credit_card['billing_address'] = $credit_card->billingAddress;
                        $user->update(['customer_profile_id' => $authorize_customer_profile['customer_profile_id']]);
                        return response()->json(['status' => 200, 'data' => $credit_card, 'message' => 'success']);
                    }
                    else
                    {
                        $message = $authorize_customer_profile['message'];
                        return response()->json(['status' => 200, 'data' => false, 'message' => $message]);
                    }
                }
                else
                {
                    $customer_payment_profile = CreditCard::createCustomerPaymentProfile($user['customer_profile_id'] , (object)$credit_card_data, $billing_address, $user);
                    if ($customer_payment_profile['status'] == 1)
                    {
                        $credit_card_data['card_number'] = substr($credit_card_data['card_number'], -4);
                        $credit_card_data['payment_profile_id'] = $customer_payment_profile['payment_profile_id'];
                        $credit_card = CreditCard::create($credit_card_data);
                        $credit_card['billing_address'] = $credit_card->billingAddress;
                        return response()->json(['status' => 200, 'data' => $credit_card, 'message' => 'success']);
                    }
                    else
                    {
                        $message = $customer_payment_profile['message'];
                        return response()->json(['status' => 200, 'data' => false, 'message' => $message]);
                    }
                }
            }
            else
            {
                return response()->json(['status' => 200, 'data' => false, 'message' => 'failure. Card expiry date is invalid']);
            }
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
            $credit_card = CreditCard::with('billingAddress')->find($id);
            return response()->json(['status' => 200, 'data' => $credit_card, 'message' => 'success']);
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
            'card_number' => 'required',
            'card_expiration' => 'required',
            'cvv' => 'required|digits_between:3,4',
            'card_name' => 'required|string',
        ]);
        try
        {
            $expiry = explode('/', $request->card_expiration);
            if (strtotime($expiry[1] . '-' . $expiry[0]) > strtotime(date('Y-m')))
            {
                $credit_card_data['user_id'] = $request->user_id;
                $credit_card_data['card_number'] = $request->card_number;
                $credit_card_data['card_name'] = $request->card_name;
                $credit_card_data['cvv'] = $request->cvv;
                $credit_card_data['expiry_month'] = $expiry[0];
                $credit_card_data['expiry_year'] = $expiry[1];

                if ($request->has('billing_address_id'))
                {
                    $credit_card_data['billing_address_id'] = $request->billing_address_id;
                    Address::find($request->billing_address_id)->update(['is_billing' => 1]);
                }

                $credit_card = CreditCard::find($id)->update($credit_card_data);

                return response()->json(['status' => 200, 'data' => $credit_card, 'message' => 'success']);
            }

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
