<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('login');
});

Auth::routes();

Route::resource('home', 'HomeController');
Route::resource('debit_category', 'DebitCategoryController');
Route::resource('credit_category', 'CreditCategoryController');
Route::resource('debit_transaction', 'DebitTransactionController');
Route::resource('credit_transaction', 'CreditTransactionController');
Route::resource('budget', 'BudgetController');
Route::resource('detail', 'DetailController');
Route::get('detail/create/{budget_id}', 'DetailController@create');
Route::get('detail/{budget_id}/{id}/edit', 'DetailController@edit');
Route::delete('detail/{budget_id}/{id}', 'DetailController@destroy');
// Route::resource('transaction', 'TransactionController');
