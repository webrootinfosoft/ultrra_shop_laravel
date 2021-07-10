<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GlobalBonusHistory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'business_center_id', 'active_left', 'active_right', 'inactive_left', 'inactive_right', 'share', 'month', 'year', 'per_share'
    ];

    public function businessCenter()
    {
        return $this->belongsTo('App\BusinessCenter');
    }
}
