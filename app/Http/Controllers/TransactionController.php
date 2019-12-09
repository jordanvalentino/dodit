<?php

namespace App\Http\Controllers;

use App\Category;
use App\Transaction;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PDF;
use Excel;

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
        $transactions = Auth::user()->transactions()
                        ->orderBy('created_at', 'desc')
                        ->get();

        // $is_transaction_exist = $transactions->count() > 0;
        $is_transaction_exist = Transaction::is_exist();

        return view('transaction.index', [
            'transactions' => $transactions,
            'is_transaction_exist' => $is_transaction_exist,
        ]);
    }

    public function monthly(Request $request)
    {
        $months = DB::table('transactions')
                        ->join('categories', 'transactions.category_id', '=', 'categories.id')
                        // ->join('users', 'categories.user_id', '=', 'users.id')
                        ->select(DB::raw('MONTHNAME(transactions.created_at) as month, YEAR(transactions.created_at) as year'))
                        // ->where('users.id', Auth::id())
                        ->where('categories.user_id', Auth::id())
                        ->orderBy('transactions.created_at')
                        ->distinct()
                        ->get();

        $transactions = Auth::user()->transactions()
                        ->whereMonth('transactions.created_at', $request->month)
                        ->whereYear('transactions.created_at', $request->year)
                        ->orderBy('transactions.created_at', 'desc')
                        ->get();

        $is_transaction_exist = $transactions->count() > 0;

        return view('transaction.index', [
            'months' => $months,
            'transactions' => $transactions,
            'is_transaction_exist' => $is_transaction_exist,
            'sel_month' => $request->month,
            'sel_year' => $request->year,
        ]);
    }

    public function annually(Request $request)
    {
        $years = DB::table('transactions')
                        ->join('categories', 'transactions.category_id', '=', 'categories.id')
                        // ->join('users', 'categories.user_id', '=', 'users.id')
                        ->select(DB::raw('YEAR(transactions.created_at) as year'))
                        ->where('categories.user_id', Auth::id())
                        // ->where('users.id', Auth::id())
                        ->distinct()
                        ->get();

        $transactions = Auth::user()->transactions()
                        ->whereYear('transactions.created_at', $request->year)
                        ->orderBy('transactions.created_at', 'desc')
                        ->get();

        $is_transaction_exist = $transactions->count() > 0;

        return view('transaction.index', [
            'years' => $years,
            'transactions' => $transactions,
            'is_transaction_exist' => $is_transaction_exist,
            'sel_year' => $request->year,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $debit_cats = Auth::user()->categories()
                        ->where('type', 'db')
                        ->where('super_id', NULL)
                        ->orderBy('name')
                        ->get();

        $credit_cats = Auth::user()->categories()
                        ->where('type', 'cr')
                        ->where('super_id', NULL)
                        ->orderBy('name')
                        ->get();

        $db_categories = [];
        foreach($debit_cats as $cat)
        {
            $db_categories[] = $cat;
            foreach($cat->children as $subcat)
            {
                $db_categories[] = $subcat;
            }
        }


        $cr_categories = [];
        foreach($credit_cats as $cat)
        {
            $cr_categories[] = $cat;
            foreach($cat->children as $subcat)
            {
                $cr_categories[] = $subcat;
            }
        }

        // dd($cr_categories);

        return view('transaction.create', [
            'db_categories' => collect($db_categories),
            'cr_categories' => collect($cr_categories),
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
        $path = NULL;
        if ($request->file('attachment') != null)
        {
            $path = $request->file('attachment')->store('public');
            Storage::setVisibility($path, 'public');
        }

        $transaction = new Transaction([
            'amount' => $request->amount,
            'detail' => $request->detail,
            'attachment' => $path,
        ]);

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

        $debit_cats = Auth::user()->categories()
                        ->where('type', 'db')
                        ->where('super_id', NULL)
                        ->orderBy('name')
                        ->get();

        $credit_cats = Auth::user()->categories()
                        ->where('type', 'cr')
                        ->where('super_id', NULL)
                        ->orderBy('name')
                        ->get();

        $db_categories = [];
        foreach($debit_cats as $cat)
        {
            $db_categories[] = $cat;
            foreach($cat->children as $subcat)
            {
                $db_categories[] = $subcat;
            }
        }


        $cr_categories = [];
        foreach($credit_cats as $cat)
        {
            $cr_categories[] = $cat;
            foreach($cat->children as $subcat)
            {
                $cr_categories[] = $subcat;
            }
        }

        return view('transaction.edit', [
            'transaction' => $transaction,
            'db_categories' => collect($db_categories),
            'cr_categories' => collect($cr_categories),
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
                dd(Storage::getVisibility($path));
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
        //
    }

    public function export_pdf()
    {
        $transactions = Auth::user()->transactions()
                        ->orderBy('created_at', 'desc')
                        ->get();

        $pdf = PDF::loadview('export.index',['transactions'=>$transactions]);
        //auto buka di browser
        return $pdf->stream();
        // auto download file pdf
        // return $pdf->download('laporankeuangan.pdf');
    }

    public function export_excel()
    {
        $transactions = Auth::user()->transactions()
                        ->orderBy('created_at', 'desc')
                        ->get();

        // return Excel::create('laporankeuangan', function($excel) {
        //     $excel->sheet('sheet1', function($sheet) {
        //         $sheet->loadView('export.index',array('transactions'=>$transactions));
        //     });
        // })->download();
        // return Excel::loadView('export.index', array('transactions => $transactions'))->export('xls');

        Excel::create('laporankeuangan', function($excel) {
            $excel->sheet('Sheet1', function($sheet) {
                    $transactions = Auth::user()->transactions()
                            ->orderBy('created_at', 'desc')
                            ->get();
                    foreach($transactions as $key => $trans) {
                     $data[] = array(
                        $key+1,
                        $trans->created_at,
                        $trans->detail,
                        $trans->amount,
                        $trans->category->name
                    );
                }

                // $headings = array('no', 'Date', 'Detail', 'Amount', 'Category');

                // $sheet->prependRow(1, $headings);
                // $sheet->row(1, array(
                //      'no', 'Date', 'Detail', 'Amount', 'Category'
                // ));
                // $sheet->cell('A1', function($cell) {
                //     // manipulate the cell
                //     $cell->setValue('no');
                // });
                // $sheet->cell('B1', function($cell) {
                //     // manipulate the cell
                //     $cell->setValue('Date');
                // });
                // $sheet->cell('C1', function($cell) {
                //     // manipulate the cell
                //     $cell->setValue('Detail');
                // });
                // $sheet->cell('D1', function($cell) {
                //     // manipulate the cell
                //     $cell->setValue('Amount');
                // });
                // $sheet->cell('E1', function($cell) {
                //     // manipulate the cell
                //     $cell->setValue('Category');
                // });
                $sheet->fromArray($data);
            });
        })->export('xls');
    }
}
