<?php

namespace App\Http\Controllers;

use DB;

use App\Budget;
use App\Category;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    private function get_colors($length)
    {    
        $colors = [];
        for ($i = 0; $i < $length; $i++)
        {
            $hex = "#";
            for ($j = 0; $j < 3; $j++)
            {
                $hex .= str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
            }
            $colors[] = $hex;
        }

        return collect($colors);
    }

    public function monthly(Request $request)
    {
        $months = Transaction::get_available_month_year();
        $transactions = Transaction::monthly($request->month, $request->year);
        
        $earnings = Transaction::earnings($transactions);
        $spendings = Transaction::spendings($transactions);

        $earning_by_category = Transaction::sum_by_category(Category::debits(), $earnings);
        $spending_by_category = Transaction::sum_by_category(Category::credits(), $spendings);

        $is_earning_exist = $earnings->count() > 0;
        $is_spending_exist = $spendings->count() > 0;

        $revenue = collect([
            'Earnings' => Transaction::earnings($transactions)->sum('amount'),
            'Spendings' => Transaction::spendings($transactions)->sum('amount'),
        ]);

        $will_overdue = Budget::willOverdue();
        $overdue = Budget::overdue();

        return view('report.index', [
            'months' => $months,
            'sel_month' => $request->month,
            'sel_year' => $request->year,
            'earning_by_category' => $earning_by_category,
            'spending_by_category' => $spending_by_category,
            'earning_colors' => $this->get_colors($earning_by_category->count()),
            'spending_colors' => $this->get_colors($spending_by_category->count()),
            'is_earning_exist' => $is_earning_exist,
            'is_spending_exist' => $is_spending_exist,
            'revenue' => $revenue,
            'colors' => $this->get_colors($revenue->count()),
            'will_overdue' => $will_overdue,
            'overdue' => $overdue,
        ]);
    }

    public function annually(Request $request)
    {
        $years = Transaction::get_available_year();
        $transactions = Transaction::annually($request->year);
        
        $earnings = Transaction::earnings($transactions);
        $spendings = Transaction::spendings($transactions);

        $earning_by_category = Transaction::sum_by_category(Category::debits(), $earnings);
        $spending_by_category = Transaction::sum_by_category(Category::credits(), $spendings);

        $is_earning_exist = $earnings->count() > 0;
        $is_spending_exist = $spendings->count() > 0;

        $revenue = collect([
            'Earnings' => Transaction::earnings($transactions)->sum('amount'),
            'Spendings' => Transaction::spendings($transactions)->sum('amount'),
        ]);

        $will_overdue = Budget::willOverdue();
        $overdue = Budget::overdue();

        return view('report.index', [
            'years' => $years,
            'sel_year' => $request->year,
            'earning_by_category' => $earning_by_category,
            'spending_by_category' => $spending_by_category,
            'earning_colors' => $this->get_colors($earning_by_category->count()),
            'spending_colors' => $this->get_colors($spending_by_category->count()),
            'is_earning_exist' => $is_earning_exist,
            'is_spending_exist' => $is_spending_exist,
            'revenue' => $revenue,
            'colors' => $this->get_colors($revenue->count()),
            'will_overdue' => $will_overdue,
            'overdue' => $overdue,
        ]);
    }
}
