<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivityLog extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'ip_address', 'title', 'description'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
