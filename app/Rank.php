<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rank extends Model
{
    use SoftDeletes;

    protected $fillable = ['ranks', 'rank', 'bonus', 'monthly_pqv', 'payleg_qv', 'binary', 'rank_icon', 'diamond_status', 'travel_credits'];
}
