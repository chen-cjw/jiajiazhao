<?php

use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Route::get('/abbr', function () {
    return \App\Model\AbbrCategory::where('parent_id',request('q'))->get(['id',\Illuminate\Support\Facades\DB::raw('abbr as text')]);// ['id','abbr']
});