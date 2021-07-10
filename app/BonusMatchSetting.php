<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BonusMatchSetting extends Model
{
    use SoftDeletes;

    protected $fillable = ['ranks', 'rank', 'level_1', 'level_2', 'level_3', 'level_4', 'level_5'];
}
