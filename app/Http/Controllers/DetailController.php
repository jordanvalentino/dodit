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

        return view('detail.create', [
            'budget_id' => $budget_id,
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
        $detail = new Detail([
            'amount' => $request->amount,
            'budget_id' => $request->budget_id,
        ]);

        $budget = Budget::find($request->budget_id);
        $budget->details()->save($detail);

        $details = $budget->details()->get();

        return view('detail.index', [
            'budget' => $budget,
            'details' => $details,
            'is_detail_exist' => true,
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

        return view('detail.edit', [
            'budget_id' => $budget_id,
            'detail' => $detail,
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

        $details = $budget->details()->get();

        return view('detail.index', [
            'budget' => $budget,
            'details' => $details,
            'is_detail_exist' => true,
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
        $details = $budget->details()->get();

        $is_detail_exist = $details->count() > 0;

        return view('detail.index', [
            'budget' => $budget,
            'details' => $details,
            'is_detail_exist' => $is_detail_exist,
        ])->with('message', "Budget detail has been deleted successfully.");
    }
}
