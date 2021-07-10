<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartProduct extends Model
{

    protected $fillable = ['cart_id', 'product_id', 'quantity'];

    public function cart()
    {
        return $this->belongsTo('App\Cart');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}
