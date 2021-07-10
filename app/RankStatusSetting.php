<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RankStatusSetting extends Model
{
    use SoftDeletes;

    protected $fillable = ['rank_status', 'min_qv'];
}
