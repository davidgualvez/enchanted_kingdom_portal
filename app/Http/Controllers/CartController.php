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

class CartController extends Controller
{
    //
    public function showCart(){
        $user   = Auth::user();
         
        $carts  = Cart::findByUserAndType($user->id,'wallet'); 
        //$cartsReward  = Cart::findByUserAndType($user->id,'points'); 

        $mct    = new CartTransformer;
        $result         = $mct->myCart($carts); 
        //$reward_carts   = $mct->myCartReward($cartsReward);
         
    
        //=============================================== 
        // $total_gross = 0;
        // $total_discount = 0;
        // $total_net = 0;
        // $cartList = [];
        // foreach ($carts as $key => $value) {
        //     # code... 
        //     //logic
        //     $product_id     = $value->part->id;
        //     $name           = $value->part->name;
        //     $description    = null;
        //     $qty            = $value->qty;
        //     $srp            = $value->part->srp;
        //     $discount_type  = null;
        //     $discount_value = null;
        //     $discount_amount= null;
        //     $selling_price  = $qty * $srp;
        //     $buying_price   = null;

        //     if(is_null($value->part->activePromo)){
        //         $discount_amount = 0; 
        //         //getting description by part
        //         $description = $value->part->description;
        //     }else{
        //         //getting description by promotion
        //         $description = $value->part->activePromo->description;

        //         if($value->part->activePromo->promotion->is_percent == 0){
        //             // actual amount to be deduct
        //             $discount_type  = 'real';
        //             $discount_value = $value->part->activePromo->promotion->amount;

        //             $discount_amount= $discount_value;
        //         }else if($value->part->activePromo->promotion->is_percent == 1){
        //             // percent amount to be deduct
        //             $discount_type  = 'percent';
        //             $discount_value = $value->part->activePromo->promotion->amount;

        //             $discount_amount= ($discount_value / 100) * $selling_price;
        //         }
        //     }
        //     //get the buying price
        //     $buying_price   = ($selling_price) - $discount_amount;

        //     //totals
        //     array_push($cartList, [
        //         'cart_id'           => $value->id,
        //         'part_id'           => $product_id,
        //         'name'              => $name,
        //         'description'       => $description,
        //         'qty'               => $qty,
        //         'srp'               => $srp,
        //         'selling_price'     => $selling_price,
        //         'discount_type'     => $discount_type,
        //         'discount_value'    => $discount_value,
        //         'discount_amount'   => $discount_amount, 
        //         'buying_price'      => $buying_price
        //     ]);

        //     $total_gross += $selling_price;
        //     $total_discount += $discount_amount;
        //     $total_net += $buying_price;
        // }

        // $total_gross        = $result['total_gross'];
        // $total_discount     = $result['total_discount'];
        // $total_net          = $result['total_net'];
        // $cartList           = $result['cartList']; 
        
        //=============================================== 
    	// return view('pages.customers.cart', 
     //        compact('cartList','total_gross','total_discount','total_net')
     //    );

        return view('pages.customers.cart', 
            compact('result')
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
