<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PcBonus extends Model
{
    use SoftDeletes;

    protected $fillable = ['bonus_amount'];
}
