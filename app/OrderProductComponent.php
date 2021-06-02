<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderProductComponent extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_product_id', 'component_id', 'name', 'model', 'quantity', 'price', 'qv', 'bv', 'total', 'status'
    ];
}
