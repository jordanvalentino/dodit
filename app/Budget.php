<?php

namespace App;

use DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
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

    public static function fromRequest(Request $request)
    {
        return new self([
            'title' => $request->title,
            'amount' => $request->amount,
            'start' => $request->start,
            'end' => $request->end,
            'is_finished' => false,
            'user_id' => Auth::id(),
        ]);
    }

    public function hasDetails()
    {
        return $this->details()->count() > 0;
    }

    public function stored()
    {
        return DB::table('details')
                ->join('budgets', 'details.budget_id', 'budgets.id')
                ->where('budgets.id', '=', $this->id)
                ->where('budgets.user_id', '=', Auth::id())
                ->sum('details.amount');
    }

    public function progress()
    {
        $progress = $this->stored() / $this->amount;
        return ($progress <= 1) ? $progress : 1;
    }

    public function dueDate()
    {
        return Carbon::parse($this->end)->diffInDays(Carbon::today());
    }

    public static function isExist()
    {
        return Auth::user()->budgets()->count() > 0;
    }

    public static function finished()
    {
        return Auth::user()->budgets()->where('is_finished', '=', '1')->get();
    }

    public static function ongoing()
    {
        return Auth::user()->budgets()->where('is_finished', '=', '0')->get();
    }

    public static function willOverdue()
    {
        return self::ongoing()
                ->where('end', '>=', Carbon::today())
                ->where('end', '<=', Carbon::today()->addDays(7));
    }

    public static function overdue()
    {
        return self::ongoing()
                ->where('end', '<', Carbon::today());
    }
}
