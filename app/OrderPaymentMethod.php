<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderPaymentMethod extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_id', 'payment_method', 'card_number', 'card_expiry', 'created_at', 'updated_at'
    ];
}
