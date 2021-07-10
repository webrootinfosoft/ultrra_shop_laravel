<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TravelDestination extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'destination_travel_credit_setting_id', 'travel_credits', 'amount', 'description_english', 'description_spanish'
    ];

    public function destinationTravelCreditSetting()
    {
        return $this->belongsTo('App\DestinationTravelCreditSetting');
    }

}
