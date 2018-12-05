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
	$now = \Carbon\Carbon::now();

	$dash = \App\Dashboard::where('start_at' , '<=', $now)
				->where('end_at', '>=', $now)
				->get(); 
				
    return view('pages.customers.home', compact('dash') );
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
Route::get('/me', 								'UserController@info');
Route::post('/me', 								'UserController@updateInfo');


//CART wallet
//show cart
Route::get('/cart', 							'CartController@showCart');
//show cart count
Route::post('/cart/count',						'CartController@cartCount');
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


//CART points
//show cart
Route::get('/cart/points', 								'CartController@showCartPoints');
//show cart count
Route::post('/cart/points/count',						'CartController@cartCountPoints');
//parameter [ product_id ]
Route::post('/cart/points/add_to_cart', 				'CartController@addToCartPoints');
//parameter [ product_id ]
Route::post('/cart/points/remove_item_from_cart',	 	'CartController@removeItemFromCartPoints');//pending
//parameter [ token ] 
Route::post('/cart/points/get_cart', 					'CartController@getCartPoints'); //pending
//parameter [ token ]
Route::post('/cart/points/delete_cart', 				'CartController@deleteCartPoints');//pending
//parameter [product_id,qty]
Route::post('/cart/points/update_cart', 				'CartController@updateCartPoints');//pending

Route::post('/cart/points/{id}/increase', 				'CartController@increasePoints');
Route::post('/cart/points/{id}/decrease',				'CartController@decreasePoints');


//checkout
Route::post('/checkout', 								'PurchaseController@checkout');
Route::post('/points/checkout', 						'PurchaseController@checkoutReward');

//products and promo 
Route::get('/promos',                            		'ProductPromotionController@activePromo');
Route::get('/products',                          		'PartController@lists');

//rewards
Route::get('/rewards', 									'RewardController@index');
Route::get('/rewards/lists', 							'RewardController@lists');

//active purchase
Route::post('/purchase/details/active', 				'PurchaseDetailController@active');
Route::post('/purchase/history', 						'PurchaseController@customerHistory');

//orders
Route::post('/order', 									'OrderController@order');