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
	return view('pages.playground.default');
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


//CART
//show cart
Route::get('/cart', 							'CartController@showCart');
//parameter [ product_id ]
Route::post('/cart/add_to_cart', 				'CartController@addToCart');
//parameter [ product_id ]
Route::post('/cart/remove_item_from_cart',	 	'CartController@removeItemFromCart');//pending
//parameter [ token ] 
Route::post('/cart/get_cart', 					'CartController@getCart'); //pending
//parameter [ token ]
Route::post('/cart/delete_cart', 				'CartController@deleteCart');//pending
//parameter [product_id,qty]
Route::post('/cart/update_cart', 				'CartController@updateCart');//pending

Route::post('/cart/{id}/increase', 				'CartController@increase');
Route::post('/cart/{id}/decrease',				'CartController@decrease');


//products and promo
Route::get('/promos',                            'ProductPromotionController@activePromo');
Route::get('/products',                          'PartController@lists');
