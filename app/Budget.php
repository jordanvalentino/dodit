<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    protected $fillable = [
    	'title', 'amount', 'saved', 'start', 'end', 'is_finished', 'user_id'
    ];

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function details()
    {
    	return $this->hasMany('App\Detail');
    }
}
