<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BingoBonusHistory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'commission_id', 'previous_week_par', 'qs', 'new_qv_left', 'new_qv_right', 'total_qv_left', 'total_qv_right', 'new_bv_left', 'new_bv_right', 'total_bv_left', 'total_bv_right', 'bv_pairs', 'rate'
    ];

    protected $with = ['commission'];

    public function commission()
    {
        return $this->belongsTo('App\Commission');
    }
}
