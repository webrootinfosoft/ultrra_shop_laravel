<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BelizeTravelCreditHistory extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'business_center_id', 'from_user_id', 'travel_destination_id', 'travel_credits', 'type', 'travel_credits_balance', 'sponsored_qvs', 'status', 'created_at'];
    
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
