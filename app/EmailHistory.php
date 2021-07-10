<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailHistory extends Model
{

    protected $fillable = ['user_id', 'email', 'subject', 'content'];

    protected $with = ['user'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
