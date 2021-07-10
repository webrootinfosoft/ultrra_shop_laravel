<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChallengePointQvHistory extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'points','contest', 'created_at'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
