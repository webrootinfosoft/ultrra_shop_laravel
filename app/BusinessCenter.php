<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessCenter extends Model
{
    use SoftDeletes;

    protected $fillable = ['id', 'user_id', 'current_rank_id', 'lifetime_rank_id', 'business_center', 'left_carry', 'left_bvcarry', 'total_left', 'total_bvleft', 'right_carry', 'right_bvcarry', 'total_right', 'total_bvright', 'pv', 'redeem_pv', 'bv', 'qv', 'leg', 'created_at', 'updated_at', 'deleted_at'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function lifetimeRank()
    {
        return $this->belongsTo('App\RankSetting', 'lifetime_rank_id');
    }

    public function currentRank()
    {
        return $this->belongsTo('App\RankSetting', 'current_rank_id');
    }

    public function treeTables()
    {
        return $this->hasMany('App\TreeTable');
    }

    public static function updatePoint($bv_update, $qv_update, $from_business_center_id, $type, $rcid)
    {

        TreeTable::$upline_users = [];
        TreeTable::getAllUpline($from_business_center_id);
        $variable = TreeTable::$upline_users;

        if($rcid > 0)
        {
            $sponsor_leg = TreeTable::where('business_center_id', $from_business_center_id)->value('leg');

            if($sponsor_leg == 'L')
            {
                self::where('id', $from_business_center_id)->increment('left_carry', $qv_update);
                self::where('id', $from_business_center_id)->increment('total_left', $qv_update);
                self::where('id', $from_business_center_id)->increment('left_bvcarry', $bv_update);
                self::where('id', $from_business_center_id)->increment('total_bvleft', $bv_update);
            }
            else
            {
                self::where('id', $from_business_center_id)->increment('right_carry', $qv_update);
                self::where('id', $from_business_center_id)->increment('total_right', $qv_update);
                self::where('id', $from_business_center_id)->increment('right_bvcarry', $bv_update);
                self::where('id', $from_business_center_id)->increment('total_bvright', $bv_update);
            }

            self::where('id', $from_business_center_id)->increment('pv', $bv_update);
            PointHistory::addPointHistoryTable($from_business_center_id, $rcid, $bv_update, $qv_update, $sponsor_leg, $type, $rcid);
        }

        foreach ($variable as $key => $value)
        {
            if($value['leg'] == 'L')
            {
                self::where('id', $value['user_id'])->increment('left_carry', $qv_update);
                self::where('id', $value['user_id'])->increment('total_left', $qv_update);
                self::where('id', $value['user_id'])->increment('left_bvcarry', $bv_update);
                self::where('id', $value['user_id'])->increment('total_bvleft', $bv_update);
            }
            else
            {
                self::where('id', $value['user_id'])->increment('right_carry', $qv_update);
                self::where('id', $value['user_id'])->increment('total_right', $qv_update);
                self::where('id', $value['user_id'])->increment('right_bvcarry', $bv_update);
                self::where('id', $value['user_id'])->increment('total_bvright', $bv_update);
            }
            self::where('id', $value['user_id'])->increment('pv', $bv_update);
            PointHistory::addPointHistoryTable($value['user_id'], $from_business_center_id, $bv_update, $qv_update, $value['leg'], $type, $rcid);
        }

    }

    public static function deductPoint($bv_update, $qv_update, $from_business_center_id, $type, $rc_id, $date_added)
    {

        TreeTable::$upline_users = [];
        TreeTable::getAllUpline($from_business_center_id);
        $variable = TreeTable::$upline_users;


        /*ADDED FOR RC*/

        if($rc_id > 0)
        {
            $sponsor_leg = TreeTable::where('business_center_id', $from_business_center_id)->value('leg');

            if($sponsor_leg == 'L')
            {
                self::where('id', $from_business_center_id)->decrement('left_carry', $qv_update);
                self::where('id', $from_business_center_id)->decrement('total_left', $qv_update);
                self::where('id', $from_business_center_id)->decrement('left_bvcarry', $bv_update);
                self::where('id', $from_business_center_id)->decrement('total_bvleft', $bv_update);
            }
            else
            {
                self::where('id', $from_business_center_id)->decrement('right_carry', $qv_update);
                self::where('id', $from_business_center_id)->decrement('total_right', $qv_update);
                self::where('id', $from_business_center_id)->decrement('right_bvcarry', $bv_update);
                self::where('id', $from_business_center_id)->decrement('total_bvright', $bv_update);
            }

            // self::where('user_id',$from_id)->decrement('pv',$bv_update);
            PointHistory::deductPointHistoryTable($from_business_center_id, $rc_id, $bv_update, $qv_update, $sponsor_leg, $type, $rc_id, $date_added);
        }
        /*ADDED FOR RC*/
        foreach ($variable as $key => $value)
        {
            if($value['leg'] == 'L')
            {
                self::where('id', $value['user_id'])->decrement('left_carry', $qv_update);
                self::where('id', $value['user_id'])->decrement('total_left', $qv_update);
                self::where('id', $value['user_id'])->decrement('left_bvcarry', $bv_update);
                self::where('id', $value['user_id'])->decrement('total_bvleft', $bv_update);
            }
            else
            {
                self::where('id', $value['user_id'])->decrement('right_carry', $qv_update);
                self::where('id', $value['user_id'])->decrement('total_right', $qv_update);
                self::where('id', $value['user_id'])->decrement('right_bvcarry', $bv_update);
                self::where('id', $value['user_id'])->decrement('total_bvright', $bv_update);
            }

            PointHistory::deductPointHistoryTable($value['user_id'], $from_business_center_id, $bv_update, $qv_update, $value['leg'], $type, $rc_id, $date_added);
        }

    }
}
