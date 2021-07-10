<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'contact_name', 'contact_number', 'address_1', 'address_2', 'city', 'postcode', 'state_id', 'country_id', 'is_shipping', 'is_billing'
    ];

    protected $with = ['state', 'country'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function state()
    {
        return $this->belongsTo('App\State');
    }

    public function country()
    {
        return $this->belongsTo('App\Country');
    }
}
