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
        $budgets = Auth::user()->budgets()->get();
        $is_budget_exist = Budget::isExist();

        $will_overdue = Budget::willOverdue();
        $overdue = Budget::overdue();

        // $saves = [];
        // for ($i = 0; $i < $budgets->count(); $i++)
        // {
        //     $save = DB::table('details')
        //             ->join('budgets', 'details.budget_id', 'budgets.id')
        //             ->where('budgets.id', '=', $budgets[$i]->id)
        //             ->where('budgets.user_id', '=', Auth::id())
        //             ->sum('details.amount');

        //     $saves[] = $save;
        // }

        return view('budget.index', [
            'budgets' => $budgets,
            'is_budget_exist' => $is_budget_exist,
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
        $will_overdue = Budget::willOverdue();
        $overdue = Budget::overdue();

        return view('budget.create', [
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
        $budget = Budget::fromRequest($request);
        $budget->save();

        return redirect('budget')->with('message', $budget->title." created successfully.");
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
        $is_detail_exist = $budget->hasDetails();

        $will_overdue = Budget::willOverdue();
        $overdue = Budget::overdue();

        return view('detail.index', [
            'budget' => $budget,
            'details' => $details,
            'is_detail_exist' => $is_detail_exist,
            'will_overdue' => $will_overdue,
            'overdue' => $overdue,
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

        $will_overdue = Budget::willOverdue();
        $overdue = Budget::overdue();

        return view('budget.edit', [
            'budget' => $budget,
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

        if ($budget->hasDetails())
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
