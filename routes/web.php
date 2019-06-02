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


use App\Exports\UserExport;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/', function () {
        return view('Administrator.dashboard');
    });
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('produk',function(){
    return view('Administrator.produk');
});
Route::get('dashboard',function(){
    return view('Administrator.dashboard');
});

Route::get('/download',function (){
   return Excel::download(new UserExport,'test.xlsx');
});


Route::get('send','MailController@send');
