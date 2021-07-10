<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingServiceSetting extends Model
{
    use SoftDeletes;

    protected $fillable = ['shipping_method_id', 'shipping_type', 'country_id', 'service_code', 'service_name', 'amount', 'shipping_method_type', 'status'];

    protected $appends = ['type'];

    public function getTypeAttribute()
    {
        $types = [1 => 'Weight Based', 2 => 'Rate Based'];

        return $types[$this->shipping_type];
    }

    public function shippingMethod()
    {
        return $this->belongsTo('App\ShippingMethod');
    }

    public function country()
    {
        return $this->belongsTo('App\Country');
    }

    public function shippingRates()
    {
        return $this->hasMany('App\ShippingRate');
    }
}
