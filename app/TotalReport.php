<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TotalReport extends Model
{
    use SoftDeletes;

    protected $fillable = ['subtotal', 'total_shipping', 'total_tax', 'total_handling_charges', 'total', 'cc_total', 'cash_total', 'ewallet_total', 'total_qv', 'total_bv', 'commission', 'total_pusd', 'total_comm_pusd', 'fee', 'fee_cash', 'field_percentage', 'corp', 'corp_of_qv', 'corp_of_rev', 'daily_average', 'payout_request', 'start_date', 'end_date', 'report_type'];
}
