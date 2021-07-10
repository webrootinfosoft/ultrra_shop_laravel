<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\CreditCard;
use App\Autoship;
use Mockery\Exception;

class CreditCardController extends Controller
{
    public function userCreditCards()
    {
        try
        {
            $credit_cards = CreditCard::with('billingAddress')->where('user_id', auth()->id())->get();

            return response()->json(['status' => 200, 'data' => $credit_cards]);
        }
        catch (Exception $exception)
        {
            return response()->json(['status' => $exception->getCode(), 'message' => $exception->getMessage()]);
        }
    }

    public function deleteCreditCard($id)
    {
        $credit_card = CreditCard::find($id);
        if (Autoship::where('credit_card_id', $id)->count() == 0 && auth()->user()->carts->count() == 0)
        {
            $deleted = CreditCard::deleteCustomerPaymentProfile($credit_card->user->customer_profile_id, $credit_card->payment_profile_id);
            if ($deleted['status'] == 1)
            {
                $credit_card->delete();
                return response()->json(['status' => 1, 'message' => $deleted['message']]);
            }
            else
            {
                return response()->json(['status' => 0, 'message' => $deleted['errorMessage']]);
            }
        }
        else
        {
            if (Autoship::where('credit_card_id', $id)->count() > 0)
            {
                return response()->json(['status' => 0, 'message' => 'Cannot delete this credit card. it\'s associated with an Autoship.']);
            }
            elseif (auth()->user()->carts->count() > 0)
            {
                return response()->json(['status' => 0, 'message' => 'Cannot delete this credit card. Complete your pending order or empty your cart first.']);
            }
        }
    }
}
