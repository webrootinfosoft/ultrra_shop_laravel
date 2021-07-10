<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActiveStatusHistory extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'user_id', 'active_status', 'week_number'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
