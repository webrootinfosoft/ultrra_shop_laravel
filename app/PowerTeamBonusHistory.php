<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PowerTeamBonusHistory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'commission_id', '4_week_qv', 'qv_left', 'qv_right', 'pay_leg_qv', 'bv_left', 'bv_right', 'power_team_bv', 'bv_block_200', 'rate'
    ];

    protected $with = ['commission'];

    public function commission()
    {
        return $this->belongsTo('App\Commission');
    }

}
