<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LinkedSocialAccount extends Model
{
    use SoftDeletes;

    protected $fillable = ['provider_id', 'provider_name', 'user_id'];
}
