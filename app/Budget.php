<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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

    public static function finished()
    {
        return Auth::user()->budgets()->where('is_finished', '=', '1')->get();
    }

    public static function ongoing()
    {
        return Auth::user()->budgets()->where('is_finished', '=', '0')->get();
    }
}
