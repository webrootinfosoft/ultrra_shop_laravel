<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id', 'user_id', 'track_info', 'track_link', 'firstname', 'lastname', 'joint_firstname', 'joint_lastname', 'email', 'mobile', 'secondary_phone', 'address_1', 'address_2', 'city', 'postcode', 'country_id', 'state_id', 'shipping_firstname', 'shipping_lastname', 'shipping_email', 'shipping_mobile', 'shipping_company', 'shipping_address_1', 'shipping_address_2', 'shipping_city', 'shipping_postcode', 'shipping_country_id', 'shipping_state_id', 'billing_firstname', 'billing_lastname', 'billing_email', 'billing_mobile', 'billing_company', 'billing_address_1', 'billing_address_2', 'billing_city', 'billing_postcode', 'billing_country_id', 'billing_state_id', 'shipping_method', 'shipping_code', 'shipping_price', 'shipping_status_id', 'note', 'tax', 'handling_charges', 'total', 'sub_total', 'order_status_id', 'ip', 'user_agent', 'sponsor_id', 'is_first_order', 'is_backorder', 'is_preorder', 'is_autoship', 'is_printed', 'autoship_id', 'payment_status', 'meta', 'created_at', 'updated_at', 'deleted_at',
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
}
