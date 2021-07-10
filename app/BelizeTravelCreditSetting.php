<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BelizeTravelCreditSetting extends Model
{
    use SoftDeletes;

    protected $fillable = ['monthly_pqv', 'travel_credits'];
}
