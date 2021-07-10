<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PowerTeamBonus extends Model
{
    use SoftDeletes;

    protected $fillable = ['min_pqv', 'min_qv', 'percent'];
}
