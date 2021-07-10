<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChallengePointHistory extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'points', 'type', 'created_at'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
