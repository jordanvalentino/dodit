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
Route::resource('budget', 'BudgetController');
Route::resource('detail', 'DetailController');
Route::resource('debit_category', 'DebitCategoryController');
Route::resource('credit_category', 'CreditCategoryController');
Route::resource('transaction', 'TransactionController');

Route::get('detail/create/{budget_id}', 'DetailController@create');
Route::get('detail/{budget_id}/{id}/edit', 'DetailController@edit');
Route::delete('detail/{budget_id}/{id}', 'DetailController@destroy');

Route::get('transaction/monthly/{month}_{year}', 'TransactionController@monthly');
Route::get('transaction/annually/{year}', 'TransactionController@annually');
Route::get('transaction/export/pdf', 'TransactionController@export_pdf');
Route::get('transaction/export/excel', 'TransactionController@export_excel');

Route::get('report/monthly/{month}_{year}', 'ReportController@monthly');
Route::get('report/annually/{year}', 'ReportController@annually');
