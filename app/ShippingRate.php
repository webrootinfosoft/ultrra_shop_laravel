<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingRate extends Model
{
    use SoftDeletes;

    protected $fillable = ['shipping_service_setting_id', 'start_range', 'end_range', 'range_amount'];

    public function shippingServiceSetting()
    {
        return $this->belongsTo('App\ShippingServiceSetting');
    }
}
