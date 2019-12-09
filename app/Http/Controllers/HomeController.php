<?php

namespace App\Http\Controllers;

use App\Budget;
use App\Category;
use App\Transaction;
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
        // $db_cats = Auth::user()->categories()
        //                 ->where('type', 'db')
        //                 ->count();

        // $cr_cats = Auth::user()->categories()
        //                 ->where('type', 'cr')
        //                 ->count();

        // if ($db_cats == 0 || $cr_cats == 0) {
        //     return redirect('debit_category');
        // }
        
        if (!Category::is_exist()) return redirect('debit_category');

        $total_earnings = Transaction::total_earnings();
        $total_spendings = Transaction::total_spendings();

        $finished_plans = collect(Budget::finished())->count();
        $ongoing_plans = collect(Budget::ongoing())->count();

        return view('home', [
            'total_earnings' => $total_earnings,
            'total_spendings' => $total_spendings,
            'finished_plans' =>$finished_plans,
            'ongoing_plans' => $ongoing_plans,
        ]);
    }
}
