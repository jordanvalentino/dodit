<?php

namespace App\Http\Controllers;

use App\Category;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CreditTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $super_categories = Auth::user()->categories()
                        ->where('type', 'cr')
                        ->where('super_id', NULL)
                        ->get();

        $is_transaction_exist = false;

        $categories = [];
        foreach($super_categories as $cat)
        {
            if ($cat->transactions()->count() > 0)
                $is_transaction_exist = true;

            $categories[] = $cat;
            foreach($cat->children as $subcat)
            {
                if ($subcat->transactions()->count() > 0) 
                    $is_transaction_exist = true;

                $categories[] = $subcat;
            }
        }

        return view('credit_transaction.index', [
            'categories' => collect($categories),
            'is_transaction_exist' => $is_transaction_exist,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $super_categories = Auth::user()->categories()
                        ->where('type', 'cr')
                        ->where('super_id', NULL)
                        ->get();

        $categories = [];
        foreach($super_categories as $cat)
        {
            $categories[] = $cat;
            foreach($cat->children as $subcat)
            {
                $categories[] = $subcat;
            }
        }

        return view('credit_transaction.create', [
            'categories' => collect($categories),
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

        $spending = new Transaction([
            'amount' => $request->amount,
            'detail' => $request->detail,
            'attachment' => $path,
        ]);

        $category = Category::find($request->category_id);
        $category->transactions()->save($spending);

        return redirect('credit_transaction')->with('message', "Transaction has been recorded successfully.");
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
        $spending = Transaction::find($id);

        $super_categories = Auth::user()->categories()
                        ->where('type', 'cr')
                        ->where('super_id', NULL)
                        ->get();

        $categories = [];
        foreach($super_categories as $cat)
        {
            $categories[] = $cat;
            foreach($cat->children as $subcat)
            {
                $categories[] = $subcat;
            }
        }

        return view('credit_transaction.edit', [
            'spending' => $spending,
            'categories' => collect($categories),
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
        $spending = Transaction::find($id);
        $spending->amount = $request->amount;
        $spending->detail = $request->detail;

        $update = $request->update == 'yes';
        if($update)
        {
            Storage::delete($spending->attachment);

            $path = NULL;
            if ($request->file('attachment') != NULL)
            {
                $path = $request->file('attachment')->store('public');
                Storage::setVisibility($path, 'public');
            }

            $spending->attachment = $path;
        }

        $category = Category::find($request->category_id);
        $category->transactions()->save($spending);

        return redirect('credit_transaction')->with('message', "Transaction updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $spending = Transaction::find($id);

        if ($spending->attachment != NULL)
        {
            Storage::delete($spending->attachment);
        }

        $spending->delete();

        return redirect('credit_transaction')->with('message', "Transaction deleted successfully.");
    }
}
