<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id', 'user_id', 'track_info', 'track_link', 'firstname', 'lastname', 'joint_firstname', 'joint_lastname', 'email', 'mobile', 'secondary_phone', 'address_1', 'address_2', 'city', 'postcode', 'country_id', 'state_id', 'shipping_firstname', 'shipping_lastname', 'shipping_email', 'shipping_mobile', 'shipping_company', 'shipping_address_1', 'shipping_address_2', 'shipping_city', 'shipping_postcode', 'shipping_country_id', 'shipping_state_id', 'billing_firstname', 'billing_lastname', 'billing_email', 'billing_mobile', 'billing_company', 'billing_address_1', 'billing_address_2', 'billing_city', 'billing_postcode', 'billing_country_id', 'billing_state_id', 'shipping_method', 'shipping_code', 'shipping_price', 'shipping_status_id', 'note', 'tax', 'handling_charges', 'total', 'sub_total', 'order_status_id', 'ip', 'user_agent', 'sponsor_id', 'is_first_order', 'is_backorder', 'is_preorder', 'is_autoship', 'is_printed', 'autoship_id', 'payment_status', 'meta', 'cash_order_date', 'created_at', 'updated_at', 'deleted_at',
    ];

    protected $appends = ['total_qv'];

    protected $casts = [
        'meta' => 'object'
    ];

    public function getTotalQvAttribute()
    {
        return $this->orderProducts->sum(function ($item) {
            return $item->qv;
        });
    }

    public function orderProducts()
    {
        return $this->hasMany('App\OrderProduct');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function sponsor()
    {
        return $this->belongsTo('App\User', 'sponsor_id');
    }

    public function paymentMethods()
    {
        return $this->hasMany('App\OrderPaymentMethod');
    }

    public function orderPaymentMethods()
    {
        return $this->hasMany('App\OrderPaymentMethod');
    }

    public function state()
    {
        return $this->belongsTo('App\State');
    }

    public function country()
    {
        return $this->belongsTo('App\Country');
    }

    public function shippingState()
    {
        return $this->belongsTo('App\State', 'shipping_state_id');
    }

    public function shippingCountry()
    {
        return $this->belongsTo('App\Country', 'shipping_country_id');
    }

    public function billingState()
    {
        return $this->belongsTo('App\State', 'billing_state_id');
    }

    public function billingCountry()
    {
        return $this->belongsTo('App\Country', 'billing_country_id');
    }

    public function orderStatus()
    {
        return $this->belongsTo('App\OrderStatus');
    }

    public function shippingStatus()
    {
        return $this->belongsTo('App\ShippingStatus');
    }

    public static function getColumns()
    {
        return ['id', 'mobile', 'email', 'billing_firstname', 'billing_lastname', 'billing_address_1', 'billing_address_2', 'billing_postcode', 'billing_city', 'billing_state', 'billing_country', 'shipping_firstname', 'shipping_lastname', 'shipping_address_1', 'shipping_address_2', 'shipping_postcode', 'shipping_city', 'shipping_state', 'shipping_country', 'shipping_method', 'is_first_order', 'tracking_number', 'autoship_template_id', 'created_at'];
    }

    public static function sendToShipStation($order)
    {
        if ($order['shipping_country_id'] == 233)
        {
            $shipStation = app('LaravelShipStation\ShipStation');

            $billing_address = new \LaravelShipStation\Models\Address();

            $billing_address->name = $order['firstname'].' '.$order['lastname'];
            $billing_address->street1 = $order['billing_address_1'];
            $billing_address->street2 = $order['billing_address_2'];
            $billing_address->city = $order['billing_city'];
            $billing_address->state = State::find($order['billing_state_id'])->name;
            $billing_address->postalCode = $order['billing_postcode'];
            $billing_address->country = Country::find($order['billing_country_id'])->iso2;
            $billing_address->phone = "";

            $shipping_address = new \LaravelShipStation\Models\Address();

            $shipping_address->name = $order['shipping_firstname'].' '.$order['shipping_lastname'];
            $shipping_address->street1 = $order['shipping_address_1'];
            $shipping_address->street2 = $order['shipping_address_2'];
            $shipping_address->city = $order['shipping_city'];
            $shipping_address->state = State::find($order['shipping_state_id'])->name;
            $shipping_address->postalCode = $order['shipping_postcode'];
            $shipping_address->country = Country::find($order['shipping_country_id'])->iso2;
            $shipping_address->phone = "";

            $shipstation_order = new \LaravelShipStation\Models\Order();

            $shipstation_order->orderNumber = $order['id'];
            $shipstation_order->customerUsername = User::find($order['user_id'])->username;
            $shipstation_order->customerEmail = $order['email'];
            $shipstation_order->orderDate = date('Y-m-d', strtotime($order['created_at']));
            $shipstation_order->paymentDate = (new \DateTime(date('Y-m-d H:i:s', strtotime($order['created_at'])), new \DateTimeZone('America/New_York')))->format('Y-m-d H:i:s');
            $shipstation_order->shipByDate = (new \DateTime(date('Y-m-d H:i:s', strtotime('+'.(int) filter_var($order['shipping_method'], FILTER_SANITIZE_NUMBER_INT).' days', strtotime($order['created_at']))), new \DateTimeZone('America/New_York')))->format('Y-m-d H:i:s');
            $shipstation_order->orderStatus = 'awaiting_shipment';
            $shipstation_order->orderTotal = $order['total'];
            $shipstation_order->amountPaid = $order['total'];
            $shipstation_order->taxAmount = $order['tax'];
            $shipstation_order->shippingAmount = $order['shipping_price'] + $order['handling_charges'];
            $shipstation_order->internalNotes = 'Testng Notes';
            $shipstation_order->serviceCode = '';
            $shipstation_order->billTo = $billing_address;
            $shipstation_order->shipTo = $shipping_address;

            $order_products = OrderProduct::where('order_id', $order['id'])->get();
            foreach ($order_products as $order_product)
            {
                $item = new \LaravelShipStation\Models\OrderItem();
                $product = Product::find($order_product['product_id']);
                $item->name = $order_product['name'];
                $item->quantity = $order_product['quantity'];
                $item->sku = $product->sku;
                $item->unitPrice  = $order_product['price'];
                $item->imageUrl = $product->image;
                $item->productId = $order_product['id'];
                $shipstation_order->items[] = $item;
            }
            if (env('ENVIRONMENT') == 'TEST')
            {
                $shipStation->orders->post($shipstation_order, 'createorder');
            }
            else
            {
                $shipStation->orders->post($shipstation_order, 'createorder');
            }

        }
    }
}
