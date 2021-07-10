<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AutoshipCartProduct extends Model
{

    protected $fillable = ['autoship_cart_id', 'product_id', 'quantity'];

    public function cart()
    {
        return $this->belongsTo('App\AutoshipCart');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}
