<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingMethod extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'order', 'status'];
}
