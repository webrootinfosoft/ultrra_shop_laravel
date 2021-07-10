<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\ErrorLog;
use App\ActivityLog;
use App\CartProduct;
use App\Commission;
use App\EwalletTransaction;
use App\SponsorTree;
use App\State;
use App\TreeTable;
use App\BusinessCenter;
use App\Country;
use App\OrderStatus;
use App\ShippingStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\CreditCard;
use App\Address;
use App\User;
use App\Product;
use App\Cart;
use App\Order;
use App\OrderProduct;
use App\OrderProductComponent;
use App\OrderPaymentMethod;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;
use Yajra\DataTables\DataTables;
use App\Jobs\OrderJob;
set_time_limit(3000);
class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax())
        {
            $order = Order::with(['user.sponsor', 'sponsor', 'paymentMethods', 'shippingState', 'shippingCountry', 'orderProducts.product', 'orderStatus'])->where(function ($q) use($request) {
                if (isset($request->id) && $request->id > 0)
                {
                    $q->where('id', $request->id);
                }
                if (isset($request->usertype) && $request->usertype != '0')
                {
                    $q->whereHas('user', function ($q) use ($request) {
                        $q->where('usertype', $request->usertype);
                    });
                }
                if (isset($request->country_id) && count($request->country_id) > 0)
                {
                    $q->whereIn('shipping_country_id', $request->country_id);
                }
                if (isset($request->distributor_id) && !empty($request->distributor_id))
                {
                    $q->where('user_id', $request->distributor_id)->orWhereHas('user', function ($q) use ($request) {
                        $q->where('username', $request->distributor_id);
                    });
                }
                if (isset($request->sponsor_id) && !empty($request->sponsor_id))
                {
                    $q->whereHas('user', function ($q) use ($request) {
                        $q->where('sponsor_id', $request->sponsor_id)->orWhereHas('sponsor', function ($q) use ($request) {
                            $q->where('username', $request->sponsor_id);
                        });
                    });
                }
                if (isset($request->order_number) && !empty($request->order_number))
                {
                    $q->where('id', $request->order_number);
                }
                if (isset($request->order_status_id) && $request->order_status_id > -1)
                {
                    $q->where('order_status_id', $request->order_status_id);
                }
                if (isset($request->product_name) && !empty($request->product_name))
                {
                    $q->whereHas('orderProducts.product', function ($q) use ($request) {
                        $q->where('name', $request->product_name);
                    });
                }
                if (isset($request->last_name) && !empty($request->last_name))
                {
                    $q->where('lastname', $request->last_name);
                }
                if (isset($request->first_name) && !empty($request->first_name))
                {
                    $q->where('firstname', $request->first_name);
                }
                if (isset($request->shipping_last_name) && !empty($request->shipping_last_name))
                {
                    $q->where('shipping_lastname', $request->shipping_last_name);
                }
                if (isset($request->shipping_first_name) && !empty($request->shipping_first_name))
                {
                    $q->where('shipping_firstname', $request->shipping_first_name);
                }
                if (isset($request->shipping_status) && $request->shipping_status > -1)
                {
                    $q->where('shipping_status_id', $request->shipping_status);
                }
                if (isset($request->shipping_method) && $request->shipping_method > -1)
                {
                    $q->where('shipping_method', $request->shipping_method);
                }
                if (isset($request->sku) && !empty($request->sku))
                {
                    $q->whereHas('orderProducts.product', function ($query) use ($request) {
                        $query->where('sku', $request->sku);
                    });
                }
                if (isset($request->email) && !empty($request->email))
                {
                    $q->where('email', $request->email);
                }
                if (isset($request->payment_method) && $request->payment_method != -1)
                {
                    $q->whereHas('paymentMethods', function ($query) use ($request) {
                        $query->where('payment_method', $request->payment_method);
                    });
                }
                if (isset($request->payment_status) && $request->payment_status != -1)
                {
                    $q->where('payment_status', $request->payment_status);
                }
                if (isset($request->start_date) && isset($request->end_date) && strtotime($request->start_date) && strtotime($request->end_date))
                {
                    $q->where('created_at', '>=', $request->start_date.' 00:00:00')->where('created_at', '<=', $request->end_date.' 23:59:59');
                }
                if (isset($request->order_type) && $request->order_type != -1)
                {
                    if ($request->order_type == 'autoship')
                    {
                        $q->where('is_autoship', 1);
                    }
                    elseif ($request->order_type == 'membership')
                    {
                        $q->whereHas('orderProducts', function ($q) use ($request) {
                            $q->whereIn('product_id', [80, 83, 84]);
                        });
                    }
                    elseif ($request->order_type == 'regular')
                    {
                        $q->whereHas('orderProducts.product', function ($q) use ($request) {
                            $q->whereNotIn('id', [80, 83, 84]);
                        })->where('is_autoship', 0);
                    }
                }
                if (isset($request->card_number) && !empty($request->card_number))
                {
                    $q->whereHas('orderPaymentMethods', function ($q) use ($request) {
                        $q->where('payment_method', 'credit_card')->where('card_number', $request->card_number);
                    });
                }
            });

            return DataTables::of($order)
                ->addColumn('user_username', function ($row) {
                    $username = $row->user ? $row->user->username : '';
                    return '<a href="/users/profile/'.$row->user_id.'" target="_blank">'.$username.'<br>('.$row->user_id.')</a>';
                })
                ->addColumn('user_name', function ($row) {
                    return wordwrap(ucwords(strtolower(trim($row->firstname . ' ' . $row->lastname))), 17, '<br>');
                })
                ->addColumn('user_email', function ($row) {
                    return $row->email;
                })
                ->addColumn('sponsor_username', function ($row) {
                    $username = $row->sponsor ? $row->sponsor->username : '';
                    return '<a href="/users/profile/'.$row->sponsor_id.'" target="_blank">'.$username.'<br>('.$row->sponsor_id.')</a>';
                })
                ->addColumn('sponsor_name', function ($row) {
                    return $row->sponsor ? wordwrap(ucwords(strtolower(trim($row->sponsor->firstname.' '.$row->sponsor->lastname))), 17, '<br>') : '';
                })
                ->addColumn('payment_date', function ($row) {
                    return Carbon::createFromFormat('Y-m-d H:i:s', count($row->orderPaymentMethods) > 0 ? $row->orderPaymentMethods[0]->created_at : date('Y-m-d H:i:s'))->format('m/d/Y');
//                    return Carbon::createFromFormat('Y-m-d H:i:s', $row->updated_at)->format('m/d/Y');
                })
                ->addColumn('shipping_status', function ($row) {
                    return $row->shippingStatus ? $row->shippingStatus->name : '';
                })
                ->addColumn('shipping_name', function ($row) {
                    return wordwrap(ucwords(strtolower(trim($row->shipping_firstname.' '.$row->shipping_lastname))), 17, '<br>');
                })
                ->addColumn('shipping_address', function ($row) {
                    $shipping_state = $row->shippingState ? $row->shippingState->name : '';
                    return $row->shipping_address_1.', '.$row->shipping_address_2.'<br>'.$row->shipping_city.', '.$shipping_state.', '.$row->shipping_postcode;
                })
                ->addColumn('shipping_country', function ($row) {
                    return $row->shippingCountry ? $row->shippingCountry->name : '';
                })
                ->addColumn('role', function ($row) {
                    return !is_null($row->user) ? '<b>'.$row->user->usertype.'</b>' : '';
                })
                ->addColumn('first_order', function ($row) {
                    return $row->is_first_order ? '<i class="fas fa-check text-success"></i>' : '';
                })
                ->addColumn('payment_method', function ($row) {
                    $payment_methods = $row->paymentMethods->map(function ($payment_method) {
                        if ($payment_method->payment_method == 'credit_card')
                        {
                            return 'CC('.substr($payment_method->card_number, -4).')';
                        }
                        elseif ($payment_method->payment_method == 'ewallet')
                        {
                            return 'EW';
                        }
                        elseif ($payment_method->payment_method == 'cash_on_delivery' || $payment_method->payment_method == 'cod')
                        {
                            return 'Cash';
                        }
                    });
                    return '<div style="display: flex"><span class="text-capitalize" style="float: left;">'.$row->total.'<br>'.implode(', ', json_decode($payment_methods)).'</span></div></div>';
                })
                ->addColumn('placement_username', function ($row) {
                    if ($row->user && $row->user->businessCenters->count() > 0)
                    {
                        $tree_table = TreeTable::where('business_center_id', $row->user->businessCenters[0]->id)->first();
                        if ($tree_table)
                        {
                            $placement_user = BusinessCenter::find($tree_table->placement_id);
                            return $placement_user ? $placement_user->user->username.'('.$tree_table->leg.')' : '';
                        }
                        elseif ($row->user->user_state == 'waiting')
                        {
                            return 'WR';
                        }
                        else
                        {
                            return '';
                        }
                    }
                    else
                    {
                        return '';
                    }
                })
                ->addColumn('qv', function ($row) {
                    return $row->orderProducts->sum('qv');
                })
                ->addColumn('bv', function ($row) {
                    return $row->orderProducts->sum('bv');
                })
                ->addColumn('status', function ($row) {
                    return $row->orderStatus ? $row->orderStatus->name : '';
                })
                ->addColumn('actions', function ($row) {
//                    return '<a href="'.url('/invoice/'.$row->id).'" target="_blank" class="btn btn-sm '.($row->is_printed == 1 ? 'btn-light' : 'btn-primary').'" onclick="printOrder('.$row->id.', this);">&nbsp;<i class="fa fa-print fa-sm"></i>&nbsp;</a><a href="'.url('/invoice/'.$row->id).'" target="_blank" class="btn btn-sm btn-info">&nbsp;<i class="fa fa-eye fa-sm"></i>&nbsp;</a><a href="#" class="btn btn-sm btn-danger" onclick="cancelOrder('.$row->id.')">&nbsp;<i class="fa fa-times fa-sm"></i>&nbsp;</a>';
                    return '<a href="'.url('/invoice/'.$row->id).'" target="_blank" class="btn btn-sm '.($row->is_printed == 1 ? 'btn-light' : 'btn-primary').'" onclick="printOrder('.$row->id.', this);">&nbsp;<i class="fa fa-print fa-sm"></i>&nbsp;</a>&nbsp;&nbsp;<a href="#" class="btn btn-sm btn-danger" onclick="cancelOrder('.$row->id.')">&nbsp;<i class="fa fa-times fa-sm"></i>&nbsp;</a>';
                })
                ->addColumn('save', function ($row) {
                    return '<a href="javascript:void(0)" class="btn btn-sm btn-primary" onclick="saveOrder('.$row->id.')">&nbsp;<i class="fa fa-save fa-sm"></i>&nbsp;</a>';
                })
                ->editColumn('id', function ($row) {
                    return '<a href="'.url('/invoice/'.$row->id).'" target="_blank">'.$row->id.'</a>';
                })
                ->editColumn('shipping_method', function ($row) {
                    return wordwrap($row->shipping_method, 16, '<br>');
                })
                ->editColumn('track_link', function ($row) {
                    return '<input class="form-control" id="track-link'.$row->id.'" value="'.$row->track_link.'" style="width: 112.5px">';
                })
                ->editColumn('track_info', function ($row) {
                    return '<input class="form-control" id="track-info'.$row->id.'" value="'.$row->track_info.'" style="width: 112.5px">';
                })
                ->editColumn('created_at', function ($row) {
                    return Carbon::createFromFormat('Y-m-d H:i:s', $row->created_at)->format('m/d/Y').'<br>'.Carbon::createFromFormat('Y-m-d H:i:s', $row->created_at)->format('h:i A');
                })
                ->editColumn('payment_status', function ($row) {
                    return $row->payment_status;
                })
                ->setRowClass(function ($row) {
                    return in_array($row->order_status_id, [5, 6, 7]) ? 'red' : ($row->order_status_id == 1 ? 'blue' : '');
                })
                ->escapeColumns([])
                ->make(true);
        }

        $countries = Country::where('status', 1)->get();
        $order_statuses = OrderStatus::all();
        $shipping_statuses = ShippingStatus::all();
        return view('orders.index', compact('countries', 'order_statuses', 'shipping_statuses'));
    }

    public function indexNew(Request $request)
    {
        if ($request->ajax())
        {
            $order = Order::with(['user.sponsor', 'sponsor', 'paymentMethods', 'shippingState', 'shippingCountry', 'orderProducts.product', 'orderStatus'])->where(function ($q) use($request) {
                if (isset($request->id) && $request->id > 0)
                {
                    $q->where('id', $request->id);
                }
                if (isset($request->usertype) && $request->usertype != '0')
                {
                    $q->whereHas('user', function ($q) use ($request) {
                        $q->where('usertype', $request->usertype);
                    });
                }
                if (isset($request->country_id) && count($request->country_id) > 0)
                {
                    $q->whereIn('shipping_country_id', $request->country_id);
                }
                if (isset($request->distributor_id) && !empty($request->distributor_id))
                {
                    $q->where('user_id', $request->distributor_id)->orWhereHas('user', function ($q) use ($request) {
                        $q->where('username', $request->distributor_id);
                    });
                }
                if (isset($request->sponsor_id) && !empty($request->sponsor_id))
                {
                    $q->whereHas('user', function ($q) use ($request) {
                        $q->where('sponsor_id', $request->sponsor_id)->orWhereHas('sponsor', function ($q) use ($request) {
                            $q->where('username', $request->sponsor_id);
                        });
                    });
                }
                if (isset($request->order_number) && !empty($request->order_number))
                {
                    $q->where('id', $request->order_number);
                }
                if (isset($request->order_status_id) && $request->order_status_id > -1)
                {
                    $q->where('order_status_id', $request->order_status_id);
                }
                if (isset($request->product_name) && !empty($request->product_name))
                {
                    $q->whereHas('orderProducts.product', function ($q) use ($request) {
                        $q->where('name', $request->product_name);
                    });
                }
                if (isset($request->last_name) && !empty($request->last_name))
                {
                    $q->where('lastname', $request->last_name);
                }
                if (isset($request->first_name) && !empty($request->first_name))
                {
                    $q->where('firstname', $request->first_name);
                }
                if (isset($request->shipping_last_name) && !empty($request->shipping_last_name))
                {
                    $q->where('shipping_lastname', $request->shipping_last_name);
                }
                if (isset($request->shipping_first_name) && !empty($request->shipping_first_name))
                {
                    $q->where('shipping_firstname', $request->shipping_first_name);
                }
                if (isset($request->shipping_status) && $request->shipping_status > -1)
                {
                    $q->where('shipping_status_id', $request->shipping_status);
                }
                if (isset($request->shipping_method) && $request->shipping_method > -1)
                {
                    $q->where('shipping_method', $request->shipping_method);
                }
                if (isset($request->sku) && !empty($request->sku))
                {
                    $q->whereHas('orderProducts.product', function ($query) use ($request) {
                        $query->where('sku', $request->sku);
                    });
                }
                if (isset($request->email) && !empty($request->email))
                {
                    $q->where('email', $request->email);
                }
                if (isset($request->payment_method) && $request->payment_method != -1)
                {
                    $q->whereHas('paymentMethods', function ($query) use ($request) {
                        $query->where('payment_method', $request->payment_method);
                    });
                }
                if (isset($request->payment_status) && $request->payment_status != -1)
                {
                    $q->where('payment_status', $request->payment_status);
                }
                if (isset($request->start_date) && isset($request->end_date) && strtotime($request->start_date) && strtotime($request->end_date))
                {
                    $q->where('created_at', '>=', $request->start_date.' 00:00:00')->where('created_at', '<=', $request->end_date.' 23:59:59');
                }
                if (isset($request->order_type) && $request->order_type != -1)
                {
                    if ($request->order_type == 'autoship')
                    {
                        $q->where('is_autoship', 1);
                    }
                    elseif ($request->order_type == 'membership')
                    {
                        $q->whereHas('orderProducts', function ($q) use ($request) {
                            $q->whereIn('product_id', [80, 83, 84]);
                        });
                    }
                    elseif ($request->order_type == 'regular')
                    {
                        $q->whereHas('orderProducts.product', function ($q) use ($request) {
                            $q->whereNotIn('id', [80, 83, 84]);
                        })->where('is_autoship', 0);
                    }
                }
                if (isset($request->card_number) && !empty($request->card_number))
                {
                    $q->whereHas('orderPaymentMethods', function ($q) use ($request) {
                        $q->where('payment_method', 'credit_card')->where('card_number', $request->card_number);
                    });
                }
            });

            return DataTables::of($order)
                ->addColumn('user_username', function ($row) {
                    $username = $row->user ? $row->user->username : (isset($row->meta->user) ? $row->meta->user->username : '');
                    return '<a href="'.($row->user_id ? '/users/profile/'.$row->user_id : 'javascript:void(0)').'" target="_blank">'.$username.'<br>'.($row->user_id ? '('.$row->user_id.')' : '').'</a>';
                })
                ->addColumn('user_name', function ($row) {
                    return wordwrap(ucwords(strtolower(trim($row->firstname . ' ' . $row->lastname))), 17, '<br>');
                })
                ->addColumn('user_email', function ($row) {
                    return $row->email;
                })
                ->addColumn('sponsor_username', function ($row) {
                    $username = $row->sponsor ? $row->sponsor->username : '';
                    return '<a href="/users/profile/'.$row->sponsor_id.'" target="_blank">'.$username.'<br>('.$row->sponsor_id.')</a>';
                })
                ->addColumn('sponsor_name', function ($row) {
                    return $row->sponsor ? wordwrap(ucwords(strtolower(trim($row->sponsor->firstname.' '.$row->sponsor->lastname))), 17, '<br>') : '';
                })
                ->addColumn('payment_date', function ($row) {
                    return Carbon::createFromFormat('Y-m-d H:i:s', count($row->orderPaymentMethods) > 0 ? $row->orderPaymentMethods[0]->created_at : date('Y-m-d H:i:s'))->format('m/d/Y');
//                    return Carbon::createFromFormat('Y-m-d H:i:s', $row->updated_at)->format('m/d/Y');
                })
                ->addColumn('shipping_status', function ($row) {
                    return $row->shippingStatus ? $row->shippingStatus->name : '';
                })
                ->addColumn('shipping_name', function ($row) {
                    return wordwrap(ucwords(strtolower(trim($row->shipping_firstname.' '.$row->shipping_lastname))), 17, '<br>');
                })
                ->addColumn('shipping_address', function ($row) {
                    $shipping_state = $row->shippingState ? $row->shippingState->name : '';
                    return $row->shipping_address_1.', '.$row->shipping_address_2.'<br>'.$row->shipping_city.', '.$shipping_state.', '.$row->shipping_postcode;
                })
                ->addColumn('shipping_country', function ($row) {
                    return $row->shippingCountry ? $row->shippingCountry->name : '';
                })
                ->addColumn('role', function ($row) {
                    return !is_null($row->user) ? '<b>'.$row->user->usertype.'</b>' : (isset($row->meta->user) ? '<b>'.$row->meta->user->usertype.'</b>' : '');
                })
                ->addColumn('first_order', function ($row) {
                    return $row->is_first_order ? '<i class="fas fa-check text-success"></i>' : '';
                })
                ->addColumn('payment_method', function ($row) {
                    $payment_methods = $row->paymentMethods->map(function ($payment_method) {
                        if ($payment_method->payment_method == 'credit_card')
                        {
                            return 'CC('.substr($payment_method->card_number, -4).')';
                        }
                        elseif ($payment_method->payment_method == 'ewallet')
                        {
                            return 'EW';
                        }
                        elseif ($payment_method->payment_method == 'cash_on_delivery' || $payment_method->payment_method == 'cod')
                        {
                            return 'Cash';
                        }
                    });
                    return '<div style="display: flex"><span class="text-capitalize" style="float: left;">'.$row->total.'<br>'.implode(', ', json_decode($payment_methods)).'</span></div></div>';
                })
                ->addColumn('placement_username', function ($row) {
                    if ($row->user && $row->user->businessCenters->count() > 0)
                    {
                        $tree_table = TreeTable::where('business_center_id', $row->user->businessCenters[0]->id)->first();
                        if ($tree_table)
                        {
                            $placement_user = BusinessCenter::find($tree_table->placement_id);
                            return $placement_user ? $placement_user->user->username.'('.$tree_table->leg.')' : '';
                        }
                        elseif ($row->user->user_state == 'waiting')
                        {
                            return 'WR';
                        }
                        else
                        {
                            return '';
                        }
                    }
                    else
                    {
                        return '';
                    }
                })
                ->addColumn('qv', function ($row) {
                    return $row->orderProducts->sum('qv');
                })
                ->addColumn('bv', function ($row) {
                    return $row->orderProducts->sum('bv');
                })
                ->addColumn('status', function ($row) {
                    return $row->orderStatus ? $row->orderStatus->name : '';
                })
                ->addColumn('actions', function ($row) {
//                    return '<a href="'.url('/invoice/'.$row->id).'" target="_blank" class="btn btn-sm '.($row->is_printed == 1 ? 'btn-light' : 'btn-primary').'" onclick="printOrder('.$row->id.', this);">&nbsp;<i class="fa fa-print fa-sm"></i>&nbsp;</a><a href="'.url('/invoice/'.$row->id).'" target="_blank" class="btn btn-sm btn-info">&nbsp;<i class="fa fa-eye fa-sm"></i>&nbsp;</a><a href="#" class="btn btn-sm btn-danger" onclick="cancelOrder('.$row->id.')">&nbsp;<i class="fa fa-times fa-sm"></i>&nbsp;</a>';
                    return '<a href="'.url('/invoice/'.$row->id).'" target="_blank" class="btn btn-sm '.($row->is_printed == 1 ? 'btn-light' : 'btn-primary').'" onclick="printOrder('.$row->id.', this);">&nbsp;<i class="fa fa-print fa-sm"></i>&nbsp;</a>&nbsp;&nbsp;<a href="#" class="btn btn-sm btn-danger" onclick="cancelOrder('.$row->id.')">&nbsp;<i class="fa fa-times fa-sm"></i>&nbsp;</a>';
                })
                ->addColumn('save', function ($row) {
                    return '<a href="javascript:void(0)" class="btn btn-sm btn-primary" onclick="saveOrder('.$row->id.')">&nbsp;<i class="fa fa-save fa-sm"></i>&nbsp;</a>';
                })
                ->editColumn('id', function ($row) {
                    return '<a href="'.url('/invoice/'.$row->id).'" target="_blank">'.$row->id.'</a>';
                })
                ->editColumn('shipping_method', function ($row) {
                    return wordwrap($row->shipping_method, 16, '<br>');
                })
                ->editColumn('track_link', function ($row) {
                    return '<input class="form-control" id="track-link'.$row->id.'" value="'.$row->track_link.'" style="width: 112.5px">';
                })
                ->editColumn('track_info', function ($row) {
                    return '<input class="form-control" id="track-info'.$row->id.'" value="'.$row->track_info.'" style="width: 112.5px">';
                })
                ->editColumn('created_at', function ($row) {
                    return Carbon::createFromFormat('Y-m-d H:i:s', $row->created_at)->format('m/d/Y').'<br>'.Carbon::createFromFormat('Y-m-d H:i:s', $row->created_at)->format('h:i A');
                })
                ->editColumn('payment_status', function ($row) {
                    return $row->payment_status;
                })
                ->setRowClass(function ($row) {
                    return in_array($row->order_status_id, [5, 6, 7]) ? 'red' : ($row->order_status_id == 1 ? 'blue' : '');
                })
                ->escapeColumns([])
                ->make(true);
        }

        $countries = Country::where('status', 1)->get();
        $order_statuses = OrderStatus::all();
        $shipping_statuses = ShippingStatus::all();
        return view('orders.index', compact('countries', 'order_statuses', 'shipping_statuses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $order = Order::find($id);
//        $order->update($request->all());

        try
        {
            if ($request->has('order_status_id') && $order->order_status_id == 1 && $request->order_status_id == 4)
            {
                $totalQV = OrderProduct::where('order_id', $id)->sum('qv');
                $placement_info = $order->meta->placement;
                $user = User::find($order->user_id);
                $sponsor = User::find($order->sponsor_id);
                $order->update(['order_status_id' => 4, 'shipping_status_id' => 4, 'payment_status' => 'Success', 'cash_order_date' => $order->created_at, 'created_at' => date('Y-m-d H:i:s')]);
                OrderJob::dispatch($order);

                $order_products = $order->orderProducts;
                foreach ($order_products as $order_product)
                {
                    $product = Product::find($order_product->product_id);
                    $product->decrement('quantity', $order_product->quantity);
                }

//                $order_ids = Order::where('id', '!=', $order->id)->where('user_id', $user->id)->where('order_status_id', 4)->pluck('id');
//                $total_existing_qv = OrderProduct::whereIn('order_id', $order_ids)->sum('qv');
//                if($total_existing_qv == 0)
//                {
//                    if ($order->orderProducts->sum('qv') > 0)
//                    {
//                        $is_first_order = 1;
//                    }
//                    else
//                    {
//                        $is_first_order = 0;
//                    }
//                }
//                else
//                {
//                    $is_first_order = 0;
//                }

                $is_first_order = $order->is_first_order;

                if ($placement_info->leg == 'auto')
                {
                    $user_table_id = BusinessCenter::where('id', $placement_info->placement_id)->value('user_id');
                    $leg = User::where('id', $user_table_id)->value('leg');
                }
                else
                {
                    $leg = $placement_info->leg;
                }

                if ($leg == 'default')
                {
                    $left_point = BusinessCenter::where('user_id', $user->sponsor_id)->value('left_carry');
                    $right_point = BusinessCenter::where('user_id', $user->sponsor_id)->value('right_carry');

                    if ($left_point > $right_point)
                    {
                        $leg = 'R';
                    }
                    else
                    {
                        $leg = 'L';
                    }
                }
                $business_center = BusinessCenter::where('user_id', $user->id)->first();
                $sponsor_act = BusinessCenter::where('user_id', $user->sponsor_id)->value('id');
                $total_bv = ($is_first_order == 1) ? $order->orderProducts->sum('qv') / 2 : $order->orderProducts->sum('qv');
                if (Order::where('user_id', $user->id)->where('order_status_id', 4)->count() == 1)
                {
                    if (is_null($business_center))
                    {
                        $business_center = BusinessCenter::create(['user_id' => $user->id, 'business_center' => $user->username . '-1']);
                    }

                    if (is_null(SponsorTree::where('sponsor_id', $user->id)->first()))
                    {
                        $sponsor_tree = SponsorTree::where('sponsor_id', $user->sponsor_id)->orderBy('id', 'desc')->first();
                        $sponsor_tree->user_id = $user->id;
                        $sponsor_tree->sponsor_id = $user->sponsor_id;
                        if ($user->usertype == "rc")
                        {
                            $sponsor_tree->type = "rc";
                        }
                        else
                        {
                            $sponsor_tree->type = "yes";
                        }
                        $sponsor_tree->save();

                        SponsorTree::create(['user_id' => 0, 'sponsor_id' => $user->sponsor_id, 'position' => $sponsor_tree->position + 1]);
                        SponsorTree::create(['user_id' => 0, 'sponsor_id' => $user->id, 'position' => 0 + 1]);
                    }

                    if ($user->usertype != 'rc' && $user->user_state == 'success' && is_null(TreeTable::where('placement_id', $business_center->id)->first()))
                    {
                        $placement_id = TreeTable::getPlacementId($placement_info->placement_id, $leg);

                        TreeTable::where('placement_id', $placement_id)->where("leg", $leg)->where("type", "=", "vaccant")->update(['business_center_id' => $business_center->id, 'sponsor_id' => $user->sponsor_id, 'placement_id' => $placement_id, 'leg' => $leg, 'type' => 'yes']);

                        TreeTable::create(['sponsor_id' => 0, 'business_center_id' => 0, 'placement_id' => $business_center->id, 'leg' => 'L', 'type' => 'vaccant']);
                        TreeTable::create(['sponsor_id' => 0, 'business_center_id' => 0, 'placement_id' => $business_center->id, 'leg' => 'R', 'type' => 'vaccant']);
                    }
                    if ($user->usertype == "rc")
                    {
                        BusinessCenter::updatePoint($total_bv, $totalQV, $sponsor_act, "rc", $business_center->id);
                    }
                    else
                    {
                        BusinessCenter::updatePoint($total_bv, $totalQV, $business_center->id, "binary", 0);
                    }

                    ActivityLog::create(['user_id' => $user->id, 'ip_address' => $request->ip(), 'title' => 'Registered user ' . $user->username, 'description' => 'Registered ' . $user->username . ' with Sponsor as ' . $sponsor->username . ' and Placement User as ' . BusinessCenter::find($placement_info->placement_id)->business_center]);
                    ActivityLog::create(['user_id' => $user->id, 'ip_address' => $request->ip(), 'title' => 'Joined as ' . $user->username, 'description' => 'Joined in system as ' . $user->username . ' with Sponsor as ' . $sponsor->username . ' and Placement User as ' . BusinessCenter::find($placement_info->placement_id)->business_center]);
                }
                elseif (Order::where('user_id', $user->id)->where('order_status_id', 4)->count() > 1)
                {
                    if ($user->usertype == "rc")
                    {
                        BusinessCenter::updatePoint($total_bv, $totalQV, $sponsor_act, "rc", $business_center->id);
                    }
                    else
                    {
                        BusinessCenter::updatePoint($total_bv, $totalQV, $business_center->id, "binary", 0);
                    }

//                    ActivityLog::create(['user_id' => $user->id, 'ip_address' => $request->ip(), 'title' => 'Registered user ' . $user->username, 'description' => 'Registered ' . $user->username . ' with Sponsor as ' . $sponsor->username . ' and Placement User as ' . BusinessCenter::find($placement_info->placement_id)->business_center]);
//                    ActivityLog::create(['user_id' => $user->id, 'ip_address' => $request->ip(), 'title' => 'Joined as ' . $user->username, 'description' => 'Joined in system as ' . $user->username . ' with Sponsor as ' . $sponsor->username . ' and Placement User as ' . BusinessCenter::find($placement_info->placement_id)->business_center]);
                }

                if ($totalQV >= 100)
                {
                    User::where('id', $user->sponsor_id)->update(['power_qualified' => 1]);

                    if($leg == 'L')
                    {
                        User::where('id', $user->sponsor_id)->increment('binary_left');
                    }
                    else
                    {
                        User::where('id', $user->sponsor_id)->increment('binary_right');
                    }

                    if ($user->user_state == 'success')
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
                    }

                }

                $total_qvs_sponsor_customers = OrderProduct::whereHas('order', function ($q) use ($sponsor) {
                    $q->where('order_status_id', 4)->where('sponsor_id', $sponsor->id)->whereHas('user', function ($q) {
                        $q->where('usertype', 'dc');
                    });
                })->sum('qv');

                if ($total_qvs_sponsor_customers >= 100 && $user->usertype == 'dc')
                {
                    if($leg == 'L')
                    {
                        User::where('id', $user->sponsor_id)->update(['bingo_left_sponsored' => 1]);
                    }
                    else
                    {
                        User::where('id', $user->sponsor_id)->update(['bingo_right_sponsored' => 1]);
                    }
                }
            }
            elseif ($request->has('order_status_id') && ($order->order_status_id == 1 || $order->order_status_id == 4) && $request->order_status_id == 5)
            {
                $order_products = $order->orderProducts;
                foreach ($order_products as $order_product)
                {
                    $product = Product::find($order_product->product_id);
                    $product->increment('quantity', $order_product->quantity);
                }

                $user_id = $order->user_id;

                $business_center_id = BusinessCenter::where('user_id', $user_id)->value('id');

                $QV = OrderProduct::where('order_id', $order->id)->sum('qv');
                $BV = OrderProduct::where('order_id', $order->id)->sum('bv');

                $order_date = OrderProduct::where('order_id', $order->id)->value('created_at');
                $first_order = $order->is_first_order;

                $user_info = User::find($user_id);

                $sponsor_act = BusinessCenter::where('user_id', $user_info->sponsor_id)->value('id');

                if ($order->order_status_id == 4)
                {
                    if ($user_info->usertype == "rc")
                    {
                        $deduct_from_uplines = BusinessCenter::deductPoint($BV, $QV, $sponsor_act, "rc", $business_center_id, $order_date);
                    }
                    else
                    {
                        $deduct_from_uplines = BusinessCenter::deductPoint($BV, $QV, $business_center_id, "binary", 0, $order_date);
                    }

                    if ($first_order == 1)
                    {
                        # FOB CLAW BACK
                        $fobs = Commission::where('from_business_center_id', $business_center_id)
                            ->where(function ($query) {
                                $query->orWhere('commission_type', 'frontline_order_bonus');
                                $query->orWhere('commission_type', 'first_order_bonus');
                            })->get();

                        foreach ($fobs as $key => $bonus)
                        {
                            if ($bonus->status == 1)
                            {

                                // Commission::decrementUserBalance($bonus->user_id,$bonus->payable_amount);

//                        ClawbackHistory::create([
//                            'user_id' => $bonus->user_id,
//                            'from_id' => $bonus->from_id,
//                            'order_id' => $order_id,
//                            'amount' => $bonus->payable_amount,
//                            'payment_type' => $bonus->payment_type,
//                        ]);

                                $note = "Deduction  - First Order Bonus - Cancelled order # " . $order->id;

                                $deduct_history = Commission::create([
                                    'business_center_id' => $bonus->business_center_id,
                                    'user_id' => $bonus->user_id,
                                    'from_business_center_id' => 1,
                                    'from_user_id' => 1,
                                    'amount' => $bonus->amount,
                                    'commission_type' => 'debited_by_admin',
                                    'notes' => $note,
                                    'status' => 1,
                                ]);
                                $current_balance = EwalletTransaction::where('user_id', $bonus->user_id)->orderBy('id', 'desc')->value('current_balance');
                                $new_balance = $current_balance - $bonus->amount;
                                EwalletTransaction::create([
                                    'commission_id' => $bonus->id,
                                    'user_id' => $bonus->user_id,
                                    'from_user_id' => 1,
                                    'from_business_center_id' => 1,
                                    'description' => 'debited_by_admin',
                                    'note' => $note,
                                    'amount' => $bonus->amount,
                                    'amount_type' => 'debit',
                                    'current_balance' => $new_balance,
                                ]);
                            }

                            $delete_entry = Commission::where('id', $bonus->id)->delete();

                        }
                        #FOB CLAW BACK end

                        #PC BONUS CLAW BACK

                        $pc_bonus = Commission::where('from_business_center_id', $business_center_id)
                            ->where('commission_type', 'prefered_customer_bonus')
                            ->get();

                        foreach ($pc_bonus as $key => $pc)
                        {
                            if ($pc->status == 1)
                            {

                                // Commission::decrementUserBalance($pc->user_id,$pc->payable_amount);

//                        ClawbackHistory::create([
//                            'user_id' => $pc->user_id,
//                            'from_id' => $pc->from_id,
//                            'order_id' => $order_id,
//                            'amount' => $pc->payable_amount,
//                            'payment_type' => $pc->payment_type,
//                        ]);

                                $note = "Deduction  - Preferred customer bonus - Cancelled order # " . $order->id;

                                $deduct_history = Commission::create([
                                    'business_center_id' => $pc->business_center_id,
                                    'user_id' => $pc->user_id,
                                    'from_business_center_id' => 1,
                                    'from_user_id' => 1,
                                    'amount' => $pc->total_amount,
                                    'commission_type' => 'debited_by_admin',
                                    'notes' => $note,
                                    'status' => 1
                                ]);

                                $current_balance = EwalletTransaction::where('user_id', $pc->userid)->orderBy('id', 'desc')->value('current_balance');
                                $new_balance = $current_balance - $pc->payable_amount;
                                EwalletTransaction::create([
                                    'commission_id' => $pc->id,
                                    'user_id' => $pc->userid,
                                    'from_user_id' => 1,
                                    'from_account_id' => 1,
                                    'description' => 'debited_by_admin',
                                    'note' => $note,
                                    'amount' => $pc->payable_amount,
                                    'amount_type' => 'debit',
                                    'current_balance' => $new_balance,
                                ]);
                            }

                            $delete_entry = Commission::where('id', $pc->id)->delete();

                        }
                        #PC BONUS CLAW BACK ends
                    }
                    #RC BONUS CLAW BACK
                    $rc_bonus = Commission::where('from_business_center_id', $business_center_id)->where('commission_type', 'retail_bonus')
                        ->where('notes', 'Order #'.$order->id)->get();

                    foreach ($rc_bonus as $key => $rc)
                    {
                        if ($rc->status == 1)
                        {
                            // Commission::decrementUserBalance($rc->user_id,$rc->payable_amount);

//                    ClawbackHistory::create([
//                        'user_id' => $rc->user_id,
//                        'from_id' => $rc->from_id,
//                        'order_id' => $order_id,
//                        'amount' => $rc->payable_amount,
//                        'payment_type' => $rc->payment_type,
//                    ]);
                            $note = "Deduction  - Retail bonus - Cancelled order # " . $order->id;

                            $deduct_history = Commission::create([
                                'business_center_id' => $rc->business_center_id,
                                'user_id' => $rc->user_id,
                                'from_business_center_id' => 1,
                                'from_user_id' => 1,
                                'amount' => $rc->amount,
                                'commission_type' => 'debited_by_admin',
                                'notes' => $note,
                                'status' => 1
                            ]);

                            $current_balance = EwalletTransaction::where('user_id', $rc->user_id)->orderBy('id', 'desc')->value('current_balance');
                            $new_balance = $current_balance - $rc->payable_amount;
                            EwalletTransaction::create([
                                'commission_id' => $rc->id,
                                'user_id' => $rc->user_id,
                                'from_user_id' => 1,
                                'from_business_center_id' => 1,
                                'description' => 'debited_by_admin',
                                'note' => $note,
                                'amount' => $rc->amount,
                                'amount_type' => 'debit',
                                'current_balance' => $new_balance,
                            ]);
                        }

                        $delete_entry = Commission::where('id', $rc->id)->delete();

                    }
                }

                $order->update(['order_status_id' => 5, 'shipping_status_id' => 2]);
            }

            if ($request->has('is_printed'))
            {
                $order->update(['is_printed' => $request->is_printed]);
            }

            if ($request->has('track_link') && $request->has('track_info'))
            {
                $order->update(['track_link' => $request->track_link, 'track_info' => $request->track_info]);
            }

            if ($request->has('shipping_status_id'))
            {
                $order->update(['shipping_status_id' => $request->shipping_status_id]);
            }

            if ($request->ajax())
            {
                return response()->json(['status' => 200, 'message' => 'success']);
            }

            return redirect('orders');
        }
        catch (\Exception $exception)
        {
            if (isset($order))
            {
                $request->merge(['order' => $order]);
            }
            ErrorLog::create(['code' => $exception->getCode(), 'error' => $exception->getMessage(), 'line_number' => $exception->getLine(), 'data' => json_encode($request->all())]);
            return response()->json(['error' => $exception->getMessage(), 'status' => $exception->getCode()]);
        }

    }

    public function updateNew(Request $request, $id)
    {
        $order = Order::find($id);
//        $order->update($request->all());

        try
        {
            if ($request->has('order_status_id') && $order->order_status_id == 1 && $request->order_status_id == 4)
            {
                $totalQV = OrderProduct::where('order_id', $id)->sum('qv');
                $placement_info = $order->meta->placement;
                $sponsor = User::find($order->sponsor_id);
                if ($order->is_first_order == 1 && is_null($order->user_id))
                {
                    $user = (array)$order->meta->user;
                    unset($user['leg']);
                    unset($user['placement_id']);
                    unset($user['placement_search_id']);
                    unset($user['password_confirmation']);
                    $user['sponsor_id'] = $sponsor->id;

                    if ($user['usertype'] != 'rc')
                    {
                        if ($sponsor['user_state'] == 'waiting')
                        {
                            $order_state = 'waiting';
                        }
                        elseif ($sponsor['user_state'] == 'success')
                        {
                            if (isset($placement_info->placement_type) && $placement_info->placement_type == 'manual')
                            {
                                $order_state = 'success';
                            }
                            else
                            {
                                if ($user['enrollment_type'] == 'bingo_tree')
                                {
                                    $order_state = 'success';
                                }
                                else
                                {
                                    $order_state = 'waiting';
                                }
                            }
                        }
                    }
                    else
                    {
                        if ($sponsor['user_state'] == 'waiting')
                        {
                            $order_state = 'waiting';
                        }
                        elseif ($sponsor['user_state'] == 'success')
                        {
                            $order_state = 'success';
                        }
                    }

                    if (isset($user['user_state']) && !is_null($user['user_state']))
                    {
                        $order_state = $user['user_state'];
                    }

                    $user['user_state'] = $order_state;
                    $user['password'] = Hash::make($user['password']);
                    $user = User::create($user);
                    $address = (array)$order->meta->address;
                    if (!isset($address['id']))
                    {
                        Address::create([
                            'user_id' => $user->id,
                            'contact_number' => $address['contact_number'],
                            'contact_name' => $address['contact_name'],
                            'address_1' => $address['address_1'],
                            'address_2' => $address['address_2'],
                            'city' => $address['city'],
                            'postcode' => $address['postcode'],
                            'state_id' => $address['state_id'],
                            'country_id' => $address['country_id'],
                        ]);
                    }
                    $shipping_address = (array)$order->meta->shipping_address;
                    if (!isset($shipping_address['id']))
                    {
                        Address::create([
                            'user_id' => $user->id,
                            'contact_number' => $shipping_address['contact_number'],
                            'contact_name' => $shipping_address['contact_name'],
                            'address_1' => $shipping_address['address_1'],
                            'address_2' => $shipping_address['address_2'],
                            'city' => $shipping_address['city'],
                            'postcode' => $shipping_address['postcode'],
                            'state_id' => $shipping_address['state_id'],
                            'country_id' => $shipping_address['country_id'],
                            'is_shipping' => 1
                        ]);
                    }

                    $order_state = '';

                }
                else
                {
                    $user = User::find($order->user_id);
                }

                $order->update(['user_id' => $user->id, 'order_status_id' => 4, 'shipping_status_id' => 4, 'payment_status' => 'Success', 'cash_order_date' => $order->created_at, 'created_at' => date('Y-m-d H:i:s')]);
                OrderJob::dispatch($order);

                $order_products = $order->orderProducts;
                foreach ($order_products as $order_product)
                {
                    $product = Product::find($order_product->product_id);
                    $product->decrement('quantity', $order_product->quantity);
                }

//                $order_ids = Order::where('id', '!=', $order->id)->where('user_id', $user->id)->where('order_status_id', 4)->pluck('id');
//                $total_existing_qv = OrderProduct::whereIn('order_id', $order_ids)->sum('qv');
//                if($total_existing_qv == 0)
//                {
//                    if ($order->orderProducts->sum('qv') > 0)
//                    {
//                        $is_first_order = 1;
//                    }
//                    else
//                    {
//                        $is_first_order = 0;
//                    }
//                }
//                else
//                {
//                    $is_first_order = 0;
//                }

                $is_first_order = $order->is_first_order;

                if ($placement_info->leg == 'auto')
                {
                    $user_table_id = BusinessCenter::where('id', $placement_info->placement_id)->value('user_id');
                    $leg = User::where('id', $user_table_id)->value('leg');
                }
                else
                {
                    $leg = $placement_info->leg;
                }

                if ($leg == 'default')
                {
                    $left_point = BusinessCenter::where('user_id', $user->sponsor_id)->value('left_carry');
                    $right_point = BusinessCenter::where('user_id', $user->sponsor_id)->value('right_carry');

                    if ($left_point > $right_point)
                    {
                        $leg = 'R';
                    }
                    else
                    {
                        $leg = 'L';
                    }
                }
                $business_center = BusinessCenter::where('user_id', $user->id)->first();
                $sponsor_act = BusinessCenter::where('user_id', $user->sponsor_id)->value('id');
                $total_bv = ($is_first_order == 1) ? $order->orderProducts->sum('qv') / 2 : $order->orderProducts->sum('qv');
                if (Order::where('user_id', $user->id)->where('order_status_id', 4)->count() == 1)
                {
                    if (is_null($business_center))
                    {
                        $business_center = BusinessCenter::create(['user_id' => $user->id, 'business_center' => $user->username . '-1']);
                    }

                    if (is_null(SponsorTree::where('sponsor_id', $user->id)->first()))
                    {
                        $sponsor_tree = SponsorTree::where('sponsor_id', $user->sponsor_id)->orderBy('id', 'desc')->first();
                        $sponsor_tree->user_id = $user->id;
                        $sponsor_tree->sponsor_id = $user->sponsor_id;
                        if ($user->usertype == "rc")
                        {
                            $sponsor_tree->type = "rc";
                        }
                        else
                        {
                            $sponsor_tree->type = "yes";
                        }
                        $sponsor_tree->save();

                        SponsorTree::create(['user_id' => 0, 'sponsor_id' => $user->sponsor_id, 'position' => $sponsor_tree->position + 1]);
                        SponsorTree::create(['user_id' => 0, 'sponsor_id' => $user->id, 'position' => 0 + 1]);
                    }

                    if ($user->usertype != 'rc' && $user->user_state == 'success' && is_null(TreeTable::where('placement_id', $business_center->id)->first()))
                    {
                        $placement_id = TreeTable::getPlacementId($placement_info->placement_id, $leg);

                        TreeTable::where('placement_id', $placement_id)->where("leg", $leg)->where("type", "=", "vaccant")->update(['business_center_id' => $business_center->id, 'sponsor_id' => $user->sponsor_id, 'placement_id' => $placement_id, 'leg' => $leg, 'type' => 'yes']);

                        TreeTable::create(['sponsor_id' => 0, 'business_center_id' => 0, 'placement_id' => $business_center->id, 'leg' => 'L', 'type' => 'vaccant']);
                        TreeTable::create(['sponsor_id' => 0, 'business_center_id' => 0, 'placement_id' => $business_center->id, 'leg' => 'R', 'type' => 'vaccant']);
                    }
                    if ($user->usertype == "rc")
                    {
                        BusinessCenter::updatePoint($total_bv, $totalQV, $sponsor_act, "rc", $business_center->id);
                    }
                    else
                    {
                        BusinessCenter::updatePoint($total_bv, $totalQV, $business_center->id, "binary", 0);
                    }

                    ActivityLog::create(['user_id' => $user->id, 'ip_address' => $request->ip(), 'title' => 'Registered user ' . $user->username, 'description' => 'Registered ' . $user->username . ' with Sponsor as ' . $sponsor->username . ' and Placement User as ' . BusinessCenter::find($placement_info->placement_id)->business_center]);
                    ActivityLog::create(['user_id' => $user->id, 'ip_address' => $request->ip(), 'title' => 'Joined as ' . $user->username, 'description' => 'Joined in system as ' . $user->username . ' with Sponsor as ' . $sponsor->username . ' and Placement User as ' . BusinessCenter::find($placement_info->placement_id)->business_center]);
                }
                elseif (Order::where('user_id', $user->id)->where('order_status_id', 4)->count() > 1)
                {
                    if ($user->usertype == "rc")
                    {
                        BusinessCenter::updatePoint($total_bv, $totalQV, $sponsor_act, "rc", $business_center->id);
                    }
                    else
                    {
                        BusinessCenter::updatePoint($total_bv, $totalQV, $business_center->id, "binary", 0);
                    }

//                    ActivityLog::create(['user_id' => $user->id, 'ip_address' => $request->ip(), 'title' => 'Registered user ' . $user->username, 'description' => 'Registered ' . $user->username . ' with Sponsor as ' . $sponsor->username . ' and Placement User as ' . BusinessCenter::find($placement_info->placement_id)->business_center]);
//                    ActivityLog::create(['user_id' => $user->id, 'ip_address' => $request->ip(), 'title' => 'Joined as ' . $user->username, 'description' => 'Joined in system as ' . $user->username . ' with Sponsor as ' . $sponsor->username . ' and Placement User as ' . BusinessCenter::find($placement_info->placement_id)->business_center]);
                }

                if ($totalQV >= 100)
                {
                    User::where('id', $user->sponsor_id)->update(['power_qualified' => 1]);

                    if($leg == 'L')
                    {
                        User::where('id', $user->sponsor_id)->increment('binary_left');
                    }
                    else
                    {
                        User::where('id', $user->sponsor_id)->increment('binary_right');
                    }

                    if ($user->user_state == 'success')
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
                    }

                }

                $total_qvs_sponsor_customers = OrderProduct::whereHas('order', function ($q) use ($sponsor) {
                    $q->where('order_status_id', 4)->where('sponsor_id', $sponsor->id)->whereHas('user', function ($q) {
                        $q->where('usertype', 'dc');
                    });
                })->sum('qv');

                if ($total_qvs_sponsor_customers >= 100 && $user->usertype == 'dc')
                {
                    if($leg == 'L')
                    {
                        User::where('id', $user->sponsor_id)->update(['bingo_left_sponsored' => 1]);
                    }
                    else
                    {
                        User::where('id', $user->sponsor_id)->update(['bingo_right_sponsored' => 1]);
                    }
                }
            }
            elseif ($request->has('order_status_id') && ($order->order_status_id == 1 || $order->order_status_id == 4) && $request->order_status_id == 5)
            {
                $order_products = $order->orderProducts;
                foreach ($order_products as $order_product)
                {
                    $product = Product::find($order_product->product_id);
                    $product->increment('quantity', $order_product->quantity);
                }

                $QV = OrderProduct::where('order_id', $order->id)->sum('qv');
                $BV = OrderProduct::where('order_id', $order->id)->sum('bv');

                $order_date = OrderProduct::where('order_id', $order->id)->value('created_at');
                $first_order = $order->is_first_order;

                if (!is_null($order->user_id))
                {
                    $user_id = $order->user_id;
                    $business_center_id = BusinessCenter::where('user_id', $user_id)->value('id');
                    $user_info = User::find($user_id);
                }
                else
                {
                    $business_center_id = null;
                    $user_info = isset($order->meta->user) ? $order->meta->user : null;
                }

                if (!is_null($user_info))
                {
                    $sponsor_act = BusinessCenter::where('user_id', $user_info->sponsor_id)->value('id');
                }
                else
                {
                    $sponsor_act = null;
                }

                if ($order->order_status_id == 4)
                {
                    if ($user_info->usertype == "rc")
                    {
                        $deduct_from_uplines = BusinessCenter::deductPoint($BV, $QV, $sponsor_act, "rc", $business_center_id, $order_date);
                    }
                    else
                    {
                        $deduct_from_uplines = BusinessCenter::deductPoint($BV, $QV, $business_center_id, "binary", 0, $order_date);
                    }

                    if ($first_order == 1)
                    {
                        # FOB CLAW BACK
                        $fobs = Commission::where('from_business_center_id', $business_center_id)
                            ->where(function ($query) {
                                $query->orWhere('commission_type', 'frontline_order_bonus');
                                $query->orWhere('commission_type', 'first_order_bonus');
                            })->get();

                        foreach ($fobs as $key => $bonus)
                        {
                            if ($bonus->status == 1)
                            {

                                // Commission::decrementUserBalance($bonus->user_id,$bonus->payable_amount);

//                        ClawbackHistory::create([
//                            'user_id' => $bonus->user_id,
//                            'from_id' => $bonus->from_id,
//                            'order_id' => $order_id,
//                            'amount' => $bonus->payable_amount,
//                            'payment_type' => $bonus->payment_type,
//                        ]);

                                $note = "Deduction  - First Order Bonus - Cancelled order # " . $order->id;

                                $deduct_history = Commission::create([
                                    'business_center_id' => $bonus->business_center_id,
                                    'user_id' => $bonus->user_id,
                                    'from_business_center_id' => 1,
                                    'from_user_id' => 1,
                                    'amount' => $bonus->amount,
                                    'commission_type' => 'debited_by_admin',
                                    'notes' => $note,
                                    'status' => 1,
                                ]);
                                $current_balance = EwalletTransaction::where('user_id', $bonus->user_id)->orderBy('id', 'desc')->value('current_balance');
                                $new_balance = $current_balance - $bonus->amount;
                                EwalletTransaction::create([
                                    'commission_id' => $bonus->id,
                                    'user_id' => $bonus->user_id,
                                    'from_user_id' => 1,
                                    'from_business_center_id' => 1,
                                    'description' => 'debited_by_admin',
                                    'note' => $note,
                                    'amount' => $bonus->amount,
                                    'amount_type' => 'debit',
                                    'current_balance' => $new_balance,
                                ]);
                            }

                            $delete_entry = Commission::where('id', $bonus->id)->delete();

                        }
                        #FOB CLAW BACK end

                        #PC BONUS CLAW BACK

                        $pc_bonus = Commission::where('from_business_center_id', $business_center_id)
                            ->where('commission_type', 'prefered_customer_bonus')
                            ->get();

                        foreach ($pc_bonus as $key => $pc)
                        {
                            if ($pc->status == 1)
                            {

                                // Commission::decrementUserBalance($pc->user_id,$pc->payable_amount);

//                        ClawbackHistory::create([
//                            'user_id' => $pc->user_id,
//                            'from_id' => $pc->from_id,
//                            'order_id' => $order_id,
//                            'amount' => $pc->payable_amount,
//                            'payment_type' => $pc->payment_type,
//                        ]);

                                $note = "Deduction  - Preferred customer bonus - Cancelled order # " . $order->id;

                                $deduct_history = Commission::create([
                                    'business_center_id' => $pc->business_center_id,
                                    'user_id' => $pc->user_id,
                                    'from_business_center_id' => 1,
                                    'from_user_id' => 1,
                                    'amount' => $pc->total_amount,
                                    'commission_type' => 'debited_by_admin',
                                    'notes' => $note,
                                    'status' => 1
                                ]);

                                $current_balance = EwalletTransaction::where('user_id', $pc->userid)->orderBy('id', 'desc')->value('current_balance');
                                $new_balance = $current_balance - $pc->payable_amount;
                                EwalletTransaction::create([
                                    'commission_id' => $pc->id,
                                    'user_id' => $pc->userid,
                                    'from_user_id' => 1,
                                    'from_account_id' => 1,
                                    'description' => 'debited_by_admin',
                                    'note' => $note,
                                    'amount' => $pc->payable_amount,
                                    'amount_type' => 'debit',
                                    'current_balance' => $new_balance,
                                ]);
                            }

                            $delete_entry = Commission::where('id', $pc->id)->delete();

                        }
                        #PC BONUS CLAW BACK ends
                    }
                    #RC BONUS CLAW BACK
                    $rc_bonus = Commission::where('from_business_center_id', $business_center_id)->where('commission_type', 'retail_bonus')
                        ->where('notes', 'Order #'.$order->id)->get();

                    foreach ($rc_bonus as $key => $rc)
                    {
                        if ($rc->status == 1)
                        {
                            // Commission::decrementUserBalance($rc->user_id,$rc->payable_amount);

//                    ClawbackHistory::create([
//                        'user_id' => $rc->user_id,
//                        'from_id' => $rc->from_id,
//                        'order_id' => $order_id,
//                        'amount' => $rc->payable_amount,
//                        'payment_type' => $rc->payment_type,
//                    ]);
                            $note = "Deduction  - Retail bonus - Cancelled order # " . $order->id;

                            $deduct_history = Commission::create([
                                'business_center_id' => $rc->business_center_id,
                                'user_id' => $rc->user_id,
                                'from_business_center_id' => 1,
                                'from_user_id' => 1,
                                'amount' => $rc->amount,
                                'commission_type' => 'debited_by_admin',
                                'notes' => $note,
                                'status' => 1
                            ]);

                            $current_balance = EwalletTransaction::where('user_id', $rc->user_id)->orderBy('id', 'desc')->value('current_balance');
                            $new_balance = $current_balance - $rc->payable_amount;
                            EwalletTransaction::create([
                                'commission_id' => $rc->id,
                                'user_id' => $rc->user_id,
                                'from_user_id' => 1,
                                'from_business_center_id' => 1,
                                'description' => 'debited_by_admin',
                                'note' => $note,
                                'amount' => $rc->amount,
                                'amount_type' => 'debit',
                                'current_balance' => $new_balance,
                            ]);
                        }

                        $delete_entry = Commission::where('id', $rc->id)->delete();

                    }
                }

                $order->update(['order_status_id' => 5, 'shipping_status_id' => 2]);
            }

            if ($request->has('is_printed'))
            {
                $order->update(['is_printed' => $request->is_printed]);
            }

            if ($request->has('track_link') && $request->has('track_info'))
            {
                $order->update(['track_link' => $request->track_link, 'track_info' => $request->track_info]);
            }

            if ($request->has('shipping_status_id'))
            {
                $order->update(['shipping_status_id' => $request->shipping_status_id]);
            }

            if ($request->ajax())
            {
                return response()->json(['status' => 200, 'message' => 'success']);
            }

            return redirect('orders');
        }
        catch (\Exception $exception)
        {
            if (isset($order))
            {
                $request->merge(['order' => $order]);
            }
            ErrorLog::create(['code' => $exception->getCode(), 'error' => $exception->getMessage(), 'line_number' => $exception->getLine(), 'data' => json_encode($request->all())]);
            return response()->json(['error' => $exception->getMessage(), 'status' => $exception->getCode()]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function placeOrder(Request $request)
    {
        try
        {
//            return response()->json(['status' => 1]);
            $placement_info = (object)$request->placement_info;
            $address = (object)$request->address;
            $user = (object)$request->user;
            $shipping_address = isset($request->shipping_address) ? (object)$request->shipping_address : $address;
            $sponsor = User::find($user->sponsor_id);
//            $credit_card = CreditCard::with('billingAddress')->find($request->credit_card_id);
//            $billing_address = !is_null($request->billing_address_id) ? Address::find($request->billing_address_id) : $shipping_address;
            $credit_card = (object)$request->credit_card;
            $billing_address = $request->payment_method == 'credit_card' ? json_decode(json_encode($credit_card->billing_address)) : $address;
            $cart = (object)$request->cart;

            if (Order::where('user_id', $user->id)->count() == 1 && Order::where('user_id', $user->id)->first()->order_status_id == 1)
            {
                return ['status' => 2, 'message' => 'Your previous cash order is not approved yet. Please contact Ultrra Corp. helpline cc@ultrra.com (+1) 888.981.1711.'];
            }
            if ($request->payment_method == 'credit_card' && $request->cvv != $credit_card->cvv)
            {
                return ['status' => 2, 'message' => 'Please enter correct CVV'];
            }
            if (empty($shipping_address->postcode) || is_null($shipping_address->postcode))
            {
                return ['status' => 2, 'message' => 'Please enter shipping postcode'];
            }

            /* Check Maximum Quantity of product */
            $productname_quantity_error = [];
            for ($i = 0; $i < count($cart->products); $i++)
            {
                $product_maximum_quantity = Product::find($cart->products[$i]['product_id'])->maximum_quantity;
                $product_quantity = $cart->products[$i]['quantity'];
                if ($product_maximum_quantity != 0)
                {
                  if ($product_quantity > $product_maximum_quantity)
                  {
                      $productname_quantity_error[$i]['product_name'] = $cart->products[$i]['product']['name'];
                      $productname_quantity_error[$i]['product_maximum_quantity'] = $product_maximum_quantity;
                  }
                }
            }
            if (sizeof($productname_quantity_error) > 0)
            {
                $message = '';
                foreach ($productname_quantity_error as $error)
                {
                    $message .= $error['product_name'].' quantity is greater than maximum available quantity '.$error['product_maximum_quantity'].'. ';
                }
                return response()->json(['status' => 2, 'message' => $message]);
            }

            $products_id = collect($cart->products)->pluck('product_id');

            $order_ids = Order::where('user_id', $user->id)->where('order_status_id', 4)->pluck('id');
            $total_existing_qv = OrderProduct::whereIn('order_id', $order_ids)->sum('qv');
            if($total_existing_qv == 0)
            {
                if ($cart->totalQV > 0)
                {
                    $is_first_order = 1;
                }
                else
                {
                    $is_first_order = 0;
                }
            }
            else
            {
                $is_first_order = 0;
            }

            $is_backorder = 0;
            if (Product::whereIn('id', $products_id)->where('maximum_quantity', '<=', 0)->count() > 0)
            {
                $is_backorder = 1;
            }

            $orderData = [
                'user_id' => $user->id,
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'joint_firstname' => isset($user->joint_firstname) ? $user->joint_firstname : null,
                'joint_lastname' => isset($user->joint_lastname) ? $user->joint_lastname : null,
                'mobile' => $user->phone,
                'secondary_phone' => $user->secondary_phone,
                'email' => $user->email,
                'country_id' => $address->country_id,
                'state_id' => $address->state_id,
                'postcode' => $address->postcode,
                'city' => $address->city,
                'address_1' => $address->address_1,
                'address_2' => $address->address_2,
                'shipping_firstname' => $shipping_address->contact_name,
                'shipping_lastname' => '',
                'shipping_email' => $user->email,
                'shipping_mobile' => $shipping_address->contact_number,
                'shipping_company' => '',
                'shipping_address_1' => $shipping_address->address_1,
                'shipping_address_2' => $shipping_address->address_2,
                'shipping_city' => $shipping_address->city,
                'shipping_postcode' => $shipping_address->postcode,
                'shipping_country_id' => $shipping_address->country_id,
                'shipping_state_id' => $shipping_address->state_id,
                'tax' => $cart->taxTotal,
                'handling_charges' => $cart->handlingCharges,
                'total' => $cart->grandTotal,
                'sub_total' => $cart->subTotal,
                'ip' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'shipping_method' => $cart->shippingMethod,
                'shipping_price' => $cart->shippingTotal,
                'shipping_status' => '',
                'billing_firstname' => $billing_address->contact_name,
                'billing_lastname' => '',
                'billing_address_1' => $billing_address->address_1,
                'billing_address_2' => $billing_address->address_2,
                'billing_city' => $billing_address->city,
                'billing_postcode' => $billing_address->postcode,
                'billing_country_id' => $billing_address->country_id,
                'billing_state_id' => $billing_address->state_id,
                'is_first_order' => $is_first_order,
                'is_backorder' => $is_backorder,
                'sponsor_id' => $sponsor->id,
                'note' => $request->notes,
                'meta' => ['placement' => $placement_info, 'total_qv' => $cart->totalQV]
            ];

            $order_state = '';

            if ($user->usertype != 'rc')
            {
                if ($sponsor->user_state == 'waiting')
                {
                    $order_state = 'waiting';
                }
                elseif ($sponsor->user_state == 'success')
                {
                    if (isset($placement_info->placement_type) && $placement_info->placement_type == 'manual')
                    {
                        $order_state = 'success';
                    }
                    else
                    {
                        if ($user->enrollment_type == 'bingo_tree')
                        {
                            $order_state = 'success';
                        }
                        else
                        {
                            $order_state = 'waiting';
                        }
                    }
                }
            }
            else
            {
                if ($sponsor->user_state == 'waiting')
                {
                    $order_state = 'waiting';
                }
                elseif ($sponsor->user_state == 'success')
                {
                    $order_state = 'success';
                }
            }

            if (!is_null($user->user_state))
            {
                $order_state = $user->user_state;
            }

            $order = Order::create($orderData);
            User::find($user->id)->update(['user_state' => $order_state, 'users_ip' => $request->ip()]);

            foreach ($cart->products as $cart_product)
            {
                $cart_product = json_decode(json_encode($cart_product));
                $product = Product::find($cart_product->product_id);
                $price = $user->usertype == 'dc' ? $cart_product->product->distributor_price : ($user->usertype == 'pc' ? $cart_product->product->preferred_customer_price : $cart_product->product->retail_customer_price);
                $retail_price = $cart_product->product->retail_customer_price;
                $pc_price = $cart_product->product->preferred_customer_price;
                $member_price = $cart_product->product->distributor_price;
                $qv = $cart_product->product->qv;
                $bv = $qv/2;
                if ($product->productCountries->count() && !is_null($product->productCountries->first(function ($value) use ($user) { return $value->country_id == $user->country_id; })))
                {
                    $product_country = $product->productCountries->first(function ($value) use ($user) {
                        return $value->country_id == $user->country_id;
                    });
                    if ($user->usertype == 'dc')
                    {
                        $price = $product_country->distributor_price;
                    }
                    elseif ($user->usertype == 'pc')
                    {
                        $price = $product_country->preferred_customer_price;
                    }
                    else
                    {
                        $price = $product_country->retail_customer_price;
                    }
                    $retail_price = $product_country->product->retail_customer_price;
                    $pc_price = $product_country->product->preferred_customer_price;
                    $member_price = $product_country->product->distributor_price;
                    $qv = $product_country->qv;
                    $bv = $qv/2;
                }

                $productData = [
                    'order_id' => $order->id,
                    'product_id' => $cart_product->product_id,
                    'name' => $cart_product->product->name,
                    'model' => $cart_product->product->name,
                    'quantity' => $cart_product->quantity,
                    'price' => $price,
                    'retail_price' => $retail_price,
                    'member_price' => $member_price,
                    'pc_price' => $pc_price,
                    'qv' => $qv * $cart_product->quantity,
                    'bv' => ($is_first_order == 1) ? ($bv * $cart_product->quantity) : ($qv * $cart_product->quantity),
                    'total' => $cart_product->quantity * $price,
                ];

                $product_quantity = $product->maximum_quantity;

                if ($product_quantity <= 0)
                {
                    $productData['backorder_quantity'] = $productData['quantity'];
                }
                elseif ($productData['quantity'] > $product_quantity)
                {
                    $productData['backorder_quantity'] = $productData['quantity'] - $product_quantity;
                }
                else
                {
                    $productData['backorder_quantity'] = 0;
                }

                $order_product = OrderProduct::create($productData);

                if(count($cart_product->product->product_components) > 0)
                {
                    $product_components = [];
                    foreach($cart_product->product->product_components as $product_component)
                    {
                        $product_component_price = $user->usertype == 'dc' ? $product_component->distributor_price : ($user->usertype == 'pc' ? $product_component->preferred_customer_price : $product_component->retail_customer_price);
                        $componentData = [
                            'order_product_id' => $order_product->id,
                            'component_id' => $product_component->id,
                            'name' => $product_component->name,
                            'model' => $product_component->name,
                            'quantity' => $product_component->pivot->quantity,
                            'price' => $product_component_price,
                            'qv' => $product_component->qv * $product_component->pivot->quantity,
                            'bv' =>  ($is_first_order == 1) ? ($bv * $cart_product->quantity) : ($qv * $cart_product->quantity),
                            'total' => $product_component->pivot->quantity * $product_component_price,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                        ];

                        $product_components[] = $componentData;
                    }
                    OrderProductComponent::insert($product_components);
                }

            }

            if ($request->payment_method === 'credit_card')
            {
                $order_payment_method_data = [
                    'order_id' => $order->id,
                    'payment_method' => 'credit_card',
                    'card_number' => substr($credit_card->card_number, -4),
                    'card_expiry' => $credit_card->expiry_month.'/'.$credit_card->expiry_year
                ];

                OrderPaymentMethod::create($order_payment_method_data);

                $customer_profile_id = User::find($user->id)->customer_profile_id;

                $payment_transaction = CreditCard::chargeCreditCard($credit_card, $billing_address, $cart->grandTotal, $order->id, $user);

                if ($payment_transaction['status'] == 1)
                {
                    OrderJob::dispatch($order);
                    Order::find($order->id)->update(['shipping_status_id' => 4, 'order_status_id' => 4, 'payment_status' => 'Success']);

                    $order_products = $order->orderProducts;
                    foreach ($order_products as $order_product)
                    {
                        $product = Product::find($order_product->product_id);
                        $product->decrement('quantity', $order_product->quantity);
                    }

                    if ($placement_info->leg == 'auto')
                    {
                        $user_table_id = BusinessCenter::where('id', $placement_info->placement_id)->value('user_id');
                        $leg = User::where('id', $user_table_id)->value('leg');

                    }
                    else
                    {
                        $leg = $placement_info->leg;
                    }

                    if ($leg == 'default')
                    {
                        $left_point = BusinessCenter::where('user_id', $user->sponsor_id)->value('left_carry');
                        $right_point = BusinessCenter::where('user_id', $user->sponsor_id)->value('right_carry');

                        if ($left_point > $right_point)
                        {
                            $leg = 'R';
                        }
                        else
                        {
                            $leg = 'L';
                        }
                    }
                    $business_center = BusinessCenter::where('user_id', $user->id)->first();
                    if (Order::where('user_id', $user->id)->where('order_status_id', 4)->count() == 1)
                    {
                        if (is_null($business_center))
                        {
                            $business_center = BusinessCenter::create(['user_id' => $user->id, 'business_center' => $user->username.'-1']);
                        }

                        if (is_null(SponsorTree::where('sponsor_id', $user->id)->first()))
                        {
                            $sponsor_tree = SponsorTree::where('sponsor_id', $user->sponsor_id)->orderBy('id', 'desc')->first();
                            $sponsor_tree->user_id = $user->id;
                            $sponsor_tree->sponsor_id = $user->sponsor_id;
                            if ($user->usertype == "rc")
                            {
                                $sponsor_tree->type = "rc";
                            }
                            else
                            {
                                $sponsor_tree->type = "yes";
                            }
                            $sponsor_tree->save();

                            SponsorTree::create(['user_id' => 0, 'sponsor_id' => $user->sponsor_id, 'position' => $sponsor_tree->position + 1]);
                            SponsorTree::create(['user_id' => 0, 'sponsor_id' => $user->id, 'position' => 0 + 1]);
                        }

                        if ($user->usertype != 'rc' && $order_state == 'success' && is_null(TreeTable::where('placement_id', $business_center->id)->first()))
                        {
                            $placement_id = TreeTable::getPlacementId($placement_info->placement_id, $leg);

                            TreeTable::where('placement_id', $placement_id)->where("leg", $leg)->where("type", "=", "vaccant")->update(['business_center_id' => $business_center->id, 'sponsor_id' => $user->sponsor_id, 'placement_id' => $placement_id, 'leg' => $leg, 'type' => 'yes']);

                            TreeTable::create(['sponsor_id' => 0, 'business_center_id' => 0, 'placement_id' => $business_center->id, 'leg' => 'L', 'type' => 'vaccant']);
                            TreeTable::create(['sponsor_id' => 0, 'business_center_id' => 0, 'placement_id' => $business_center->id, 'leg' => 'R', 'type' => 'vaccant']);
                        }

                        ActivityLog::create(['user_id' => $user->id, 'ip_address' => $request->ip(), 'title' => 'Registered user '.$user->username, 'description' => 'Registered '.$user->username.' with Sponsor as '.$sponsor->username.' and Placement User as '.BusinessCenter::find($placement_info->placement_id)->business_center]);
                        ActivityLog::create(['user_id' => $user->id, 'ip_address' => $request->ip(), 'title' => 'Joined as '.$user->username, 'description' => 'Joined in system as '.$user->username.' with Sponsor as '.$sponsor->username.' and Placement User as '.BusinessCenter::find($placement_info->placement_id)->business_center]);
                    }

                    $sponsor_act = BusinessCenter::where('user_id', $user->sponsor_id)->value('id');

                    $total_bv = ($is_first_order == 1) ? $order->orderProducts->sum('qv') / 2 : $order->orderProducts->sum('qv');
                    if ($user->usertype == "rc")
                    {
                        BusinessCenter::updatePoint($total_bv, $cart->totalQV, $sponsor_act, "rc", $business_center->id);
                    }
                    else
                    {
                        BusinessCenter::updatePoint($total_bv, $cart->totalQV, $business_center->id, "binary", 0);
                    }

                    if ($cart->totalQV >= 100)
                    {
                        User::where('id', $user->sponsor_id)->update(['power_qualified' => 1]);

                        if($leg == 'L')
                        {
                            User::where('id', $user->sponsor_id)->increment('binary_left');
                        }
                        else
                        {
                            User::where('id', $user->sponsor_id)->increment('binary_right');
                        }

                        if ($order_state == 'success')
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
                        }

                    }

                    $total_qvs_sponsor_customers = OrderProduct::whereHas('order', function ($q) use ($sponsor) {
                        $q->where('order_status_id', 4)->where('sponsor_id', $sponsor->id)->whereHas('user', function ($q) {
                            $q->where('usertype', 'dc');
                        });
                    })->sum('qv');

                    if ($total_qvs_sponsor_customers >= 100 && $user->usertype == 'dc')
                    {
                        if($leg == 'L')
                        {
                            User::where('id', $user->sponsor_id)->update(['bingo_left_sponsored' => 1]);
                        }
                        else
                        {
                            User::where('id', $user->sponsor_id)->update(['bingo_right_sponsored' => 1]);
                        }
                    }

                    ActivityLog::create(['user_id' => $user->id, 'ip_address' => $request->ip(), 'title' => $user->username.' Placed Order', 'description' => $user->username.' Placed an Order']);

                    Cart::destroy([$cart->id]);
                    CartProduct::where('cart_id', $cart->id)->delete();

                    return response()->json(['data' => $order, 'status' => 200, 'message' => 'Transaction is successful']);
                }
                elseif ($payment_transaction['status'] == 0)
                {
                    $meta = (array)$order->meta;
                    $meta['transaction_response'] = $payment_transaction;
                    Order::find($order->id)->update(['order_status_id' => 7, 'shipping_status_id' => 2, 'payment_status' => 'Failed', 'meta' => $meta]);

                    ActivityLog::create(['user_id' => $user->id, 'ip_address' => $request->ip(), 'title' => $user->username.' Placed Order', 'description' => $user->username.' Placed an Order']);

                    return response()->json(['data' => $order, 'status' => 300, 'message' => $payment_transaction['errorMessage']]);
                }
            }
            elseif ($request->payment_method == 'cod')
            {
                $order_payment_method_data = [
                    'order_id' => $order->id,
                    'payment_method' => 'cash_on_delivery',
                    'card_number' => '',
                    'card_expiry' => ''
                ];

                OrderPaymentMethod::create($order_payment_method_data);
                Order::find($order->id)->update(['shipping_status_id' => 4]);

                ActivityLog::create(['user_id' => $user->id, 'ip_address' => $request->ip(), 'title' => $user->username.' Placed Order', 'description' => $user->username.' Placed an Order']);

                Cart::destroy([$cart->id]);
                CartProduct::where('cart_id', $cart->id)->delete();

                return response()->json(['data' => $order, 'status' => 200, 'message' => 'Order was successful']);
            }

        }
        catch (Exception $exception)
        {
            if (isset($order))
            {
                $request->merge(['order' => $order]);
            }
            ErrorLog::create(['code' => $exception->getCode(), 'error' => $exception->getMessage(), 'line_number' => $exception->getLine(), 'data' => $request->all()]);
            return response()->json(['error' => $exception->getMessage(), 'status' => $exception->getCode()]);
        }
    }

    public function placeOrderNew(Request $request)
    {
        try
        {
//           return $request->all();
            $placement_info = (object)$request->placement_info;
            $address = (object)$request->address;
            $user = (object)$request->user;
            $shipping_address = isset($request->shipping_address) ? (object)$request->shipping_address : $address;
            $sponsor = User::where(function ($q) use ($user) {
                if ((int)$user->sponsor_id > 0)
                {
                    $q->where('id', $user->sponsor_id);
                }
                else
                {
                    $q->where('username', $user->sponsor_id);
                }
            })->first();
//            $credit_card = CreditCard::with('billingAddress')->find($request->credit_card_id);
//            $billing_address = !is_null($request->billing_address_id) ? Address::find($request->billing_address_id) : $shipping_address;
            $credit_card = (object)$request->credit_card;
            $billing_address = $request->payment_method == 'credit_card' ? json_decode(json_encode($credit_card->billing_address)) : $address;
            $cart = (object)$request->cart;

            $address->state = State::find($address->state_id);
            $address->country = Country::find($address->country_id);
            $shipping_address->state = State::find($shipping_address->state_id);
            $shipping_address->country = Country::find($shipping_address->country_id);
            $billing_address->state = State::find($billing_address->state_id);
            $billing_address->country = Country::find($billing_address->country_id);

            $is_first_order = 1;
            if (isset($user->id))
            {
                if (Order::where('user_id', $user->id)->count() == 1 && Order::where('user_id', $user->id)->first()->order_status_id == 1)
                {
                    return ['status' => 2, 'message' => 'Your previous cash order is not approved yet. Please contact Ultrra Corp. helpline cc@ultrra.com (+1) 888.981.1711.'];
                }

                $order_ids = Order::where('user_id', $user->id)->where('order_status_id', 4)->pluck('id');
                $total_existing_qv = OrderProduct::whereIn('order_id', $order_ids)->sum('qv');
                if($total_existing_qv == 0)
                {
                    if ($cart->totalQV > 0)
                    {
                        $is_first_order = 1;
                    }
                    else
                    {
                        $is_first_order = 0;
                    }
                }
                else
                {
                    $is_first_order = 0;
                }
            }
            else
            {
                if ($cart->totalQV > 0)
                {
                    $is_first_order = 1;
                }
                else
                {
                    $is_first_order = 0;
                }
            }

            if ($request->payment_method == 'credit_card' && $request->cvv != $credit_card->cvv)
            {
                return ['status' => 2, 'message' => 'Please enter correct CVV'];
            }

            $existing_users_count = User::where(function ($q) use ($user) {
                if (isset($user->id))
                {
                    $q->where('id', '!=', $user->id)->where(function ($q) use ($user) {
                        $q->where('username', $user->username)->orWhere('email', $user->email);
                    });
                }
                else
                {
                    $q->where('username', $user->username)->orWhere('email', $user->email);
                }
            })->count();
            if ($existing_users_count > 0)
            {
                return response()->json(['status' => 2, 'message' => 'User with this username or email already exist']);
            }

            /* Check Maximum Quantity of product */
            $productname_quantity_error = [];
            for ($i = 0; $i < count($cart->products); $i++)
            {
                $product_maximum_quantity = Product::find($cart->products[$i]['product_id'])->maximum_quantity;
                $product_quantity = $cart->products[$i]['quantity'];
                if ($product_maximum_quantity != 0)
                {
                    if ($product_quantity > $product_maximum_quantity)
                    {
                        $productname_quantity_error[$i]['product_name'] = $cart->products[$i]['product']['name'];
                        $productname_quantity_error[$i]['product_maximum_quantity'] = $product_maximum_quantity;
                    }
                }
            }
            if (sizeof($productname_quantity_error) > 0)
            {
                $message = '';
                foreach ($productname_quantity_error as $error)
                {
                    $message .= $error['product_name'].' quantity is greater than maximum available quantity '.$error['product_maximum_quantity'].'. ';
                }
                return response()->json(['status' => 2, 'message' => $message]);
            }

            $products_id = collect($cart->products)->pluck('product_id');

            $is_backorder = 0;
            if (Product::whereIn('id', $products_id)->where('maximum_quantity', '<=', 0)->count() > 0)
            {
                $is_backorder = 1;
            }

            $orderData = [
                'user_id' => isset($user->id) ? $user->id : null,
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'joint_firstname' => isset($user->joint_firstname) ? $user->joint_firstname : null,
                'joint_lastname' => isset($user->joint_lastname) ? $user->joint_lastname : null,
                'mobile' => $user->phone,
                'email' => $user->email,
                'country_id' => $address->country_id,
                'state_id' => $address->state_id,
                'postcode' => $address->postcode,
                'city' => $address->city,
                'address_1' => $address->address_1,
                'address_2' => $address->address_2,
                'shipping_firstname' => $shipping_address->contact_name,
                'shipping_lastname' => '',
                'shipping_email' => $user->email,
                'shipping_mobile' => $shipping_address->contact_number,
                'shipping_company' => '',
                'shipping_address_1' => $shipping_address->address_1,
                'shipping_address_2' => $shipping_address->address_2,
                'shipping_city' => $shipping_address->city,
                'shipping_postcode' => $shipping_address->postcode,
                'shipping_country_id' => $shipping_address->country_id,
                'shipping_state_id' => $shipping_address->state_id,
                'tax' => $cart->taxTotal,
                'handling_charges' => $cart->handlingCharges,
                'total' => $cart->grandTotal,
                'sub_total' => $cart->subTotal,
                'ip' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'shipping_method' => $cart->shippingMethod,
                'shipping_price' => $cart->shippingTotal,
                'shipping_status' => '',
                'billing_firstname' => $billing_address->contact_name,
                'billing_lastname' => '',
                'billing_address_1' => $billing_address->address_1,
                'billing_address_2' => $billing_address->address_2,
                'billing_city' => $billing_address->city,
                'billing_postcode' => $billing_address->postcode,
                'billing_country_id' => $billing_address->country_id,
                'billing_state_id' => $billing_address->state_id,
                'is_first_order' => $is_first_order,
                'is_backorder' => $is_backorder,
                'sponsor_id' => $sponsor->id,
                'note' => $request->notes,
                'meta' => ['placement' => $placement_info, 'total_qv' => $cart->totalQV, 'user' => $user, 'address' => $address, 'shipping_address' => $shipping_address]
            ];

            $order_state = '';

            if ($user->usertype != 'rc')
            {
                if ($sponsor->user_state == 'waiting')
                {
                    $order_state = 'waiting';
                }
                elseif ($sponsor->user_state == 'success')
                {
                    if (isset($placement_info->placement_type) && $placement_info->placement_type == 'manual')
                    {
                        $order_state = 'success';
                    }
                    else
                    {
                        if ($user->enrollment_type == 'bingo_tree')
                        {
                            $order_state = 'success';
                        }
                        else
                        {
                            $order_state = 'waiting';
                        }
                    }
                }
            }
            else
            {
                if ($sponsor->user_state == 'waiting')
                {
                    $order_state = 'waiting';
                }
                elseif ($sponsor->user_state == 'success')
                {
                    $order_state = 'success';
                }
            }

            if (isset($user->user_state) && !is_null($user->user_state))
            {
                $order_state = $user->user_state;
            }

            $order = Order::create($orderData);

            foreach ($cart->products as $cart_product)
            {
                $cart_product = json_decode(json_encode($cart_product));
                $product = Product::find($cart_product->product_id);
                $price = $user->usertype == 'dc' ? $cart_product->product->distributor_price : ($user->usertype == 'pc' ? $cart_product->product->preferred_customer_price : $cart_product->product->retail_customer_price);
                $retail_price = $cart_product->product->retail_customer_price;
                $pc_price = $cart_product->product->preferred_customer_price;
                $member_price = $cart_product->product->distributor_price;
                $qv = $cart_product->product->qv;
                $bv = $qv/2;
                if ($product->productCountries->count() && !is_null($product->productCountries->first(function ($value) use ($address) { return $value->country_id == $address->country_id; })))
                {
                    $product_country = $product->productCountries->first(function ($value) use ($address) {
                        return $value->country_id == $address->country_id;
                    });
                    if ($user->usertype == 'dc')
                    {
                        $price = $product_country->distributor_price;
                    }
                    elseif ($user->usertype == 'pc')
                    {
                        $price = $product_country->preferred_customer_price;
                    }
                    else
                    {
                        $price = $product_country->retail_customer_price;
                    }
                    $retail_price = $product_country->product->retail_customer_price;
                    $pc_price = $product_country->product->preferred_customer_price;
                    $member_price = $product_country->product->distributor_price;
                    $qv = $product_country->qv;
                    $bv = $qv/2;
                }

                $productData = [
                    'order_id' => $order->id,
                    'product_id' => $cart_product->product_id,
                    'name' => $cart_product->product->name,
                    'model' => $cart_product->product->name,
                    'quantity' => $cart_product->quantity,
                    'price' => $price,
                    'retail_price' => $retail_price,
                    'member_price' => $member_price,
                    'pc_price' => $pc_price,
                    'qv' => $qv * $cart_product->quantity,
                    'bv' => ($is_first_order == 1) ? ($bv * $cart_product->quantity) : ($qv * $cart_product->quantity),
                    'total' => $cart_product->quantity * $price,
                ];

                $product_quantity = $product->maximum_quantity;

                if ($product_quantity <= 0)
                {
                    $productData['backorder_quantity'] = $productData['quantity'];
                }
                elseif ($productData['quantity'] > $product_quantity)
                {
                    $productData['backorder_quantity'] = $productData['quantity'] - $product_quantity;
                }
                else
                {
                    $productData['backorder_quantity'] = 0;
                }

                $order_product = OrderProduct::create($productData);

                if(count($cart_product->product->product_components) > 0)
                {
                    $product_components = [];
                    foreach($cart_product->product->product_components as $product_component)
                    {
                        $product_component_price = $user->usertype == 'dc' ? $product_component->distributor_price : ($user->usertype == 'pc' ? $product_component->preferred_customer_price : $product_component->retail_customer_price);
                        $componentData = [
                            'order_product_id' => $order_product->id,
                            'component_id' => $product_component->id,
                            'name' => $product_component->name,
                            'model' => $product_component->name,
                            'quantity' => $product_component->pivot->quantity,
                            'price' => $product_component_price,
                            'qv' => $product_component->qv * $product_component->pivot->quantity,
                            'bv' =>  ($is_first_order == 1) ? ($bv * $cart_product->quantity) : ($qv * $cart_product->quantity),
                            'total' => $product_component->pivot->quantity * $product_component_price,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                        ];

                        $product_components[] = $componentData;
                    }
                    OrderProductComponent::insert($product_components);
                }

            }

            if ($request->payment_method === 'credit_card')
            {
                $order_payment_method_data = [
                    'order_id' => $order->id,
                    'payment_method' => 'credit_card',
                    'card_number' => substr($credit_card->card_number, -4),
                    'card_expiry' => $credit_card->expiry_month.'/'.$credit_card->expiry_year
                ];

                OrderPaymentMethod::create($order_payment_method_data);

//                $customer_profile_id = User::find($user->id)->customer_profile_id;
//                $credit_card->card_number = '4561 7455 1234 7896';
                $payment_transaction = CreditCard::chargeCreditCard($credit_card, $billing_address, $cart->grandTotal, $order->id, $user);

                if ($payment_transaction['status'] == 1)
                {
                    unset($user->leg);
                    unset($user->placement_id);
                    unset($user->placement_search_id);
                    unset($user->password_confirmation);
                    if (!isset($user->id))
                    {
                        $user = (array)$user;
                        $user['sponsor_id'] = $sponsor->id;
                        $user['password'] = Hash::make($user['password']);
                        $user = User::create($user);
                        User::find($user->id)->update(['user_state' => $order_state, 'users_ip' => $request->ip()]);
                        $address = (array)$address;
                        if (!isset($address['id']))
                        {
                            Address::create([
                                'user_id' => $user->id,
                                'contact_number' => $address['contact_number'],
                                'contact_name' => $address['contact_name'],
                                'address_1' => $address['address_1'],
                                'address_2' => $address['address_2'],
                                'city' => $address['city'],
                                'postcode' => $address['postcode'],
                                'state_id' => $address['state_id'],
                                'country_id' => $address['country_id'],
                            ]);
                        }
                        $shipping_address = (array)$shipping_address;
                        if (!isset($shipping_address['id']))
                        {
                            Address::create([
                                'user_id' => $user->id,
                                'contact_number' => $shipping_address['phone'],
                                'contact_name' => $shipping_address['contact_name'],
                                'address_1' => $shipping_address['address_1'],
                                'address_2' => $shipping_address['address_2'],
                                'city' => $shipping_address['city'],
                                'postcode' => $shipping_address['postcode'],
                                'state_id' => $shipping_address['state_id'],
                                'country_id' => $shipping_address['country_id'],
                                'is_shipping' => 1
                            ]);
                        }
                    }

                    Order::find($order->id)->update(['user_id' => $user->id, 'shipping_status_id' => 4, 'order_status_id' => 4, 'payment_status' => 'Success']);
                    OrderJob::dispatch($order);
                    $order = Order::find($order->id);
                    $order_products = $order->orderProducts;
                    foreach ($order_products as $order_product)
                    {
                        $product = Product::find($order_product->product_id);
                        $product->decrement('quantity', $order_product->quantity);
                    }

                    if ($placement_info->leg == 'auto')
                    {
                        $user_table_id = BusinessCenter::where('id', $placement_info->placement_id)->value('user_id');
                        $leg = User::where('id', $user_table_id)->value('leg');

                    }
                    else
                    {
                        $leg = $placement_info->leg;
                    }

                    if ($leg == 'default')
                    {
                        $left_point = BusinessCenter::where('user_id', $user->sponsor_id)->value('left_carry');
                        $right_point = BusinessCenter::where('user_id', $user->sponsor_id)->value('right_carry');

                        if ($left_point > $right_point)
                        {
                            $leg = 'R';
                        }
                        else
                        {
                            $leg = 'L';
                        }
                    }
                    $business_center = BusinessCenter::where('user_id', $user->id)->first();
                    if (Order::where('user_id', $user->id)->where('order_status_id', 4)->count() == 1)
                    {
                        if (is_null($business_center))
                        {
                            $business_center = BusinessCenter::create(['user_id' => $user->id, 'business_center' => $user->username.'-1']);
                        }

                        if (is_null(SponsorTree::where('sponsor_id', $user->id)->first()))
                        {
                            $sponsor_tree = SponsorTree::where('sponsor_id', $user->sponsor_id)->orderBy('id', 'desc')->first();
                            $sponsor_tree->user_id = $user->id;
                            $sponsor_tree->sponsor_id = $user->sponsor_id;
                            if ($user->usertype == "rc")
                            {
                                $sponsor_tree->type = "rc";
                            }
                            else
                            {
                                $sponsor_tree->type = "yes";
                            }
                            $sponsor_tree->save();

                            SponsorTree::create(['user_id' => 0, 'sponsor_id' => $user->sponsor_id, 'position' => $sponsor_tree->position + 1]);
                            SponsorTree::create(['user_id' => 0, 'sponsor_id' => $user->id, 'position' => 0 + 1]);
                        }

                        if ($user->usertype != 'rc' && $order_state == 'success' && is_null(TreeTable::where('placement_id', $business_center->id)->first()))
                        {
                            $placement_id = TreeTable::getPlacementId($placement_info->placement_id, $leg);

                            TreeTable::where('placement_id', $placement_id)->where("leg", $leg)->where("type", "=", "vaccant")->update(['business_center_id' => $business_center->id, 'sponsor_id' => $user->sponsor_id, 'placement_id' => $placement_id, 'leg' => $leg, 'type' => 'yes']);

                            TreeTable::create(['sponsor_id' => 0, 'business_center_id' => 0, 'placement_id' => $business_center->id, 'leg' => 'L', 'type' => 'vaccant']);
                            TreeTable::create(['sponsor_id' => 0, 'business_center_id' => 0, 'placement_id' => $business_center->id, 'leg' => 'R', 'type' => 'vaccant']);
                        }

                        ActivityLog::create(['user_id' => $user->id, 'ip_address' => $request->ip(), 'title' => 'Registered user '.$user->username, 'description' => 'Registered '.$user->username.' with Sponsor as '.$sponsor->username.' and Placement User as '.BusinessCenter::find($placement_info->placement_id)->business_center]);
                        ActivityLog::create(['user_id' => $user->id, 'ip_address' => $request->ip(), 'title' => 'Joined as '.$user->username, 'description' => 'Joined in system as '.$user->username.' with Sponsor as '.$sponsor->username.' and Placement User as '.BusinessCenter::find($placement_info->placement_id)->business_center]);
                    }

                    $sponsor_act = BusinessCenter::where('user_id', $user->sponsor_id)->value('id');

                    $total_bv = ($is_first_order == 1) ? $order->orderProducts->sum('qv') / 2 : $order->orderProducts->sum('qv');
                    if ($user->usertype == "rc")
                    {
                        BusinessCenter::updatePoint($total_bv, $cart->totalQV, $sponsor_act, "rc", $business_center->id);
                    }
                    else
                    {
                        BusinessCenter::updatePoint($total_bv, $cart->totalQV, $business_center->id, "binary", 0);
                    }

                    if ($cart->totalQV >= 100)
                    {
                        User::where('id', $user->sponsor_id)->update(['power_qualified' => 1]);

                        if($leg == 'L')
                        {
                            User::where('id', $user->sponsor_id)->increment('binary_left');
                        }
                        else
                        {
                            User::where('id', $user->sponsor_id)->increment('binary_right');
                        }

                        if ($order_state == 'success')
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
                        }

                    }

                    $total_qvs_sponsor_customers = OrderProduct::whereHas('order', function ($q) use ($sponsor) {
                        $q->where('order_status_id', 4)->where('sponsor_id', $sponsor->id)->whereHas('user', function ($q) {
                            $q->where('usertype', 'dc');
                        });
                    })->sum('qv');

                    if ($total_qvs_sponsor_customers >= 100 && $user->usertype == 'dc')
                    {
                        if($leg == 'L')
                        {
                            User::where('id', $user->sponsor_id)->update(['bingo_left_sponsored' => 1]);
                        }
                        else
                        {
                            User::where('id', $user->sponsor_id)->update(['bingo_right_sponsored' => 1]);
                        }
                    }

                    ActivityLog::create(['user_id' => $user->id, 'ip_address' => $request->ip(), 'title' => $user->username.' Placed Order', 'description' => $user->username.' Placed an Order']);

                    Cart::destroy([$cart->id]);
                    CartProduct::where('cart_id', $cart->id)->delete();

                    return response()->json(['data' => $order, 'status' => 200, 'message' => 'Transaction is successful']);
                }
                elseif ($payment_transaction['status'] == 0)
                {
                    $meta = (array)$order->meta;
                    $meta['transaction_response'] = $payment_transaction;
                    Order::find($order->id)->update(['order_status_id' => 7, 'shipping_status_id' => 2, 'payment_status' => 'Failed', 'meta' => $meta]);

                    if (isset($user->id))
                    {
                        ActivityLog::create(['user_id' => $user->id, 'ip_address' => $request->ip(), 'title' => $user->username.' Placed Order', 'description' => $user->username.' Placed an Order']);
                    }

                    return response()->json(['data' => $order, 'status' => 300, 'message' => $payment_transaction['errorMessage']]);
                }
            }
            elseif ($request->payment_method == 'cod')
            {
                $order_payment_method_data = [
                    'order_id' => $order->id,
                    'payment_method' => 'cash_on_delivery',
                    'card_number' => '',
                    'card_expiry' => ''
                ];

                OrderPaymentMethod::create($order_payment_method_data);
                Order::find($order->id)->update(['shipping_status_id' => 4]);

//                ActivityLog::create(['user_id' => $user->id, 'ip_address' => $request->ip(), 'title' => $user->username.' Placed Order', 'description' => $user->username.' Placed an Order']);

                Cart::destroy([$cart->id]);
                CartProduct::where('cart_id', $cart->id)->delete();

                foreach($cart->products as $cart_product)
                {
                    if (in_array($cart_product['product_id'], [83, 84]) && OrderProduct::where('order_id', $order->id)->whereIn('product_id', [83, 84])->value('price') == 0)
                    {
                        self::update(new Request(['order_status_id' => 4]), $order->id);
                    }
                }

                return response()->json(['data' => $order, 'status' => 200, 'message' => 'Order was successful']);
            }

        }
        catch (Exception $exception)
        {
            if (isset($order))
            {
                $request->merge(['order' => $order]);
            }
            ErrorLog::create(['code' => $exception->getCode(), 'error' => $exception->getMessage(), 'line_number' => $exception->getLine(), 'data' => $request->all()]);
            return response()->json(['error' => $exception->getMessage(), 'status' => $exception->getCode()]);
        }
    }

    public function cashPendingOrders(Request $request)
    {
        if ($request->ajax())
        {
            $order = Order::with(['user.sponsor', 'sponsor', 'paymentMethods', 'orderProducts'])->where('order_status_id', 1);

            return DataTables::of($order)
                ->editColumn('created_at', function ($row) {
                    return date('m/d/Y h:i A', strtotime($row->created_at));
                })
                ->addColumn('user_username', function ($row) {
                    $username = $row->user ? $row->user->username : '';
                    return '<a href="/users/profile/'.$row->user_id.'" target="_blank">'.$username.'<br>('.$row->user_id.')</a>';
                })
                ->addColumn('user_name', function ($row) {
                    return trim($row->firstname . ' ' . $row->lastname);
                })
                ->addColumn('user_email', function ($row) {
                    return $row->email;
                })
                ->addColumn('sponsor_username', function ($row) {
                    $username = $row->sponsor ? $row->sponsor->username : '';
                    return '<a href="/users/profile/'.$row->sponsor_id.'" target="_blank">'.$username.'<br>('.$row->sponsor_id.')</a>';
                })
                ->addColumn('sponsor_name', function ($row) {
                    return $row->sponsor ? $row->sponsor->firstname.' '.$row->sponsor->lastname : '';
                })
                ->addColumn('payment_date', function ($row) {
//                    return Carbon::createFromFormat('Y-m-d H:i:s', count($row->paymentMethods) > 0 ? $row->paymentMethods[0]->created_at : date('Y-m-d H:i:s'))->format('m/d/Y');
                    return Carbon::createFromFormat('Y-m-d H:i:s', $row->updated_at)->format('m/d/Y');
                })
                ->addColumn('role', function ($row) {
                    return $row->user ? $row->user->usertype : '';
                })
                ->addColumn('first_order', function ($row) {
                    return $row->is_first_order ? '<i class="fas fa-check text-success"></i>' : '';
                })
                ->addColumn('payment_method', function ($row) {
                    $payment_methods = $row->paymentMethods->map(function ($payment_method) {
                        if ($payment_method->payment_method == 'credit_card')
                        {
                            return 'CC';
                        }
                        elseif ($payment_method->payment_method == 'ewallet')
                        {
                            return 'EW';
                        }
                        elseif ($payment_method->payment_method == 'cash_on_delivery')
                        {
                            return 'Cash';
                        }
                    });
                    return '<div style="display: flex"><span class="text-capitalize" style="float: left;">'.implode(', ', json_decode($payment_methods)).'</span></div></div>';
                })
                ->addColumn('actions', function ($row) {
                    return '<a href="'.url('/invoice/'.$row->id).'" target="_blank" class="btn btn-sm btn-info">&nbsp;<i class="fa fa-eye fa-sm"></i>&nbsp;</a><a href="#" class="btn btn-sm btn-primary" onclick="saveOrder('.$row->id.')">&nbsp;<i class="fa fa-check-square fa-sm"></i>&nbsp;</a><a href="#" class="btn btn-sm btn-danger" onclick="cancelOrder('.$row->id.')">&nbsp;<i class="fa fa-times fa-sm"></i>&nbsp;</a>';
                })
                ->setRowClass(function ($row) {
                    return $row->order_status_id == 5 ? 'red' : '';
                })
                ->escapeColumns([])
                ->make(true);
        }

        return view('orders.cash-pending-orders');
    }

    public function cashPendingOrdersNew(Request $request)
    {
        if ($request->ajax())
        {
            $order = Order::with(['user.sponsor', 'sponsor', 'paymentMethods', 'orderProducts'])->where('order_status_id', 1);

            return DataTables::of($order)
                ->editColumn('created_at', function ($row) {
                    return date('m/d/Y h:i A', strtotime($row->created_at));
                })
                ->addColumn('user_username', function ($row) {
                    $username = $row->user ? $row->user->username : (isset($row->meta->user) ? $row->meta->user->username : '');
                    return '<a href="'.($row->user_id ? '/users/profile/'.$row->user_id : 'javascript:void(0)').'" target="_blank">'.$username.'<br>'.($row->user_id ? '('.$row->user_id.')' : '').'</a>';
                })
                ->addColumn('user_name', function ($row) {
                    return trim($row->firstname . ' ' . $row->lastname);
                })
                ->addColumn('user_email', function ($row) {
                    return $row->email;
                })
                ->addColumn('sponsor_username', function ($row) {
                    $username = $row->sponsor ? $row->sponsor->username : '';
                    return '<a href="/profile/'.$row->sponsor_id.'" target="_blank">'.$username.'<br>('.$row->sponsor_id.')</a>';
                })
                ->addColumn('sponsor_name', function ($row) {
                    return $row->sponsor ? $row->sponsor->firstname.' '.$row->sponsor->lastname : '';
                })
                ->addColumn('payment_date', function ($row) {
//                    return Carbon::createFromFormat('Y-m-d H:i:s', count($row->paymentMethods) > 0 ? $row->paymentMethods[0]->created_at : date('Y-m-d H:i:s'))->format('m/d/Y');
                    return Carbon::createFromFormat('Y-m-d H:i:s', $row->updated_at)->format('m/d/Y');
                })
                ->addColumn('role', function ($row) {
                    return $row->user ? $row->user->usertype : (isset($row->meta->user) ? $row->meta->user->usertype : '');
                })
                ->addColumn('first_order', function ($row) {
                    return $row->is_first_order ? '<i class="fas fa-check text-success"></i>' : '';
                })
                ->addColumn('payment_method', function ($row) {
                    $payment_methods = $row->paymentMethods->map(function ($payment_method) {
                        if ($payment_method->payment_method == 'credit_card')
                        {
                            return 'CC';
                        }
                        elseif ($payment_method->payment_method == 'ewallet')
                        {
                            return 'EW';
                        }
                        elseif ($payment_method->payment_method == 'cash_on_delivery')
                        {
                            return 'Cash';
                        }
                    });
                    return '<div style="display: flex"><span class="text-capitalize" style="float: left;">'.implode(', ', json_decode($payment_methods)).'</span></div></div>';
                })
                ->addColumn('actions', function ($row) {
                    return '<a href="'.url('/invoice/'.$row->id).'" target="_blank" class="btn btn-sm btn-info">&nbsp;<i class="fa fa-eye fa-sm"></i>&nbsp;</a><a href="#" class="btn btn-sm btn-primary" onclick="saveOrder('.$row->id.')">&nbsp;<i class="fa fa-check-square fa-sm"></i>&nbsp;</a><a href="#" class="btn btn-sm btn-danger" onclick="cancelOrder('.$row->id.')">&nbsp;<i class="fa fa-times fa-sm"></i>&nbsp;</a>';
                })
                ->setRowClass(function ($row) {
                    return $row->order_status_id == 5 ? 'red' : '';
                })
                ->escapeColumns([])
                ->make(true);
        }

        return view('orders.cash-pending-orders');
    }

}
