<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductAdditionalField extends Model
{
    //
    use SoftDeletes;

    protected $fillable = ['product_id', 'title', 'description', 'language'];

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}
