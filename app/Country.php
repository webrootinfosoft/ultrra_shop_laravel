<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    //
    use SoftDeletes;

    protected $fillable = ['name', 'iso2', 'iso3', 'phonecode', 'capital', 'currency', 'status'];

    public function states()
    {
        return $this->hasMany('App\State');
    }
}
