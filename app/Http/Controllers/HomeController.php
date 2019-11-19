<?php

namespace App\Http\Controllers;

use App\Category;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $db_cats = Auth::user()->categories()
                        ->where('type', 'db')
                        ->count();

        $cr_cats = Auth::user()->categories()
                        ->where('type', 'cr')
                        ->count();

        if ($db_cats == 0 || $cr_cats == 0) {
            return redirect('debit_category');
        }

        $total_earnings = DB::table('transactions')
                        ->join('categories', 'transactions.category_id', 'categories.id')
                        ->where('categories.type', '=', 'db')
                        ->where('categories.user_id', Auth::id())
                        ->sum('transactions.amount');

        $total_spendings = DB::table('transactions')
                        ->join('categories', 'transactions.category_id', 'categories.id')
                        ->where('categories.type', '=', 'cr')
                        ->where('categories.user_id', Auth::id())
                        ->sum('transactions.amount');

        $finished_plans = Auth::user()->budgets()
                        ->where('is_finished', '=', '1')
                        ->count();

        $ongoing_plans = Auth::user()->budgets()
                        ->where('is_finished', '=', '0')
                        ->count();

        return view('home', [
            'total_earnings' => $total_earnings,
            'total_spendings' => $total_spendings,
            'finished_plans' =>$finished_plans,
            'ongoing_plans' => $ongoing_plans,
        ]);
    }
}
