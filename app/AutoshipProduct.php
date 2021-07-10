<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AutoshipProduct extends Model
{
    use SoftDeletes;

    protected $fillable = ['autoship_id', 'product_id', 'quantity', 'status'];

    public function autoship()
    {
        return $this->belongsTo('App\Autoship');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}
