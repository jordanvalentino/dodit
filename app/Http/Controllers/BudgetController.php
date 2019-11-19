<?php

namespace App\Http\Controllers;

use App\Budget;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $budgets = Auth::user()->budgets()
                        ->get();

        $is_budget_exist = Auth::user()->budgets()->count() > 0;

        $saves = [];
        for ($i = 0; $i < $budgets->count(); $i++)
        {
            $save = DB::table('details')
                    ->join('budgets', 'details.budget_id', 'budgets.id')
                    ->where('budgets.id', '=', $budgets[$i]->id)
                    ->where('budgets.user_id', '=', Auth::id())
                    ->sum('details.amount');

            $saves[] = $save;
        }

        return view('budget.index', [
            'budgets' => $budgets,
            'is_budget_exist' => $is_budget_exist,
            'saves' => collect($saves),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('budget.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $budget = new Budget([
            'title' => $request->title,
            'amount' => $request->amount,
            'start' => $request->start,
            'end' => $request->end,
            'is_finished' => false,
            'user_id' => Auth::id(),
        ]);

        $budget->save();

        return redirect('budget')->with('message', $budget->name." created successfully.");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $budget = Budget::find($id);
        $details = $budget->details()->get();

        $is_detail_exist = $details->count() > 0;

        return view('detail.index', [
            'budget' => $budget,
            'details' => $details,
            'is_detail_exist' => $is_detail_exist,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $budget = Budget::find($id);

        return view('budget.edit', [
            'budget' => $budget,
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
        $budget = Budget::find($id);
        $budget->title = $request->title;
        $budget->amount = $request->amount;
        $budget->start = $request->start;
        $budget->end = $request->end;

        $budget->save();

        return redirect('budget')->with('message', "Budget plan updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $budget = Budget::find($id);

        if ($budget->details->count() > 0)
        {
            return redirect('budget')->with('message', "Cannot delete budget plan because it still has details.");
        }
        else
        {
            $budget->delete();
            return redirect('budget')->with('message', "Budget plan deleted successfully.");
        }

        return redirect('budget');
    }
}
