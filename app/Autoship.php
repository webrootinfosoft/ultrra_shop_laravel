<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Autoship extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'credit_card_id', 'autoship_last_run_date', 'autoship_next_run_date', 'shipping_rate_id', 'shipping_address_id', 'status'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function creditCard()
    {
        return $this->belongsTo('App\CreditCard');
    }

    public function shippingAddress()
    {
        return $this->belongsTo('App\Address', 'shipping_address_id');
    }

    public function products()
    {
        return $this->hasMany('App\AutoshipProduct');
    }

    public function shippingRate()
    {
        return $this->belongsTo('App\ShippingRate');
    }
}
