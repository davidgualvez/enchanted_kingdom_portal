<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth, Validator, DB; 
use Carbon\Carbon;
use App\User;
use App\Cart;
use App\CartComponent;
use App\SitePart;

use App\Part;
use App\ProductPromotion;
use App\Promotion;
use App\Transformers\CartTransformer; 
use App\AppServices\CustomerServices;
use App\AppServices\TaxServices;


class CartController extends Controller
{
    //
    public function showCart(){
        $user   = Auth::user();

        $customer = new CustomerServices($user->customer);

        $carts  = Cart::findByUserAndType($user->id,'wallet');  
 
        $ts = new TaxServices;
		$result = $ts->items($carts, $customer, $user);

		$carts_modifiable_components = null;
		$carts_none_modifiable_components = null;
		if(	count($result['items']) > 0 ){
			$carts_modifiable_components = $result['items'][0]['cart']->components->filter( function($v) {
				if ($v->base_product_id == $v->product_id) {
					return $v;
				}
			});

			$carts_none_modifiable_components = $result['items'][0]['cart']->components->filter( function($v) {
				if ($v->base_product_id != $v->product_id) {
					return $v;
				}
			});
		}
		  
		// return [
		// 	"_id" 				=> $v->id,
		// 	"cart_id" 			=> $v->cart_id,
		// 	"base_product_id" 	=> $v->base_product_id,
		// 	"product_id" 		=> $v->product_id,
		// 	"base_qty" 			=> $v->base_qty,
		// 	"qty" 				=> $v->qty,
		// 	"price" 			=> $v->price
		// ];

        return view('pages.customers.cart', 
            compact(
                'result',
				'customer',
				'carts_modifiable_components',
				'carts_none_modifiable_components'
                )
        );
	} 
	
    public function addToCart(Request $request){  

    	$data	=	$request->only('product_id','qty');
    	$rules 	= 	[
    		'product_id'	=>	'required',
            'qty'           =>  'required'
    	];

    	$validator	= 	Validator::make($data,$rules);
    	if($validator->fails()){
    		return response()->json([
    			'success'		=> 		false, 
    			'status' 		=> 		200,
    			'message'		=> 		'Product ID & Qty is required.'
    		]);
    	}

    	//$token = $request->token; 
    	if(!Auth::check()){
    		return response()->json([
    			'success'		=> 		false, 
    			'status' 		=> 		401,
    			'message'		=> 		'Unauthorized Access'
    		]);
    	}  
    	$user  = Auth::user();

		try {

			DB::beginTransaction();

			$cart = Cart::findByUserAndProduct($user->id,$request->product_id);
			if($cart){
				$cart->qty = $cart->qty + $request->qty; 
				$cart->save(); 

				// update components with new quantity
				$this->updateComponentsQty($cart);
				
			}else{

				$cart = new Cart;
				$cart->branch_id 	= config('cpp.branch_id');
				$cart->user_id 		= $user->id;
				$cart->product_id 	= $request->product_id;
				$cart->qty 			= $request->qty;
				$cart->type         = 'wallet'; 
				$cart->save();

				if(is_null($cart)){
					DB::rollback();
					return response()->json([
						'success' => true,
						'status' => 401,
						'message' => 'Error adding item in the cart.'
					]);
				}
				
				$sp = SitePart::findByIdAndBranch($request->product_id);
				
				// check if food and postmix 
				// if true get postmix 
				if($sp->postmix == 1 && $sp->is_food == 1){
					//check if there is a modifiable enable
					//if true add it also to cart, tagged with its parent part id
					foreach($sp->components as $comp){
						if($comp->modifiable == 1){  

							$cc = new CartComponent;
							$cc->cart_id 			= $cart->id;
							$cc->base_product_id	= $comp->product_id;
							$cc->product_id			= $comp->product_id;
							$cc->base_qty 			= $request->qty * $comp->quantity;
							$cc->qty 				= $request->qty * $comp->quantity;
							$cc->price 				= 0;
							$cc->save(); 

							if (is_null($cc)) {
								DB::rollback();
								return response()->json([
									'success' 	=> true,
									'status' 	=> 401,
									'message' 	=> 'Error adding item in the cart.'
								]);
							} 

						}
					}
				} 
			}

			/**
			 * Committing all changes in the database
			 */
			DB::commit();

			/**
			 * @return Success
			 */
			return response()->json([
				'success'		=> 		true, 
				'status' 		=> 		200,
				'message'		=> 		'Added to cart.'
			]);

		} catch (\Exception $e) {
            // if something went wrong
			DB::rollback();
			return response()->json([
				'success' => false,
				'status' => 500,
				'message' => $e->getMessage()
			]);
		}  
    }

    public function removeItemFromCart(Request $request){
    	$data	=	$request->only('product_id');
    	$rules 	= 	[
    		'product_id'	=>	'required', 
    	];

    	$validator	= 	Validator::make($data,$rules);
    	if($validator->fails()){
    		return response()->json([
    			'success'		=> 		false, 
    			'message'		=> 		'Product ID is required.'
    		],200);
    	}

    	$token = $request->token;
    	$user  = User::findByToken($token); 

    	$cart = Cart::findByUserAndProduct($user->ACCTCODE,$request->product_id);
    	if(!$cart){
    		return response()->json([
    			'success'		=> 		false, 
    			'message'		=> 		'Cart item not found.'
    		],200);
    	}

    	$cart->delete();

    	return response()->json([
    			'success'		=> 		true, 
    			'message'		=> 		'Item has been removed.'
    		],200); 
    }

    public function getCart(Request $request){
    	$token = $request->token;
    	$user  = User::findByToken($token); 
        //dd($user->USERNAME);
    	$my_carts = Cart::findByUser($user->ACCTCODE); 
        //dd($my_carts);

    	$c = array();
    	$total_amount = 0;
    	foreach($my_carts as $cart){
    		$part = Product::findById($cart->product_id);
            $amount = $cart->qty * ($part->UNITPRICE + $user->sc) ;
    		$total_amount +=  $amount;
    		$data = [
    			'product_id' 	=> $cart->product_id,
    			'description'	=> trim($part->DESCRIPTION), 
    			'qty' 			=> $cart->qty,
    			'price'			=> $part->UNITPRICE,
    			'amount'		=> $amount,
                'surcharge'     => $user->sc
    		];
    		array_push($c , $data);
    	}

    	$data = [
    		'total_amount' 	=> $total_amount,
    		'items' 		=> $c
    	];
    	
    	return response()->json([
    			'success'		=> 	true, 
    			'message'		=> 	'Carts Item list.',
    			'data'			=>  $data
    		],200); 
    }

    public function deleteCart(Request $request){
    	$token = $request->token;
    	$user  = User::findByToken($token); 

    	$result = Cart::removeCartByUserID($user->ACCTCODE);
    	if(!$result){
    		return response()->json([
    			'success'		=> 		false, 
    			'message'		=> 		'No cart can be remove.'
    		],200);
    	}

    	return response()->json([
    			'success'		=> 		true, 
    			'message'		=> 		'Cart successfuly removed.'
    		],200);
    }

    public function updateCart(Request $request){
    	$data	=	$request->only('product_id','qty');
    	$rules 	= 	[
    		'product_id'	=>	'required', 
    		'qty'			=> 	'required'
    	];

    	$validator	= 	Validator::make($data,$rules);
    	if($validator->fails()){
    		return response()->json([
    			'success'		=> 		false, 
    			'message'		=> 		'Product and Qty are required.'
    		],200);
    	}

    	$token = $request->token;
    	$user  = User::findByToken($token); 
        
    	$cart = Cart::findByUserAndProduct($user->ACCTCODE,$request->product_id);
        //dd($token,$user->ACCTCODE,$user->USERNAME,$cart);
    	if(!$cart){
    		return response()->json([
    			'success'		=> 		false, 
    			'message'		=> 		'Item not found.'
    		],200);
    	}

    	$cart->qty = $request->qty;
    	$cart->save();

    	return response()->json([
    			'success'		=> 		true, 
    			'message'		=> 		'You have successfuly updated item on cart.'
    		],200);
    }

    public function increase($id){ 
        $user = Auth::user(); 
        $cart = Cart::find($id); 
        if($cart->user_id != $user->id){
            return back()->with('error', 'Item not belong to the user.');
        }

        $cart->increment('qty');
		$cart->save();
		
		// update components with new quantity
		$this->updateComponentsQty($cart, 'increase');

        return back()->with('message', 'Item has been updated.');
    }

    public function decrease($id){
        $user = Auth::user(); 
		$cart = Cart::find($id);
		
		if( is_null($cart) ){
			return back()->with('error', 'Product not found on your cart.');
		}
        
        if($cart->user_id != $user->id){
            return back()->with('error', 'Item not belong to the user.');
        }

        if($cart->qty <= 1){
			$cart->delete();
			$this->removeComponents($cart);
            return back()->with('error', 'Item has been removed from your cart.');
        }

        $cart->decrement('qty');
		$cart->save();
		
		// update components with new quantity
		$this->updateComponentsQty($cart, 'decrease');


        return back()->with('message', 'Item has been updated.');
    }

    public function cartCount(){

        //$token = $request->token; 
        if(!Auth::check()){
            return response()->json([
                'success'       =>      false, 
                'status'        =>      401,
                'message'       =>      'Unauthorized Access'
            ]);
        }  

        //$count = Auth::user()->cartPerBranch->count();
		$carts = Auth::user()->cart; 

		// $carts = $carts->unique('is_component_of_pid')->count();
 

        return response()->json([
                'success'       =>      true, 
                'status'        =>      200,
                'count'         => 		$carts->count()
            ]);
    }



	public function checkout1(Request $request)
	{

		$points_payment = null;
		if (is_null($request->points_payment)) {
			$points_payment = 0;
		} else {
			$points_payment = $request->points_payment;
		}

		DB::beginTransaction();

		$blin = new BranchLastIssuedNumberServices;
		$blin->findOrCreate();

		$user = Auth::user();
		$carts = Cart::findByUserAndType($user->id, 'wallet');

		try {  

            //check if the cart has not contain an item 
			if ($carts->isEmpty()) {
				DB::rollback();
				return response()->json([
					'success' => false,
					'status' => 401,
					'message' => 'Please maintain atleast 1 item from your cart to continue.'
				]);
			}

        	//create a purchase header
			$new_sales_order_id = $blin->getNewIdForSalesOrderHeader();
			$ph = new Purchase;
			$ph->branch_id = config('app.branch_id');
			$ph->sales_order_id = $new_sales_order_id;
			$ph->customer_id = $user->customer->customer_id;
			$ph->transaction_type_id = 1;
			$ph->save();  
             
            //===============================================    
			$total_gross = 0;
			$total_discount = 0;
			$total_net = 0;
			$cartList = [];

			foreach ($carts as $key => $value) {
        	    # code... 
				$partt = new SitePart;
				$part = $partt->where('branch_id', config('app.branch_id'))
					->where('sitepart_id', $value->product_id)
					->first();

				if ($part->postmix == 1 && $part->is_food == 0) { 

                    // the all the postmix items at postmix
					$postmix = Postmix::where('parent_id', $part->sitepart_id)
						->get();

					for ($i = 0; $i < $value->qty; $i++) {
						foreach ($postmix as $pm) {

							$ppart = SitePart::where('branch_id', config('app.branch_id'))
								->where('sitepart_id', $pm->PARTSID)
								->first();

                            //if the product is a group of wallet
                            // do not allow to proceed and display a warning message
							if ($ppart->group_id == config('app.group_wallet_id')) {
								DB::rollback();
								return response()->json([
									'success' => false,
									'status' => 401,
									'message' => 'You cannot purchase e-Wallet using a e-Wallet'
								]);
							}

							$result = $this->saveToSalesOrderDetail($user, $ppart, (int)$pm->QUANTITY, $new_sales_order_id);

						}
					}
                    
                    //logic
					$product_id = $part->sitepart_id;
					$name = $part->product_name;
					$description = '';
					$qty = $value->qty;
					$srp = $part->srp;
					$product_promotion_id = null;
					$discount_type = 0;
					$discount_value = 0;
					$discount_amount = 0;
					$selling_price = $qty * $srp;
					$buying_price = 0;
					$is_unli = $part->is_unli;

                    //get the buying price
					$buying_price = ($selling_price) - $discount_amount;

					$total_gross += $selling_price;
					$total_discount += $discount_amount;
					$total_net += $buying_price;

				} else {

                    //if the product is a group of wallet
                    // do not allow to proceed and display a warning message
					if ($part->group_id == config('app.group_wallet_id')) {
						DB::rollback();
						return response()->json([
							'success' => false,
							'status' => 401,
							'message' => 'You cannot purchase e-Wallet using a e-Wallet'
						]);
					}

					$result = $this->saveToSalesOrderDetail($user, $part, $value->qty, $new_sales_order_id);

					$total_gross += $result['selling_price'];
					$total_discount += $result['discount_amount'];
					$total_net += $result['buying_price'];
				}
			}
        	//===============================================   

			$virtual_wallet = $user->customer->wallet;
			$virtual_points = $user->customer->points;  

            // check the points entered as payment if greater than the customer wallet
			if ($points_payment > $user->customer->points) {
				DB::rollback();
				return response()->json([
					'success' => false,
					'status' => 401,
					'message' => 'The points you entered as payment is greater than your actual points!'
				]);
			} 

            // check and not allow if the points payment is greater than the total net
			if ($points_payment > $total_net) {
				DB::rollback();
				return response()->json([
					'success' => false,
					'status' => 401,
					'message' => 'The points you entered as payment must be equal or less than the NET Amount!'
				]);
			}

			$wallet_payment = $total_net - $points_payment;

        	//check if the e money is enough to purchase this transaction 
			if ($user->customer->wallet < $wallet_payment) {
				DB::rollback();
				return response()->json([
					'success' => false,
					'status' => 401,
					'message' => 'Your wallet is not enough to purchase this order!'
				]);

			} 

            //-------------------------
            
            //earned points 
			$ept = new EarnPointTransactionServices;
			$ep = $ept->earnPoints(
				$new_sales_order_id,
				$total_net,
				$user->customer->customer_id,
				$points_payment //this will be the amount excemption for points earning
			);
			$eps = $ept->save();


			$virtual_points -= $points_payment;
			$virtual_wallet -= $wallet_payment; 

            //adding earned point to virtual points
			$virtual_points += $eps->earned_points;

        	//update purchase header for total;
			Purchase::where('sales_order_id', $new_sales_order_id)
				->update([
					'total_amount' => $total_gross,
					'total_discount' => $total_discount,
					'net_amount' => $total_net,
					'used_wallet' => $wallet_payment,
					'used_points' => $points_payment,
					'added_points' => $eps->earned_points,
					'wallet_balance' => $virtual_wallet,
					'points_balance' => $virtual_points
				]);

            //update customer points for new earned point
			$customerrr = Customer::where('CUSTOMERID', $user->customer->customer_id)
				->update([
					'wallet' => $virtual_wallet,
					'points' => $virtual_points,
				]); 
                        
 
        	//remove cart from this current branch
			$cart = Cart::removeCartByUserIDAndType($user->id, 'wallet');

			DB::commit();
		    // all good
			return response()->json([
				'success' => true,
				'status' => 200,
				'message' => 'success'
			]);

		} catch (\Exception $e) {
			DB::rollback();
            //dd($e); 
			return response()->json([
				'success' => false,
				'status' => 500,
				'message' => $e->getMessage()
			]);
		    // something went wrong 
		}
	} 

	private function updateComponentsQty($cart,$type){

		if($type == 'increase'){
			foreach ($cart->components as $component) {
				if ($component->base_product_id == $component->product_id) {
					$component->qty += $component->base_qty;
					$component->save();
				}
			}
			return true;
		}

		if ($type == 'decrease') {

			$filtered = $cart->components->filter(function ($v) {
				if ($v->base_product_id == $v->product_id) {
					return $v;
				}
			});

			// execute the deduction  
			foreach ($filtered as $key => $value) {
				# code...
				$egoc = $value->cart->components->filter( function($v) use ($value){ // each group of components
					if($value->base_product_id == $v->base_product_id){
						return $v;
					}
				});

				$qty = $value->base_qty;
				$egoc = $egoc->reverse();
				foreach ($egoc as $_egoc) {
					# code... 
					if($qty != 0){
						if ($_egoc->qty > $qty) {
							$_egoc->qty -= $qty;
							$_egoc->save();
							$qty = 0;
						}else{
							$qty -= $_egoc->qty;
							$_egoc->delete();
						}
					} 
				}
			}	
		}

		
	}

	private function removeComponents($cart){
		foreach ($cart->components as $component) { 
			$component->delete();
		} 
		return true;
	}

}
