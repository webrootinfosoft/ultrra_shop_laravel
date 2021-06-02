<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class State extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'country_id', 'country_code', 'fips_code', 'iso2', 'status'];

    public function country()
    {
        return $this->belongsTo('App\Country');
    }
}
