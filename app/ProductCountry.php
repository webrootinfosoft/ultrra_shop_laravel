<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCountry extends Model
{
    //
    use SoftDeletes;

    protected $fillable = ['product_id', 'country_id', 'distributor_price', 'preferred_customer_price', 'retail_customer_price', 'autoship_price', 'qv'];

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function country()
    {
        return $this->belongsTo('App\Country');
    }
}
