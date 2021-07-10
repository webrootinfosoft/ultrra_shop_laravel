<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Commission extends Model
{
    use SoftDeletes;

    protected $fillable = ['id', 'user_id', 'business_center_id', 'from_user_id', 'from_business_center_id', 'amount', 'commission_type', 'notes', 'status', 'created_at', 'updated_at', 'deleted_at'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function businessCenter()
    {
        return $this->belongsTo('App\BusinessCenter');
    }

    public function fromUser()
    {
        return $this->belongsTo('App\User', 'from_user_id');
    }
    
    public function fromBusinessCenter()
    {
        return $this->belongsTo('App\BusinessCenter', 'from_business_center_id');
    }

    public function ewalletTransactions()
    {
        return $this->hasMany('App\EwalletTransaction');
    }
}
