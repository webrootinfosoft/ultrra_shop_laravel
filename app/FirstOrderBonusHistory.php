<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FirstOrderBonusHistory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'commission_id', 'order_id', 'qv', 'level_1_sponsor', 'level_1_bonus', 'level_2_sponsor', 'level_2_bonus', 'level_3_sponsor', 'level_3_bonus', 'total_fob_payout'
    ];

    protected $with = ['commission', 'order'];

    public function commission()
    {
        return $this->belongsTo('App\Commission');
    }

    public function order()
    {
        return $this->belongsTo('App\Order');
    }
}
