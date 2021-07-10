<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'firstname', 'lastname', 'joint_firstname', 'joint_lastname', 'username', 'email', 'password', 'dateofbirth', 'phone', 'address1', 'address2', 'city', 'postcode', 'country_id', 'state_id', 'image', 'gender', 'left_dc', 'right_dc', 'usertype', 'language_id', 'tax_id', 'tax_exemption', 'sponsor_id', 'sort_code', 'hw_token', 'secondary_phone', 'ssn_number', 'leg', 'maintenance_date', 'lifetime_rank_id', 'qualified_status', 'is_forced_qualified_status', 'power_qualified', 'binary_qualified', 'global_qualified', 'force_global_shares', 'active_status', 'fob_status', 'binary_left', 'binary_right', 'matchingbonus_percentage', 'is_forced_matchingbonus_percentage', 'supervisor_rank', 'director_rank', 'onestar_rank', 'active_left', 'active_right', 'active_left_previous', 'active_right_previous', 'inactive_left_previous', 'inactive_right_previous', 'is_forced_active_status', 'forced_pqv', 'level', 'monthly_maintenance', 'renewal_date', 'entity', 'company', 'gender', 'sort_code', 'status', 'users_ip', 'user_state', 'created_by', 'customer_profile_id', 'enrollment_type', 'last_login_at', 'team_name', 'bingo_left_sponsored', 'bingo_right_sponsored', 'matchingbonus_maintenance', 'created_at', 'updated_at', 'deleted_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'name', 'joint_name'
    ];

    public function getNameAttribute()
    {
        return $this->firstname.' '.$this->lastname;
    }

    public function getJointNameAttribute()
    {
        return $this->joint_firstname.' '.$this->joint_lastname;
    }

    public function sponsor()
    {
        return $this->belongsTo('App\User', 'sponsor_id');
    }

    public function users()
    {
        return $this->hasMany('App\User', 'sponsor_id');
    }

    public function country()
    {
        return $this->belongsTo('App\Country');
    }

    public function state()
    {
        return $this->belongsTo('App\State');
    }

    public function creator()
    {
        return $this->belongsTo('App\Admin', 'created_by');
    }

    public function commissions()
    {
        return $this->hasMany('App\Commission');
    }

    public function addresses()
    {
        return $this->hasMany('App\Address');
    }

    public function carts()
    {
        return $this->hasMany('App\Cart');
    }

    public function ewalletTransactions()
    {
        return $this->hasMany('App\EwalletTransaction');
    }

    public function rank()
    {
        return $this->belongsTo('App\RankStatusSetting', 'qualified_status');
    }

    public function pointHistories()
    {
        return $this->hasMany('App\PointHistory');
    }

    public function businessCenters()
    {
        return $this->hasMany('App\BusinessCenter');
    }

    public function creditCards()
    {
        return $this->hasMany('App\CreditCard');
    }

    public function rankSetting()
    {
        return $this->belongsTo('App\RankSetting', 'lifetime_rank_id');
    }

    public function rankStatusSetting()
    {
        return $this->belongsTo('App\RankStatusSetting', 'qualified_status');
    }

    public function orders()
    {
        return $this->hasMany('App\Order');
    }

    public function sponsorTrees()
    {
        return $this->hasMany('App\SponsorTree');
    }

    public function qualifiedStatusHistories()
    {
        return $this->hasMany('App\QualifiedStatusHistory');
    }

    public static function getUserColumns()
    {
        return ['email', 'firstname', 'lastname', 'username', 'password'];
    }

    public static function getSponsorColumns()
    {
        return ['email', 'firstname', 'lastname', 'username', 'phone'];
    }
}
