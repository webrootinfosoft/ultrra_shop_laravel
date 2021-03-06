<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
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

    public function addresses()
    {
        return $this->hasMany('App\Address');
    }
}
