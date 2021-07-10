<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CarryForwardHistory extends Model
{
    use SoftDeletes;

    protected $fillable = ['business_center_id', 'total_left', 'total_right', 'left', 'right', 'action', 'created_at'];

    public function businessCenter()
    {
        return $this->belongsTo('App\User');
    }
}
