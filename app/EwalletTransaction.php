<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EwalletTransaction extends Model
{
    use SoftDeletes;

    protected $fillable = ['commission_id', 'user_id', 'from_user_id', 'from_business_center_id', 'amount', 'amount_type', 'current_balance', 'description', 'note', 'response', 'status', 'created_at', 'updated_at', 'deleted_at'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function fromUser()
    {
        return $this->belongsTo('App\User', 'from_user_id');
    }

    public function fromBusinessCenter()
    {
        return $this->belongsTo('App\BusinessCenter', 'from_business_center_id');
    }

    public function commission()
    {
        return $this->belongsTo('App\Commission');
    }
}
