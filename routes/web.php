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

//TEST GROUND
Route::get('/default', function(){
	return view('pages.customers.default');
});
//END

Route::get('/', function () {
    return view('pages.customers.home');
});

Route::get('/shops', function(){
    return view('pages.shop');
});

Route::get('/tickets', function(){
    return view('pages.tickets');
});

Route::get('/wallet', function(){
    return view('pages.wallet');
});

//user
Route::get('/signup',                           'SignupController@create');
Route::post('/signup',                          'SignupController@signup');
Route::get('/login',                            'Auth\LoginController@showLogin');
Route::post('/login',                           'Auth\LoginController@login');
Route::get('/logout',                           'Auth\LoginController@logout');
