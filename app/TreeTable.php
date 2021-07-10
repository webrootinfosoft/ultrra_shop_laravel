<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TreeTable extends Model
{
    use SoftDeletes;

    public static $upline_users = [];
    public static $downline = [];
    public static $downline_id_list = [];
    public static $downline_id_top = [];
    public static $downline_users = '';
    public static $upline_id_list = [];

    protected $fillable = ['business_center_id', 'sponsor_id', 'placement_id', 'leg', 'type', 'created_at', 'updated_at'];

    public function businessCenter()
    {
        return $this->belongsTo('App\BusinessCenter');
    }

    public function sponsor()
    {
        return $this->belongsTo('App\User', 'sponsor_id');
    }

    public function placementBusinessCenter()
    {
        return $this->belongsTo('App\BusinessCenter', 'placement_id');
    }

    public static function getTree($root = true, $placement_id = "", $treedata = [], $level = 0)
    {
        if ($level == 5)
        {
            return false;
        }
        if ($root)
        {
            $data = self::with('businessCenter.lifeTimeRank', 'businessCenter.user.rank')->where('business_center_id', $placement_id)->get();
        }
        else
        {
            $data = self::with('businessCenter.lifeTimeRank', 'businessCenter.user.rank')->where('placement_id', $placement_id)->get();
        }
        // return $data;
        $currentuserid = auth()->user()->id;
        $treearray = [];

        foreach ($data as $key => $value)
        {
            if ($value->type == "yes" || $value->type == "no")
            {
                if ($root)
                {
                    $push = self::getTree(false, $value->business_center_id, $treearray, $level + 1);
                    $class = 'up';
                    $usertype = 'root';
                }
                else
                {
                    $push = self::getTree(false, $value->business_center_id, $treearray, $level + 1);
                    $class = 'down';
                    $usertype = 'child';
                }

                $qualified_status = $value->businessCenter && $value->businessCenter->user && $value->businessCenter->user->rank ? $value->businessCenter->user->rank->rank_status : "";
                $user_type = $value->businessCenter && $value->businessCenter->user ? $value->businessCenter->user->usertype : 'rc';
                $power_qualified = $value->businessCenter && $value->businessCenter->user && $value->businessCenter->user->power_qualified ? 'Yes' : 'No';
                $binary_qualified = $value->businessCenter && $value->businessCenter->user && $value->businessCenter->user->binary_qualified ? 'Yes' : 'No';
                $content = $value->businessCenter && $value->businessCenter->user ? '<img class="" style="max-width:50px;cursor:pointer;" id="'.$value->business_center_id.'" upper-id="'.$value->placement_id.'" src="'.$value->businessCenter->user->image.'"><br/>': '';

                $dots = [];
//                if($qualified_status == "Member")
//                {
//
//                    $dots[] = '<i class="far fa-circle mr-2"></i>';
//                }
//                if($qualified_status == "Basic")
//                {
//
//                    $dots[] = '<i class="far fa-circle mr-2" style="color: #47bcd4;"></i>';
//                }
//                if($qualified_status == "Entrepreneur")
//                {
//
//                    $dots[] = '<i class="far fa-circle mr-2"></i>';
//                }
//                if($qualified_status == "Platinum")
//                {
//
//                    $dots[] = '<i class="far fa-circle mr-2" style="color: #FFD700;"></i>';
//                }
//                if($qualified_status == "Diamond")
//                {
//
//                    $dots[] = '<i class="far fa-circle mr-2" style="color: black;"></i>';
//                }
                if($user_type == "pc")
                {

                    $dots[] = '<i class="fa fa-circle mr-2" style="color:#4B0082;"></i>';
                }
                if($binary_qualified == "Yes")
                {

                    $dots[] = '<i class="fa fa-circle mr-2" style="color:#00BCD4;"></i>';
                }
                if($power_qualified == "Yes")
                {

                    $dots[] = '<i class="fa fa-circle mr-2" style="color:#9932CC;"></i>';
                }
                if($value->matchingbonus_percentage == 50)
                {

                    $dots[] = '<i class="fa fa-circle mr-2" style="color:#DAA520;"></i>';
                }

//                $left_carry = $value->businessCenter ?  $value->businessCenter->left_carry : 0;
//                $left_bvcarry = $value->businessCenter ?  $value->businessCenter->left_bvcarry : 0;
//                $right_carry = $value->businessCenter ?  $value->businessCenter->right_carry : 0;
//                $right_bvcarry = $value->businessCenter ?  $value->businessCenter->right_bvcarry : 0;

                $currentweek_leftbv = PointHistory::where('business_center_id', $value->business_center_id)->whereBetween('created_at', [Carbon::now()->startOfWeek()->format('Y-m-d H:i:s'), Carbon::now()->endOfWeek()->format('Y-m-d H:i:s')])->where('leg', 'L')->sum('bv');

                $currentweek_rightbv = PointHistory::where('business_center_id', $value->business_center_id)->whereBetween('created_at', [Carbon::now()->startOfWeek()->format('Y-m-d H:i:s'), Carbon::now()->endOfWeek()->format('Y-m-d H:i:s')])->where('leg', 'R')->sum('bv');

                $currentweek_leftqv = PointHistory::where('business_center_id', $value->business_center_id)->whereBetween('created_at', [Carbon::now()->startOfWeek()->format('Y-m-d H:i:s'), Carbon::now()->endOfWeek()->format('Y-m-d H:i:s')])->where('leg', 'L')->sum('pv');

                $currentweek_rightqv = PointHistory::where('business_center_id', $value->business_center_id)->whereBetween('created_at', [Carbon::now()->startOfWeek()->format('Y-m-d H:i:s'), Carbon::now()->endOfWeek()->format('Y-m-d H:i:s')])->where('leg', 'R')->sum('pv');

                $last_entry = QvCarryForwardHistory::where('business_center_id', $value->business_center_id)
                    ->where('created_at', '<', date('Y-m-d H:i:s'))
                    ->max('created_at');

                if($last_entry == null)
                {
                    $carryover_qv = PointHistory::where('business_center_id', $value->business_center_id)
                        ->where('created_at', '<=', date('Y-m-d H:i:s'))->where('leg', 'L')->sum('pv');

                    $carryover_qvright = PointHistory::where('business_center_id', $value->business_center_id)
                        ->where('created_at', '<=', date('Y-m-d H:i:s'))->where('leg', 'R')->sum('pv');
                }
                else
                {
                    $carryover_qv = QvCarryForwardHistory::where('business_center_id', $value->business_center_id)
                        ->where('created_at', $last_entry)->value('left');

                    $upto_thisweek = PointHistory::where('business_center_id', $value->business_center_id)
                        ->where('created_at', '>', $last_entry)
                        ->where('created_at', '<', Carbon::now()->startOfWeek())
                        ->where('leg', 'L')->sum('pv');

                    $carryover_qv = $carryover_qv + $upto_thisweek;

                    $carryover_qvright = QvCarryForwardHistory::where('business_center_id', $value->business_center_id)
                        ->where('created_at', $last_entry)->value('right');

                    $upto_thisweek = PointHistory::where('business_center_id', $value->business_center_id)
                        ->where('created_at', '>', $last_entry)
                        ->where('created_at', '<', Carbon::now()->startOfWeek())
                        ->where('leg', 'R')->sum('pv');

                    $carryover_qvright = $carryover_qvright + $upto_thisweek;
                }

                $left_carry = is_null($last_entry) ? $currentweek_leftqv : $carryover_qv + $currentweek_leftqv;
                $right_carry = is_null($last_entry) ? $currentweek_rightqv : $carryover_qvright + $currentweek_rightqv;

                $last_entry = CarryForwardHistory::where('business_center_id', $value->business_center_id)
                    ->where('created_at', '<', date('Y-m-d H:i:s'))
                    ->where('action', '<>', 'added')
                    ->max('created_at');


                if($last_entry == null)
                {
                    $last_entry = CarryForwardHistory::where('business_center_id', $value->business_center_id)->where('created_at', '<', date('Y-m-d H:i:s'))->max('created_at');

                    if($last_entry != null)
                    {
                        $carryover_bv = CarryForwardHistory::where('business_center_id', $value->business_center_id)->where('created_at', $last_entry)->value('left');

                        $upto_thisweek = PointHistory::where('business_center_id', $value->business_center_id)
                            ->where('created_at', '>', $last_entry)
                            ->where('created_at', '<', Carbon::now()->startOfWeek())
                            ->where('leg', 'L')->sum('bv');

                        $carryover_bv = $carryover_bv + $upto_thisweek;

                        $carryover_bvright = CarryForwardHistory::where('business_center_id', $value->business_center_id)->where('created_at',$last_entry)->value('right');

                        $upto_thisweek = PointHistory::where('business_center_id', $value->business_center_id)
                            ->where('created_at', '>', $last_entry)
                            ->where('created_at', '<', Carbon::now()->startOfWeek())
                            ->where('leg', 'R')->sum('bv');

                        $carryover_bvright = $carryover_bvright + $upto_thisweek;
                    }
                    else
                    {
                        $carryover_bv = PointHistory::where('business_center_id', $value->business_center_id)
                            ->where('created_at', '<', Carbon::now()->startOfWeek())
                            ->where('leg', 'L')->sum('bv');

                        $carryover_bvright = PointHistory::where('business_center_id', $value->business_center_id)
                            ->where('created_at', '<', Carbon::now()->startOfWeek())
                            ->where('leg', 'R')->sum('bv');
                    }
                }
                else
                {
                    $carryover_bv = CarryForwardHistory::where('business_center_id', $value->business_center_id)
                        ->where('created_at', $last_entry)
                        ->value('left');

                    $upto_thisweek = PointHistory::where('business_center_id', $value->business_center_id)
                        ->where('created_at', '>', $last_entry)
                        ->where('created_at', '<', Carbon::now()->startOfWeek())
                        ->where('leg', 'L')->sum('bv');

                    $carryover_bv = $carryover_bv + $upto_thisweek;


                    $carryover_bvright = CarryForwardHistory::where('business_center_id', $value->business_center_id)
                        ->where('created_at', $last_entry)
                        ->value('right');


                    $upto_thisweek = PointHistory::where('business_center_id', $value->business_center_id)
                        ->where('created_at', '>', $last_entry)
                        ->where('created_at', '<', Carbon::now()->startOfWeek())
                        ->where('leg', 'R')->sum('bv');

                    $carryover_bvright = $carryover_bvright + $upto_thisweek;
                }

                $left_bvcarry = $carryover_bv + $currentweek_leftbv;
                $right_bvcarry = $carryover_bvright + $currentweek_rightbv;


                if($level < 2)
                {
                    $content = $content.'<p style="position:absolute;top: 45px; margin-left: -38px;">'.$left_carry.' QV</p><p style="position:absolute;top: 60px; margin-left: -38px;">'.$left_bvcarry.'BV</p>';

                    $content = $content.'<p style="position:absolute;right: -12px;top: 45px; margin-right: -18px;">'.$right_carry.'QV</p><p style="position:absolute;right: -12px;top: 60px; margin-right: -18px;">'.$right_bvcarry.'BV</p>';
                }

                $rank_icon = $value->businessCenter->lifetimeRank;

                if($rank_icon != '')
                {
                    if($value->businessCenter->user && $value->businessCenter->user->rank && $value->businessCenter->user->rank != 'Member')
                    {
                        $content = $content.'<img src="/assets/images/'.$rank_icon.'" style="width:30px;height:25px;"/>';
                    }
                }

                if (!is_null($rank_icon))
                {
                    $rank_icon = '<img src="'.url('/images/'.$rank_icon->rank_icon).'" style="width:30px;height:25px;"/>';
                }
                else
                {
                    $rank_icon = '<img src="'.url('/images/promoter.png').'" style="width:30px;height:25px;"/>';
                }

                $personal_qv = OrderProduct::whereHas('order', function ($q) use ($value) {
                    $q->where('user_id', $value->businessCenter->user_id)->where('order_status_id', 4);
                })->where('created_at', '>=', date('Y-m-d', strtotime('-28 days')))->sum('qv');

                $customer_qv = OrderProduct::whereHas('order', function ($q) use ($value) {
                    $q->where('sponsor_id', $value->businessCenter->user_id)->where('order_status_id', 4)->whereHas('user', function ($q) {
                        $q->where('usertype', '!=', 'dc');
                    });
                })->where('created_at', '>=', date('Y-m-d', strtotime('-28 days')))->sum('qv');

                $row = [
                    'name' => $value->businessCenter->user ? $value->businessCenter->user->name : '',
                    'username' => $value->businessCenter ? $value->businessCenter->business_center : '',
                    'active_status' => $value->businessCenter->user ? $value->businessCenter->user->active_status : 0,
                    'pqv' => $personal_qv + $customer_qv,
                    'qs' => $value->businessCenter->user ? $value->businessCenter->user->qualified_status : 0,
                    'placement_user_id' => $value->placementBusinessCenter ? $value->placementBusinessCenter->user_id : '',
                    'placement_business_center_id' => $value->placement_id,
                    'sponsor_id' => $value->sponsor_id,
                    'user_id' => $value->businessCenter ? $value->businessCenter->user_id : 0,
                    'dots' => $dots,
                    'left_carry' => $left_carry,
                    'left_bv_carry' => $left_bvcarry,
                    'right_carry' => $right_carry,
                    'right_bv_carry' => $right_bvcarry,
                    'image' => $value->businessCenter && $value->businessCenter->user ? '/'.$value->businessCenter->user->image : '/avatar-big.png',
                    'nodeContentPro' => $content,
                    'business_center_id' => $value->business_center_id,
//                    'lifetime_rank' => $value->businessCenter ? $value->businessCenter->lifetimeRank : null,
                    'leg' => $value->leg,
                    'rank' => $rank_icon,
                    'className' => $value->businessCenter && $value->businessCenter->user && $value->businessCenter->user->active_user == 1 ? 'active' : 'inactive'
                ];

                $row['children'] = $push;
            }
            else
            {
                $row = [
                    'name' => 'Add Here',
                    'username' => '',
                    'pqv' => 0,
                    'qs' => 0,
                    'active_status' => 0,
                    'placement_user_id' => 0,
                    'placement_business_center_id' => 0,
                    'dots' => [],
                    'left_carry' => 0,
                    'left_bv_carry' => 0,
                    'right_carry' => 0,
                    'right_bv_carry' => 0,
                    'image' => '/avatar-big.png',
                    'leg' => $value->leg,
                    'nodeContentPro' => '<h1 class="text-center"><i class="fa fa-plus-circle"></i></h1>',
                    'className' => 'vaccant'
                ];
            }

            $treearray[] = $row;
        }
        $treedata = $treearray;
        return $treedata;

    }

    public static function getAllDownlinesAutocomplete($placement_id)
    {

        $data = self::whereIn('placement_id', $placement_id)->get();

        $placement_id =[];
        $downline_ids = [];

        foreach ($data as $value)
        {
            if($value->type=="yes" || $value->type=="no")
            {
                if ($value->business_center_id > 0)
                {
                    self::$downline_id_list[] = $value->business_center_id;

                    array_push($placement_id, $value->business_center_id);
                }
            }
        }

        if(count($placement_id) > 0)
        {
            self::getAllDownlinesAutocomplete($placement_id);
        }

        return 1;
    }

    public static function getDownlines($root = true, $placement_id = "", $level = 0)
    {

        $data= self::where('placement_id', $placement_id)->get();

        foreach ($data as $key => $value)
        {
            if($value->type=="yes" || $value->type=="no")
            {

                self::$downline[$value->id]['user_id'] = $value->business_center_id;
                self::$downline[$value->id]['id'] = $value->business_center_id;
                self::$downline[$value->id]['rank'] = $value->business_center_id;
                self::$downline[$value->id]['placement'] = $value->placement_id;
                self::$downline_id_list[] = $value->business_center_id;
                self::getDownlines(false, $value->business_center_id,$level + 1);
            }
        }
    }

    public static function topEnrollersGetDownlines($placement_id, $start, $end)
    {
        $data = self::whereIn('placement_id', $placement_id)->get();

        $placement_id =[];

        foreach ($data as $value)
        {
            if($value->type=="yes" || $value->type=="no")
            {
                $business_center = BusinessCenter::where('id', $value->business_center_id)->first();

                $check_count = SponsorTree::whereHas('user', function ($q) use ($start, $end) {
                    $q->where('created_at', '>=', date('Y-m-d 00:00:00', strtotime($start)))->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($end)));
                })->where('type', '!=', 'vaccant')->where('sponsor_id', $business_center->user_id)->count();

                if($check_count > 0)
                {
                    self::$downline_id_list[] = $value->business_center_id;
                }

                array_push($placement_id, $value->business_center_id);

            }
        }

        if(count($placement_id) > 0)
        {
            self::topEnrollersGetDownlines($placement_id, $start, $end);
        }
        return 1;
    }

    public static function getAllUpline($business_center_id)
    {
        try
        {
            $result = self::where('business_center_id', $business_center_id)->get();

            foreach ($result as $key => $value)
            {
                if ($value->type != 'vaccant' && $value->placement_id > 1)
                {
                    self::$upline_users[] = ['business_center_id' => $value->business_center_id, 'user_id' => $value->placement_id, 'leg' => $value->leg];
                    self::$upline_id_list[] = $value->placement_id;
                }

                if ($value->placement_id > 1)
                {
                    self::getAllUpline($value->placement_id);
                }
            }

            return 1;
        }
        catch (\Exception $exception)
        {
            return $exception->getLine().' '.$exception->getMessage();
        }

    }

    public static function getPlacementId($sponsor_id, $leg)
    {
        $user_id = self::where('placement_id', $sponsor_id)->where("leg", $leg)->where("type", "<>", "vaccant")->value('business_center_id');

        if (!$user_id)
        {
            return $sponsor_id;
        }

        return self::getPlacementId($user_id, $leg);
    }

    public static function getDownlinesWithLeg($root, $placement_id, $leg)
    {

        $data= self::where('placement_id', $placement_id)->where('leg', $leg)->get();

        foreach ($data as $key => $value)
        {
            if($value->type=="yes" || $value->type=="no")
            {

                self::$downline[$value->id]['user_id'] = $value->business_center_id;
                self::$downline[$value->id]['id'] = $value->business_center_id;
                self::$downline[$value->id]['rank'] = $value->business_center_id;
                self::$downline[$value->id]['placement'] = $value->placement_id;
                self::$downline[$value->id]['leg'] = $value->leg;
                self::$downline_id_list[] = $value->business_center_id;
                self::getDownlinesWithLeg(false, $value->business_center_id, $leg);
            }
        }
    }
}
