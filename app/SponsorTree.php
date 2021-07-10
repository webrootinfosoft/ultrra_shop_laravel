<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SponsorTree extends Model
{
    use SoftDeletes;

    public static $upline_users = [];

    public static $upline_id_lists = [];

    protected $fillable = ['user_id', 'sponsor_id', 'position', 'type'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function sponsor()
    {
        return $this->belongsTo('App\User', 'sponsor_id');
    }

    public static function getAllUpline($user_id, $level = 0)
    {
        if($level >= 4)
        {
            return true;
        }

        $result = self::where('user_id', $user_id)->select('sponsor_id')->take(1)->get();
        foreach ($result as $key => $value)
        {
            self::$upline_users[] = ['user_id' => $value->sponsor_id];
            self::$upline_id_lists[] = $value->sponsor_id;
            if ($value->sponsor_id)
            {
                self::getAllUpline($value->sponsor_id, $level++);
            }
        }

        return true;

    }

    public static function getAllUplineForDownlineOrderReport($user_id)
    {
        $result = self::where('user_id', $user_id)->select('sponsor_id')->take(1)->get();
        foreach ($result as $key => $value)
        {
            self::$upline_users[] = ['user_id' => $value->sponsor_id];
            self::$upline_id_lists[] = $value->sponsor_id;
            if ($value->sponsor_id)
            {
                self::getAllUplineForDownlineOrderReport($value->sponsor_id);
            }
        }

        return true;

    }

    public static function getTreeJson($user_id)
    {
        $result_arr = [];

        $result = self::with('user')->where('sponsor_id', '=', $user_id)
            ->where('type', '!=', 'vaccant')
            ->orderBy('position', 'asc')
            ->get();

        foreach ($result as $key => $user) {
            $child_count = self::where('sponsor_id', $user->user_id)->count();

            if ($child_count)
            {
                $children = true;
                $type = "root";
            }
            else
            {
                $type = "folder";
                $children = false;
            }

            $icon = "fa fa-user text-success";

            if ($user->type == 'yes')
            {
                $icon = "fa fa-user text-warning";
            }
            elseif ($user->type == 'no')
            {
                $icon = "fa fa-user text-danger";
            }
            else
            {
                $icon = "fa fa-plus-circle text-success";
            }
            $user->username = ($user->username == null) ? "server" : $user->username;

            $result_arr[] = [
                'id' => $user->user_id,
                'text' => !is_null($user->user) ? $user->user->username : 'server',
                'children' => $children,
                'type' => $type,
                'file' => 'treedata',
                'icon' => $icon,
                "state" => ["opened" => false]
            ];
        }

        return $result_arr;
    }

    public static function getTree($root = true, $sponsor = "", $treedata = [], $level = 0)
    {
        if($level == 3)
        {
            return false ;
        }
        if ($root)
        {
            $data = self::with('user')->where('user_id', $sponsor)->get();
        }
        else
        {
            $data = self::with('user')->where('sponsor_id', $sponsor)
                ->where('type', '!=', 'vaccant')
                ->orderBy('position', 'asc')
                ->get();
        }

        $currentuserid = auth()->user()->id;
        $treearray = [];


        foreach ($data as $value)
        {
            if($value->type == "yes" || $value->type == "no" )
            {
                $push = self::getTree(false, $value->user_id, $treearray, $level + 1);

                $leg = '';
                if ($value->user && $value->user->businessCenters->count() > 0)
                {
                    $leg = TreeTable::where('business_center_id', $value->user->businessCenters[0]->id)->value('leg');
                }
                if ($root)
                {
                    $class = 'up';
                    $usertype = 'root';
                }
                else
                {
                    $class='down';
                    $usertype = 'child';
                }

                $qualified_status = $value->user && $value->user->rank ? $value->user->rank->rank_status : "";
                $user_type = $value->user ? $value->user->usertype : 'rc';
                $power_qualified = $value->user && $value->user->power_qualified ? 'Yes' : 'No';
                $binary_qualified = $value->user && $value->user->binary_qualified ? 'Yes' : 'No';
                $content = $value->user ? '<img class="" style="max-width:50px;cursor:pointer;" id="'.$value->user_id.'" upper-id="'.$value->sponsor_id.'" src="'.(file_exists(public_path('/user_images/'.$value->user->image)) ? '/user_images/'.$value->user->image : '/avatar-big.png').'"><br/>': '';

                $dots = [];
                if($qualified_status == "Member")
                {

                    $dots[] = '<i class="far fa-circle mr-2"></i>';
                }
                if($qualified_status == "Basic")
                {

                    $dots[] = '<i class="far fa-circle mr-2" style="color: #47bcd4;"></i>';
                }
                if($qualified_status == "Entrepreneur")
                {

                    $dots[] = '<i class="far fa-circle mr-2"></i>';
                }
                if($qualified_status == "Platinum")
                {

                    $dots[] = '<i class="far fa-circle mr-2" style="color: #FFD700;"></i>';
                }
                if($qualified_status == "Diamond")
                {

                    $dots[] = '<i class="far fa-circle mr-2" style="color: black;"></i>';
                }
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
                if($value->user->matchingbonus_percentage == 50)
                {

                    $dots[] = '<i class="fa fa-circle mr-2" style="color:#DAA520;"></i>';
                }

                $personal_qv = OrderProduct::whereHas('order', function ($q) use ($value) {
                    $q->where('user_id', $value->user_id)->where('order_status_id', 4);
                })->sum('qv');

                $customer_qv = OrderProduct::whereHas('order', function ($q) use ($value) {
                    $q->where('sponsor_id', $value->user_id)->where('order_status_id', 4)->whereHas('user', function ($q) {
                        $q->where('usertype', '!=', 'dc');
                    });
                })->sum('qv');

                $row = [
                    'name' => $value->user ? $value->user->username : '',
                    'full_name' => $value->user ? $value->user->name : '',
                    'qs' => $value->user ? $value->user->qualified_status : 0,
                    'pqv' => $personal_qv + $customer_qv,
                    'nodeContentPro' => $content,
                    'className' => 'active',
                    'type' => $value->type,
                    'leg' => $leg,
                    'dots' => $dots,
                    'image' => $value->user->image,
                    'active' => $value->user->active_status,
                    'user_id' => $value->user_id
                ];
                $row['children'] = $push;
            }
            else
            {
                $row = [
                    'name' => 'Add Here',
                    'nodeContentPro' => '<h1 class="text-center"><i class="fa fa-plus-circle"></i></h1>',
                    'type' => $value->type,
                    'leg' => '',
                    'dots' => [],
                ];
            }
            $treearray[] = $row;
        }

        $treedata = $treearray;

        return $treedata;
    }
}
