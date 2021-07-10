<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Order;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class OrderController extends Controller
{
    public function userOrders(Request $request)
    {
        try
        {
            $orders = Order::with('user', 'orderProducts', 'orderStatus')->where(function ($q) use ($request) {
                if ($request->key == 1)
                {
                    $q->where('user_id', auth()->id());
                }
                elseif ($request->key == 2)
                {
                    $q->where('sponsor_id', auth()->id())->whereHas('user', function ($q) {
                        $q->where('usertype', 'dc');
                    });
                }
                elseif ($request->key == 3)
                {
                    $q->where('sponsor_id', auth()->id())->whereHas('user', function ($q) {
                        $q->where('usertype', 'pc');
                    });
                }
                elseif ($request->key == 4)
                {
                    $q->where('sponsor_id', auth()->id())->whereHas('user', function ($q) {
                        $q->where('usertype', 'rc');
                    });
                }
                elseif ($request->key == 5)
                {
                    $q->where('user_id', auth()->id())->where('is_autoship', 1);
                }
            })->whereIn('order_status_id', [1, 4, 5])->orderBy($request->column == '' ? 'id' : $request->column, $request->order == 'ascend' ? 'asc' : 'desc')->paginate(10);

            return response()->json(['status' => 200, 'data' => $orders]);
        }
        catch (Exception $exception)
        {
            return response()->json(['status' => $exception->getCode(), 'message' => $exception->getMessage()]);
        }
    }

    public function ordersByUserId($user_id)
    {
        try
        {
            $orders = Order::with('user', 'orderProducts', 'orderStatus')->where('user_id', $user_id)->whereIn('order_status_id', [1, 4, 5])->get();

            return response()->json(['status' => 200, 'data' => $orders]);
        }
        catch (Exception $exception)
        {
            return response()->json(['status' => $exception->getCode(), 'message' => $exception->getMessage()]);
        }
    }
}
