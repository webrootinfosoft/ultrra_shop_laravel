<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ErrorLog extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code', 'error', 'line_number', 'data',
    ];
}
