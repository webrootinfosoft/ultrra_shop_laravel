<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StateTax extends Model
{
    use SoftDeletes;

    protected $fillable = ['state_id', 'tax_percentage', 'other_charges'];

    protected $with = ['state'];

    public function state()
    {
        return $this->belongsTo('App\State');
    }
}
