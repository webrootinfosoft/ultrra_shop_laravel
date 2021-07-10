<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AutoshipCart extends Model
{

    protected $fillable = ['user_id'];

    public function products()
    {
        return $this->hasMany('App\AutoshipCartProduct');
    }
}
