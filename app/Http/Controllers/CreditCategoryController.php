<?php

namespace App\Http\Controllers;

use App\Budget;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreditCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::credits(true);
        $is_category_exist = Category::isExist();

        $will_overdue = Budget::willOverdue();
        $overdue = Budget::overdue();

        return view('credit_category.index', [
            'categories' => $categories,
            'is_category_exist' => $is_category_exist,
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
        $categories = Category::credits();
        $is_category_exist = Category::isExist();

        $will_overdue = Budget::willOverdue();
        $overdue = Budget::overdue();

        return view('credit_category.create', [
            'categories' => $categories,
            'is_category_exist' => $is_category_exist,
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
        $category = Category::fromRequest($request, 'cr');

        if ($request->super_id == 0) // if not sub-category
        {
            $category->save();
        }
        else
        {
            $parent = Category::find($request->super_id);
            $parent->children()->save($category);
        }

        return redirect('credit_category')->with('message', $request->name." created successfully.");
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
        $categories = collect(Category::credits())->where('id', '!=', $id);
        $category = Category::find($id);
        $is_category_exist = Category::isExist();

        $will_overdue = Budget::willOverdue();
        $overdue = Budget::overdue();

        return view('credit_category.edit', [
            'categories' => $categories,
            'category' => $category,
            'is_category_exist' => $is_category_exist,
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
        $category = Category::find($id);
        $category->name = $request->name;

        $category->parent()->dissociate();

        if ($request->super_id != 0)
        {
            $parent = Category::find($request->super_id);
            $parent->children()->save($category);

            $category->super_id = $request->super_id;
        }
        else
        {
            $category->save();
        }

        return redirect('credit_category')->with('message', $category->name." updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);

        if ($category->children()->count() > 0)
        {
            return redirect('credit_category')->with('message', 
                "Cannot delete ".$category->name." because it has sub-categories."
            );
        }
        else
        {
            $category->delete();
            return redirect('credit_category')->with('message', $category->name." deleted successfully.");
        }

        return redirect('credit_category');
    }
}
