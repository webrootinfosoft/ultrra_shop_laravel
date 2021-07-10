<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QualifiedStatusHistory extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'status_id', 'status_updated', 'remarks'];

    public function qualifiedStatus()
    {
        return $this->belongsTo('App\QualifiedStatusSetting', 'status_id');
    }

    public function qualifiedStatusUpdated()
    {
        return $this->belongsTo('App\QualifiedStatusSetting', 'status_updated');
    }
}
