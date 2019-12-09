<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Transaction extends Model
{
    protected $fillable = [
    	'amount', 'detail', 'attachment', 'category_id'
    ];

    public function category()
    {
    	return $this->belongsTo('App\Category');
    }

    public static function is_exist()
    {
        return Auth::user()->transactions()->count() > 0;
    }

    public static function total_earnings()
    {
    	return DB::table('transactions')
				->join('categories', 'transactions.category_id', 'categories.id')
                ->where('categories.type', '=', 'db')
                ->where('categories.user_id', Auth::id())
                ->sum('transactions.amount');
    }

    public static function total_spendings()
    {
    	return DB::table('transactions')
                ->join('categories', 'transactions.category_id', 'categories.id')
                ->where('categories.type', '=', 'cr')
                ->where('categories.user_id', Auth::id())
                ->sum('transactions.amount');
    }
}
