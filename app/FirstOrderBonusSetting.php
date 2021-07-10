<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FirstOrderBonusSetting extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_type', 'level_1', 'level_2', 'level_3'];
}
