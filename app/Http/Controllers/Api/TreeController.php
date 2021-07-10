<?php

namespace App\Http\Controllers\Api;

use App\BusinessCenter;
use App\Http\Controllers\Controller;
use App\OrderProduct;
use App\QvCarryForwardHistory;
use App\PointHistory;
use App\CarryForwardHistory;
use App\RankHistory;
use App\RankSetting;
use App\SponsorTree;
use App\TreeTable;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Mockery\Exception;

class TreeController extends Controller
{
    public function bingoTree(Request $request, $id = 1)
    {
        if ($id == 1)
        {
            $user = User::with('businessCenters')->find(auth()->id());
            if (count($user->businessCenters) > 0)
            {
                $id = $user->businessCenters[0]->id;
            }
            else
            {
                $id = 0;
            }
        }

        $tree = TreeTable::getTree(true, $id);
        // return $tree;

        return response()->json(['data' => $tree[0], 'status' => 200]);
    }

    public function sponsorTree($id = 1)
    {
        if ($id == 1)
        {
            $id = auth()->id();
        }

        $tree = SponsorTree::getTree(true, $id);
        return response()->json(['data' => $tree[0], 'status' => 200]);
    }

    public function getBingoTreeUserData($id = 1)
    {
        if ($id == 1)
        {
            $user = User::with('businessCenters')->find(auth()->id());
            if (count($user->businessCenters) > 0)
            {
                $id = $user->businessCenters[0]->id;
                $business_center = $user->businessCenters[0];
            }
            else
            {
                $id = 0;
                $business_center = null;
            }
        }
        else
        {
            $business_center = BusinessCenter::find($id);
            $user = User::with('businessCenters')->find($business_center->user_id);
        }

        $sponsored_customers = User::whereHas('sponsorTrees', function ($q) use ($user) {
            $q->where('sponsor_id', $user->id);
        })->whereIn('usertype', ['rc', 'pc'])
            ->whereHas('orders')
            ->get();

        $sponsored_distributors = User::whereHas('sponsorTrees', function ($q) use ($user) {
            $q->where('sponsor_id', $user->id);
        })->where('usertype', 'dc')
            ->whereHas('orders')
            ->get();

        $user = User::find($user->id);
        $data['username'] = $user->username;
        $data['business_center'] = !is_null($business_center) ? $business_center->business_center : '';
        $data['firstname'] = $user->firstname;
        $data['lastname'] = $user->lastname;
        $data['phone'] = $user->phone;
        $data['country'] = $user->country ? $user->country->name : '';
        $data['sponsor'] = $user->sponsor->username;
        $data['rank'] = RankSetting::where('id', $business_center->lifetime_rank_id)->value('rank');
        $data['sponserd_customers'] = count($sponsored_customers);
        $data['sponserd_distibutors'] = count($sponsored_distributors);

        $data['current_new_bv'] = PointHistory::where('business_center_id', $id)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->where('leg', 'L')->sum('bv');

        $last_entry = CarryForwardHistory::where('business_center_id', $id)
            ->where('created_at', '<', date('Y-m-d H:i:s'))
            ->where('action', '<>', 'added')
            ->max('created_at');


        if($last_entry == null)
        {
            $last_entry = CarryForwardHistory::where('business_center_id', $id)->where('created_at', '<', date('Y-m-d H:i:s'))->max('created_at');

            if($last_entry != null)
            {
                $data['carryover_bv'] = CarryForwardHistory::where('business_center_id', $id)->where('created_at', $last_entry)->value('left');

                $upto_thisweek = PointHistory::where('business_center_id', $id)
                    ->where('created_at', '>', $last_entry)
                    ->where('created_at', '<', Carbon::now()->startOfWeek())
                    ->where('leg', 'L')->sum('bv');

                $data['carryover_bv'] = $data['carryover_bv'] + $upto_thisweek;

                $data['carryover_bvright'] = CarryForwardHistory::where('business_center_id', $id)->where('created_at',$last_entry)->value('right');

                $upto_thisweek = PointHistory::where('business_center_id', $id)
                    ->where('created_at', '>', $last_entry)
                    ->where('created_at', '<', Carbon::now()->startOfWeek())
                    ->where('leg', 'R')->sum('bv');

                $data['carryover_bvright'] = $data['carryover_bvright'] + $upto_thisweek;
            }
            else
            {
                $data['carryover_bv'] = PointHistory::where('business_center_id', $id)
                    ->where('created_at', '<', Carbon::now()->startOfWeek())
                    ->where('leg', 'L')->sum('bv');

                $data['carryover_bvright'] = PointHistory::where('business_center_id', $id)
                    ->where('created_at', '<', Carbon::now()->startOfWeek())
                    ->where('leg', 'R')->sum('bv');
            }
        }
        else
        {
            $data['carryover_bv'] = CarryForwardHistory::where('business_center_id', $id)
                ->where('created_at', $last_entry)
                ->value('left');

            $upto_thisweek = PointHistory::where('business_center_id', $id)
                ->where('created_at', '>', $last_entry)
                ->where('created_at', '<', Carbon::now()->startOfWeek())
                ->where('leg', 'L')->sum('bv');

            $data['carryover_bv'] = $data['carryover_bv'] + $upto_thisweek;


            $data['carryover_bvright'] = CarryForwardHistory::where('business_center_id', $id)
                ->where('created_at', $last_entry)
                ->value('right');


            $upto_thisweek = PointHistory::where('business_center_id', $id)
                ->where('created_at', '>', $last_entry)
                ->where('created_at', '<', Carbon::now()->startOfWeek())
                ->where('leg', 'R')->sum('bv');

            $data['carryover_bvright'] = $data['carryover_bvright'] + $upto_thisweek;
        }

        $data['current_new_qv'] = PointHistory::where('business_center_id', $id)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->where('leg', 'L')->sum('pv');

        $last_entry = QvCarryForwardHistory::where('business_center_id', $id)
            ->where('created_at', '<', date('Y-m-d H:i:s'))
            ->max('created_at');

        if($last_entry == null)
        {
           $data['carryover_qv'] = PointHistory::where('business_center_id', $id)
               ->where('created_at', '<', date('Y-m-d H:i:s'))->where('leg', 'L')->sum('pv');

            // $data['carryover_qv'] = 0;

           $data['carryover_qvright'] = PointHistory::where('business_center_id', $id)
               ->where('created_at', '<', date('Y-m-d H:i:s'))->where('leg', 'R')->sum('pv');

            // $data['carryover_qvright'] = 0;
        }
        else
        {
            $data['carryover_qv'] = QvCarryForwardHistory::where('business_center_id', $id)
                ->where('created_at', $last_entry)->value('left');

            $upto_thisweek = PointHistory::where('business_center_id', $id)
                ->where('created_at', '>', $last_entry)
                ->where('created_at', '<', Carbon::now()->startOfWeek())
                ->where('leg', 'L')->sum('pv');

            $data['carryover_qv'] = $data['carryover_qv'] + $upto_thisweek;

            $data['carryover_qvright'] = QvCarryForwardHistory::where('business_center_id', $id)
                ->where('created_at', $last_entry)->value('right');

            $upto_thisweek = PointHistory::where('business_center_id', $id)
                ->where('created_at', '>', $last_entry)
                ->where('created_at', '<', Carbon::now()->startOfWeek())
                ->where('leg', 'R')->sum('pv');

            $data['carryover_qvright'] = $data['carryover_qvright'] + $upto_thisweek;
        }

        $data['current_new_bvright'] = PointHistory::where('business_center_id', $id)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->where('leg', 'R')->sum('bv');

        $data['current_new_qvright'] = PointHistory::where('business_center_id', $id)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->where('leg', 'R')->sum('pv');

        $rank_history_id = RankHistory::where('business_center_id', $id)
            ->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime(date('Y-m-d'))))
            ->max('id');

        $rank_id = ($rank_history_id == null) ? 1 : RankHistory::where('id', $rank_history_id)->value('rank_updated');

        $data['par'] = "Processing...";
        // RankSetting::where('id', $business_center->id)->value('current_rank_id');

        $last_4_week =  date('Y-m-d H:i:s', strtotime('-28 Day', strtotime(date('Y-m-d H:i:s'))));

        $personal_qv = OrderProduct::whereHas('order', function ($q) use ($user) {
            $q->where('user_id', $user->id)->where('order_status_id', 4);
        })->where('created_at', '>=', date('Y-m-d 23:59:59', strtotime($last_4_week)))->sum('qv');

        $customer_qv = OrderProduct::whereHas('order', function ($q) use ($user) {
            $q->where('sponsor_id', $user->id)->where('order_status_id', 4)->whereHas('user', function ($q) {
                $q->where('usertype', '!=', 'dc');
            });
        })->where('created_at', '>=', date('Y-m-d 23:59:59', strtotime($last_4_week)))->sum('qv');

        $data['total_qv'] = $personal_qv + $customer_qv;

        $data['enrolled_date'] = date('Y-m-d', strtotime($user->created_at));
        if($user->last_login_at != null)
        {
            $data['last_login'] = date('Y-m-d H:i:s', strtotime($user->last_login_at));
        }
        else
        {
            $data['last_login'] = 0;
        }

        $data['sponsor_name'] = $user->sponsor->firstname;
        $data['sponsor_lname'] = $user->sponsor->lastname;
        $data['sponsor_phone'] = $user->sponsor->phone;
        $data['active_left'] = $user->active_left;
        $data['active_right'] = $user->active_right;

        $maintenance_date = $user->maintenance_date;

        if(is_null($maintenance_date))
        {
            $maintenance_date = date('Y-m-d');
        }

        $data['maintenance_date'] = $maintenance_date;

        return response()->json(['data' => $data, 'status' => 200]);
    }

    public function getMaintenanceDate($user_id)
    {
        $list = User::where('sponsor_id', '=', $user_id)->where('usertype','!=','dc')->whereHas('orders')->pluck('id')->toArray() ;

        $personal = OrderProduct::whereHas('order', function ($q) use ($list) {
            $q->where('order_status_id', 4)->whereIn('user_id', $list);
        })->where(function ($q) {
            $q->where('created_at', '>=', date('Y-m-d 00:00:00', strtotime('-28 days')));
            $q->where('created_at', '<=', date('Y-m-d 23:59:59'));
        })->orderby('id', 'desc')->get();

        $qv = 0;
        $grace_period_date = OrderProduct::whereHas('order', function ($q) use ($list) {
            $q->where('order_status_id', 4)->whereIn('user_id', $list);
        })->first();

        if($grace_period_date != null)
        {
            $grace_period_date = OrderProduct::whereHas('order', function ($q) use ($list) {
                $q->where('order_status_id', 4)->whereIn('user_id', $list);
            })->first()->created_at;
        }
        else
        {
            $grace_period_date = null;
        }

        if($grace_period_date != null)
        {
            foreach ($personal as $key => $value)
            {
                $qv += $value->qv;
                if($qv >= 50)
                {
                    $grace_period_date = $value->created_at;
                    break;
                }
            }
        }
        else
        {
            $grace_period_date = "past_due";
        }

        return $grace_period_date;
    }

    public function getLastTreeUser($placement_id, $leg)
    {
        TreeTable::$downline = [];
        TreeTable::getDownlinesWithLeg(true, $placement_id, $leg);
        $last_users = array_values(TreeTable::$downline);
        return response()->json(['data' => $last_users[count($last_users) - 1], 'status' => 200]);
    }
}
