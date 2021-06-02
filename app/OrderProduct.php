<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderProduct extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id', 'order_id', 'product_id', 'name', 'model', 'quantity', 'components', 'price', 'retail_price', 'member_price', 'pc_price', 'qv', 'bv', 'total', 'backorder_quantity', 'status', 'created_at', 'updated_at', 'deleted_at'
    ];

    protected $with = ['orderProductComponents'];

    public function order()
    {
        return $this->belongsTo('App\Order');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function orderProductComponents()
    {
        return $this->hasMany('App\OrderProductComponent');
    }
}
