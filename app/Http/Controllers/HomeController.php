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
        if (!Category::isExist()) return redirect('debit_category');

        $total_earnings = Transaction::total_earnings();
        $total_spendings = Transaction::total_spendings();

        $finished_plans = Budget::finished()->count();
        $ongoing_plans = Budget::ongoing()->count();

        $will_overdue = Budget::willOverdue();
        $overdue = Budget::overdue();

        return view('home', [
            'total_earnings' => $total_earnings,
            'total_spendings' => $total_spendings,
            'finished_plans' =>$finished_plans,
            'ongoing_plans' => $ongoing_plans,
            'will_overdue' => $will_overdue,
            'overdue' => $overdue,
        ]);
    }
}
