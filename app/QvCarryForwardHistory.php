<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QvCarryForwardHistory extends Model
{
    use SoftDeletes;

    protected $fillable = ['business_center_id', 'total_left', 'total_right', 'left', 'right', 'created_at'];

    public function businessCenter()
    {
        return $this->belongsTo('App\BusinessCenter');
    }
}
