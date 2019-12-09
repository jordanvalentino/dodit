<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Transaction extends Model
{
    protected $fillable = [
    	'amount', 'detail', 'attachment', 'category_id'
    ];

    public function category()
    {
    	return $this->belongsTo('App\Category');
    }

    public static function fromRequest(Request $request)
    {
        $path = NULL;
        if ($request->file('attachment') != null)
        {
            $path = $request->file('attachment')->store('public');
            Storage::setVisibility($path, 'public');
        }

        return new self([
            'amount' => $request->amount,
            'detail' => $request->detail,
            'attachment' => $path,
        ]);
    }

    public static function isExist()
    {
        return Auth::user()->transactions()->count() > 0;
    }

    public static function order_by_date($reverse = false)
    {
        return Auth::user()->transactions()
                ->orderBy('created_at', $reverse? 'desc': 'asc')
                ->get();
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

    public static function monthly($month, $year)
    {
        return Auth::user()->transactions()
                ->whereMonth('transactions.created_at', $month)
                ->whereYear('transactions.created_at', $year)
                ->orderBy('transactions.created_at', 'desc')
                ->get();
    }

    public static function annually($year)
    {
        return Auth::user()->transactions()
                ->whereYear('transactions.created_at', $year)
                ->orderBy('transactions.created_at', 'desc')
                ->get();
    }

    public static function get_available_month_year()
    {
        return DB::table('transactions')
                ->join('categories', 'transactions.category_id', '=', 'categories.id')
                ->select(DB::raw('MONTHNAME(transactions.created_at) as month, YEAR(transactions.created_at) as year'))
                ->where('categories.user_id', Auth::id())
                ->orderBy('transactions.created_at')
                ->distinct()
                ->get();
    }

    public static function get_available_year()
    {
        return DB::table('transactions')
                ->join('categories', 'transactions.category_id', '=', 'categories.id')
                ->select(DB::raw('YEAR(transactions.created_at) as year'))
                ->where('categories.user_id', Auth::id())
                ->distinct()
                ->get();
    }
}
