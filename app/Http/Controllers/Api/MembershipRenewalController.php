<?php

namespace App\Http\Controllers\Api;

use App\ErrorLog;
use App\Country;
use App\CreditCard;
use App\EwalletTransaction;
use App\Http\Controllers\Controller;
use App\OrderPaymentMethod;
use App\OrderProduct;
use App\OrderProductComponent;
use App\Product;
use App\State;
use App\User;
use App\MembershipRenewal;
use App\Order;
use Illuminate\Http\Request;

class MembershipRenewalController extends Controller
{
    public function renewalHistory()
    {
        $product_data = Product::find(80);
        $user = User::find(auth()->id());
        $membership_price = $product_data['member_price'];
        $country = Country::find($user->country_id);
        $state = State::find($user->state_id);
        $taxation = app('App\Http\Controllers\Api\TaxController')->chargeTaxes(new Request(['country_id' => $country->id, 'state_id' => $state->id, 'total' => $product_data['member_price']]));
        $ewallet_balance = EwalletTransaction::where('user_id', $user->id)->orderBy('id', 'desc')->value('current_balance');

        if ($user->renewal_date == '0000/00/00')
        {
            $due_date = date('Y/m/d', strtotime('+1 year', strtotime($user->created_at)));
        }
        else
        {
            $due_date = date('Y/m/d', strtotime($user->renewal_date));
        }

        $due_date_minus_one_month = date('Y/m/d', strtotime('-1 month', strtotime($due_date)));
        $membership_renewals = MembershipRenewal::with('order.paymentMethods')->where('user_id', $user->id)->get();

        if ($due_date_minus_one_month <= date('Y/m/d'))
        {
            $membership_due = 1;
        }
        else
        {
            $membership_due = 0;
        }
        $due_date = date('m/d/Y', strtotime($due_date));

        $data = [
            'ewallet_balance' => $ewallet_balance,
            'due_date' => $due_date,
            'membership_renewals' => $membership_renewals,
            'membership_due' => $membership_due,
            'product' => $product_data
        ];

        return response()->json(['data' => $data, 'status' => 200]);
    }

    public function renewMembership(Request $request)
    {
        try
        {
            //        $credit_card = CreditCard::with('billingAddress')->find($request->credit_card_id);
            $credit_card = json_decode(json_encode($request->credit_card));
            if ($credit_card)
            {
                $credit_card->billingAddress->state = State::find($credit_card->billingAddress->state_id);
                $credit_card->billingAddress->country = State::find($credit_card->billingAddress->country_id);
            }
            $user = auth()->user();
            $address = $credit_card ? $credit_card->billingAddress : (count($user->addresses) > 0 ? $user->addresses[0] : (object)['address_1' => $user->address1, 'address_2' => $user->address2, 'city' => $user->city, 'postcode' => $user->postcode, 'country_id' => $user->country_id, 'country' => Country::find($user->country_id),'state_id' => $user->state_id, 'state' => State::find($user->state_id)]);
            $shipping_address = isset($request->shipping_address) ? $request->shipping_address : $address;
            $sponsor = User::find($user->sponsor_id);
            $billing_address = $credit_card ? $credit_card->billingAddress : $address;

            $product = Product::find(80);

            $sub_total = $product->default_price;
            $total_qv = $product->qv;
            $total_bv = $product->bv;

            $orderData = [
                'user_id' => $user->id,
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'joint_firstname' => isset($user->joint_firstname) ? $user->joint_firstname : null,
                'joint_lastname' => isset($user->joint_lastname) ? $user->joint_lastname : null,
                'mobile' => $user->phone,
                'secondary_phone' => $user->secondary_phone,
                'email' => $user->email,
                'country_id' => $user->country_id,
                'state_id' => $user->state_id,
                'postcode' => $user->postcode,
                'city' => $user->city,
                'address_1' => $user->address1,
                'address_2' => $user->address2,
                'shipping_firstname' => $user->firstname,
                'shipping_lastname' => $user->lastname,
                'shipping_email' => $user->email,
                'shipping_mobile' => $user->phone,
                'shipping_company' => '',
                'shipping_address_1' => $shipping_address->address_1,
                'shipping_address_2' => $shipping_address->address_2,
                'shipping_city' => $shipping_address->city,
                'shipping_postcode' => $shipping_address->postcode,
                'shipping_country_id' => $shipping_address->country_id,
                'shipping_state_id' => $shipping_address->state_id,
                'tax' => 0,
                'handling_charges' => 0,
                'total' => $sub_total,
                'sub_total' => $sub_total,
                'shipping_method' => 'N/A',
                'shipping_price' => 0,
                'shipping_status_id' => 5,
                'billing_firstname' => $user->firstname,
                'billing_lastname' => $user->lastname,
                'billing_address_1' => $billing_address->address_1,
                'billing_address_2' => $billing_address->address_2,
                'billing_city' => $billing_address->city,
                'billing_postcode' => $billing_address->postcode,
                'billing_country_id' => $billing_address->country_id,
                'billing_state_id' => $billing_address->state_id,
                'ip' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'sponsor_id' => $user->sponsor_id,
                'is_first_order' => 0,
                'is_backorder' => 0,
            ];

            $order = Order::create($orderData);

            $product_components = [];
            $productData = [
                'order_id' => $order->id,
                'product_id' => $product->id,
                'name' => $product->name,
                'model' => $product->name,
                'quantity' => 1,
                'price' => $product->default_price,
                'qv' => $product->qv,
                'bv' => 0,
                'total' => 1 * $product->default_price,
            ];

            $product_quantity = Product::find($product->id)->maximum_quantity;

            if ($product_quantity <= 0)
            {
                $productData['backorder_quantity'] = $productData['quantity'];
            }
            elseif ($productData['quantity'] > $product_quantity)
            {
                $productData['backorder_quantity'] = $productData['quantity'] - $product_quantity;
            }
            else
            {
                $productData['backorder_quantity'] = 0;
            }

            $order_product = OrderProduct::create($productData);

            if($product->productComponents->count() > 0)
            {
                foreach($product->productComponents as $product_component)
                {
                    $componentData = [
                        'order_product_id' => $order_product->id,
                        'component_id' => $product_component->id,
                        'name' => $product_component->name,
                        'model' => $product_component->name,
                        'quantity' => $product_component->pivot->quantity,
                        'price' => $product_component->default_price,
                        'qv' => $product_component->qv,
                        'bv' => 0,
                        'total' => $product_component->quantity * $product_component->default_price,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];

                    $product_components[] = $componentData;
                }
                OrderProductComponent::insert($product_components);
            }

            $order_payment_method_data = [];

            if ($credit_card && $request->ewallet_amount < $product->default_price)
            {
                $order_payment_method_data = [
                    'order_id' => $order->id,
                    'payment_method' => 'credit_card',
                    'card_number' => substr($credit_card->card_number, -4),
                    'card_expiry' => $credit_card->expiry_month.'/'.$credit_card->expiry_year,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];

//            $customer_profile_id = User::find($user->id)->customer_profile_id;
//            $payment_transaction = CreditCard::chargeCustomerProfile($customer_profile_id, $credit_card->payment_profile_id, $sub_total);
                $payment_transaction = CreditCard::chargeCreditCard($credit_card, $billing_address, $sub_total, $order->id, $user);
            }
            elseif ($request->ewallet_amount > 0 && $request->ewallet_amount >= $product->default_price)
            {
                $order_payment_method_data = [
                    'order_id' => $order->id,
                    'payment_method' => 'ewallet',
                    'card_number' => '',
                    'card_expiry' => '',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];

                $ewallet_balance = EwalletTransaction::where('user_id', auth()->id())->orderBy('id', 'desc')->value('current_balance');
                if ($ewallet_balance > 0)
                {
                    EwalletTransaction::create(['user_id' => $user->id, 'description' => 'order_place', 'note' => $order->id, 'amount' => $product->default_price, 'amount_type' => 'debit', 'current_balance' => $ewallet_balance - $product->default_price, 'status' => 1, 'response' => '']);
                }

                $payment_transaction = [
                    'status' => 1,
                ];
            }

            OrderPaymentMethod::create($order_payment_method_data);
//            return $payment_transaction;
            if ($payment_transaction['status'] == 1)
            {
//            OrderJob::dispatch($order);
                Order::find($order->id)->update(['order_status_id' => 4, 'payment_status' => 'Success']);
                if ($user->renewal_date == '0000/00/00')
                {
                    $due_date = date('Y/m/d', strtotime('+1 year', strtotime(auth()->user()->created_at)));
                }
                else
                {
                    $due_date = date('Y/m/d', strtotime($user->renewal_date));
                }
                if (date('Y', strtotime($due_date)) < 2019)
                {
                    $due_date = date('2019/m/d', strtotime($due_date));
                }

                $next_due_date = date('Y/m/d', strtotime('+1 year', strtotime($due_date)));
                $user->update(['renewal_date' => $next_due_date]);

                $membership_renewal = MembershipRenewal::create(['user_id' => $user->id, 'order_id' => $order->id, 'due_date' => $due_date, 'next_due_date' => $next_due_date]);

                $data = [
                    'renewal_date' => $user->renewal_date,
                    'membership_due' => 0
                ];

                return response()->json(['data' => $data, 'status' => 1]);
            }
            elseif ($payment_transaction['status'] == 0)
            {
                Order::find($order->id)->update(['order_status_id' => 7, 'payment_status' => 'Failed']);
                $data = [
                    'renewal_date' => $user->renewal_date,
                    'membership_due' => 1
                ];

                return response()->json(['data' => $data, 'status' => 0]);
            }

        }
        catch (\Exception $exception)
        {
            if (isset($order))
            {
                $request->merge(['order' => $order]);
            }
            ErrorLog::create(['code' => $exception->getCode(), 'error' => $exception->getMessage(), 'line_number' => $exception->getLine(), 'data' => json_encode($request->all())]);
            return response()->json(['error' => $exception->getMessage(), 'status' => $exception->getCode()]);
        }

    }
}
