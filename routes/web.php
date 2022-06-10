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

/*
Route::get('/', function () {
    return view('welcome');
});
*/



Auth::routes(['register' => true, 'reset' => false, 'verify' => false]);

Route::middleware('auth')
    ->namespace('Admin')
    ->name('admin.')
    ->prefix('admin')
    ->group(function () {
        Route::get('/', 'HomeController@index')->name('index');
        Route::resource('/posts', 'PostController');
    });


//qualunque tipo di accesso senza admin (per i guest) viene reindirizzato alla home guest
Route::get("{any?}", function(){
    return view('guest.home');
    })->where("any", ".*");
