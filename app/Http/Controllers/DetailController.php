<?php

namespace App\Http\Controllers;

use App\Budget;
use App\Detail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $budget_id = $request->budget_id;

        $will_overdue = Budget::willOverdue();
        $overdue = Budget::overdue();

        return view('detail.create', [
            'budget_id' => $budget_id,
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
        $detail = Detail::fromRequest($request);
        $budget = Budget::find($request->budget_id);
        $budget->details()->save($detail);

        $will_overdue = Budget::willOverdue();
        $overdue = Budget::overdue();

        if ($budget->progress() == 1)
        {
            $budget->is_finished = 1;
            $budget->save();

            return redirect('budget')->with('message', 'Congratulation! Budget plan has been fulfilled.');
        }

        $details = $budget->details()->get();

        return view('detail.index', [
            'budget' => $budget,
            'details' => $details,
            'is_detail_exist' => true,
            'will_overdue' => $will_overdue,
            'overdue' => $overdue,
        ])->with('message', "Budget detail has been recorded successfully.");
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
    public function edit(Request $request)
    {
        $budget_id = $request->budget_id;
        $detail = Detail::find($request->id);

        $will_overdue = Budget::willOverdue();
        $overdue = Budget::overdue();

        return view('detail.edit', [
            'budget_id' => $budget_id,
            'detail' => $detail,
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
        $detail = Detail::find($id);
        $detail->amount = $request->amount;

        $detail->save();

        $budget = Budget::find($request->budget_id);

        if ($budget->progress() == 1)
        {
            $budget->is_finished = 1;
            $budget->save();

            return redirect('budget')->with('message', 'Congratulation! Budget plan has been fulfilled.');
        }
        else
        {
            $budget->is_finished = 0;
            $budget->save();
        }

        $details = $budget->details()->get();

        $will_overdue = Budget::willOverdue();
        $overdue = Budget::overdue();

        return view('detail.index', [
            'budget' => $budget,
            'details' => $details,
            'is_detail_exist' => true,
            'will_overdue' => $will_overdue,
            'overdue' => $overdue,
        ])->with('message', "Budget detail has been deleted successfully.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $detail = Detail::find($request->id);
        $detail->budget()->dissociate();
        $detail->delete();

        $budget = Budget::find($request->budget_id);

        if ($budget->progress() == 1)
        {
            $budget->is_finished = 1;
            $budget->save();

            return redirect('budget')->with('message', 'Congratulation! Budget plan has been fulfilled.');
        }
        else
        {
            $budget->is_finished = 0;
            $budget->save();
        }

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
        ])->with('message', "Budget detail has been deleted successfully.");
    }
}
