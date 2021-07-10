<?php

namespace App\Http\Controllers\Api;

use App\ChallengePointHistory;
use App\Image;
use App\ActivityLog;
use App\Country;
use App\EwalletTransaction;
use App\OrderProduct;
use App\QualifiedStatusSetting;
use App\QualifiedStatusHistory;
use App\RankHistory;
use App\RankSetting;
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
use App\RankStatusSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function dashboard(Request $request)
    {
        try
        {
            $user = User::with('state', 'country', 'rankSetting')->find(auth()->id());

            $profile_photo = $user->image;

            $referals = TreeTable::with('sponsor')->where('sponsor_id', auth()->id())->limit(6)->get();
            $user_status = $user->active_status;
            $sponsor = $user->sponsor;

            $left_bv = \DB::table('point_tables')->where('user_id', '=', $user->id)->value('left_carry');
            $left_total_bv = \DB::table('point_tables')->where('user_id', '=', $user->id)->value('total_left');
            $right_bv = \DB::table('point_tables')->where('user_id', '=', $user->id)->value('right_carry');
            $right_total_bv = \DB::table('point_tables')->where('user_id', '=', $user->id)->value('total_right');

            $total_balance = EwalletTransaction::where('user_id', auth()->user()->id)->orderBy('id', 'desc')->value('current_balance');
            $user_balance = $total_balance;

            $qualified_status = User::where('id', '=', $user->id)->value('qualified_status');

            $left = BusinessCenter::where('user_id', '=', $user->id)->value('left_carry');

            $right = BusinessCenter::where('user_id', '=', $user->id)->value('right_carry');

            if ($left > $right)
            {
                $l_status = 1;
            }
            else
            {
                $l_status = 0;
            }

            $calander_month_active = SponsorTree::with('sponsor')->whereHas('sponsor', function ($q) {
                $q->where('id', auth()->id())->where('usertype', 'dc')->where('active_status','=','1')->whereMonth('created_at', '=', date('m'));
            })->get();

            $calander_month_count = count($calander_month_active);

            $left_ac_bv = SponsorTree::with('sponsor')->whereHas('sponsor', function ($q) {
                $q->where('id', auth()->id())->where('lifetime_rank_id', 4);
            })->get();

            $left_ac_bv_count = count($left_ac_bv);

            $right_ac_bv = SponsorTree::with('sponsor')->whereHas('sponsor', function ($q) {
                $q->where('id', auth()->id())->where('lifetime_rank_id', 5);
            })->get();
            $right_ac_bv_count = count($right_ac_bv);

            $one_star = SponsorTree::with('sponsor')->whereHas('sponsor', function ($q) {
                $q->where('id', auth()->id())->where('lifetime_rank_id', '>', 6);
            })->get();
            $one_star_count = count($one_star);

//            if ($userState) {
//                $states = CountryState::getStates($userCountry);
//                $state  = array_search($userState,$states);
//            } else {
//                $state = "unknown";
//            }
//
//            $imagefile = images::orderBy('id', 'desc')->get();

            $left_dc_count = $right_dc_count = 0;
            $start_date = $user->maintenance_date;
            $end_date =  date('Y-m-d', strtotime('+28 Day', strtotime($start_date)));

            $date1 = date_create($start_date);
            $date2 = date_create($end_date);
            $diff = date_diff($date1, $date2);

            /*match bonus tracker*/
            $i = 0;
            $to_date = date('Y-m-d H:i:s', strtotime("-28 days"));
            $qualified_from_cloud = 0;
            if($user->maintenance_date > '2018-11-07')
            {
                $from_date = date('Y-m-d 00:00:00', strtotime('-27 Day', strtotime($user->maintenance_date)));
                $qualified_from_cloud = 1;
            }

            $users_list = User::where('usertype', '=', 'dc')->where('matchingbonus_percentage', '<>', 50)->whereHas('orders')->get();

            $matching_bonus_tracker_data = self::matching_bonus_tracker($to_date, $i);
            $enroll_count = $matching_bonus_tracker_data['enroll_count'];
            $enroll_28pqv = $matching_bonus_tracker_data['enroll_28pqv'];

            $last_4_week =  date('Y-m-d 00:00:00', strtotime('-27 Day', strtotime(date('Y-m-d H:i:s'))));

            $personal_qv = OrderProduct::whereHas('order', function ($q) {
                $q->where('user_id', auth()->user()->id)->where('order_status_id', 4);
            })->where('created_at', '>=', $last_4_week)->sum('qv');

            $rank_status = RankStatusSetting::where('id', max($user->qualified_status, $user->is_forced_qualified_status))->first();

            $rank_status_settings = RankStatusSetting::all();

            $customer_qv = OrderProduct::whereHas('order', function ($q) {
                $q->where('order_status_id', 4)->whereHas('user', function ($q) {
                    $q->where('sponsor_id', auth()->id())->where('usertype', '!=', 'dc');
                });
            })->where('created_at', '>=', $last_4_week)->sum('qv');

            $total_qv = $personal_qv + $customer_qv;

            if ($user->is_forced_active_status == 1)
            {
                $total_qv = $total_qv + auth()->user()->forced_pqv;
            }

            if ($user->is_forced_active_status == 0)
            {
//            $today = date('Y-m-d');
//            if($user->forced_active_start_date<=$today && Auth::user()->forced_active_end_date>=$today)
//
//                $total_qv = $total_qv + Auth::user()->forced_pqv;
//            else
//                $total_qv = $personal_qv + $customer_qv;

            }

            $sponsored_customers = SponsorTree::where('sponsor_id', auth()->user()->id)->pluck('user_id');

            $recent_order = OrderProduct::where('created_at', '>=', date('Y-m-d 00:00:00', strtotime($last_4_week)))
                ->whereHas('order', function ($q) use ($sponsored_customers) {
                    $q->where('order_status_id', 4)->where(function ($q) use ($sponsored_customers) {
                        $q->whereIn('user_id', $sponsored_customers)->orWhere('user_id', auth()->id());
                    });
                })->max('order_id');

            $recent_order_date = OrderProduct::where('order_id', $recent_order)->value('created_at');

            $next_28 = date('Y-m-d H:i:s', strtotime('+27 Day', strtotime($recent_order_date)));

            $total_active_dc = $user->active_left + $user->active_right;

            $user_status_grace = $user->active_status;

            // $maintenance_date = self::getMaintanaceDate(auth()->user()->id);

            $maintenance_date = User::where('id', auth()->user()->id)->value('maintenance_date');

            // if ($maintenance_date != "past_due")
            // {
            //     $maintenance_date = date('Y-m-d', strtotime($maintenance_date.' +27 days'));


            if($maintenance_date != null)
            {
                if ($maintenance_date >= date('Y-m-d'))
                {
                    $grace_lable = "Active";
                    $grace_lable_background = "background-color: #6dc601;";
                }
                else
                {
                    $grace_lable = "Inactive/Flushed";
                    $grace_lable_background = "background-color: #c60101;";
                }
            }
            else
            {
                $grace_lable = "Inactive";
                $grace_lable_background = "background-color: #6dc601;";
                $maintenance_date = date('Y-m-d');
            }

            // }
            // else
            // {
            //     $maintenance_date = date('Y-m-d');
            //     $grace_lable = "Past Due";
            //     $grace_lable_background =  date('Y-m-d');
            // }

            $remaining_date = $user->created_at;

            $remain = $remaining_date;
            if($remaining_date != "")
            {
                $end_date = date('Y-m-d', strtotime("28 days", strtotime($remaining_date)));
                $date_diff = round((strtotime($end_date) - (strtotime(date('Y-m-d')))) / (60 * 60 * 24));
                $remaining_date = $date_diff;
            }
            else
            {
                $remaining_date = 0;
            }

            $match_qualified_date = $remain != "" ? date('Y/m/d', strtotime($remain)) : '';
            if($remaining_date >= 0)
            {
                $remaining_match_date = $remaining_date;
            }
            else
            {
                $remaining_match_date = 0;
            }

            // Current Rank
            $current_rank_info = RankSetting::join('business_centers', 'rank_settings.id', '=', 'business_centers.current_rank_id')
                ->select('rank_settings.rank', 'rank_settings.binary')
                ->where('business_centers.user_id', auth()->user()->id)
                ->whereNull('business_centers.deleted_at')
                ->get();

            // Lifetime Rank
            $lifetime_rank_info = $user->rankSetting;
            $max_lifetime_rank = RankSetting::find(BusinessCenter::where('user_id', $user->id)->max('lifetime_rank_id'));

            // QS fetched
            $qs_rate = 10;
            $last_26_week = date('Y-m-d H:i:s', strtotime('-26 Week'));
            $data_last_qs_date = QualifiedStatusHistory::where('user_id', auth()->user()->id)
                ->where('created_at', '>=', $last_26_week)
                ->where('status_updated', 5)
                ->orderBy('id', 'desc')
                ->count();

            $bingo_percentage = [];
            $i = 1;
            foreach($current_rank_info as $data)
            {
                if ($data_last_qs_date > 0 && $i == 1)
                {
                    $qs_rate = 15;
                }
                $bingo_percentage[] = null;
                $i++;
                // max($data->binary, $qs_rate);
            }

            $data_last_qs_date = QualifiedStatusHistory::where('user_id', $user->id)->where('status_updated', 5)->orderBy('id', 'desc')->first();
            $highest_qs = QualifiedStatusHistory::with('qualifiedStatusUpdated')->where('user_id', $user->id)->orderBy('status_updated', 'desc')->first();
            $date_now = Carbon::now();
            $qs_weeks_left = '';
            if($data_last_qs_date != '')
            {
                $diff_in_weeks = $date_now->diffInWeeks($data_last_qs_date->created_at);
                if($diff_in_weeks<=26)
                {
                    $qs_weeks_left = 26-$diff_in_weeks;
                    $qs_weeks_left = 'Weeks Remaining @ 15%: '.$qs_weeks_left.' of 26';
                }
            }

            $sponsored_qv_accumulator = (int)BelizeTravelCreditHistory::where('user_id', auth()->user()->id)->where('business_center_id', 0)->where('from_user_id', 0)->where('type', 'credit')->where('status', 1)->orderBy('id', 'desc')->value('travel_credits');
            $par_accumulator = BelizeTravelCreditHistory::whereIn('business_center_id', BusinessCenter::where('user_id', auth()->user()->id)->pluck('id'))->sum('travel_credits');
            $carry_over_tc = (int)BelizeTravelCreditHistory::where('user_id', auth()->user()->id)->where('business_center_id', 0)->where('from_user_id', '!=', 0)->where('type', 'credit')->where('status', 1)->orderBy('id', 'desc')->value('travel_credits');
            $total_travel_credits = (int)BelizeTravelCreditHistory::where('user_id', auth()->user()->id)->orderBy('id', 'desc')->value('travel_credits_balance');
            $next_update_date = date('Y-m-d', strtotime('next monday'));

            $activity_logs = ActivityLog::where('user_id', $user->id)->orderBy('id', 'desc')->take(10)->get()->groupBy(function($row) {
                return Carbon::parse($row->created_at)->format('Y-m-d');
            });

//            $downline_users = app('App\Http\Controllers\Api\UserController')->autocomplete(new Request(['id' => $user->id]));
            $downline_users = [];

            $banner_images = Image::all();

            $binary_users = Order::with('user.businessCenters.treeTables')->whereHas('user', function ($q) {
                $q->where('usertype', 'dc');
            })->where('order_status_id', 4)->where('sponsor_id', auth()->id())->get();
            $binary_users = $binary_users->filter(function ($value) {
                return $value->total_qv > 99;
            });
            $binary_left_user = $binary_users->first(function ($value) {
                return $value->user->businessCenters->count() > 0 && $value->user->businessCenters[0]->treeTables->count() > 0 && $value->user->businessCenters[0]->treeTables[0]->leg == 'L';
            });
            $binary_right_user = $binary_users->first(function ($value) {
                return $value->user->businessCenters->count() > 0 && $value->user->businessCenters[0]->treeTables->count() > 0 && $value->user->businessCenters[0]->treeTables[0]->leg == 'R';
            });

            $data = compact('rank_status', 'rank_status_settings', 'total_qv', 'recent_order', 'next_28', 'data_last_qs_date', 'highest_qs', 'qs_weeks_left', 'start_date', 'grace_lable', 'maintenance_date', 'lifetime_rank_info', 'max_lifetime_rank', 'user_balance', 'total_travel_credits', 'sponsored_qv_accumulator', 'par_accumulator', 'carry_over_tc', 'next_update_date', 'bingo_percentage', 'user', 'activity_logs', 'enroll_28pqv', 'total_active_dc', 'downline_users', 'banner_images', 'binary_left_user', 'binary_right_user');

            return response()->json(['data' => $data, 'status' => 200]);
        }
        catch (\Exception $exception)
        {
            return $exception->getMessage();
        }
    }

    public function dashboardAdditionalData()
    {
        $user = User::find(auth()->id());
        if (auth()->user()->businessCenters->count() > 0)
        {
            $business_center_ids = $user->businessCenters->pluck('id');
            foreach ($business_center_ids as $business_center_id)
            {
                $currentweek_leftcarry = PointHistory::where('business_center_id', $business_center_id)->whereBetween('created_at', [Carbon::now()->startOfWeek()->format('Y-m-d H:i:s'), Carbon::now()->endOfWeek()->format('Y-m-d H:i:s')])->where('leg', 'L')->where('commission_type', '<>', 'manual_volume')->sum('bv');

                $currentweek_rightcarry = PointHistory::where('business_center_id', $business_center_id)->whereBetween('created_at', [Carbon::now()->startOfWeek()->format('Y-m-d H:i:s'), Carbon::now()->endOfWeek()->format('Y-m-d H:i:s')])->where('leg', 'R')->where('commission_type', '<>', 'manual_volume')->sum('bv');

                $currentweek_leftqv = PointHistory::where('business_center_id', $business_center_id)->whereBetween('created_at', [Carbon::now()->startOfWeek()->format('Y-m-d H:i:s'), Carbon::now()->endOfWeek()->format('Y-m-d H:i:s')])->where('leg', 'L')->where('commission_type', '<>', 'manual_volume')->sum('pv');

                $currentweek_rightqv = PointHistory::where('business_center_id', $business_center_id)->whereBetween('created_at', [Carbon::now()->startOfWeek()->format('Y-m-d H:i:s'), Carbon::now()->endOfWeek()->format('Y-m-d H:i:s')])->where('leg', 'R')->where('commission_type', '<>', 'manual_volume')->sum('pv');

                $last_entry = QvCarryForwardHistory::where('business_center_id', $business_center_id)
                    ->where('created_at', '<', date('Y-m-d H:i:s'))
                    ->max('created_at');

                if($last_entry == null)
                {
                    $carryover_qv = PointHistory::where('business_center_id', $business_center_id)
                        ->where('created_at', '<=', date('Y-m-d H:i:s'))->where('leg', 'L')->sum('pv');

                    $carryover_qvright = PointHistory::where('business_center_id', $business_center_id)
                        ->where('created_at', '<=', date('Y-m-d H:i:s'))->where('leg', 'R')->sum('pv');
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

                $left_qv = is_null($last_entry) ? $currentweek_leftqv : $carryover_qv + $currentweek_leftqv;
                $right_qv = is_null($last_entry) ? $currentweek_rightqv : $carryover_qvright + $currentweek_rightqv;
                $currentweek_leftcarries[] = $currentweek_leftcarry;
                $currentweek_rightcarries[] = $currentweek_rightcarry;
                $currentweek_leftqvs[] = $currentweek_leftqv;
                $currentweek_rightqvs[] = $currentweek_rightqv;
                $left_qvs[] = $left_qv;
                $right_qvs[] = $right_qv;
            }

        }
        else
        {
            $currentweek_leftcarry = 0;
            $currentweek_rightcarry = 0;
            $currentweek_leftcarries = [0];
            $currentweek_rightcarries = [0];
            $currentweek_leftqvs = [0];
            $currentweek_rightqvs = [0];
            $left_qvs = [0];
            $right_qvs = [0];
        }

        $current_week_payleg_bv = min($currentweek_leftcarry, $currentweek_rightcarry);

        $data = compact('current_week_payleg_bv', 'currentweek_leftcarries', 'currentweek_rightcarries', 'currentweek_leftqvs', 'currentweek_rightqvs', 'left_qvs', 'right_qvs');

        return response()->json(['data' => $data, 'status' =>200]);
    }

    public function leaderboard()
    {
//        $value->sum('points')
        $challenge_points = ChallengePointHistory::with('user')->get();
        $challenge_points = $challenge_points->groupBy('user_id')->map(function ($value) {
            $value[0]['user']['image'] = file_exists(public_path('/user_images/'. $value[0]['user']['image'])) ? $value[0]['user']['image'] : 'avatar-big.png';
            return ['user' => $value[0]->user, 'points' => $value->sum('points'), 'rank' => RankSetting::find($value[0]->user->businessCenters->max('lifetime_rank_id'))];
        })->values()->all();
        usort($challenge_points, function ($item1, $item2) {
            return $item2['points'] <=> $item1['points'];
        });

        return response()->json(['data' => array_slice($challenge_points, request()->page == 1 ? 0 : 50, 50)]);
    }

    public function travelLeaderboard()
    {
        $travel_credit_histories = BelizeTravelCreditHistory::with('user')->get();
        $travel_credit_histories = $travel_credit_histories->groupBy('user_id')->map(function ($value) {
            $value[0]['user']['image'] = file_exists(public_path('/user_images/'. $value[0]['user']['image'])) ? $value[0]['user']['image'] : 'avatar-big.png';
            return ['user' => $value[0]->user, 'team_name' => $value[0]->user->team_name, 'travel_credits_balance' => $value->last()->travel_credits_balance, 'rank' => RankSetting::find($value[0]->user->businessCenters->max('lifetime_rank_id'))];
        })->values()->all();
        usort($travel_credit_histories, function ($item1, $item2) {
            return $item2['travel_credits_balance'] <=> $item1['travel_credits_balance'];
        });
//        return $travel_credit_histories;

        return response()->json(['data' => array_slice($travel_credit_histories, 0, 50)]);
    }

    public function matching_bonus_tracker($to_date, $i)
    {
        $qualified_referrals = [];

        $enrolled_users = User::whereHas('orders')->where('created_at','>=', $to_date)->where('sponsor_id', auth()->id())->get();

        foreach ($enrolled_users as $key => $user)
        {
            $first_28 = date('Y-m-d H:i:s', strtotime('+27 Day', strtotime($user->created_at)));

            $personal_qv = OrderProduct::whereHas('order', function ($q) use ($user) {
                $q->where('user_id', $user->id)->where('order_status_id', 4);
            })->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($first_28)))->sum('qv');

            $customer_qv = OrderProduct::whereHas('order', function ($q) use ($user) {
                $q->where('sponsor_id', $user->id)->where('order_status_id', 4)->whereHas('user', function ($q) {
                    $q->where('usertype', '!=', 'dc');
                });
            })->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($first_28)))->sum('qv');

            $total_pqv = $personal_qv + $customer_qv;

            if($total_pqv >= 100)
            {
                $qualified_referrals[] = $user->id;
            }
        }
        $enroll_count = count($qualified_referrals);
        $enroll_28pqv = [];

        foreach ($qualified_referrals as $key => $value)
        {
            $referel_info = User::find($value);
            $first_28 = date('Y-m-d H:i:s', strtotime('+27 Day', strtotime($referel_info->created_at)));

            $personal_qv = OrderProduct::whereHas('order', function ($q) use ($value) {
                $q->where('user_id', $value)->where('order_status_id', 4);
            })->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($first_28)))->sum('qv');

            $customer_qv = OrderProduct::whereHas('order', function ($q) use ($value) {
                $q->where('sponsor_id', $value)->where('order_status_id', 4)->whereHas('user', function ($q) {
                    $q->where('usertype', '!=', 'dc');
                });
            })->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($first_28)))->sum('qv');

            $total_pqv = $personal_qv + $customer_qv;
            $enroll_28pqv[] = [
                'pqv' => $total_pqv,
                'created_at' => $referel_info->created_at,
                'image' => $referel_info->image,
                'name' => $referel_info->name,
                'id' => $referel_info->id,
            ];
        }
        return compact('enroll_count', 'enroll_28pqv');
    }

    public function getRemainingMatchDate($user_id)
    {
        $date_added = '';

        $from_date = date('Y-m-d H:i:s', strtotime('-28 Day', strtotime(date('Y-m-d H:i:s'))));
        $to_date = date('Y-m-d 23:59:59');

        $enroll_customers =  User::join('sponsor_trees', 'sponsor_trees.user_id', '=', 'users.id')
            ->join('purchase_history','purchase_history.user_id','=','users.id')
            ->where('purchase_history.type','enroll')
            ->having(DB::raw('SUM(purchase_history.qv)'), '>=', 100)
            ->groupBY('purchase_history.order_id')
            ->select('sponsor_trees.user_id')
            ->where('sponsor_trees.sponsor', $user_id)
            ->where('users.created_at', '>=', $from_date)
            ->whereHas('orders')
            ->get();

        $enroll_count = count($enroll_customers);

        if ($enroll_count >= 0)
        {
            $date_added = User::where('sponsor_id',$user_id)
                ->whereHas('orders')
                ->where('created_at','>=',$from_date)
                ->where('created_at','<=',$to_date)
                ->value('created_at');

        }
        else
        {
            $plus_28_days_enroll_date = date('Y-m-d', strtotime('+28 days', strtotime(auth()->user()->created_at)));
            if ($plus_28_days_enroll_date >= date('Y-m-d'))
            {
                $date_added = auth()->user()->created_at;
            }
            else
            {
                $date_added = '';
            }
        }

        return $date_added;
    }

    public function getMaintanaceDate($user_id)
    {
        $list = User::where('sponsor_id', '=', $user_id)->where('usertype', '!=', 'dc')->whereHas('orders')->pluck('id')->toArray() ;
        $listr = array_push($list, auth()->user()->id) ;

        $personal = OrderProduct::whereHas('order', function ($q) use ($list) {
            $q->whereIn('user_id', $list);
        })->where(function ($q) {
            $q->where('created_at', '>=', date('Y-m-d 00:00:00', strtotime('-27 days')));
            $q->where('created_at', '<=', date('Y-m-d 23:59:59'));
        })->orderby('id', 'DESC')->get();

        $qv= 0 ;

        $grace_period_date = OrderProduct::whereHas('order', function ($q) use ($list) {
            $q->whereIn('user_id', $list);
        })->first();

        if($grace_period_date != null)
        {
            $grace_period_date = OrderProduct::whereHas('order', function ($q) use ($list) {
                $q->whereIn('user_id', $list);
            })->orderBy('id', 'desc')->first()->created_at;
        }
        else
        {
            $grace_period_date = null;
        }

        if ($grace_period_date != null)
        {
            foreach ($personal as $key => $value)
            {
                $qv += $value->qv;
                if ($qv >= 50)
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

    public function renewalDays()
    {
        if(auth()->user()->usertype == 'dc'){
            $now = Carbon::now();
            if(auth()->user()->renewal_date == '0000/00/00')
            {
                $due_date = date('Y/m/d', strtotime('+1 year', strtotime(auth()->user()->created_at)));
            }
            else
            {
                $due_date = date('Y/m/d', strtotime(auth()->user()->renewal_date));
            }

            $due_date_minus_one_month = date('Y/m/d', strtotime('-1 month', strtotime($due_date)));

            if($due_date <= date('2019/10/25'))
            {
                $renewal_days_remaining = $now->diffInDays("2019/10/25");
            }
            else
            {
                $renewal_days_remaining = $now->diffInDays($due_date);
            }
    //        return response()->json(['data' => null, 'status' => 500]);
            if(($due_date <= date('2019/10/25')) || ($due_date_minus_one_month <= date('Y/m/d') && $due_date >= date('Y/m/d')))
            {
                return response()->json(['data' => ['due_date' => date('m/d/Y', strtotime($due_date)), 'renewal_days_remaining' => $renewal_days_remaining], 'status' => 200]);
            }
            else
            {
                return response()->json(['data' => null, 'status' => 500]);
            }
        }
        else{
            return response()->json(['data' => null, 'status' => 500]);
        }
    }

    public function contactUs(Request $request)
    {
        return response()->json(['data' => $request->all(), 'status' => 200]);
    }

    public function invoice($id)
    {

        $order = Order::with('orderStatus', 'user', 'sponsor', 'orderPaymentMethods', 'state', 'country', 'shippingState', 'shippingCountry', 'billingState', 'billingCountry', 'shippingStatus')->find($id);
        $order_status = !is_null($order->orderStatus) ? $order->orderStatus->name : '';
        $products = OrderProduct::where('order_id', $order->id)->get();

        return response()->json(['data' => compact('order', 'order_status', 'products')]);
    }
}
