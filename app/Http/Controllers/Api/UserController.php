<?php

namespace App\Http\Controllers\Api;

use App\Address;
use App\BusinessCenter;
use App\Cart;
use App\CartProduct;
use App\Order;
use App\OrderProduct;
use App\Product;
use App\TreeTable;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function show($id)
    {
        try
        {
            $user = User::with('sponsor', 'addresses', 'carts', 'country', 'state')->find($id);
            return response()->json(['status_code' => 200, 'user' => $user, 'message' => 'Success', 'result' => true]);
        }
        catch (\Exception $error)
        {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error in Login',
                'error' => $error,
                'result' => false
            ]);
        }
    }

    public function store(Request $request)
    {

    }

    public function update(Request $request, $id)
    {
        $user_data = $request->user;
        $address_data = $request->address;
        $address_data['user_id'] = $id;
        $address_data['contact_name'] = $user_data['firstname'] . ' ' . $user_data['lastname'];
        $address_data['contact_number'] = $user_data['phone'];
        $address_data['address1'] = $address_data['address_1'];
        $address_data['address2'] = $address_data['address_2'];
        $user_data = $user_data + Arr::except($address_data, ['id']);
        $user_data['language_id'] = $request->language;
        $user_data['image'] = 'avatar-big.png';
        $user_data['dateofbirth'] = Carbon::createFromFormat('D M d Y', explode(' 00:00:00', $request->user['dateofbirth'])[0])->toDateString();

        if ($request->has('password') && !preg_match('/^\$2y\$/', $request->user['password']))
        {
            $user_data['password'] = Hash::make($request->user['password']);
        }
        if ((int)$user_data['sponsor_id'] === 0)
        {
            $user_data['sponsor_id'] = User::where('username', $user_data['sponsor_id'])->first()->id;
        }

        User::find($id)->update($user_data);
        $data['user'] = User::find($id);

        if ($address_data['id'] > 0)
        {
            Address::find($address_data['id'])->update($address_data);
            $data['address'] = Address::with('state', 'country')->find($address_data['id']);
        }
        else
        {
            $data['address'] = Address::create($address_data);
            $data['address'] = Address::with('state', 'country')->find($data['address']['id']);
        }
        $data['cart'] = Cart::where('user_id', $id)->first();
        if (is_null($data['cart']))
        {
            $data['cart'] = Cart::create(['user_id' => $id]);
        }
        $total_orders = Order::where('user_id', $id)->count();
        if (in_array($data['user']['usertype'], ['dc', 'pc']) && $total_orders == 0)
        {
            if ($data['user']['usertype'] == 'dc')
            {
                $product = Product::find(83);
                CartProduct::updateOrCreate(['cart_id' => $data['cart']['id'], 'product_id' => $product->id], ['quantity' => 1]);
            }
            else
            {
                $product = Product::find(84);
                CartProduct::updateOrCreate(['cart_id' => $data['cart']['id'], 'product_id' => $product->id], ['quantity' => 1]);
            }
        }

        if (!is_null($request->shipping_address))
        {
            $shipping_address_data = $request->shipping_address;
            $shipping_address_data['user_id'] = $id;
            if ($shipping_address_data['id'] > 0)
            {
                Address::find($shipping_address_data['id'])->update($shipping_address_data);
                $data['shipping_address'] = Address::with('state', 'country')->find($shipping_address_data['id']);
            }
            else
            {
                $data['shipping_address'] = Address::create($shipping_address_data);
                $data['shipping_address'] = Address::with('state', 'country')->find($data['shipping_address']['id']);
            }
        }
        $data['user'] = User::with('sponsor', 'state', 'country')->find($id);

        return response()->json(['data' => $data, 'status' => 200]);
    }

    public function sponsor($id)
    {
        $user = User::find($id);

        return response()->json(['data' => $user]);
    }

    public function updateProfile(Request $request, $id)
    {
        $data = $request->except('image');

        if ($request->hasFile('image'))
        {
            $image = $request->file('image');
            $name = $request->image->getClientOriginalName().time().'.'.$request->image->getClientOriginalExtension();
            $request->image->move('user_images/', $name);
            $data['image'] = $name;
        }
        elseif ($request->has('image_removed'))
        {
            $data['image'] = null;
        }

        $user = User::find($id);
        $user->update($data);

        return response()->json(['data' => $user, 'status' => 200]);
    }

    public function enroll($sponsor_id, $placement_user_id, $business_center_id, $leg)
    {
        $sponsor = User::find($sponsor_id);
        $business_center = BusinessCenter::find($business_center_id);

        $placement_info = self::validatePlacement($sponsor_id, $placement_user_id);

        $data = [
            'sponsor' => $sponsor,
            'business_center' => $business_center,
            'placement_info' => $placement_info,
            'leg' => $leg
        ];

        return response()->json(['data' => $data, 'status' => 200]);
    }

    public function validatePlacement($sponsor_id, $placement_user_id)
    {
        $placement_business_center_id = BusinessCenter::where('user_id',$placement_user_id)->value('id');
        $sponsor_business_center_id = BusinessCenter::where('user_id',$sponsor_id)->value('id');

        TreeTable::$downline_id_list = [];
        TreeTable::getAllDownlinesAutocomplete([$sponsor_business_center_id]);
        $downline_id_list = TreeTable::$downline_id_list;

        if($sponsor_id == $placement_user_id)
        {
            $user = User::find($placement_user_id);
            return $user;
        }
        elseif(in_array($placement_business_center_id, $downline_id_list))
        {
            $user = User::find($placement_user_id);
            return $user;
        }
    }

    public function checkUser(Request $request)
    {
        $requestKeys = collect($request->all())->keys();
        $user = User::with('sponsor')->where($requestKeys[0], $request->get($requestKeys[0]))->where(function ($q) {
            if (auth('sanctum')->check() && !is_null(auth('sanctum')->user()->username))
            {
                $q->where('id', '!=', auth('sanctum')->id());
            }
        })->first();

        if ($request->has('reset_password') && !is_null($user))
        {
        //    print_r("localhost:3001/resetPassword/".base64_encode(json_encode($user)));
            Mail::send('emails.password-reset', ['user' => $user], function ($m) use ($user) {
                $m->to($user->email, $user->name)->subject('Reset Your Password');
            });
        }

        return response()->json(['data' => $user, 'status' => 200]);
    }

    public function checkSponsor($id)
    {
        $user = User::with('businessCenters')->whereHas('businessCenters')->where('usertype', 'dc')->where(function ($q) use ($id) {
            if ((int)$id > 0)
            {
                $q->where('id', $id);
            }
            else
            {
                $q->where('username', $id);
            }
        })->first();

        return response()->json(['data' => $user, 'status' => 200]);
    }

    public function getPlacement(Request $request, $id)
    {
//        $user = User::where(function ($q) use ($id) {
//            if ((int)$id > 0)
//            {
//                $q->where('id', $id);
//            }
//            else
//            {
//                $q->where('username', $id);
//            }
//        })->first();
//        $data = BusinessCenter::where('user_id', $user->id)->get();

        $term = $id;

        if ((int)$request->sponsor_id > 0)
        {
            $sponsor_id = $request->sponsor_id;
        }
        else
        {
            $sponsor_id = User::where('username', $request->sponsor_id)->value('id');
        }

        $business_center_ids = BusinessCenter::where('user_id', $sponsor_id)->pluck('id')->toArray();
        // return $business_center_ids;
        TreeTable::$downline_id_list = [];
        TreeTable::getAllDownlinesAutocomplete([BusinessCenter::where('user_id', $sponsor_id)->value('id')]);

//        return array_merge(TreeTable::$downline_id_list, (array)$business_center_ids);
        $users = User::with('businessCenters')->whereHas('businessCenters', function ($q) use ($term, $business_center_ids) {
            $q->whereIn('id', array_merge(TreeTable::$downline_id_list, $business_center_ids));
        })->where(function ($q) use ($request, $term) {
            if (!empty($term))
            {
                $q->where('username', $term);
            }
        })->latest()->get();
        if (count($users) > 0)
        {
            $data = BusinessCenter::where('user_id', $users[0]->id)->get();
        }
        else
        {
            $data = [];
        }
        return response()->json(['data' => $data, 'status' => 200]);
    }

    public function getPlacementInfo($id)
    {
        $user = User::find($id);
        $business_center = BusinessCenter::where('user_id', $user->id)->first();
        $tree_table = TreeTable::where('business_center_id', $business_center->id)->first();

        return response()->json(['data' => $tree_table, 'status' => 200]);
    }

    public function getBusinessCenter($id)
    {
        $business_center = BusinessCenter::with('user')->find($id);

        return response()->json(['data' => $business_center, 'status' => 200]);
    }

    public function autocomplete(Request $request)
    {
        $term = $request->get('q');

        TreeTable::$downline_id_list = [];
        TreeTable::getAllDownlinesAutocomplete([BusinessCenter::where('user_id', $request->id)->value('id')]);

        $users = User::with('businessCenters')->whereHas('businessCenters', function ($q) use ($term) {
            $q->whereIn('id', TreeTable::$downline_id_list);
        })->where(function ($q) use ($request, $term) {
            if (!empty($term))
            {
                $q->where('username', 'LIKE', $term.'%');
            }
        })->where('id', '!=', $request->id)->latest()->get();

        if (isset($request->name) && !empty($request->name))
        {
            $users = array_values($users->where('name', $request->name)->all());
        }

        if (isset($request->validate_placement))
        {
            if ($request->id == User::where('username', $term)->value('id'))
            {
                $users = User::with('businessCenters')->where('id', $request->id)->get();
            }
        }

        return response($users);
    }

    public function waitingRoomUsers($user_id)
    {
        $waiting_room_users = User::with('state', 'country', 'users')->whereHas('orders', function ($q) {
            $q->where('order_status_id', 4);
        })->where('sponsor_id', $user_id)->where('user_state', 'waiting')->get();

        foreach ($waiting_room_users as $waiting_room_user)
        {
            $waiting_room_user['personal_qv'] = OrderProduct::whereHas('order', function ($q) use ($waiting_room_user) {
                $q->where('user_id', $waiting_room_user->id)
                  ->where('order_status_id', 4);
            })->sum('qv');
            $waiting_room_user['customer_qv'] = OrderProduct::whereHas('order', function ($q) use ($waiting_room_user) {
                $q->where('sponsor_id', $waiting_room_user->id)
                  ->where('order_status_id', 4);
            })->sum('qv');
            $waiting_room_user['personal_bv'] = OrderProduct::whereHas('order', function ($q) use ($waiting_room_user) {
                $q->where('user_id', $waiting_room_user->id)
                  ->where('order_status_id', 4);
            })->sum('bv');
            $waiting_room_user['customer_bv'] = OrderProduct::whereHas('order', function ($q) use ($waiting_room_user) {
                $q->where('sponsor_id', $waiting_room_user->id)
                  ->where('order_status_id', 4);
            })->sum('bv');
        }

        return response()->json(['users' => $waiting_room_users]);
    }

    public function placeWaitingRoom(Request $request)
    {
        $user = User::find($request->user_id);
        $business_center = BusinessCenter::where('user_id', $user->id)->first();

        $orders = Order::where('user_id', $user->id)->where('order_status_id', 4)->take(1)->get();

//        try
//        {
            foreach($orders as $order)
            {
//                DB::beginTransaction();
                if($user->user_state == 'waiting')
                {
                    $BV = ($order->orderProducts->sum('qv') > 0) ? $order->orderProducts->sum('qv') / 2 : 0;
                    $placement_id = $request->business_center_id;

                    if ($request->placement_side == 'auto')
                    {
                        $user_table_id = $business_center->user_id;
//                        $user_table_id = BusinessCenter::where('id', $placement_id)->value('user_id');
                        $placementSide = User::where('id', $user_table_id)->value('leg');
                    }
                    else
                    {
                        $placementSide = $request->placement_side;
                    }

                    if ($placementSide == 'default')
                    {
                        $left_point = BusinessCenter::where('user_id', $user->sponsor_id)->value('left_carry');
                        $right_point = BusinessCenter::where('user_id', $user->sponsor_id)->value('right_carry');

                        if ($left_point > $right_point)
                        {
                            $placementSide = 'R';
                        }
                        else
                        {
                            $placementSide = 'L';
                        }
                    }

                    $placement_id = TreeTable::getPlacementId($placement_id, $placementSide);
                    $tree_id = TreeTable::where('placement_id', $placement_id)->where("leg", $placementSide)->where("type", "=", "vaccant")->value('id');

                    $tree = TreeTable::find($tree_id);
                    $tree->business_center_id = $business_center->id;
                    $tree->sponsor_id = $user->sponsor_id;
                    $tree->placement_id = $placement_id;
                    $tree->leg = $placementSide;
                    $tree->type = 'yes';
                    $tree->save();

                    TreeTable::create(['sponsor_id' => 0, 'business_center_id' => '0', 'placement_id' => $business_center->id, 'leg' => 'L', 'type' => 'vaccant']);
                    TreeTable::create(['sponsor_id' => 0, 'business_center_id' => '0', 'placement_id' => $business_center->id, 'leg' => 'R', 'type' => 'vaccant',]);
                    BusinessCenter::updatePoint($BV, $order->orderProducts->sum('qv'), $business_center->id, "binary", 0);

                    //
                    if ($order->orderProducts->sum('qv') >= 100)
                    {
                        TreeTable::$upline_users = [];
                        TreeTable::getAllUpline($business_center->id);
                        $upline_users = TreeTable::$upline_users;
                        foreach ($upline_users as $key => $upline)
                        {
                            $user_table_id = BusinessCenter::where('id', $upline['user_id'])->value('user_id');
                            $reentry_check = BusinessCenter::where('id', '<', $upline['user_id'])->where('user_id', $user_table_id)->count();
                            if ($user_table_id == $user->sponsor_id && $reentry_check == 0)
                            {
                                if ($upline['leg'] == 'L')
                                {
                                    User::where('id', $user->sponsor_id)->increment('binary_left');
                                }
                                else if ($upline['leg'] == 'R')
                                {
                                    User::where('id', $user->sponsor_id)->increment('binary_right');
                                }
                            }
                        }
                        TreeTable::$upline_users = [];
                    }
                    /*BINARY QUALIFIED DISTRIBUTORSHIP STATUS*/
                    $sponsor_info = User::find($user->sponsor_id);
                    if ($sponsor_info->binary_left >= 1 && $sponsor_info->binary_right >= 1)
                    {
                        $binary_qualified = User::where('id', $user->sponsor_id)->update(['binary_qualified' => 1]);
                    }
                    /*BINARY QUALIFIED DISTRIBUTORSHIP STATUS END*/
                    User::find($user->id)->update(['user_state' => 'success']);
                }
                else
                {
                    $check_order = OrderProduct::whereHas('order', function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    })->where('qv', '>', 0)->count();
                    $first_order  = ($check_order == 0 && $order->orderProducts->sum('qv') > 0) ? 1 : 0;
                    if($first_order == 1)
                    {
                        $BV = $order->orderProducts->sum('qv') / 2;
                    }
                    else
                    {
                        $BV = $order->orderProducts->sum('qv');
                    }
                    TreeTable::$upline_users = [];
                    TreeTable::getAllUpline($business_center->id);
                    $upline_users = TreeTable::$upline_users;

                    foreach ($upline_users as $key => $upline)
                    {
                        $user_table_id = BusinessCenter::where('id', $upline['user_id'])->value('user_id');
                        $reentry_check = BusinessCenter::where('id', '<', $upline['user_id'])->where('user_id', $user_table_id)->count();
                        if($user_table_id == $user->sponsor_id && $reentry_check == 0)
                        {
                            if ($upline['leg'] == 'L')
                            {
                                User::where('id', $user->sponsor_id)->increment('binary_left');
                            }
                            else if ($upline['leg'] == 'R')
                            {
                                User::where('id', $user->sponsor_id)->increment('binary_right');
                            }
                        }
                    }

                    TreeTable::$upline_users = [];
                    $sponsor_act = BusinessCenter::where('user_id', $user->sponsor_id)->value('id');
                    BusinessCenter::updatePoint($BV, $order->orderProducts->sum('qv'), $business_center->id, "binary", 0);
                }
                Order::where('id', $order->id)->update(['order_status_id' => 4]);
//                DB::commit();
            }
            return 1;
//        }
//        catch (\Exception $e)
//        {
//            DB::rollback();
//            $error1 = $e->getMessage();
//            $line_number = $e->getLine();
//            return $e->getMessage().' line-'.$line_number;
//        }
    }
}
