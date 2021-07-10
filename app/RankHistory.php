<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RankHistory extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'business_center_id', 'rank_id', 'rank_updated', 'remarks', 'created_at'];

    public function oldRank()
    {
        return $this->belongsTo('App\RankSetting', 'rank_id');
    }

    public function newRank()
    {
        return $this->belongsTo('App\RankSetting', 'rank_updated');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
