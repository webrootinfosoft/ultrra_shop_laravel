<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategory extends Model
{
    //
    use SoftDeletes;

    protected $fillable = ['product_type_id', 'country_ids', 'name', 'name_spanish', 'name_japanese', 'name_chinese', 'description', 'language', 'sort_order', 'status'];

    protected $casts = [
        'country_ids' => 'array',
    ];

    public function productType()
    {
        return $this->belongsTo('App\ProductType');
    }

}
