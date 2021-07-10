<?php

namespace App\Http\Controllers\Api;

use App\Commission;
use App\OrderProduct;
use App\RankHistory;
use App\RankSetting;
use App\RankStatusSetting;
use App\TreeTable;
use App\BusinessCenter;
use App\Http\Controllers\Controller;
use App\User;
use App\Order;
use App\PointHistory;
use App\SponsorTree;
use App\ActiveStatusHistory;
use App\TravelCreditHistory;
use App\BelizeTravelCreditHistory;
use App\TravelDestination;
use App\CarryForwardHistory;
use App\QvCarryForwardHistory;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function newRank(Request $request)
    {
        $auth_user = BusinessCenter::where('user_id', auth()->id())->value('id');

        $downline_ids = TreeTable::getAllDownlinesAutocomplete([$auth_user]);

//        $data = RankHistory::with('oldRank', 'newRank', '')
    }

    public function downlineReport(Request $request)
    {
        $side = $request->side;
        $request_start = $request->start;
        $request_end = $request->end;
        $user_id = auth()->user()->id;
        $username = auth()->user()->username;

        if ($request->username)
        {
            $user_id = User::where('username', $request->username)->value('id');
            $username = $request->username;
        }

        $business_center_id = BusinessCenter::where('user_id', $user_id)->value('id');

        if ($side == "left")
        {
            $left_user = TreeTable::where('placement_id', $business_center_id)->where('leg','L')->value('business_center_id');
            TreeTable::$downline = [];
            TreeTable::getDownlines(true, $left_user);
            $downlines = TreeTable::$downline;

            $user_list[] = BusinessCenter::where('id', $business_center_id)->value('user_id');
            $user_list_waiting = [];
            foreach ($downlines as $key => $value)
            {
                $user_id_of_user = BusinessCenter::where('id',$value['user_id'])->value('user_id');
                $user_list[] = $user_id_of_user;
                // Waiting room banked qv and bv userlist
                $user_ids_waiting = User::where('user_state', 'waiting')
                    ->where('sponsor_id', $user_id_of_user)
                    ->whereHas('orders')
                    ->pluck('id')->toArray();
                foreach ($user_ids_waiting as &$user_id_waiting)
                {
                    $user_list_waiting[] = $user_id_waiting;
                    $user_list[] = $user_id_waiting;
                    $user_ids_waiting_chain = User::where('user_state', 'waiting')->where('sponsor_id', $user_id_waiting)->whereHas('orders')->pluck('id')->toArray();
                    foreach ($user_ids_waiting_chain as $user_id_waiting_chain)
                    {
                        array_push($user_ids_waiting, $user_id_waiting_chain);
                    }
                }
                // Waiting room banked qv and bv userlist
            }

            // Waiting room banked qv and bv
            $banked_qv = OrderProduct::whereHas('order', function ($q) use ($user_list_waiting) {
                $q->whereIn('user_id', $user_list_waiting)->where('order_status_id', 4);
            })->where('created_at', '>', date('Y-m-d 00:00:00', strtotime($request->start)))
                ->where('created_at', '<', date('Y-m-d 23:59:59', strtotime($request->end)))
                ->sum('qv');
            $banked_bv = OrderProduct::whereHas('order', function ($q) use ($user_list_waiting) {
                $q->whereIn('user_id', $user_list_waiting)->where('order_status_id', 4);
            })->where('created_at', '>', date('Y-m-d 00:00:00', strtotime($request->start)))
                ->where('created_at', '<', date('Y-m-d 23:59:59', strtotime($request->end)))
                ->sum('bv');
            // Waiting room banked qv ends

            $retail = PointHistory::where('business_center_id', $business_center_id)
                ->where('created_at', '>', date('Y-m-d 00:00:00', strtotime($request->start)))
                ->where('created_at', '<', date('Y-m-d 23:59:59', strtotime($request->end)))
                ->where('leg', 'L')->where('commission_type', 'rc')->get();

            $rc = array();
            foreach ($retail as $key => $value)
            {

                $rc[] = BusinessCenter::where('id', $value->rcid)->value('user_id');
            }

            $user_list = array_merge($user_list, $rc);

        }
        elseif ($side == "right")
        {
            $right_user = TreeTable::where('placement_id', $business_center_id)->where('leg', 'R')->value('business_center_id');
            TreeTable::$downline = [];
            TreeTable::getDownlines(true, $right_user);
            $downlines = TreeTable::$downline;

            $user_list[] = BusinessCenter::where('id', $business_center_id)->value('user_id');
            $user_list_waiting = [];
            foreach ($downlines as $key => $value)
            {
                $user_id_of_user = BusinessCenter::where('id', $value['user_id'])->value('user_id');
                $user_list[] = $user_id_of_user;

                // Waiting room banked qv and bv userlist
                $user_ids_waiting = User::where('user_state', 'waiting')->where('sponsor_id', $user_id_of_user)->whereHas('orders')->pluck('id')->toArray();
                foreach($user_ids_waiting as &$user_id_waiting)
                {
                    $user_list_waiting[] = $user_id_waiting;
                    $user_list[] = $user_id_waiting;
                    $user_ids_waiting_chain = User::where('user_state', 'waiting')->where('sponsor_id', $user_id_waiting)->whereHas('orders')->pluck('id')->toArray();
                    foreach($user_ids_waiting_chain as $user_id_waiting_chain)
                    {
                        array_push($user_ids_waiting, $user_id_waiting_chain);
                    }
                }
                // Waiting room banked qv and bv userlist
            }

            // Waiting room banked qv and bv
            $banked_qv = OrderProduct::whereHas('order', function ($q) use ($user_list_waiting) {
                $q->whereIn('user_id', $user_list_waiting)->where('order_status_id', 4);
            })->where('created_at', '>', date('Y-m-d 00:00:00', strtotime($request->start)))
                ->where('created_at', '<', date('Y-m-d 23:59:59', strtotime($request->end)))
                ->sum('qv');
            $banked_bv = OrderProduct::whereHas('order', function ($q) use ($user_list_waiting) {
                $q->whereIn('user_id', $user_list_waiting)->where('order_status_id', 4);
            })->where('created_at', '>', date('Y-m-d 00:00:00', strtotime($request->start)))
                ->where('created_at', '<', date('Y-m-d 23:59:59', strtotime($request->end)))
                ->sum('bv');
            // Waiting room banked qv and bv ends

            $retail = PointHistory::where('business_center_id', $business_center_id)
                ->where('created_at', '>', date('Y-m-d 00:00:00', strtotime($request->start)))
                ->where('created_at', '<', date('Y-m-d 23:59:59', strtotime($request->end)))
                ->where('leg', 'R')->where('commission_type', 'rc')->get();

            $rc = array();
            foreach ($retail as $key => $value)
            {
                $rc[] = BusinessCenter::where('id',$value->rcid)->value('user_id');
            }

            $user_list = array_merge($user_list,$rc);

        }
        elseif ($side == "all")
        {
            TreeTable::$downline = [];
            TreeTable::getDownlines(true, $business_center_id);
            $downlines = TreeTable::$downline;

            $user_list = [];
            $user_list_waiting = [];
            foreach ($downlines as $key => $value)
            {
                $user_id_of_user = BusinessCenter::where('id', $value['user_id'])->value('user_id');
                $user_list[] = $user_id_of_user;

                $business_center_ids[] = BusinessCenter::where('user_id', $user_id_of_user)->value('id');

                // Waiting room banked qv and bv userlist

                $user_ids_waiting = User::where('user_state', 'waiting')
                    ->where('sponsor_id', $user_id_of_user)
                    ->whereHas('orders')
                    ->pluck('id')->toArray();
                foreach($user_ids_waiting as &$user_id_waiting)
                {
                    $user_list_waiting[] = $user_id_waiting;
                    $user_list[] = $user_id_waiting;
                    $user_ids_waiting_chain = User::where('user_state', 'waiting')->where('sponsor_id', $user_id_waiting)->whereHas('orders')->pluck('id')->toArray();
                    foreach($user_ids_waiting_chain as $user_id_waiting_chain)
                    {
                        array_push($user_ids_waiting, $user_id_waiting_chain);
                    }
                }

                // Waiting room banked qv and bv userlist
            }
            // return $user_list;
            $self_sponsored_users = User::where('sponsor_id', auth()->user()->id)->where('user_state', 'waiting')->whereHas('orders')->pluck('id');
            foreach($self_sponsored_users as $self_sponsored_user)
            {
                $user_list_waiting[] = $self_sponsored_user;
                $user_list[] = $self_sponsored_user;
            }

            // Waiting room banked qv and bv
            $banked_qv = OrderProduct::whereHas('order', function ($q) use ($user_list_waiting) {
                $q->whereIn('user_id', $user_list_waiting)->where('order_status_id', 4);
            })->where('created_at', '>', date('Y-m-d 00:00:00', strtotime($request->start)))
                ->where('created_at', '<', date('Y-m-d 23:59:59', strtotime($request->end)))
                ->sum('qv');
            $banked_bv = OrderProduct::whereHas('order', function ($q) use ($user_list_waiting) {
                $q->whereIn('user_id', $user_list_waiting)->where('order_status_id', 4);
            })->where('created_at', '>', date('Y-m-d 00:00:00', strtotime($request->start)))
                ->where('created_at', '<', date('Y-m-d 23:59:59', strtotime($request->end)))
                ->sum('bv');
            // Waiting room banked qv and bv ends

            $retail = PointHistory::where('business_center_id', $business_center_id)
                ->where('created_at', '>', date('Y-m-d 00:00:00', strtotime($request->start)))
                ->where('created_at', '<', date('Y-m-d 23:59:59', strtotime($request->end)))
                ->where('commission_type', 'rc')->get();


            $rc = [];
            foreach ($retail as $key => $value)
            {

                $rc[] = BusinessCenter::where('id',$value->rcid)->value('user_id');
            }

            $user_list = array_merge($user_list,$rc);
        }

        $start_date = date('Y-m-d', strtotime($request->start));
        $end_date = date('Y-m-d', strtotime($request->end));

        $report = Order::with('user.sponsor')
            ->whereIn('order_status_id', [1, 4, 5])
            ->where('created_at', '>=', date('Y-m-d 00:00:00', strtotime($request->start)))
            ->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($request->end)))
            ->whereIn('user_id', $user_list)
            // ->where('id', 36151)
            ->withTrashed()
            ->paginate(500);

        $reportdata = [];
        foreach ($report as $key => $value)
        {
            $reportdata[$key]['id'] = $value->id;
            $reportdata[$key]['username'] = $value->user->username;
            $reportdata[$key]['name'] = $value->user->name;
            $reportdata[$key]['email'] = $value->user->email;
            $reportdata[$key]['sponsor_username'] = $value->user->sponsor ? $value->user->sponsor->username : '';
            $reportdata[$key]['sponsor_name'] = $value->user->sponsor ? $value->user->sponsor->name : '';
            $reportdata[$key]['order_date'] = $value->created_at;
            $reportdata[$key]['order_status_id'] = $value->order_status_id;
            $reportdata[$key]['order_status'] = $value->orderStatus->name;
            $reportdata[$key]['qv'] = OrderProduct::where('order_id', $value->id)->sum('qv');
            $reportdata[$key]['bv'] = OrderProduct::where('order_id', $value->id)->sum('bv');
            $reportdata[$key]['total'] = $value->sub_total;
            $reportdata[$key]['order_info'] = 'https://office.ultrra.com/invoice/'.$value->order_id;

            if ($value->order_status_id == 5)
            {
                $reportdata[$key]['status'] = "cancelled";
            }
            elseif ($value->order_status_id == 1)
            {
                $reportdata[$key]['status'] = "pending";
            }

            $sponsor_upline_ids = [];
            if(auth()->user()->id != $value->user_id)
            {
                // return $value->user_id;
                SponsorTree::$upline_users = [];
                $upline = SponsorTree::getAllUplineForDownlineOrderReport($value->user_id);
                $variable = SponsorTree::$upline_users;
                // return auth()->user()->id;
                foreach($variable as $var)
                {
                    $sponsor_upline_ids[] = $var['user_id'];
                }
                if(!in_array(auth()->user()->id, $sponsor_upline_ids))
                {
                    $reportdata[$key]['order_info'] = "#";
                    $reportdata[$key]['id'] = "Private";
                    $reportdata[$key]['username'] = "Private";
                    $reportdata[$key]['name'] = "Private";
                    $reportdata[$key]['email'] = "Private";
                    $reportdata[$key]['sponsor_name'] = "Private";
                    $reportdata[$key]['sponsor_username'] = "Private";
                    // $reportdata[$key]['order_date'] = "Private";
                }
            }
        }
        
        $total_bv = OrderProduct::whereHas('order', function ($q) use ($user_list, $request_start, $request_end) {
            $q->whereIn('user_id', $user_list)->where('order_status_id', 4)
            ->where('created_at', '>=', date('Y-m-d 00:00:00', strtotime($request_start)))
            ->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($request_end)));
        })
            ->sum('bv');

        $total_qv = OrderProduct::whereHas('order', function ($q) use ($user_list, $request_start, $request_end) {
            $q->whereIn('user_id', $user_list)->where('order_status_id', 4)
            ->where('created_at', '>=', date('Y-m-d 00:00:00', strtotime($request_start)))
            ->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($request_end)));
        })
            ->sum('qv');

//        // carry over data

        // for bv
        $last_entry = CarryForwardHistory::where('business_center_id', $business_center_id)
            ->where('created_at', '<', date('Y-m-d H:i:s'))
            ->where('action', '<>', 'added')
            ->max('created_at');


        if($last_entry == null)
        {
            $last_entry = CarryForwardHistory::where('business_center_id', $business_center_id)->where('created_at', '<', date('Y-m-d H:i:s'))->max('created_at');

            if($last_entry != null)
            {
                $carryover_bv = CarryForwardHistory::where('business_center_id', $business_center_id)->where('created_at', $last_entry)->value('left');

                $upto_thisweek = PointHistory::where('business_center_id', $business_center_id)
                    ->where('created_at', '>', $last_entry)
                    ->where('created_at', '<', Carbon::now()->startOfWeek())
                    ->where('leg', 'L')->sum('bv');

                $carryover_bv = $carryover_bv + $upto_thisweek;

                $carryover_bvright = CarryForwardHistory::where('business_center_id', $business_center_id)->where('created_at',$last_entry)->value('right');

                $upto_thisweek = PointHistory::where('business_center_id', $business_center_id)
                    ->where('created_at', '>', $last_entry)
                    ->where('created_at', '<', Carbon::now()->startOfWeek())
                    ->where('leg', 'R')->sum('bv');

                $carryover_bvright = $carryover_bvright + $upto_thisweek;
            }
            else
            {
                $carryover_bv = PointHistory::where('business_center_id', $business_center_id)
                    ->where('created_at', '<', Carbon::now()->startOfWeek())
                    ->where('leg', 'L')->sum('bv');

                $carryover_bvright = PointHistory::where('business_center_id', $business_center_id)
                    ->where('created_at', '<', Carbon::now()->startOfWeek())
                    ->where('leg', 'R')->sum('bv');
            }
        }
        else
        {
            $carryover_bv = CarryForwardHistory::where('business_center_id', $business_center_id)
                ->where('created_at', $last_entry)
                ->value('left');

            $upto_thisweek = PointHistory::where('business_center_id', $business_center_id)
                ->where('created_at', '>', $last_entry)
                ->where('created_at', '<', Carbon::now()->startOfWeek())
                ->where('leg', 'L')->sum('bv');

            $carryover_bv = $carryover_bv + $upto_thisweek;


            $carryover_bvright = CarryForwardHistory::where('business_center_id', $business_center_id)
                ->where('created_at', $last_entry)
                ->value('right');


            $upto_thisweek = PointHistory::where('business_center_id', $business_center_id)
                ->where('created_at', '>', $last_entry)
                ->where('created_at', '<', Carbon::now()->startOfWeek())
                ->where('leg', 'R')->sum('bv');

            $carryover_bvright = $carryover_bvright + $upto_thisweek;
        }


        // for qv
        $last_entry = QvCarryForwardHistory::where('business_center_id', $business_center_id)
            ->where('created_at', '<', date('Y-m-d H:i:s'))
            ->max('created_at');

        if($last_entry == null)
        {
            $carryover_qv = PointHistory::where('business_center_id', $business_center_id)
                ->where('created_at', '<', date('Y-m-d H:i:s'))->where('leg', 'L')->sum('pv');

            $carryover_qvright = PointHistory::where('business_center_id', $business_center_id)
                ->where('created_at', '<', date('Y-m-d H:i:s'))->where('leg', 'R')->sum('pv');

        }
        else
        {
            $carryover_qv = QvCarryForwardHistory::where('business_center_id', $business_center_id)
                ->where('created_at', $last_entry)->value('left');

            $upto_thisweek = PointHistory::where('business_center_id', $business_center_id)
                ->where('created_at', '>', $last_entry)
                ->where('created_at', '<', Carbon::now()->startOfWeek())
                ->where('leg', 'L')->sum('pv');

            $carryover_qv = $carryover_qv + $upto_thisweek;

            $carryover_qvright = QvCarryForwardHistory::where('business_center_id', $business_center_id)
                ->where('created_at', $last_entry)->value('right');

            $upto_thisweek = PointHistory::where('business_center_id', $business_center_id)
                ->where('created_at', '>', $last_entry)
                ->where('created_at', '<', Carbon::now()->startOfWeek())
                ->where('leg', 'R')->sum('pv');

            $carryover_qvright = $carryover_qvright + $upto_thisweek;
        }
//        end for carry over data

        $total_orders = count($report);

        $sponsor_id = User::where('username', $request->username)->value('id');

        $total_enroll = OrderProduct::whereHas('order', function ($q) use ($user_list) {
            $q->whereIn('user_id', $user_list)->where('order_status_id', 4)->where('is_first_order', 1);
        })->where('created_at', '>', date('Y-m-d 00:00:00', strtotime($request->start)))
            ->where('created_at', '<', date('Y-m-d 23:59:59', strtotime($request->end)))
            ->groupBy('order_id')->count();

//        $total_enroll = count($total_enroll);
        $total_bv_after_carryover_left = $carryover_bv + $total_bv;
        $total_bv_after_carryover_right = $carryover_bvright + $total_bv;

        $total_qv_after_carryover_left = $carryover_qv + $total_qv;
        $total_qv_after_carryover_right = $carryover_qvright + $total_qv;
        $data = [
            'report' => $reportdata,
            'total_enroll' => $total_enroll,
            'total_orders' => $report->total(),
//            'total_bv' => $total_bv." | Total BV After Carryover Left: ".$total_bv_after_carryover_left." | Total BV After Carryover Right: ".$total_bv_after_carryover_right,
            'total_bv' => $total_bv,
//            'total_qv' => $total_qv." | Total QV After Carryover Left: ".$total_qv_after_carryover_left." | Total QV After Carryover Right: ".$total_qv_after_carryover_right,
            'total_qv' => $total_qv,
            'banked_qv' => $banked_qv,
            'banked_bv' => $banked_bv,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'total' => count($reportdata)
        ];

        return response()->json(['data' => $data, 'status' => 200]);
    }

    public function downlineUsersReport(Request $request)
    {
        $auth_user = BusinessCenter::where('user_id', auth()->user()->id)->value('id');
        $downline_id_list = [];
        TreeTable::getAllDownlinesAutocomplete([$auth_user]);
        $downline_id_list = TreeTable::$downline_id_list;
        $user_list = $downline_id_list;

        $users = User::with('sponsor', 'businessCenters')->whereHas('businessCenters', function ($q) use ($user_list) {
            $q->whereIn('id', $user_list);
        })->whereHas('orders', function ($q) {
            $q->where('order_status_id', 4);
        })->paginate();

        $reportdata = [];

        foreach ($users as $key => $value)
        {
            $user_account = BusinessCenter::where('user_id', $value->id)->value('id');
            $placement_user = TreeTable::where('business_center_id', $user_account)->value('placement_id');
            $user_leg = TreeTable::where('business_center_id', $user_account)->value('leg');
            $placement_name = BusinessCenter::where('id', $placement_user)->value('business_center');
            $last_4_week = date('Y-m-d H:i:s', strtotime('-28 Day', strtotime(date('Y-m-d H:i:s'))));

            $personal_qv = OrderProduct::whereHas('order', function ($q) use ($value) {
                $q->where('user_id', $value->id)->where('order_status_id', 4);
            })->where('created_at', '>=', date('Y-m-d 23:59:59', strtotime($last_4_week)))->sum('qv');

            $customer_qv = OrderProduct::whereHas('order', function ($q) use ($value) {
                $q->where('order_status_id', 4)->whereHas('user', function ($q) use ($value) {
                    $q->where('usertype', '!=', 'dc')->whereHas('sponsorTrees', function ($q) use ($value) {
                        $q->where('sponsor_id', $value->id)->where('type', '!=', 'vaccant');
                    });
                });
            })->where('created_at', '>=', date('Y-m-d 23:59:59', strtotime($last_4_week)))
                ->sum('qv');

            $total_qv = $personal_qv + $customer_qv;

            $qualified_status = RankStatusSetting::where('id', $value->qualified_status)->value('rank_status');

            $reportdata[$value->id]['user_id'] = $value->id;
            $reportdata[$value->id]['username'] = $value->username;
            $reportdata[$value->id]['name'] = $value->name;
            $reportdata[$value->id]['created'] = $value->created_at;
            $reportdata[$value->id]['rank'] = $value->rankSetting !== null ? $value->rankSetting->rank : 'Member';
            $reportdata[$value->id]['left_carry'] = BusinessCenter::find($user_account)->left_carry;
            $reportdata[$value->id]['right_carry'] = BusinessCenter::find($user_account)->right_carry;
            $reportdata[$value->id]['leg'] = $user_leg;
            $reportdata[$value->id]['matching'] = ($value->matchingbonus_percentage == 50) ? 'YES' : 'NO';
            $reportdata[$value->id]['status'] = $qualified_status;
            $reportdata[$value->id]['sponsor'] = $value->sponsor ? $value->sponsor->username : '';
            $reportdata[$value->id]['placement'] = $placement_name;
            $reportdata[$value->id]['total_pqv'] = $total_qv;
            $reportdata[$value->id]['par'] = $value->rankSetting !== null ? $value->rankSetting->rank : 'Member';
            $sponsor_upline_ids = [];
            if(auth()->user()->id != $value->id)
            {
                SponsorTree::$upline_users = [];
                $upline = SponsorTree::getAllUplineForDownlineOrderReport($value->id);
                $variable = SponsorTree::$upline_users;

                foreach ($variable as $var)
                {
                    $sponsor_upline_ids[] = $var['user_id'];
                }
//                if(!in_array(auth()->user()->id, $sponsor_upline_ids))
//                {
//                    $reportdata[$value->id]['username'] = "Private";
//                    $reportdata[$value->id]['name'] = "Private";
//                    $reportdata[$value->id]['rank'] = "Private";
//                    $reportdata[$value->id]['matching'] = "Private";
//                    $reportdata[$value->id]['status'] = "Private";
//                    $reportdata[$value->id]['sponsor'] = "Private";
//                    $reportdata[$value->id]['placement'] = "Private";
//                    $reportdata[$value->id]['par'] = "Private";
//                }
            }
        }

        return response()->json(['data' => array_values($reportdata), 'status' => 200, 'total' => $users->total()]);
    }

    public function globalShareReport(Request $request)
    {
        $auth_user = BusinessCenter::where('user_id', auth()->user()->id)->value('id');
        TreeTable::$downline_id_list = [];
        TreeTable::getAllDownlinesAutocomplete([$auth_user]);
        $downline_id_list = TreeTable::$downline_id_list;
        $user_list = $downline_id_list;

        $users = User::with('sponsor')->whereHas('businessCenters', function ($q) use ($user_list) {
            $q->whereIn('id', $user_list);
        })->where('usertype', 'dc')->get();

        $reportdata = [];
        $total_left = $total_right = $total_dc = $total_pc = $count_rows = 0;
        $counts = "";
        foreach ($users as $key => $value)
        {
            $personal_qv = OrderProduct::whereHas('order', function ($q) use ($value) {
                $q->where('user_id', $value->id)->where('order_status_id', 4);
            })->whereMonth('created_at', '=', $request->month)->whereYear('created_at', '=', $request->year)->sum('qv');

            $customer_qv = OrderProduct::whereHas('order', function ($q) use ($value) {
                $q->where('sponsor_id', $value->id)->where('order_status_id', 4)->whereHas('user', function ($q) {
                    $q->where('usertype', '!=', 'dc');
                });
            })->whereMonth('created_at', '=', $request->month)->whereYear('created_at', '=', $request->year)->sum('qv');

            $total_qv = $personal_qv + $customer_qv;
            if($total_qv >= 50)
            {
                $user_account = BusinessCenter::where('user_id', $value->id)->value('id');
                $check_sponsortree = self::checkInSponsorTree($value->id, auth()->user()->id);
                if($check_sponsortree == 1)
                {
                    $user_leg = TreeTable::where('business_center_id', $user_account)->value('leg');
                    $reportdata[$key]['id'] = $value->id;
                    $reportdata[$key]['username'] = $value->username;
                    $reportdata[$key]['name'] = $value->name;
                    $reportdata[$key]['usertype'] = $value->usertype;
                    $reportdata[$key]['leg'] = $user_leg == 'L' ? 'Left' : 'Right';
                    $reportdata[$key]['sponsor'] = $value->sponsor->username;
                    $reportdata[$key]['total_pqv'] = $total_qv;
                    $reportdata[$key]['CountData'] = $count_rows + 1;
                    if($user_leg == 'L')
                    {
                        $total_left++;
                    }
                    else
                    {
                        $total_right++;
                    }

                    if($value->usertype == "dc")
                    {
                        $total_dc++;
                    }
                    else
                    {
                        $total_pc++;
                    }
                    // $count_rows++;
                }
            }
        }

        return response()->json(['data' => array_values($reportdata), 'status' => 200]);
    }

    public function newRankReport(Request $request)
    {
        $auth_user = BusinessCenter::where('user_id', auth()->user()->id)->value('id');
        TreeTable::$downline_id_list = [];
        TreeTable::getAllDownlinesAutocomplete([$auth_user]);
        $downline_id_list = TreeTable::$downline_id_list;
        $user_list = $downline_id_list;

        $new_rank = RankHistory::with('oldRank', 'newRank', 'user.sponsor', 'user.businessCenters')
            ->whereHas('user.businessCenters', function ($q) use ($user_list) {
                $q->whereIn('id', $user_list);
            })
            ->where('created_at', '>', date('Y-m-d 00:00:00', strtotime($request->start)))
            ->where('created_at', '<', date('Y-m-d 23:59:59', strtotime($request->end)))
            ->get();

        $reportdata = [];
        foreach ($new_rank as $key => $value)
        {
            $date = date('Y-m-d', strtotime($value->created_at));
            $lastmonday = date('Y-m-d',strtotime('last Monday', strtotime($date)));

            $advance_rankid = RankHistory::where('user_id', '=', $value->user_id)
                ->where('created_at', '<=', $lastmonday)
                ->max('rank_updated');

            if($value->rank_updated > $advance_rankid)
            {
                $personal_qv = OrderProduct::whereHas('order', function ($q) use ($value) {
                    $q->where('user_id', '=', $value->user_id)->where('order_status_id', 4);
                })->sum('qv');

                $customer_qv = OrderProduct::whereHas('order', function ($q) use ($value) {
                    $q->where('sponsor_id', '=', $value->user_id)->where('order_status_id', 4)->whereHas('user', function ($q) {
                        $q->where('usertype', '!=', 'dc');
                    });
                })->sum('qv');

                $reportdata[$value->user_id]['id'] = $value->id;
                $reportdata[$value->user_id]['name'] = $value->user->name;
                $reportdata[$value->user_id]['username'] = $value->user->username;
                $reportdata[$value->user_id]['created_at'] = $value->user->created_at;
                $reportdata[$value->user_id]['old_rank'] = $value->oldRank ? $value->oldRank->rank : '';
                $reportdata[$value->user_id]['new_rank'] = $value->newRank ? $value->newRank->rank : '';
                $reportdata[$value->user_id]['sponsor_username'] = $value->user->sponsor->username;
                $reportdata[$value->user_id]['pqv'] = $personal_qv + $customer_qv;
                $reportdata[$value->user_id]['enroldate'] = $date;
            }
        }

        return response()->json(['data' => array_values($reportdata), 'status' => 200]);

    }

    public function topEnrollersReport(Request $request)
    {
        $user_id = auth()->user()->id;
        $username = auth()->user()->username;

        if ($request->username)
        {
            $user_id = User::where('username', $request->username)->value('id');
            $username = $request->username;
        }

        $auth_user = BusinessCenter::where('user_id', $user_id)->value('id');

        TreeTable::$downline_id_list = [];
        TreeTable::topEnrollersGetDownlines([$auth_user], $request->start, $request->end);
        $downline_id_list = TreeTable::$downline_id_list;
        $user_list = $downline_id_list;

        $users = User::with('rankSetting', 'rankStatusSetting')->whereHas('businessCenters', function ($q) use ($user_list) {
            $q->whereIn('id', $user_list);
        })->orderBy('username')->get();

        $report_data = [];

        foreach ($users as $key => $user)
        {

            $sponsored = User::where('sponsor_id', $user->id)
                ->where('created_at', '>=', date('Y-m-d 00:00:00', strtotime($request->start)))
                ->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($request->end)))
                ->get();

            $dc_qv = 0; $pc_qv = 0; $rc_qv = 0; $total_qv = 0;
            $dc_count = 0; $pc_count = 0; $rc_count = 0;

            foreach ($sponsored as $key => $value)
            {
                $first_28 = date('Y-m-d H:i:s', strtotime('+28 Day', strtotime($value->created_at)));

                $personal_qv = OrderProduct::whereHas('order', function ($q) use ($value) {
                    $q->where('user_id', $value->id)->where('order_status_id', 4);
                })->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($first_28)))->sum('qv');

                $customers = User::where('sponsor_id', $value->id)->get();

                $team_purchase = 0;
                foreach ($customers as $customer)
                {
                    $customerfirst_28 = date('Y-m-d H:i:s', strtotime('+28 Day', strtotime($customer->created_at)));

                    $customer_qv = OrderProduct::whereHas('order', function ($q) use ($customer) {
                        $q->where('user_id', $customer->id)->where('order_status_id', 4);
                    })->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($customerfirst_28)))->sum('qv');

                    $team_purchase += $customer_qv;
                }

                $total_qv = $personal_qv + $team_purchase;
                if ($value->usertype == "dc")
                {
                    $dc_count += 1;
                    $dc_qv += $total_qv;
                }
                elseif ($value->usertype == "pc")
                {
                    $pc_count += 1;
                    $pc_qv += $total_qv;
                }
                elseif ($value->usertype == "rc")
                {
                    $rc_count += 1;
                    $rc_qv += $total_qv;
                }
            }

            $max_rank = RankHistory::where('user_id', $user->id)->max('rank_updated');
            $rank = $max_rank > 0 ? RankSetting::find($max_rank) : RankSetting::find(1);

            $report_item['id'] = $user->id;
            $report_item['username'] = $user->username;
            $report_item['name'] = $user->name;
            $report_item['rank'] = $rank->rank;
            $report_item['qualified_status'] = !is_null($user->rankStatusSetting) ? $user->rankStatusSetting->rank_status : '';
            $report_item['no_dc'] = $dc_count;
            $report_item['no_rc'] = $rc_count;
            $report_item['no_pc'] = $pc_count;
            $report_item['qv_dc'] = $dc_qv;
            $report_item['qv_pc'] = $pc_qv;
            $report_item['qv_rc'] = $rc_qv;
            $report_item['total_qv'] = $dc_qv + $pc_qv + $rc_qv;
            $report_data[] = $report_item;
        }

        return response()->json(['data' => ($report_data), 'total' => 0, 'status' => 200]);
    }

    public function rankHistoryReport(Request $request)
    {
        $weekend = date("W",time());

        $start = date("Y-m-d", strtotime('next monday', strtotime($request->start)));
        if (date("w") == 0)
        {
            $end_date = date("Y-m-d");
        }
        else
        {
            $end_date = date("Y-m-d", strtotime('next sunday'));
        }

        if (date("Y-m-d", strtotime($request->end)) <= $end_date)
        {
            $end = $request->end;
        }
        else
        {
            $end = $end_date;
        }
        $period = array();

        while (strtotime($start) <  strtotime($end) )
        {
            $sunday = date("Y-m-d", strtotime('next sunday', strtotime($start)));

            $period[$start]['end'] = $sunday;
            $period[$start]['start'] = $start;

            $start = date("Y-m-d", strtotime('next monday', strtotime($start)));
        }

        $user_id = auth()->user()->id;
        $username = auth()->user()->username;

        if ($request->username)
        {
            $user_id = User::where('username', $request->username)->value('id');
            $username = $request->username;
        }

        $auth_user = BusinessCenter::where('user_id', $user_id)->value('id');

        $report_data = [];

        foreach ($period as $key => $value)
        {
            $active_status_weekend = ActiveStatusHistory::where('created_at', '<=', $value['end'])->where('user_id', auth()->user()->id)->orderBy('id', 'desc')->value('active_status');
            $last_4_week = date('Y-m-d H:i:s', strtotime('-28 Day', strtotime($value['end'])));
            $report_data[$key]['periodend'] = $value['end'];
            $report_data[$key]['new_qv_left'] = PointHistory::where('business_center_id', $auth_user)->where('created_at', '>=', $value['start'])->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($value['end'])))->where('leg', 'L')->sum('pv');
            $report_data[$key]['new_qv_right'] = PointHistory::where('business_center_id', $auth_user)->where('created_at', '>=', $value['start'])->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($value['end'])))->where('leg', 'R')->sum('pv');
            $report_data[$key]['new_bv_left'] = PointHistory::where('business_center_id', $auth_user)->where('created_at', '>=', $value['start'])->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($value['end'])))->where('leg', 'L')->sum('bv');
            $report_data[$key]['new_bv_right'] = PointHistory::where('business_center_id', $auth_user)->where('created_at', '>=', $value['start'])->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($value['end'])))->where('leg', 'R')->sum('bv');

            $personal_qv = OrderProduct::whereHas('order', function ($q) use ($user_id) {
                $q->where('user_id', $user_id)->where('order_status_id', 4);
            })->where('created_at', '>=', $value['start'])
                ->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($value['end'])))
                ->sum('qv');

            $customer_qv = OrderProduct::whereHas('order', function ($q) use ($user_id) {
                $q->where('order_status_id', 4)->whereHas('user', function ($q) use ($user_id) {
                    $q->where('usertype', '!=', 'dc')->where('sponsor_id', $user_id);
                });
            })->where('created_at', '>=', $value['start'])
                ->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($value['end'])))
                ->sum('qv');

            $report_data[$key]['newpqv'] = $personal_qv + $customer_qv;

            $personal_qv_28 = OrderProduct::whereHas('order', function ($q) use ($user_id) {
                $q->where('user_id', $user_id)->where('order_status_id', 4);
            })->where('created_at', '>=', $last_4_week)
                ->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($value['end'])))
                ->sum('qv');

            $customer_qv_28 = OrderProduct::whereHas('order', function ($q) use ($user_id) {
                $q->where('order_status_id', 4)->whereHas('user', function ($q) use ($user_id) {
                    $q->where('usertype', '!=', 'dc')->where('sponsor_id', $user_id);
                });
            })->where('created_at', '>=', $last_4_week)
                ->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($value['end'])))
                ->sum('qv');

            $report_data[$key]['pqv'] = $personal_qv_28 + $customer_qv_28;

            $business_centers = BusinessCenter::where('user_id', auth()->id())->get();
            $rank_history_ids = [];
            foreach($business_centers as $business_center){
                $rank_history_ids[] = RankHistory::where('business_center_id', $business_center->id)
                ->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($value['end'])))
                ->max('id');
            }
            
            $rank_id = ($rank_history_ids == []) ? 1 : RankHistory::whereIn('id', $rank_history_ids)->max('rank_updated');

            $report_data[$key]['par'] = RankSetting::where('id', $rank_id)->value('rank');

            $report_data[$key]['total_bonus'] = Commission::where('business_center_id', $auth_user)
                ->where('created_at', '>=', $value['start'])
                ->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($value['end'])))
                ->sum('amount');
            //new

            $last_start = date('Y-m-d H:i:s', strtotime('-6 Day', strtotime($value['end'])));
            $last_end = $value['end'];

            #QV
            if ($active_status_weekend != 0)
            {
                $last_entry = QvCarryForwardHistory::where('business_center_id', $auth_user)
                    ->where('created_at', '<', $last_start)
                    ->max('created_at');
                if ($last_entry != null)
                {
                    $active_status_after_last_entry = self::active_status_after_last_entry($last_entry);
                }
                if ($last_entry == null)
                {
                    $carry_qv_left = PointHistory::where('id', $auth_user)
                        ->where('created_at', '<', $last_start)
                        ->where('leg', 'L')->sum('pv');
                }
                else
                {
                    if ($active_status_after_last_entry != 0)
                    {
                        $carry_qv_left = QvCarryForwardHistory::where('business_center_id', $auth_user)
                            ->where('created_at', $last_entry)
                            ->value('left');
                    }
                    else
                    {
                        $carry_qv_left = 0;
                    }
                }

                $report_data[$key]['total_qv_left'] = $carry_qv_left + $report_data[$key]['new_qv_left'];

                if ($last_entry == null)
                {
                    $carry_qv_right = PointHistory::where('business_center_id', $auth_user)
                        ->where('created_at', '<', $last_start)
                        ->where('leg', 'R')->sum('pv');
                }
                else
                {
                    if ($active_status_after_last_entry != 0)
                    {
                        $carry_qv_right = QvCarryForwardHistory::where('business_center_id', $auth_user)
                            ->where('created_at', $last_entry)
                            ->value('right');
                    }
                    else
                    {
                        $carry_qv_right = 0;
                    }
                }

                $report_data[$key]['total_qv_right'] = $carry_qv_right+ $report_data[$key]['new_qv_right'];
                #QV#

                #BV
                $last_entry = CarryForwardHistory::where('business_center_id',$auth_user)
                    ->where('created_at', '<', $last_start)
                    ->where('action', '!=', 'added')
                    ->max('created_at');

                if ($last_entry != null)
                {
                    $active_status_after_last_entry = self::active_status_after_last_entry($last_entry);
                }

                if ($last_entry == null)
                {
                    $last_entry = CarryForwardHistory::where('business_center_id', $auth_user)
                        ->where('created_at', '<', $last_start)
                        ->max('created_at');

                    $carry_bv_left = CarryForwardHistory::where('business_center_id', $auth_user)
                        ->where('created_at', $last_entry)
                        ->value('left');

                }
                else
                {
                    if ($active_status_after_last_entry != 0)
                    {
                        $carry_bv_left = CarryForwardHistory::where('business_center_id', $auth_user)
                            ->where('created_at', $last_entry)
                            ->value('left');

                        $upto_thisweek = PointHistory::where('id', $auth_user)
                            ->where('created_at', '>', $last_entry)
                            ->where('created_at', '<', $value['start'])
                            ->where('leg', 'L')->sum('bv');

                        $carry_bv_left = $carry_bv_left + $upto_thisweek;
                    }
                    else
                    {
                        $carry_bv_left = 0;
                    }
                }

                $report_data[$key]['total_bv_left'] = $carry_bv_left + $report_data[$key]['new_bv_left'];

                if ($last_entry == null)
                {
                    $last_entry = CarryForwardHistory::where('business_center_id', $auth_user)
                        ->where('created_at', '<', $last_start)
                        ->max('created_at');

                    $carry_bv_right = CarryForwardHistory::where('business_center_id', $auth_user)
                        ->where('created_at', $last_entry)
                        ->value('right');
                }
                else
                {
                    if ($active_status_after_last_entry != 0)
                    {
                        $carry_bv_right = CarryForwardHistory::where('business_center_id', $auth_user)
                            ->where('created_at', $last_entry)
                            ->value('right');

                        $upto_thisweek = PointHistory::where('id', $auth_user)
                            ->where('created_at', '>', $last_entry)
                            ->where('created_at', '<', $value['start'])
                            ->where('leg', 'R')->sum('bv');

                        $carry_bv_right = $carry_bv_right + $upto_thisweek;
                    }
                    else
                    {
                        $carry_bv_right = 0;
                    }
                }

                $report_data[$key]['total_bv_right'] = $carry_bv_right + $report_data[$key]['new_bv_right'];
                $report_data[$key]['qv_pairs'] = floor(min($report_data[$key]['total_qv_left']/200,$report_data[$key]['total_qv_right']/200)) * 200;
                $report_data[$key]['bv_pairs'] = floor(min($report_data[$key]['total_qv_left']/200,$report_data[$key]['total_qv_right']/200)) * 200;
            }
            else
            {
                $report_data[$key]['new_qv_left'] = 0;
                $report_data[$key]['new_qv_right'] = 0;
                $report_data[$key]['total_qv_left'] = 0;
                $report_data[$key]['total_qv_right'] = 0;

                $report_data[$key]['new_bv_left'] = 0;
                $report_data[$key]['new_bv_right'] = 0;
                $report_data[$key]['total_bv_left'] = 0;
                $report_data[$key]['total_bv_right'] = 0;

                $report_data[$key]['qv_pairs'] = 0;
                $report_data[$key]['bv_pairs'] = 0;
            }
        }

        return response()->json(['data' => array_values($report_data), 'status' => 200]);
    }

    public function sponsorReport(Request $request)
    {
        if ($request->username !== null)
        {
            $sponsor_id = User::where('username',$request->username)->value('id') ;
        }
        else
        {
            $sponsor_id = auth()->user()->id;
        }

        $sponsorname = $request->username;

        $sponsor_users = SponsorTree::with('user')
            ->whereHas('user')
            ->where('sponsor_id', $sponsor_id)
            ->where('type', '!=', 'vaccant')
            ->paginate();

        $reportdata = [];

        foreach ($sponsor_users as $key => $value)
        {
            $user_list = $value->user_id;
            $checkuser = User::where('id', '=', $user_list)->value('id');

            if ($checkuser != null)
            {
                $value = User::find($user_list);
                $reportdata[$user_list]['id'] = $user_list;
                $reportdata[$user_list]['username'] = $value->username;
                $reportdata[$user_list]['email'] = $value->email;
                $reportdata[$user_list]['name'] = $value->name;
                $reportdata[$user_list]['lastname'] = $value->lastname;
                $reportdata[$user_list]['created_at'] = $value->created_at;
                if ($value->country_id && !is_null($value->country))
                {
                    $country = $value->country->name;
                }
                else
                {
                    $country = "Unknown";
                }

                $reportdata[$user_list]['country'] = $country;

                #1st oder pqv
                $firststOderQV = OrderProduct::whereHas('order', function ($q) use ($user_list) {
                    $q->where('user_id', $user_list)->where('is_first_order', '=', '1');
                })->sum('qv');
                $reportdata[$user_list]['firststOderQV'] = $firststOderQV;

                #1st 28 day
                $first_28 = date('Y-m-d H:i:s', strtotime('+27 Day', strtotime($value->created_at)));

                $personal_qv = OrderProduct::whereHas('order', function ($q) use ($value) {
                    $q->where('user_id', $value->id)->where('is_first_order', '=', '1');
                })->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($first_28)))->sum('qv');

                $customers = User::where('sponsor_id', $value->id)->get();

                $team_purchase = 0;

                foreach ($customers as $key => $customer)
                {
                    $customerfirst_28 = date('Y-m-d H:i:s', strtotime('+27 Day', strtotime($customer->created_at)));

                    $customer_qv = OrderProduct::whereHas('order', function ($q) use ($customer) {
                        $q->where('order_status_id', 4)->whereHas('user', function ($q) use ($customer) {
                            $q->where('id', $customer->id)->where('usertype', '!=', 'dc');
                        });
                    })->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($customerfirst_28)))
                        ->sum('qv');

                    $team_purchase += $customer_qv;
                }

                $total_qv = $personal_qv + $team_purchase;
                $reportdata[$user_list]['total_28'] = $total_qv;

                #1st 28 day pqv end

                # current 28 pqv
                $today = date('Y-m-d H:i:s');
                $last_28 = date('Y-m-d H:i:s', strtotime('-27 Day', strtotime($today)));

                $personal_current_qv = OrderProduct::whereHas('order', function ($q) use ($value) {
                    $q->where('user_id', $value->id)->where('order_status_id', 4);
                })->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($last_28)))->sum('qv');

                $team_current_purchase = 0;

                foreach ($customers as $key => $customer)
                {
                    $customerlast_28 = date('Y-m-d H:i:s', strtotime('-27 Day', strtotime($today)));

                    $customer_current_qv = OrderProduct::whereHas('order', function ($q) use ($customer) {
                        $q->where('order_status_id', 4)->whereHas('user', function ($q) use ($customer) {
                            $q->where('id', $customer->id)->where('usertype', '!=', 'dc');
                        });
                    })->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($customerlast_28)))
                        ->sum('qv');

                    $team_current_purchase += $customer_current_qv;
                }

                $total_current_qv = $personal_current_qv + $team_current_purchase;
                $reportdata[$user_list]['total_current_28'] = $total_current_qv;
                #end


                $commision_date = Commission::where('from_user_id', $value->id)->where('commission_type', '=', 'first_order_bonus')->value('created_at');
                $reportdata[$user_list]['commission_date'] = $commision_date;

                $currentqs = User::with('users', 'rankStatusSetting')->where('id', $value->id)->first();


                $reportdata[$user_list]['current_QS'] = !is_null($currentqs->rankStatusSetting) ? $currentqs->rankStatusSetting->rank_status : '';
            }
        }

        return response()->json(['data' => array_values($reportdata), 'status' => 200, 'total' => $sponsor_users->total()]);
    }

    public function tcHistoryReport(Request $request)
    {
        $weekend = date("W",time());

        $start = date("Y-m-d", strtotime('next monday', strtotime($request->start)));
        if (date("w") == 0)
        {
            $end_date = date("Y-m-d");
        }
        else
        {
            $end_date = date("Y-m-d", strtotime('next sunday'));
        }

        if (date("Y-m-d", strtotime($request->end)) <= $end_date){
            $end = $request->end;
        }
        else
        {
            $end = $end_date;
        }
        $period = array();

        while (strtotime($start) <  strtotime($end))
        {
            $sunday = date("Y-m-d", strtotime('next sunday', strtotime($start)));
            $period[$start]['end'] = $sunday;
            $period[$start]['start'] = $start;
            $start = date("Y-m-d", strtotime('next monday', strtotime($start)));
        }


        $user_id = auth()->user()->id;
        $username = auth()->user()->username;

        if ($request->username)
        {
            $user_id = User::where('username', $request->username)->value('id');
            $username = $request->username;
        }

        $bcs = BusinessCenter::where('user_id', $user_id)->get();

        $report_data = [];
        foreach ($period as $key => $value)
        {
            $active_status_weekend = ActiveStatusHistory::where('created_at', '<=', $value['end'])->where('user_id', auth()->user()->id)->orderBy('id', 'desc')->value('active_status');
            $last_4_week = date('Y-m-d H:i:s', strtotime('-28 Day', strtotime($value['end'])));
            $report_data[$key]['periodend'] = $value['end'];

            foreach($bcs as $bc_key => $bc)
            {
                $report_data[$key]['travel_credits_'.$bc_key] = BelizeTravelCreditHistory::where('business_center_id', $bc->id)->where('created_at', '>=', $value['start'])->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($value['end'])))->sum('travel_credits');
                $value['end'];
                $report_data[$key]['bc_'.$bc_key] = $bc->business_center;
                $rank_history_id = RankHistory::where('business_center_id', $bc->id)
                    ->where('created_at','<=',date('Y-m-d 23:59:59', strtotime($value['end'])))
                    ->max('id');

                $rank_id = ($rank_history_id == null) ? 1 : RankHistory::where('id', $rank_history_id)->value('rank_updated');
                $report_data[$key]['par_'.$bc_key] = RankSetting::where('id', $rank_id)->value('rank');
            }
            $last_start = date('Y-m-d H:i:s', strtotime('-6 Day', strtotime($value['end'])));
            $last_end = $value['end'];
        }

        $total_travel_credits = (int)BelizeTravelCreditHistory::where('user_id', auth()->user()->id)->orderBy('id', 'desc')->value('travel_credits_balance');
        $travel_destinations = TravelDestination::all();
        $qualified_travel_destination = (int)TravelDestination::where('travel_credits', '<=', $total_travel_credits)->max('id');
        $sponsored_qv_accumulator = (int)BelizeTravelCreditHistory::where('user_id', auth()->user()->id)->where('business_center_id', 0)->where('from_user_id', 0)->where('type', 'credit')->where('status', 1)->orderBy('id', 'desc')->value('travel_credits');
        $sponsored_qvs = (int)BelizeTravelCreditHistory::where('user_id', auth()->user()->id)->where('business_center_id', 0)->where('from_user_id', 0)->where('type', 'credit')->where('status', 1)->orderBy('id', 'desc')->value('sponsored_qvs');
        $par_accumulator = BelizeTravelCreditHistory::whereIn('business_center_id', BusinessCenter::where('user_id', auth()->user()->id)->pluck('id'))->sum('travel_credits');
        $carry_over_tc = (int)BelizeTravelCreditHistory::where('user_id', auth()->user()->id)->where('business_center_id', 0)->where('from_user_id', '!=', 0)->where('type', 'credit')->where('status', 1)->orderBy('id', 'desc')->value('travel_credits');
        $next_update_date = date('Y-m-d', strtotime('next monday'));

        $data = [
            'report' => array_values($report_data),
            'total_travel_credits' => $total_travel_credits,
            'travel_destinations' => $travel_destinations,
            'qualified_travel_destination' => $qualified_travel_destination,
            'sponsored_qv_accumulator' => $sponsored_qv_accumulator,
            'sponsored_qvs' => $sponsored_qvs,
            'par_accumulator' => $par_accumulator,
            'carry_over_tc' => $carry_over_tc,
            'next_update_date' => $next_update_date
        ];

        return response()->json(['data' => $data, 'status' => 200]);
    }

    public function checkInSponsorTree($user_id, $from_id)
    {
        SponsorTree::$upline_id_lists = [];
        $upline = SponsorTree::getAllUpline($user_id);
        $variable = SponsorTree::$upline_id_lists;

        if(in_array($from_id, $variable))
        {
            return 1;
        }
        else
        {
            return 0;
        }

    }

    public function travelDestinationReservationRequest(Request $request)
    {
        $user_id = auth()->user()->id;
        $travel_credits_balance = BelizeTravelCreditHistory::where('user_id', $user_id)->orderBy('id', 'desc')->value('travel_credits_balance');
        $qualified_travel_destination = TravelDestination::where('travel_credits', '<=', $travel_credits_balance)->max('id');
        if($request->travel_destination_id <= $qualified_travel_destination && TravelCreditHistory::where('user_id', $user_id)->where('status', '!=', 2)->where('type', 'debit')->count() == 0)
        {
            $travel_destination = TravelDestination::find($request->travel_destination_id);
            $new_travel_credits_balance = $travel_credits_balance - $travel_destination->travel_credits;
            $travel_credit_history_created = BelizeTravelCreditHistory::create([
                'user_id' => $user_id,
                'business_center_id' => 0,
                'travel_destination_id' => $travel_destination->id,
                'travel_credits' => $travel_destination->travel_credits,
                'type' => 'debit',
                'status' => 0,
                'travel_credits_balance' => $new_travel_credits_balance
            ]);
            return response()->json(['status' => 200, 'message' => 'Travel Destination Reservation submitted successfully']);
        }
        else
        {
            return response()->json(['status' => 300, 'message' => 'Something Went wrong']);
        }
    }

    public function active_status_after_last_entry($last_entry)
    {
        $active_status_after_last_entry = ActiveStatusHistory::where('created_at', '>=', $last_entry)
            ->where('user_id', auth()->user()->id)
            ->where('active_status', 0)
            ->value('active_status');

        if (!is_numeric($active_status_after_last_entry))
        {
            $active_status_after_last_entry = 1;
        }
        return $active_status_after_last_entry;
    }
}
