<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CountryTax extends Model
{
    use SoftDeletes;

    protected $fillable = ['country_id', 'tax_percentage', 'other_charges'];

    protected $with = ['country'];

    public function country()
    {
        return $this->belongsTo('App\Country');
    }
}
