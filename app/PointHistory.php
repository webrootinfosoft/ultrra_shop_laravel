<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PointHistory extends Model
{
    use SoftDeletes;

    protected $fillable = ['business_center_id', 'user_id', 'from_user_id', 'rcid', 'from_business_center_id', 'pv', 'bv', 'leg', 'commission_type', 'created_at', 'updated_at', 'deleted_at'];

    public function businessCenter()
    {
        return $this->belongsTo('App\BusinessCenter');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function fromUser()
    {
        return $this->belongsTo('App\User', 'from_user_id');
    }

    public static function addPointHistoryTable($business_center_id, $from_business_center_id, $bv, $qv, $leg, $commission_type, $rcid)
    {
        self::create(['business_center_id' => $business_center_id, 'from_business_center_id' => $from_business_center_id, 'rcid' => $rcid, 'bv' => $bv, 'leg' => $leg, 'commission_type' => $commission_type]);
        self::create(['business_center_id' => $business_center_id, 'from_business_center_id' => $from_business_center_id, 'rcid' => $rcid, 'pv' => $qv, 'leg' => $leg, 'commission_type' => $commission_type]);
    }

    public static function deductPointHistoryTable($business_center_id, $from_business_center_id, $bv, $qv, $leg, $commission_type, $rc_id, $date_added)
    {
        $bv = $bv * -1;
        $qv = $qv * -1;

        self::create(['business_center_id' => $business_center_id, 'from_business_center_id' => $from_business_center_id, 'rcid' => $rc_id, 'bv' => $bv, 'leg' => $leg, 'commission_type' => $commission_type, 'created_at' => $date_added]);
        self::create(['business_center_id' => $business_center_id, 'from_business_center_id' => $from_business_center_id, 'rcid' => $rc_id, 'pv' => $qv, 'leg' => $leg, 'commission_type' => $commission_type, 'created_at' => $date_added]);
    }

}
