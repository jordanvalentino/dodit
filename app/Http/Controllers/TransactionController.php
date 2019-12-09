<?php

namespace App\Http\Controllers;

use DB;
use Excel;
use PDF;
use App\Budget;
use App\Category;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transactions = Transaction::order_by_date($reverse = true);
        $is_transaction_exist = Transaction::isExist();

        $will_overdue = Budget::willOverdue();
        $overdue = Budget::overdue();

        return view('transaction.index', [
            'transactions' => $transactions,
            'is_transaction_exist' => $is_transaction_exist,
            'will_overdue' => $will_overdue,
            'overdue' => $overdue,
        ]);
    }

    public function monthly(Request $request)
    {
        $months = Transaction::get_available_month_year();
        $transactions = Transaction::monthly($request->month, $request->year);
        $is_transaction_exist = Transaction::isExist();

        $will_overdue = Budget::willOverdue();
        $overdue = Budget::overdue();

        return view('transaction.index', [
            'months' => $months,
            'transactions' => $transactions,
            'is_transaction_exist' => $is_transaction_exist,
            'sel_month' => $request->month,
            'sel_year' => $request->year,
            'will_overdue' => $will_overdue,
            'overdue' => $overdue,
        ]);
    }

    public function annually(Request $request)
    {
        $years = Transaction::get_available_year();
        $transactions = Transaction::annually($request->year);
        $is_transaction_exist = Transaction::isExist();

        $will_overdue = Budget::willOverdue();
        $overdue = Budget::overdue();

        return view('transaction.index', [
            'years' => $years,
            'transactions' => $transactions,
            'is_transaction_exist' => $is_transaction_exist,
            'sel_year' => $request->year,
            'will_overdue' => $will_overdue,
            'overdue' => $overdue,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $debits = Category::debits($parent_only = true);
        $credits = Category::credits($parent_only = true);

        $will_overdue = Budget::willOverdue();
        $overdue = Budget::overdue();

        return view('transaction.create', [
            'debits' => $debits,
            'credits' => $credits,
            'will_overdue' => $will_overdue,
            'overdue' => $overdue,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $transaction = Transaction::fromRequest($request);
        $category = Category::find($request->category_id);
        $category->transactions()->save($transaction);

        return redirect('transaction')->with('message', "Transaction has been recorded successfully.");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $transaction = Transaction::find($id);
        $debits = Category::debits($parent_only = true);
        $credits = Category::credits($parent_only = true);

        $will_overdue = Budget::willOverdue();
        $overdue = Budget::overdue();

        return view('transaction.edit', [
            'transaction' => $transaction,
            'debits' => $debits,
            'credits' => $credits,
            'will_overdue' => $will_overdue,
            'overdue' => $overdue,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $transaction = Transaction::find($id);
        $transaction->amount = $request->amount;
        $transaction->detail = $request->detail;

        $update = $request->update == 'yes';
        if($update)
        {
            Storage::delete($transaction->attachment);

            $path = NULL;
            if ($request->file('attachment') != NULL)
            {
                $path = $request->file('attachment')->store('public');
                Storage::setVisibility($path, 'public');
            }

            $transaction->attachment = $path;
        }

        $category = Category::find($request->category_id);
        $category->transactions()->save($transaction);

        return redirect('transaction')->with('message', "Transaction updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $transaction = Transaction::find($id);
        $transaction->delete();

        return redirect('transaction')->with('message', 'Transaction deleted successfully.');
    }

    public function export_pdf()
    {
        $transactions = Transaction::order_by_date($reverse = true);

        $pdf = PDF::loadview('export.index',['transactions'=>$transactions]);
        //auto buka di browser
        return $pdf->stream();
        // auto download file pdf
        // return $pdf->download('laporankeuangan.pdf');
    }

    public function export_excel()
    {
        Excel::create('laporankeuangan', function($excel) {
            $excel->sheet('Sheet1', function($sheet) {
                    $transactions = Transaction::order_by_date($reverse = true);

                    foreach($transactions as $key => $trans) {
                        if($trans->category->type == 'cr')
                        {
                            $trans->amount = '-'.$trans->amount;
                        }
                        else
                        {
                            $trans->amount = '+'.$trans->amount;
                        }
                    }
                    
                    foreach($transactions as $key => $trans) {
                     $data[] = array(
                        $key+1,
                        $trans->created_at,
                        $trans->detail,
                        $trans->amount,
                        $trans->category->name
                        );
                    }

                $headings = array('no', 'Date', 'Detail', 'Amount', 'Category');
                $sheet->prependRow(1, $headings);
                $sheet->fromArray($data);
            });
        })->export('xls');
    }
}
