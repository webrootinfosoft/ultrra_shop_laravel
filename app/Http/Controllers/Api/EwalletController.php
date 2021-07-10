<?php

namespace App\Http\Controllers\Api;

use App\Country;
use App\HyperwalletHistories;
use App\ActivityLog;
use App\RankStatusSetting;
use App\SponsorTree;
use App\BusinessCenter;
use App\Commission;
use App\FirstOrderBonusSetting;
use App\Http\Controllers\Controller;
use App\OrderProduct;
use App\RankHistory;
use App\RankSetting;
use App\GlobalBonusHistory;
use App\QvCarryForwardHistory;
use App\ActiveStatusHistory;
use App\QualifiedStatusHistory;
use App\CarryForwardHistory;
use App\State;
use App\User;
use App\PointHistory;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use App\EwalletTransaction;
use App\Order;
use Mockery\Exception;

class EwalletController extends Controller
{
    public function ewalletBalance()
    {
        $total_balance = EwalletTransaction::where('user_id', auth()->user()->id)->orderBy('id', 'desc')->value('current_balance');

        return response()->json(['data' => $total_balance, 'status' => 200]);
    }

    public function ewalletLog(Request $request)
    {
        $ewallet_data = EwalletTransaction::with('user', 'fromUser', 'fromBusinessCenter', 'commission')
            ->where('user_id', auth()->user()->id)
            ->orderBy('id', 'desc')->paginate();

        return response()->json(['data' => $ewallet_data, 'status' => 200]);
    }

    public function fundTransfer(Request $request)
    {
        $request->validate([
            'username' => 'required|exists:users|not_in:'.auth()->user()->username,
            'amount' => 'required|numeric'
        ]);

        $user_balance = EwalletTransaction::where('user_id', auth()->user()->id)->orderBy('id', 'desc')->value('current_balance');

        if($user_balance >= $request->amount)
        {
            $user_id = User::where('username', $request->username)->value('id');
            $business_center_id = BusinessCenter::where('user_id', $user_id)->value('id');

            $from_business_center_id = BusinessCenter::where('user_id', auth()->user()->id)->value('id');

            $user_credit_commission = Commission::create([
                'user_id' => $user_id,
                'business_center_id' => $business_center_id,
                'from_business_center_id' => $from_business_center_id,
                'from_user_id' => auth()->user()->id,
                'total_amount' => $request->amount,
                'amount' => $request->amount,
                'commission_type' => 'user_credit',
                'notes' => $request->notes,
                'status' => 1,
            ]);

            $current_balance = EwalletTransaction::where('user_id', $user_id)->orderBy('id', 'desc')->value('current_balance');

            $new_balance = $current_balance + $request->amount;
            EwalletTransaction::create([
                'commission_id' => $user_credit_commission->id,
                'user_id' => $user_id,
                'from_user_id' => auth()->user()->id,
                'from_account_id' => $business_center_id,
                'amount' => $request->amount,
                'amount_type' => 'credit',
                'current_balance' => $new_balance,
                'description' => 'user_credit',
                'note' => $request->notes,
            ]);

            $new_balance = $user_balance - $request->amount;
            EwalletTransaction::create([
                'user_id' => auth()->user()->id,
                'from_user_id' => $user_id,
                'from_account_id' => $business_center_id,
                'amount' => $request->amount,
                'amount_type' => 'debit',
                'current_balance' => $new_balance,
                'description' =>  'fund_transfer',
                'note' => $request->notes,
            ]);

            $username = $request->username;
            $amount = $request->amount;

            ActivityLog::create(['user_id' => auth()->id(), 'request_ip' => $request->ip(), 'title' => "Fund Credited to $request->username", 'description' => auth()->user()->username . " credited a fund of $request->amount to $request->username"]);

            return response()->json(['data' => 'success', 'status' => 200]);

        }
    }

    public function requestPayoutHistory()
    {
        $data = EwalletTransaction::with('commission')
            ->where('user_id', auth()->user()->id)
            ->where('description', 'payout_request')
            ->latest()
            ->paginate();

        return response()->json(['data' => $data, 'status' => 200]);
    }

    public function payoutRequest(Request $request)
    {
        $total_balance = EwalletTransaction::where('user_id', auth()->id())->orderBy('id', 'desc')->value('current_balance');

        if($request->amount > 0 and $request->amount <= $total_balance)
        {
            $new_balance = $total_balance - $request->amount;
            EwalletTransaction::create([
                'user_id' => auth()->id(),
                'from_user_id' => 1,
                'from_account_id' => 1,
                'amount' => $request->amount,
                'amount_type' => 'debit',
                'current_balance' => $new_balance,
                'description' => 'payout_request',
                'note' => "",
                'status' => 0
            ]);

            ActivityLog::create(['user_id' => auth()->id(), 'request_ip' => $request->ip(), 'title' => "Payout Requested", 'description' => auth()->user()->username . " requested for a payout of $request->amount"]);
        }

        return response()->json(['status' => 1, 'message' => 'success']);
    }

    public function commissionMonthly(Request $request)
    {
        $weekend = date("W", time()) - 1;

        $duration = [];

        for ($i = 0; $i < 12; $i++)
        {
            $duration[$i]['number'] = $i + 1;
        }

        $selected_bc = BusinessCenter::where('user_id', auth()->user()->id)->value('id');

        if ($request->month == "")
        {
            $selectdate = date('m');
        }
        else
        {
            $selectdate = $request->month;
        }

        if ($request->year == "")
        {
            $year = date('Y');
        }
        else
        {
            $year = $request->year;
        }

        $total['global_bonus'] = $global_total = 0;

        $reportdata = [];

        if ($selectdate != "")
        {
            $reportdata['global_bonus'] = $total['global_bonus'] = $global_total = 0;

            $global_bonus_history = GlobalBonusHistory::where('month', $selectdate)->where('year', $year)->where('business_center_id', $selected_bc)->max('id');

            $global_bonus_history = GlobalBonusHistory::where('id', $global_bonus_history)->get();

            $share_bonus_count = count($global_bonus_history);

            $earned_status = 0;

            if ($share_bonus_count > 0)
            {

                $global_bonus_history = $global_bonus_history[0];
                $dateObj = DateTime::createFromFormat('!m',$selectdate);

                $reportdata['monthly_period'] = $dateObj->format('F');
                $reportdata['active_left'] = $global_bonus_history->active_left;
                $reportdata['active_right'] = $global_bonus_history->active_right;
                $reportdata['total_active'] = $global_bonus_history->share;

                if ($global_bonus_history->share == 0)
                {
                    $reportdata['total_active'] = $reportdata['active_left'] + $reportdata['active_right'];
                }

                $reportdata['share_value'] = $global_bonus_history->per_share;
                $reportdata['active_left_49'] = $global_bonus_history->inactive_left;
                $reportdata['active_right_49'] = $global_bonus_history->inactive_right;

                $reportdata['global_bonus'] = $total['global_bonus'] = $global_total = $reportdata['total_active'] * $reportdata['share_value'];

                if ($reportdata['share_value'] > 0)
                {
                    $earned_status = 1;
                }
            }

            $rank_bonus = Commission::where('commission_type', 'rank_advancement_bonus')->whereMonth('created_at', '=', $selectdate)->where('user_id', $selected_bc)->count();

            if($rank_bonus > 0)
            {
                $reportdata['rank_bonus'] = Commission::where('commission_type', 'rank_advancement_bonus')->whereMonth('created_at', '=', $selectdate)->where('user_id', $selected_bc)->sum('amount');

                $date_achieved = Commission::where('commission_type', 'rank_advancement_bonus')->whereMonth('created_at', '=', $selectdate)->where('user_id', $selected_bc)->value('created_at');

                $reportdata['date_achieved'] = Carbon::parse($date_achieved)->format('Y-m-d');

                $reportdata['left_total_qv'] = QvCarryForwardHistory::where('business_center_id', $selected_bc)->whereMonth('created_at', '=', $selectdate)->value('total_left');

                $reportdata['right_total_qv'] = QvCarryForwardHistory::where('business_center_id', $selected_bc)->whereMonth('created_at', '=', $selectdate)->value('total_right');

                $reportdata['prev_rank'] = RankSetting::where('id', RankHistory::where('user_id', $selected_bc)->whereDate('created_at', '=', $reportdata['date_achieved'])->value('rank_id'))->value('rank');

                $reportdata['new_rank'] = RankSetting::where('id', RankHistory::where('user_id', $selected_bc)->whereDate('created_at', '=', $reportdata['date_achieved'])->value('rank_updated'))->value('rank');
            }
        }

        $total['rank_bonus'] = $rank_total = Commission::where('commission_type', 'rank_advancement_bonus')
            ->whereMonth('created_at', '=', $selectdate)
            ->where('user_id', $selected_bc)
            ->sum('amount');

        $earnings = [];

        foreach ($duration as $key => $value)
        {
            if ($value['number'] != 12)
            {
                $calculated_month = $value['number'] + 1;
                $calculated_year = $year;
            }
            else
            {
                $calculated_month = 01;
                $calculated_year = $year + 1;
            }


            $earnings[] = $com = Commission::where('user_id', auth()->user()->id)
                ->whereMonth('created_at', $calculated_month)
                ->whereYear('created_at', $calculated_year)
                ->where(function ($q) {
                    $q->orWhere('commission_type', 'rank_advancement_bonus');
                    $q->orWhere('commission_type', 'global_bonus');
                })
                ->sum('amount');

        }

        foreach ($earnings as $key => $value)
        {
            $earnings[$key + 2] = $value;
        }

        $business_centers = BusinessCenter::where('user_id', auth()->user()->id)->get();

        $selectedyear = $year;

        $data = [
            'reportdata' => $reportdata,
            'earnings' => $earnings,
            'total' => $total,
            'business_centers' => $business_centers,
            'duration' => $duration
        ];

        return response()->json(['data' => $data, 'status' => 200]);
    }

    public function commissionWeekly(Request $request)
    {
        $year_end = date("m-d", time());

        $period = [];

        if ($request->year != "")
        {
            $selectedyear = $request->year;
            if (date('Y') == $selectedyear)
            {
                $weekend = date('W', strtotime('previous sunday'));
            }
            else
            {
                $weekend = date('W', strtotime('last sunday of december '.$selectedyear)) - 1;
            }
        }
        else
        {
            $selectedyear = date('Y');
            if (date("W", time()) == 1 && date('m') == 12)
            {
                $weekend = date('W', strtotime('last week'));
            }
            else
            {
                $weekend = date("W",time()) - 1;
            }
        }

        for ($weeknumber = $weekend; $weeknumber >= 0 ; $weeknumber--)
        {

            $time = strtotime("1 January $selectedyear", time());
            $day = date('w', $time);
            $time += ((7*$weeknumber)+1-$day)*24*3600;
            $startdate = date('Y-m-d', $time);
            $time += 6*24*3600;
            $enddate = date('Y-m-d', $time);

            $period[$weeknumber]['end'] = $enddate;
            $period[$weeknumber]['start'] = $startdate;

            $current = $enddate;
        }

        $selected_bc = BusinessCenter::where('user_id', auth()->user()->id)->value('id');

        if ($request->month != "")
        {
            $selected = date('Y-m-d 23:59:59', strtotime($request->month));
            $selected_start = date('Y-m-d', strtotime('last Monday', strtotime($request->month)));
        }
        else
        {
            $selected = date('Y-m-d 23:59:59', strtotime($period[$weekend]['end']));
            $selected_start = date('Y-m-d', strtotime('last Monday', strtotime($selected)));
        }

//        return $selected.'|'.$selected_start;
        $fob_list = Commission::where(function ($q) {
            $q->orWhere('commission_type', 'frontline_order_bonus');
            $q->orWhere('commission_type', 'first_order_bonus');
        })->where('created_at', '<=', $selected)
            ->where('created_at', '>=', $selected_start)
            ->where('business_center_id', $selected_bc)
            ->get();

        $reportdata = [];
        foreach ($fob_list as $key => $value)
        {
            $level = self::findLevel($value['user_id'], $value['from_user_id']);

            if ($level == null)
            {
                $level = 1;
            }
            $levelrate = "level_".$level;

            $bonus_rate = FirstOrderBonusSetting::where('id', 1)->value($levelrate);
            $user_info = User::find($value['from_user_id']);
            $sponsor_info = User::find($user_info->sponsor_id);
            $first_order = OrderProduct::whereHas('order', function ($q) use($value) {
                $q->where('user_id', $value['from_user_id']);
            })->where('qv', '>', 0)->get();
            if ($first_order->count() > 0)
            {
                $first_order = $first_order[0];

                $created = date('m/d/Y', strtotime($first_order->created_at));

                $row['order_id'] = $first_order->order_id;
                $row['created_at'] = $created;
                $row['member_type'] = strtoupper($user_info->usertype);
                $row['name'] = $user_info->name;
                $row['username'] = $user_info->username;
                if(isset($sponsor_info))
                {
                    $row['sposnorname'] = $sponsor_info->name;
                    $row['sposnorusername'] = $sponsor_info->username;
                }
                else
                {
                    $row['sposnorname'] = "na";
                    $row['sposnorusername'] = "na";
                }
                $row['bonus_level'] = $level;
                $row['qv'] = OrderProduct::where('order_id', $first_order->order_id)->sum('qv');
                $row['rate'] = $bonus_rate;
                $row['bonus'] = $value->amount;
                $reportdata[] = $row;
            }
        }

        $total['retail'] = $retail_total = Commission::where('business_center_id', $selected_bc)
            ->where('created_at', '<=', $selected)
            ->where('created_at', '>=', $selected_start)
            ->where(function($q) {
                $q->orWhere('commission_type', 'retail_profit_register');
                $q->orWhere('commission_type', 'retail_profit_repurchase');
                $q->orWhere('commission_type', 'retail_bonus');
            })
            ->sum('amount');

        $total['fob'] = $fob_total = Commission::where(function($q) {
            $q->orWhere('commission_type', 'frontline_order_bonus');
            $q->orWhere('commission_type', 'first_order_bonus');
        })->where('created_at', '<=', $selected)
            ->where('created_at', '>=', $selected_start)
            ->where('business_center_id', $selected_bc)
            ->sum('amount');

        // $total['fob'] = '(IN PROCESS)';

        $total['power'] = $power_total = Commission::where('commission_type', 'power_team_bonus')
            ->where('created_at', '<=', $selected)
            ->where('created_at', '>=', $selected_start)
            ->where('business_center_id', $selected_bc)
            ->sum('amount');

        $total['bingo'] = $bingo_total = Commission::where(function($q) {
            $q->orWhere('commission_type', 'leg');
            $q->orWhere('commission_type', 'bingo_bonus');
        })->where('created_at', '<=', $selected)
            ->where('created_at', '>=', $selected_start)
            ->where('user_id', auth()->user()->id)
            ->sum('amount');

//        $total['bingo'] = '(IN PROCESS)';

        $total['match'] = $match_total = Commission::where(function($q){
            $q->orWhere('commission_type','matching_bonus');
            $q->orWhere('commission_type','binary_match_bonus');
        })->where('created_at', '<=', $selected)
            ->where('created_at', '>=', $selected_start)
            ->where('business_center_id', $selected_bc)
            ->sum('amount');

        $total['adj'] = Commission::where(function($q) {
            $q->orWhere('commission_type', 'credited_by_admin');
            $q->orWhere('commission_type', 'admin_credit');
            $q->orWhere('commission_type', 'user_credit');
        })->where('debit_status', 0)
            ->where('created_at', '<=', $selected)
            ->where('created_at', '>=', $selected_start)
            ->where('business_center_id', $selected_bc)
            ->sum('amount');

        $adj_credits = Commission::where(function($q){
            $q->orWhere('commission_type','credited_by_admin');
            $q->orWhere('commission_type','admin_credit');
            $q->orWhere('commission_type','user_credit');
        })->where('debit_status', 0)
            ->where('created_at', '<=', $selected)
            ->where('created_at', '>=', $selected_start)
            ->where('business_center_id', $selected_bc)
            ->sum('amount');

        $adj_debits = Commission::where(function($q) {
            $q->orWhere('commission_type', 'debited_by_admin');
            $q->orWhere('commission_type', 'admin_debit');
            $q->orWhere('commission_type', 'user_debit');
        })->where('debit_status', 0)
            ->where('created_at', '<=', $selected)
            ->where('created_at', '>=', $selected_start)
            ->where('business_center_id', $selected_bc)
            ->sum('amount');

        $total['adj'] = $adj_total = $adj_credits - $adj_debits;

        $total['rank_bonus'] = $rank_total = Commission::where('commission_type', 'rank_advancement_bonus')
            ->where('created_at', '<=', $selected)
            ->where('created_at', '>=', $selected_start)
            ->where('business_center_id', $selected_bc)
            ->sum('amount');


        $total['pc_bonus'] = $pc_total = Commission::where(function($q){
            $q->orWhere('commission_type', 'preferred_customer_bonus');
            $q->orWhere('commission_type', 'prefered_customer_bonus');
        })->where('created_at', '<=', $selected)
            ->where('created_at', '>=', $selected_start)
            ->where('business_center_id', $selected_bc)
            ->sum('amount');

        $total['global_bonus'] = $global_total = Commission::where(function($q){
            $q->orWhere('commission_type', 'global_bonus');
        })->where('created_at', '<=', $selected)
            ->where('created_at', '>=', $selected_start)
            ->where('business_center_id', $selected_bc)
            ->sum('amount');

        $earnings = [];

        foreach ($period as $key => $value)
        {

            $earnings[] = $com = Commission::where('user_id', auth()->id())
                ->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($value['end'])))
                ->where('created_at', '>=', $value['start'])
                ->where(function ($q) {
                    $q->orWhere('commission_type', 'retail_profit_register')
                        ->orWhere('commission_type', 'retail_profit_repurchase')
                        ->orWhere('commission_type', 'retail_bonus')
                        ->orWhere('commission_type', 'frontline_order_bonus')
                        ->orWhere('commission_type', 'first_order_bonus')
                        ->orWhere('commission_type', 'power_team_bonus')
                        ->orWhere('commission_type', 'leg')
                        ->orWhere('commission_type', 'bingo_bonus')
                        ->orWhere('commission_type', 'matching_bonus')
                        ->orWhere('commission_type', 'binary_match_bonus')
                        ->orWhere('commission_type', 'rank_advancement_bonus')
                        ->orWhere('commission_type', 'preferred_customer_bonus')
                        ->orWhere('commission_type', 'prefered_customer_bonus')
                        ->orWhere('commission_type', 'global_bonus');
                })->sum('amount');

        }

        $earnings = array_reverse($earnings);

//        foreach ($earnings as $key => $value)
//        {
//            $earnings[$key + 1] = $value;
//        }

        $BCs = BusinessCenter::where('user_id', auth()->user()->id)->get();

        if ($selected >= '2019-05-19')
        {
            $rank_history_id = RankHistory::where('business_center_id', $selected_bc)
                ->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($selected)))
                ->where('created_at', '>=', date('Y-m-d 23:59:59', strtotime($selected_start)))
                ->max('id');
        }
        else
        {
            $rank_history_id = RankHistory::where('user_id', auth()->user()->id)
                ->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($selected)))
                ->max('id');
        }

        $rank_id = ($rank_history_id == null) ? 1 : RankHistory::where('id', $rank_history_id)->value('rank_updated');

        $par = RankSetting::where('id', $rank_id)->value('rank');

        $period = array_reverse(array_reverse(array_values($period)));

        $data = [
            'report' => $reportdata,
            'fob_total' => $fob_total,
            'power_total' => $power_total,
            'bingo_total' => $bingo_total,
            'match_total' => $match_total,
            'rank_total' => $rank_total,
            'retail_total' => $retail_total,
            'pc_total' => $pc_total,
            'earnings' => $earnings,
            'total' => $total,
            'bc' => $BCs,
            'adj_total' => $adj_total,
            'par' => $par,
            'year_end' => $year_end,
            'weekend' => $weekend,
            'period' => $period
        ];

        return response(['data' => $data, 'status' => 200]);
    }

    public function weeklyDetails(Request $request)
    {
        $selected_bc = $request->bc;
        $last_4_week = date('Y-m-d 00:00:00', strtotime('-28 Day', strtotime($request->end)));
        $end_period = date('Y-m-d 23:59:59', strtotime($request->end));
        $active_status_weekend = ActiveStatusHistory::where('created_at', '<=', $end_period)->where('user_id', auth()->id())->orderBy('id', 'desc')->value('active_status');

        // Rank history
        if ($request->end >= '2019-05-19')
        {
            $rank_history_id = RankHistory::where('business_center_id', $selected_bc)
                ->where('created_at', '<=', $end_period)
                ->max('id');
        }
        else{
            $rank_history_id = RankHistory::where('user_id', auth()->user()->id)
                ->where('created_at', '<=', $end_period)
                ->max('id');
        }
        $rank_id = ($rank_history_id == null) ? 1 : RankHistory::where('id', $rank_history_id)->value('rank_updated');
        $rank_setting = RankSetting::where('id', $rank_id)->first();
//        $reportdata['rank'] = $rank_setting->rank;
        // #End Rank history

        if ($request->bonustype == "fob")
        {
            $fob_list = Commission::where('business_center_id', '=', $selected_bc)
                ->where(function($q){
                    $q->orWhere('commission_type', 'frontline_order_bonus');
                    $q->orWhere('commission_type', 'first_order_bonus');

                })->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($request->end)))
                ->where('created_at', '>=', $request->start)
                ->get();
            $reportdata = [];

            foreach ($fob_list as $key => $value)
            {
                $level = self::findLevel($value->user_id, $value->from_user_id);

                if($level == null)
                {
                    $level = 1 ;
                }
                $levelrate = "level_".$level;
                $bonus_rate = FirstOrderBonusSetting::where('id', 1)->value($levelrate);

                $user_info = User::find($value->from_user_id);
                $sponsor_info = User::find($user_info->sponsor_id);
                $first_order = OrderProduct::whereHas('order', function ($q) use($value) {
                    $q->where('user_id', $value->from_user_id);
                })->where('qv', '>', 0)->get();

                if ($first_order->count() > 0)
                {
                    $first_order = $first_order[0];
                    $created = strtotime('m/d/Y', strtotime($first_order->created_at));

                    $row['order_id'] = $first_order->order_id;
                    $row['created_at'] = $created;
                    $row['member_type'] = strtoupper($user_info->usertype);
                    $row['fname'] = $user_info->name;
                    $row['lname'] = $user_info->lastname;
                    $row['username'] = $user_info->username;
                    if (isset($sponsor_info))
                    {
                        $row['sposnorname'] = $sponsor_info->name;
                        $row['sposnorusername'] = $sponsor_info->username;
                    }
                    else
                    {
                        $row['sposnorname'] = "na";
                        $row['sposnorusername'] = "na";
                    }
                    $row['bonus_level'] = $level;
                    $row['qv'] = OrderProduct::where('order_id', $first_order->order_id)->sum('qv');
                    $row['rate'] = $bonus_rate;
                    $row['bonus'] = $value->amount;
                    $reportdata[] = $row;
                }

            }


        }
        elseif ($request->bonustype == "match")
        {
            $report = Commission::where(function($q) {
                $q->where('commission_type', 'matching_bonus')->orWhere('commission_type', 'binary_match_bonus');
            })->where('business_center_id', $selected_bc)
                ->where('created_at', '>=', $request->start)
                ->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($request->end)))
                ->get();

            $reportdata = [];
            foreach ($report as $key => $value)
            {
                $level = self::findLevel($value->user_id, $value->from_user_id);
                $bingo_earned = Commission::where('user_id', $value->from_user_id)
                    ->where('created_at', '>=', $request->start)
                    ->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($request->end)))
                    ->where('commission_type', 'leg')->sum('amount');

                $user_info = User::find($value->from_user_id);
                $sponsor_info = User::find($user_info->sponsor_id);

                $created = strtotime('m/d/Y', strtotime($user_info->created_at));

                $row['username'] = $user_info->username;
                $row['fname'] = $user_info->name;
                $row['lname'] = $user_info->lastname;
                $row['sposnorname'] = $sponsor_info->name;
                $row['sposnorusername'] = $sponsor_info->username;
                $row['created_at'] = $created;

                $row['qs'] = RankStatusSetting::where('id', $user_info->qualified_status)->value('rank_status');
                if (auth()->user()->matchingbonus_percentage == 0)
                {
                    $row['matchingbonus_percentage'] = 20;
                }
                else
                {
                    $row['matchingbonus_percentage'] = auth()->user()->matchingbonus_percentage;
                }
                $row['bingo_earned'] = $value->amount * 100/$row['matchingbonus_percentage'];
                $row['match_bonus'] = $value->amount;
                $row['level'] = $level;

                $reportdata[] = $row;
            }


        }
        elseif ($request->bonustype == "power")
        {
            $request->end = date('Y-m-d 23:59:59', strtotime($request->end));
            $active_status_weekend = 1;

            $qv_left = PointHistory::where('business_center_id', $selected_bc)
                ->where('created_at', '>=', $request->start)
                ->where('created_at', '<=', $request->end)
                ->where('leg', 'L')->sum('pv');

            $qv_right = PointHistory::where('business_center_id', $selected_bc)
                ->where('created_at', '>=', $request->start)
                ->where('created_at', '<=', $request->end)
                ->where('leg','R')->sum('pv');

            $bv_left = PointHistory::where('business_center_id', $selected_bc)
                ->where('created_at', '>=', $request->start)
                ->where('created_at', '<=', $request->end)
                ->where('leg','L')->sum('bv');

            $bv_right = PointHistory::where('business_center_id', $selected_bc)
                ->where('created_at', '>=', $request->start)
                ->where('created_at', '<=', $request->end)
                ->where('leg','R')->sum('bv');

            $reportdata = [];

            $row['pqv'] = OrderProduct::whereHas('order', function ($q) use ($selected_bc) {
                $q->where('user_id', auth()->id())->where('order_status_id', 4);
            })->where('created_at', '>=', $last_4_week)
                ->where('created_at', '<=', $request->end)
                ->sum('qv');

            $customer_qv = OrderProduct::whereHas('order', function ($q) use ($selected_bc) {
                $q->where('sponsor_id', auth()->id())->where('order_status_id', 4)->whereHas('user', function ($q) {
                    $q->where('usertype', '!=', 'dc');
                });
            })->where('created_at', '>=', $last_4_week)
                ->where('created_at', '<=', $request->end)
                ->sum('qv');

            $row['pqv'] = $row['pqv'] + $customer_qv;
            $row['week_leg'] = min($bv_left, $bv_right);
            $row['new_qv_left'] = $qv_left;
            $row['new_qv_right'] = $qv_right;
            $row['new_bv_left'] = $bv_left;
            $row['new_bv_right'] = $bv_right;
            $row['power_team_bv'] = max($bv_left, $bv_right);

            $row['bv_in_200'] = floor($row['power_team_bv'] / 200) * 200;
            $row['rate'] = 5;

            if (($row['pqv'] >= 200 || $row['week_leg'] >= 100) && $active_status_weekend == 1)
            {
                $row['bonus'] = $row['bv_in_200'] * 5/100;
                if ($row['bonus'] > 250)
                {
                    $row['bonus'] = 250;
                }
            }
            else
            {
                $row['bonus'] = 0;
            }
            $reportdata[] = $row;
        }
        elseif ($request->bonustype == "bingo")
        {
            $request->end = date('Y-m-d 23:59:59', strtotime($request->end));
            $request->start = date('Y-m-d 00:00:00', strtotime($request->start));

            $report = BusinessCenter::join('point_tables','point_tables.user_id','=','business_centers.id')
                ->join('users','business_centers.user_id', '=', 'users.id')
                ->where('business_centers.id', '=', $selected_bc)
                ->select('point_tables.left_carry','point_tables.right_carry','point_tables.left_bvcarry','point_tables.right_bvcarry','point_tables.total_left','point_tables.total_bvleft','point_tables.total_right','point_tables.total_bvright')
                ->get();

            $user_info = User::find(auth()->user()->id);

            $rank_rate = $rank_setting->binary;

            // Previous week PAR
            $prev_week_end_date = date('Y-m-d 23:59:59', strtotime('last sunday', strtotime($request->end)));
            if ($prev_week_end_date >= date('Y-m-d 00:00:00', strtotime('2019-05-19')))
            {
                $prev_rank_history_id = RankHistory::where('business_center_id', $selected_bc)
                    ->where('created_at', '<=', $request->end)
                    ->where('created_at', '>=', $request->start)
                    ->max('id');
            }
            else
            {
                $prev_rank_history_id = RankHistory::where('user_id', auth()->user()->id)
                    ->where('created_at', '<=', $prev_week_end_date)
                    ->max('id');
            }
            $prev_rank_id = ($prev_rank_history_id == null) ? 1 : RankHistory::where('id', $prev_rank_history_id)->value('rank_updated');
            $prev_rank_setting = RankSetting::where('id', $prev_rank_id)->first();
            $reportdata = [];
            $row['prev_week_rank'] = $prev_rank_setting->rank;
            // #End Previous week PAR

            $row['qs'] = RankStatusSetting::where('id', $user_info->qualified_status)->value('rank_status');

            $qs_rate = 10;
            $last_26_week = date('Y-m-d H:i:s', strtotime('-26 Week', strtotime($request->end)));
            $data_last_qs_date = QualifiedStatusHistory::where('user_id', $user_info->id)
                ->where('status_updated', 5)
                ->where('created_at', '>=', $last_26_week)
                ->where('created_at', '<=', $request->end)
                ->orderBy('id', 'desc')
                ->count();
            $first_bc = BusinessCenter::where('user_id', $user_info->id)->value('id');
            // return $first_bc;
            if ($data_last_qs_date > 0 && $selected_bc == $first_bc)
            {
                $qs_rate = 15;
            }

            $row['bingo_rate'] = max($rank_rate,$qs_rate);

            //qv
            if($active_status_weekend != 0)
            {
                $last_entry = QvCarryForwardHistory::where('business_center_id', $selected_bc)
                    ->where('created_at', '<', $request->start)
                    ->max('created_at');
                if($last_entry != null)
                {
                    $active_status_after_last_entry = self::active_status_after_last_entry($last_entry, $request->end);
                }
                if($last_entry == null)
                {
                    $row['carry_qv_left'] = PointHistory::where('business_center_id', $selected_bc)
                        ->where('created_at', '<', $request->start)
                        ->where('leg', 'L')->sum('pv');
                }
                else
                {
                    if($active_status_after_last_entry != 0)
                    {
                        $row['carry_qv_left'] = QvCarryForwardHistory::where('business_center_id', $selected_bc)
                            ->where('created_at', $last_entry)
                            ->value('left');
                    }
                    else
                    {
                        $row['carry_qv_left'] = 0;
                    }
                }

                $row['current_qv_left'] = PointHistory::where('business_center_id', $selected_bc)
                    ->where('created_at', '>=', $request->start)
                    ->where('created_at', '<=', $request->end)
                    ->where('leg', 'L')
                    ->sum('pv');

                $row['total_qv_left'] = $row['carry_qv_left'] + $row['current_qv_left'];

                if($last_entry == null)
                {
                    $row['carry_qv_right'] = PointHistory::where('business_center_id', $selected_bc)
                        ->where('created_at', '<', $request->start)
                        ->where('leg', 'R')->sum('pv');
                }
                else
                {
                    if($active_status_after_last_entry != 0)
                    {
                        $row['carry_qv_right'] = QvCarryForwardHistory::where('business_center_id', $selected_bc)
                            ->where('created_at', $last_entry)
                            ->value('right');
                    }
                    else
                    {
                        $row['carry_qv_right'] = 0;
                    }
                }

                $row['current_qv_right'] = PointHistory::where('business_center_id',$selected_bc)
                    ->where('created_at', '>=', $request->start)
                    ->where('created_at', '<=', $request->end)
                    ->where('leg','R')->sum('pv');

                $row['total_qv_right'] = $row['carry_qv_right'] + $row['current_qv_right'];


                $last_entry = CarryForwardHistory::where('business_center_id', $selected_bc)
                    ->where('created_at', '<', $request->start)
                    // ->where('action','deducted')
                    ->where('action', '<>', 'added')
                    ->max('created_at');
                if($last_entry != null)
                {
                    $active_status_after_last_entry = self::active_status_after_last_entry($last_entry, $request->end);
                }

                //dd($last_entry);

                if($last_entry == null)
                {
                    $last_entry = CarryForwardHistory::where('business_center_id', $selected_bc)
                        ->where('created_at', '<', $request->start)
                        ->max('created_at');
                    /*added by vincy on feb 08*/
                    if($last_entry != null)
                    {
                        $active_status_after_last_entry = self::active_status_after_last_entry($last_entry, $request->end);
                        if($active_status_after_last_entry != 0)
                        {
                            $row['carry_bv_left'] = CarryForwardHistory::where('business_center_id', $selected_bc)
                                ->where('created_at', $last_entry)
                                ->value('left');

                            $upto_thisweek = PointHistory::where('business_center_id', $selected_bc)
                                ->where('created_at', '>', $last_entry)
                                ->where('created_at', '<', $request->start)
                                ->where('leg', 'L')->sum('bv');

                            $row['carry_bv_left'] = $row['carry_bv_left'] + $upto_thisweek;
                        }
                        else
                        {
                            $row['carry_bv_left'] = 0;
                        }
                    }
                    else
                    {
                        $row['carry_bv_left'] = PointHistory::where('business_center_id', $selected_bc)
                            ->where('created_at', '<', $request->start)
                            ->where('leg', 'L')->sum('bv');
                    }
                    /*added by vincy on feb 08*/

                }
                else
                {
                    if($active_status_after_last_entry != 0)
                    {
                        $row['carry_bv_left'] = CarryForwardHistory::where('business_center_id', $selected_bc)
                            ->where('created_at', $last_entry)
                            ->value('left');

                        $upto_thisweek = PointHistory::where('business_center_id', $selected_bc)
                            ->where('created_at', '>', $last_entry)
                            ->where('created_at', '<', $request->start)
                            ->where('leg', 'L')->sum('bv');

                        $row['carry_bv_left'] = $row['carry_bv_left'] + $upto_thisweek;
                    }
                    else
                    {
                        $row['carry_bv_left'] = 0;
                    }
                }

                $row['current_bv_left'] = PointHistory::where('business_center_id', $selected_bc)
                    ->where('created_at', '>=', $request->start)
                    ->where('created_at', '<=', $request->end)
                    ->where('leg', 'L')->sum('bv');
                $row['total_bv_left'] = $row['carry_bv_left'] + $row['current_bv_left'];

                $last_entry = CarryForwardHistory::where('business_center_id', $selected_bc)
                    ->where('created_at', '<', $request->start)
                    // ->where('action','deducted')
                    ->where('action', '<>', 'added')
                    ->max('created_at');
                if($last_entry == null)
                {
                    $last_entry = CarryForwardHistory::where('business_center_id', $selected_bc)
                        ->where('created_at', '<', $request->start)
                        ->max('created_at');

                    // $reportdata['carry_bv_right'] = CarryForwardHistory::where('user_id',$selected_bc)
                    // ->where('created_at',$last_entry)
                    // ->value('right');

                    /*added by vincy on feb 08*/
                    if($last_entry != null)
                    {

                        $active_status_after_last_entry = self::active_status_after_last_entry($last_entry, $request->end);
                        if($active_status_after_last_entry != 0){

                            $row['carry_bv_right'] = CarryForwardHistory::where('business_center_id', $selected_bc)
                                ->where('created_at', $last_entry)
                                ->value('right');

                            $upto_thisweek = PointHistory::where('business_center_id', $selected_bc)
                                ->where('created_at', '>', $last_entry)
                                ->where('created_at', '<', $request->start)
                                ->where('leg', 'R')->sum('bv');

                            $row['carry_bv_right'] = $row['carry_bv_right'] + $upto_thisweek;
                        }
                        else
                        {
                            $row['carry_bv_right'] = 0;
                        }
                    }
                    else
                    {
                        $row['carry_bv_right'] = PointHistory::where('business_center_id', $selected_bc)
                            ->where('created_at', '<', $request->start)
                            ->where('leg', 'R')->sum('bv');
                    }
                    /*added by vincy on feb 08*/
                }
                else
                {
                    if($active_status_after_last_entry != 0)
                    {
                        $row['carry_bv_right'] = CarryForwardHistory::where('business_center_id', $selected_bc)
                            ->where('created_at', $last_entry)
                            ->value('right');

                        $upto_thisweek = PointHistory::where('business_center_id', $selected_bc)
                            ->where('created_at', '>', $last_entry)
                            ->where('created_at', '<', $request->start)
                            ->where('leg', 'R')->sum('bv');

                        $row['carry_bv_right'] = $row['carry_bv_right'] + $upto_thisweek;
                    }
                    else
                    {
                        $row['carry_bv_right'] = 0;
                    }
                }

                $row['current_bv_right'] = PointHistory::where('business_center_id',$selected_bc)
                    ->where('created_at', '>=', $request->start)
                    ->where('created_at', '<=', $request->end)
                    ->where('leg', 'R')->sum('bv');

                $row['total_bv_right'] = $row['carry_bv_right'] + $row['current_bv_right'];

                $row['total_pairs'] = floor(min($row['total_bv_left']/200,$row['total_bv_right']/200)) * 200;

                $row['bingo_bonus'] = $row['total_pairs'] * $row['bingo_rate']/100;
            }
            else
            {
                $row['carry_qv_left'] = 0;
                $row['carry_qv_right'] = 0;
                $row['current_qv_left'] = 0;
                $row['current_qv_right'] = 0;
                $row['total_qv_left'] = 0;
                $row['total_qv_right'] = 0;

                $row['carry_bv_left'] = 0;
                $row['carry_bv_right'] = 0;
                $row['current_bv_left'] = 0;
                $row['current_bv_right'] = 0;
                $row['total_bv_left'] = 0;
                $row['total_bv_right'] = 0;

                $row['total_pairs'] = 0;
                $row['bingo_bonus'] = 0;
            }
            $reportdata[] = $row;


        }
        elseif($request->bonustype == "retail")
        {
            $retail_list = Commission::where('business_center_id', $selected_bc)
                ->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($request->end)))
                ->where('created_at', '>=', $request->start)
                ->where(function($q){
                    $q->orWhere('commission_type', 'retail_profit_register');
                    $q->orWhere('commission_type', 'retail_profit_repurchase');
                    $q->orWhere('commission_type', 'retail_bonus');
                })->get();

            $reportdata = [];
            foreach ($retail_list as $key => $value)
            {
                $user_info = User::find($value->from_user_id);
                $sponsor_info = User::find($user_info->sponsor_id);

                $order = OrderProduct::whereHas('user', function ($q) use ($value) {
                    $q->where('user_id', $value->from_user_id);
                })->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($request->end)))->where('created_at', '>=', $request->start)->get();

                $order = $order[0];

                $created = Carbon::parse($order->created_at)->format('m/d/Y');

                $row['order_id'] = $order->id;
                $row['created_at'] = $created;
                $row['member_type'] = strtoupper($user_info->usertype);
                $row['name'] = $user_info->name;
                $row['username'] = $user_info->username;
                $row['retail_price'] = $order->product ? $order->product->retail_price * $order->quantity : 0;
                $row['member_price'] = $order->product ? $order->product->member_price * $order->quantity : 0;
                $row['retail_profit'] = $value->amount;
                $reportdata[] = $row;
            }
        }
        elseif($request->bonustype == "adj")
        {

            $adj_list = Commission::where('business_center_id', $selected_bc)
                ->where(function($q) {
                    $q->orWhere('commission_type', 'credited_by_admin');
                    $q->orWhere('commission_type', 'debited_by_admin');
                    $q->orWhere('commission_type', 'admin_credit');
                    $q->orWhere('commission_type', 'admin_debit');
                    $q->orWhere('commission_type', 'user_credit');
                    $q->orWhere('commission_type', 'user_debit');
                })
                ->where('created_at', '<=', date('Y-m-d 23:59:59',strtotime($request->end)))
                ->where('created_at', '>=', $request->start)
                ->get();

            $reportdata = [];

            foreach ($adj_list as $key => $value)
            {
                $user_info = User::find($value->user_id);

                $from_userinfo = User::find($value->from_user_id);
                $created = Carbon::parse($value->created_at)->format('m/d/Y');

                $row['created_at'] = $created;
                $row['amount'] = $value->amount;
                $row['username'] = $from_userinfo->username;

                if($value->commission_type == 'debited_by_admin' || $value->commission_type == 'admin_debit')
                {
                    $row['amount'] = $value->amount * -1;
                }
                // $reportdata[$value->id]['note']=($value->payment_type == "credited_by_admin") ? "Credited By Admin": "Debited By Admin";
                $row['note'] = $value->notes;
                $reportdata[] = $row;
            }
        }
        elseif($request->bonustype == "pcbonus")
        {
            $pcbonus_list = Commission::where('business_center_id','=',$selected_bc)
                ->where(function($q) {
                    $q->orWhere('commission_type', 'preferred_customer_bonus');
                    $q->orWhere('commission_type', 'prefered_customer_bonus');

                })
                ->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($request->end)))
                ->where('created_at', '>=', $request->start)
                ->get();
            $reportdata = [];

            foreach ($pcbonus_list as $key => $value)
            {
                $user_info = User::find($value->fromid);
                $sponsor_info = User::find($user_info->sponsor_id);
                $first_order = Order::where('user_id', $value->fromid)->whereHas('qv', '>', 0)->get();
                $first_order = $first_order[0];

                $created = Carbon::parse($first_order->created_at)->format('m/d/Y');

                $row['order_id'] = $first_order->order_id;
                $row['created_at'] = $created;
                $row['member_type'] = strtoupper($user_info->usertype);
                $row['fname'] = $user_info->name;
                $row['lname'] = $user_info->lastname;
                $row['username'] = $user_info->username;
                if(isset($sponsor_info)){
                    $row['sposnorname'] = $sponsor_info->name;
                    $row['sposnorusername'] = $sponsor_info->username;
                }else{
                    $row['sposnorname'] = "na";
                    $row['sposnorusername'] = "na";
                }

                $row['bonus'] = $value->amount;
                $row['qv'] = OrderProduct::where('order_id',$first_order->id)->sum('qv');
                $reportdata[] = $row;
            }
        }

        // Rank Advancement Bonus
        if($request->bonustype == "rank")
        {

            $reportdata = [];

            $rank_bonus = Commission::where('commission_type', 'rank_advancement_bonus')
                ->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($request->end)))
                ->where('created_at', '>=', date('Y-m-d 23:59:59', strtotime($request->start)))
                ->where('business_center_id', $selected_bc)
                ->count();

            if($rank_bonus > 0)
            {

                $reportitem['rank_bonus'] = Commission::where('commission_type', 'rank_advancement_bonus')
                    ->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($request->end)))
                    ->where('created_at', '>=', date('Y-m-d 23:59:59', strtotime($request->start)))
                    ->where('business_center_id', $selected_bc)
                    ->sum('amount');

                $date_achieved = Commission::where('commission_type', 'rank_advancement_bonus')
                    ->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($request->end)))
                    ->where('created_at', '>=', date('Y-m-d 23:59:59', strtotime($request->start)))
                    ->where('business_center_id', $selected_bc)
                    ->value('created_at');

                $reportitem['date_achieved'] = Carbon::parse($date_achieved)->format('Y-m-d');

                $reportitem['left_total_qv'] = QvCarryForwardHistory::where('business_center_id', $selected_bc)
                    ->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($request->end)))
                    ->where('created_at', '>=', date('Y-m-d 23:59:59', strtotime($request->start)))
                    ->value('total_left');

                $reportitem['right_total_qv'] = QvCarryForwardHistory::where('business_center_id', $selected_bc)
                    ->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($request->end)))
                    ->where('created_at', '>=', date('Y-m-d 23:59:59', strtotime($request->start)))
                    ->value('total_right');

                if($request->end >= '2019-05-19')
                {
                    $reportitem['prev_rank'] = RankSetting::where('id', RankHistory::where('business_center_id', $selected_bc)->whereDate('created_at', '=', $reportitem['date_achieved'])->value('rank_id'))->value('rank');

                    $reportitem['new_rank'] = RankSetting::where('id', RankHistory::where('business_center_id', $selected_bc)->whereDate('created_at', '=', $reportitem['date_achieved'])->value('rank_updated'))->value('rank');
                }
                else
                {
                    $reportitem['prev_rank'] = RankSetting::where('id', RankHistory::where('business_center_id', $selected_bc)->whereDate('created_at', '=', $reportitem['date_achieved'])->value('rank_id'))->value('rank');

                    $reportitem['new_rank'] = RankSetting::where('id', RankHistory::where('business_center_id', $selected_bc)->whereDate('created_at', '=', $reportitem['date_achieved'])->value('rank_updated'))->value('rank');
                }
                $reportdata[] = $reportitem;
            }
        }

        return response()->json(['data' => $reportdata]);
    }

    public function findLevel ($user_id, $from_id)
    {
        SponsorTree::$upline_users = [];
        $upline = SponsorTree::getAllUpline($from_id);
        $variable = SponsorTree::$upline_users;

        foreach ($variable as $key => $value)
        {
            $level = $key + 1;
            if ($value['user_id'] == $user_id)
            {
                return $level;
            }
        }

    }

    public function active_status_after_last_entry ($last_entry, $start_date)
    {
//        $active_status_after_last_entry = ActiveStatusHistory::where('created_at', '>=', $last_entry)->where('created_at', '<', $start_date)
//            ->where('user_id', auth()->user()->id)
//            ->where('active_status', 0)
//            ->value('active_status');
//
//        if (!is_numeric($active_status_after_last_entry))
//        {
//            $active_status_after_last_entry = 1;
//        }
//        return $active_status_after_last_entry;

        $active_status_after_last_entry = ActiveStatusHistory::where('created_at', '>=', $last_entry)->where('created_at', '<=', $start_date)
            ->where('user_id', auth()->user()->id)
            ->latest()
            ->first();

        if (is_null($active_status_after_last_entry))
        {
            $active_status_after_last_entry = 1;
        }
        else
        {
            $active_status_after_last_entry = $active_status_after_last_entry->active_status;
        }

        return $active_status_after_last_entry;
    }

    public function paylutionAccount()
    {
        $userToken = auth()->user()->hw_token;
        if($userToken != null)
        {
            $user = env('HYPERWALLET_USERNAME');
            $password = env('HYPERWALLET_PASSWORD');
            $url = (env('HYPERWALLET_MODE') == 'test') ?'https://uat-api.paylution.com/rest/v3/users/' :'https://api.paylution.com/rest/v3/users/';
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url.$userToken);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_USERPWD, $user . ':' . $password);

            $headers = array();
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Accept: application/json';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $data = json_decode(curl_exec($ch),true);
            if (curl_errno($ch))
            {
                echo 'Error:' . curl_error($ch);
            }
            curl_close ($ch);

            if(isset($data['errors']))
            {
                if($data['errors'][0]['code'] == 'RESOURCE_NOT_FOUND')
                {
                    $paylutionData = -1;
                }
            }
            else
            {
                $paylutionData = 1;
            }

        }
        else
        {
            $paylutionData = 0;
        }

        return response()->json(['data' => $paylutionData, 'status' => 200]);
    }

    public function createPaylutionAccount(Request $request)
    {
        $user_id = auth()->id();
        $username = auth()->user()->username;
        $dateofbirth = date('Y-m-d', strtotime($request->dateofbirth));

        User::where('id', $user_id)->update([
                "firstname" => $request->firstname,
                "lastname" => $request->lastname,
                "email" => $request->email,
                "address1" => $request->address1,
                "city" => $request->city,
                "country_id" => $request->country_id,
                "state_id"=> $request->state_id,
                "dateofbirth"=> $dateofbirth,
                "phone"=> $request->phone,
                "gender"=> $request->gender,
            ]);

        $data = [
            "address"=> $request->address1,
            "city"=> $request->city,
            "username"=> $username,
            "country"=> Country::find(auth()->user()->country_id)->iso2,
            "dob"=> $dateofbirth,
            "email"=> $request->email,
            "firstname"=> $request->firstname,
            "lastname"=> $request->lastname,
            "postcode"=> $request->postcode,
            "phone"=> auth()->user()->mobile,
            "gender"=> $request->gender,
            "state"=> State::find(auth()->user()->state_id)->iso2
        ];

        $responseData = HyperwalletHistories::createaccount($data, $user_id);

        return response()->json(['status' => 200, 'data' => isset($responseData['errors']) ? 0 : 1, 'message' => isset($responseData['errors']) ? $responseData['errors'][0]['fieldName'].' '.$responseData['errors'][0]['message'] : 'success']);
    }
}
