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
    return view('auth.login');
});

Auth::routes();

Route::redirect('js/bootstrap.js.map', '/dashboard');
Route::get('/dashboard', 'HomeController@index')->name('dashboard');

Route::group(['middleware' => 'auth'], function () {
    
    Route::get('/{vue_capture?}', function () {
        return view('application');
    })->where('vue_capture', '[\/\w\.-]*');

});
