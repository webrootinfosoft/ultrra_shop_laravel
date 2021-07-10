<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductType extends Model
{
    //
    use SoftDeletes;

    protected $fillable = ['name', 'description'];

    public function products()
    {
        return $this->hasMany('App\Product');
    }

    public function productCategories()
    {
        return $this->hasMany('App\ProductCategory');
    }
}
