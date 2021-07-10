<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DestinationTravelCreditSetting extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'start_date', 'end_date', 'carry_over_start_date', 'carry_over_end_date'
    ];

    public function travelDestinations()
    {
        return $this->hasMany('App\TravelDestination');
    }
}
