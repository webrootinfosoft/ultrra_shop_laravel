<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;
use App\EmailHistory;
use App\Order;
use Illuminate\Support\Facades\Mail;

class EmailTemplate extends Model
{
    use SoftDeletes;

    protected $fillable = ['subject', 'body'];

    public static function sendOrderConfirmationEmail($order)
    {
        $sponsorInfo = User::find($order->sponsor_id);
        $userInfo = User::find($order->user_id);

        $email_template_order = self::find(6);

        $order_confirmation_template = $email_template_order->body;
        $order_confirmation_subject = $email_template_order->subject;

        $user_keys = User::getUserColumns();
        $sponsor_keys = User::getSponsorColumns();
        $order_keys = Order::getColumns();

        foreach ($user_keys as $item)
        {
            $new_variables['{$user.'.$item.'}'] = $userInfo[$item];
        }

        foreach ($sponsor_keys as $item)
        {
            $new_variables['{$sponsor.'.$item.'}'] = $sponsorInfo[$item];
        }

        foreach ($order_keys as $item)
        {
            $new_variables['{$order.'.$item.'}'] = $order[$item];
        }
        $products_table = '<table border="1" style="border-collapse: collapse;width: 100%"><tr><th>Item</th><th>Quantity</th><th>Components</th><th>Qv</th><th>Price</th><th>Total</th></tr>';
        $data_rows = '';
        $productsData = $order->orderProducts;
        foreach ($productsData as $product)
        {
            $data_rows .= '<tr><td>'.$product["name"].'</td><td>'.$product["quantity"].'</td><td>'.$product["components"].'</td><td>'.$product["QV"].'</td><td>'.$product["price"].'</td><td>'.$product["total"].'</td></tr>';
        }

        $products_table = $products_table.$data_rows.'<tr><td colspan="4">Notes: '.$order["comment"].'</td><td>Sub Total:</td><td>'.$order["sub_total"].'</td></tr><tr><td colspan="4"></td><td>Sales Tax:</td><td>'.$order["tax"].'</td></tr><tr><td colspan="4"></td><td>Shipping:</td><td>'.$order["shipping_price"].'</td></tr><tr><td colspan="4">Payment Method: ('.$order["payment_method"].') **** **** **** '.explode(" ", $order["card_number"])[count(explode(" ", $order["card_number"])) - 1].'</td><td>Total:</td><td>'.$order["total"].'</td></tr></table>';

        $new_variables['{$products}'] = $products_table;
        $new_variables['{$order.total_qv}'] = $order->orderProducts->sum('qv');
        $new_variables['{$order.shipping_state}'] = $order->shippingState->name;
        $new_variables['{$order.shipping_country}'] = $order->shippingCountry->name;
        $new_variables['{$order.billing_state}'] = $order->billingState->name;
        $new_variables['{$order.billing_country}'] = $order->billingCountry->name;
        $new_variables['{$order.card_number}'] = $order->orderPaymentMethods[0]->card_number !== '' ? '**** **** **** '.$order->orderPaymentMethods[0]->card_number : '';
        $new_variables['{$logo}'] = '<img style="display: block; margin-left: auto; margin-right: auto;" src="https://admin.ultrra.com/logo.png" alt="" width="296" height="59" />';

        $order_confirmation_template = stripslashes(str_replace(['\r', '\n'],'', $order_confirmation_template));
        $order_confirmation_template = str_replace(array_keys($new_variables), array_values($new_variables), $order_confirmation_template);
        $order_confirmation_subject = str_replace(array_keys($new_variables), array_values($new_variables), $order_confirmation_subject);

        Mail::send('emails.email', ['html' => $order_confirmation_template], function ($m) use ($order, $order_confirmation_subject) {
            $m->to($order->email, $order->firstname)->subject($order_confirmation_subject);
        });

        EmailHistory::create(['user_id' => $userInfo['id'], 'email' => $order->email, 'subject' => $order_confirmation_subject, 'content' => $order_confirmation_template]);
    }

    public static function sendSponsorNotificationEmail($order)
    {
        $sponsorInfo = User::find($order->sponsor_id);
        $userInfo = User::find($order->user_id);

        $email_template_sponsor = self::find(5);

        $sponsor_notification_template = $email_template_sponsor->body;
        $sponsor_notification_subject = $email_template_sponsor->subject;

        $user_keys = User::getUserColumns();
        $sponsor_keys = User::getSponsorColumns();
        $order_keys = Order::getColumns();

        foreach ($user_keys as $item)
        {
            $new_variables['{$user.'.$item.'}'] = $userInfo[$item];
        }

        foreach ($sponsor_keys as $item)
        {
            $new_variables['{$sponsor.'.$item.'}'] = $sponsorInfo[$item];
        }

        foreach ($order_keys as $item)
        {
            $new_variables['{$order.'.$item.'}'] = $order[$item];
        }
        $products_table = '<table border="1" style="border-collapse: collapse;width: 100%"><tr><th>Item</th><th>Quantity</th><th>Components</th><th>Qv</th><th>Price</th><th>Total</th></tr>';
        $data_rows = '';
        $productsData = $order->orderProducts;
        foreach ($productsData as $product)
        {
            $data_rows .= '<tr><td>'.$product["name"].'</td><td>'.$product["quantity"].'</td><td>'.$product["components"].'</td><td>'.$product["QV"].'</td><td>'.$product["price"].'</td><td>'.$product["total"].'</td></tr>';
        }

        $products_table = $products_table.$data_rows.'<tr><td colspan="4">Notes: '.$order["comment"].'</td><td>Sub Total:</td><td>'.$order["sub_total"].'</td></tr><tr><td colspan="4"></td><td>Sales Tax:</td><td>'.$order["tax"].'</td></tr><tr><td colspan="4"></td><td>Shipping:</td><td>'.$order["shipping_price"].'</td></tr><tr><td colspan="4">Payment Method: ('.$order["payment_method"].') **** **** **** '.explode(" ", $order["card_number"])[count(explode(" ", $order["card_number"])) - 1].'</td><td>Total:</td><td>'.$order["total"].'</td></tr></table>';

        $new_variables['{$products}'] = $products_table;
        $new_variables['{$order.total_qv}'] = $order->orderProducts->sum('qv');
        $new_variables['{$logo}'] = '<img style="display: block; margin-left: auto; margin-right: auto;" src="https://admin.ultrra.com/logo.png" alt="" width="296" height="59" />';

        $sponsor_notification_template = stripslashes(str_replace(['\r', '\n'],'', $sponsor_notification_template));
        $sponsor_notification_template = str_replace(array_keys($new_variables), array_values($new_variables), $sponsor_notification_template);
        $sponsor_notification_subject = str_replace(array_keys($new_variables), array_values($new_variables), $sponsor_notification_subject);

        Mail::send('emails.email', ['html' => $sponsor_notification_template], function ($m) use ($sponsorInfo, $sponsor_notification_subject) {
            $m->to($sponsorInfo->email, $sponsorInfo->firstname)->subject($sponsor_notification_subject);
        });

        EmailHistory::create(['user_id' => $sponsorInfo['id'], 'email' => $sponsorInfo['email'], 'subject' => $sponsor_notification_subject, 'content' => $sponsor_notification_template]);
    }
}
