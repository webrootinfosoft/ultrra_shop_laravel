<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MembershipRenewal extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'order_id', 'due_date', 'next_due_date'
    ];

    protected $with = ['user', 'order'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function order()
    {
        return $this->belongsTo('App\Order');
    }

}
