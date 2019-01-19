<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth, Validator; 
use Carbon\Carbon;
use App\User;
use App\Cart;

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

        //================================================================
        $carts->transform(function($v) use ($customer) {  
            $price = $v->product->srp * $v->qty;
            $priceWithDiscount = 0;

            $vatable_sales  = 0;
            $vat_amount     = 0;
            $r_vat_amount   = 0; // removed vat

            $vat_exempt_sales   = 0;
            $sc_pwd_discount    = 0;

            $zero_rated_sales = 0;
            $zero_rated_vat_amount = 0;

            $admission_fee = 0;
            $admission_tax_amount = 0;
            $r_admission_tax_amount = 0; // removed admission tax amount

            // for zero rated
            if($customer->getType()['type'] == 'ZERO-RATED'){
                $price = $price / 1.12;
            }

            // for admission 
            $priceWithoutAdmission = $price;
            if($v->product->pre_part_no == 1){
                $af = $v->product->admission_fee * $v->qty;

                $priceWithoutAdmission -= $af;
                $admission_fee = ($af) / 1.10;
                $admission_tax_amount = $admission_fee * .10;
            }

            // for tax computation with non SC/PWD 
            if(
                $v->product->is_vat == 1
                
                //$v->product->is_food == 0 && 
                //$v->product->pre_part_no != 1
                // ($customer->getType()['type'] != 'ZERO-RATED' &&
                // $customer->getType()['type'] != 'SENIOR' &&
                // $customer->getType()['type'] != 'PWD')
            ){
                if($customer->getType()['type'] == 'NORMAL'){
                    $newPrice       = $priceWithoutAdmission;
                    $vatable_sales  = $newPrice / 1.12;
                    $vat_amount     = $vatable_sales * .12;
                }

                if(
                    ($customer->getType()['type'] == 'SENIOR' ||
                    $customer->getType()['type'] == 'PWD') &&
                    $v->product->is_food == 0 
                    ){
                        if($v->product->pre_part_no == 0){
                            $newPrice = $priceWithoutAdmission;
                            $vatable_sales = $newPrice / 1.12;
                            $vat_amount = $vatable_sales * .12;
                        }
                    
                }

                if($customer->getType()['type'] == 'ZERO-RATED'){
                    $newPrice = $priceWithoutAdmission;
                    $zero_rated_sales = $newPrice / 1.12;
                    $zero_rated_vat_amount = $zero_rated_sales * .12;
                }
            }

            // for PWD/SC Discount
            if(
                ($customer->getType()['type'] == 'SENIOR' ||
                $customer->getType()['type'] == 'PWD') &&
                ($v->product->is_food == 1 || $v->product->pre_part_no==1)
            ){
                $newPrice = $priceWithoutAdmission;
                $vat_exempt_sales = $newPrice / 1.12;
                $vat_amount = ($vat_exempt_sales * .12);

                //getting the discount
                $sc_pwd_discount = ( ($price - ($admission_tax_amount + $vat_amount ) ) * .20); 
               
                $r_vat_amount = $vat_amount;
                $vat_amount = 0;
                $r_admission_tax_amount = $admission_tax_amount;
                $admission_tax_amount = 0;
            }

            // new price
            $priceWithDiscount = $price - $sc_pwd_discount - $r_vat_amount - $r_admission_tax_amount;

            return [ 
                'cart_id'       => $v->id,
                'qty'           => $v->qty,
                'product_id'    => $v->product->sitepart_id,
                'product_name'  => $v->product->product_name,
                'price'         => $price, 
                'is_vat'        => $v->product->is_vat,
                'is_food'       => $v->product->is_food,
                'vatable_sales'         => $vatable_sales,
                'vat_exempt_sales'      => $vat_exempt_sales,
                'vat_zerorated_sales'   => $zero_rated_sales,
                'zero_rated_vat_amount' => $zero_rated_vat_amount,
                'vat_amount'            => $vat_amount,
                'is_admission'          => $v->product->pre_part_no,
                'admission_fee'         => $admission_fee,
                'amusement_tax_amount'  => $admission_tax_amount,
                'sc_pwd_discount'       => $sc_pwd_discount,
                'new_price'             => $priceWithDiscount
            ];
        });
        //======================================================================
       $result = [
            'ct'                    => $user->customer->customer_type,
            'customer_type'         => $customer->getType()['type'],
            'items'                 => $carts,
            'vatable_sales'         => $carts->sum('vatable_sales'),
            'vat_exempt_sales'      => $carts->sum('vat_exempt_sales'),
            'vat_zerorated_sales'   => $carts->sum('vat_zerorated_sales'),
            'vat_amount'            => $carts->sum('vat_amount'), 
            'admission_fee'         => $carts->sum('admission_fee'),
            'amusement_tax_amount'  => $carts->sum('amusement_tax_amount'),
            
            'sc_pwd_discount'       => $carts->sum('sc_pwd_discount'),
            'total_amount_due' => $carts->sum('new_price'),
            'cash'                  => 0,
            'change'                => 0
       ];
       dd($result);
        // $mct    = new CartTransformer;  
        // $result = $mct->myCart($carts);  
        
      
        
        //dd( $customer->getType()['type'] );

        return view('pages.customers.cart', 
            compact(
                'result',
                'customer'
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

    	$cart = Cart::findByUserAndProduct($user->id,$request->product_id);
    	if($cart){
    		$cart->qty = $cart->qty + $request->qty; 
    		$cart->save(); 
    	}else{
    		$cart = new Cart;
    		$cart->branch_id 	= config('cpp.branch_id');
	    	$cart->user_id 		= $user->id;
	    	$cart->product_id 	= $request->product_id;
	    	$cart->qty 			= $request->qty;
            $cart->type         = 'wallet';
	    	$cart->save();
    	} 

    	return response()->json([
			'success'		=> 		true, 
			'status' 		=> 		200,
			'message'		=> 		'Added to cart.'
    	]);
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

        return back()->with('message', 'Item has been updated.');
    }

    public function decrease($id){
        $user = Auth::user(); 
        $cart = Cart::find($id);
        
        if($cart->user_id != $user->id){
            return back()->with('error', 'Item not belong to the user.');
        }

        if($cart->qty <= 1){
            $cart->delete();
            return back()->with('error', 'Item has been removed from your cart.');
        }

        $cart->decrement('qty');
        $cart->save();

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

        $count = Auth::user()->cartPerBranch->count();
        
        return response()->json([
                'success'       =>      true, 
                'status'        =>      200,
                'count'         =>      $count
            ]);
    }

    // PPPPPPPPPPPPPP  OOOOOOOOO IIIIIIIII NNNNNNNNNNN TTTTTTTT SSSSSSSSSSS
    // public function addToCartPoints(Request $request){  

    //     $data   =   $request->only('product_id','qty');
    //     $rules  =   [
    //         'product_id'    =>  'required',
    //         'qty'           =>  'required'
    //     ];

    //     $validator  =   Validator::make($data,$rules);
    //     if($validator->fails()){
    //         return response()->json([
    //             'success'       =>      false, 
    //             'status'        =>      200,
    //             'message'       =>      'Product ID & Qty is required.'
    //         ]);
    //     }

    //     //$token = $request->token; 
    //     if(!Auth::check()){
    //         return response()->json([
    //             'success'       =>      false, 
    //             'status'        =>      401,
    //             'message'       =>      'Unauthorized Access'
    //         ]);
    //     }  
    //     $user  = Auth::user(); 

    //     $cart = Cart::findByUserAndProductPoints($user->id,$request->product_id);
    //     if($cart){
    //         $cart->qty = $cart->qty + $request->qty; 
    //         $cart->save(); 
    //     }else{
    //         $cart = new Cart;
    //         $cart->branch_id    = config('cpp.branch_id');
    //         $cart->user_id      = $user->id;
    //         $cart->product_id   = $request->product_id;
    //         $cart->qty          = $request->qty;
    //         $cart->type         = 'points';
    //         $cart->save();
    //     } 

    //     return response()->json([
    //         'success'       =>      true, 
    //         'status'        =>      200,
    //         'message'       =>      'Added to cart.'
    //     ]);
    // }


}
