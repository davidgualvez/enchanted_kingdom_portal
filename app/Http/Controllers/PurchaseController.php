<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth, Validator; 
use Carbon\Carbon;
use App\User;
use App\Customer;
use App\Cart;

use App\Part;
use App\ProductPromotion;
use App\Promotion;
use App\Transformers\CartTransformer;

use App\Purchase;
use App\PurchaseDetail;

use App\AppServices\EarnPointTransactionServices;

use DB;

class PurchaseController extends Controller
{
    //
    public function checkout(Request $request){
    	$user   = Auth::user();
        $carts  = Cart::findByUser($user->id); 


        DB::beginTransaction();

        try { 
        	//create a purchase header
        	$ph = new Purchase;
        	$ph->branch_id 		= config('cpp.branch_id');
        	$ph->user_id		= $user->id;
        	$ph->type 			= 'WEB';
        	$ph->save();

        	//=============================================== 
        	$total_gross = 0;
        	$total_discount = 0;
        	$total_net = 0;
        	$cartList = [];
        	foreach ($carts as $key => $value) {
        	    # code... 
        	    //logic
        	    $product_id     = $value->part->id;
        	    $name           = $value->part->name;
        	    $description    = null;
        	    $qty            = $value->qty;
        	    $srp            = $value->part->srp;
        	    $product_promotion_id = null;
        	    $discount_type  = null;
        	    $discount_value = null;
        	    $discount_amount= null;
        	    $selling_price  = $qty * $srp;
        	    $buying_price   = null;

        	    if(is_null($value->part->activePromo)){
        	        $discount_amount = 0; 
        	        //getting description by part
        	        $description = $value->part->description;
        	    }else{
        	    	$product_promotion_id = $value->part->activePromo->id;
        	        //getting description by promotion
        	        $description = $value->part->activePromo->description;

        	        if($value->part->activePromo->promotion->is_percent == 0){
        	            // actual amount to be deduct
        	            $discount_type  = 'real';
        	            $discount_value = $value->part->activePromo->promotion->amount;

        	            $discount_amount= $discount_value;
        	        }else if($value->part->activePromo->promotion->is_percent == 1){
        	            // percent amount to be deduct
        	            $discount_type  = 'percent';
        	            $discount_value = $value->part->activePromo->promotion->amount;

        	            $discount_amount= ($discount_value / 100) * $selling_price;
        	        }
        	    }
        	    //get the buying price
        	    $buying_price   = ($selling_price) - $discount_amount;

        	    //totals
        	    array_push($cartList, [
        	        'cart_id'           => $value->id,
        	        'part_id'           => $product_id,
        	        'name'              => $name,
        	        'description'       => $description,
        	        'qty'               => $qty,
        	        'srp'               => $srp,
        	        'selling_price'     => $selling_price,
        	        'discount_type'     => $discount_type,
        	        'discount_value'    => $discount_value,
        	        'discount_amount'   => $discount_amount, 
        	        'buying_price'      => $buying_price
        	    ]);

        	    //create purchase details
        	    $pd = new PurchaseDetail; 
        	    $pd->purchase_id 			= $ph->id; 
        	    $pd->part_id           		= $product_id; 
        	    $pd->product_promotion_id 	= $product_promotion_id;
        	    $pd->description       = $description;
        	    $pd->qty               = $qty;
        	    $pd->srp               = $srp;
        	    $pd->selling_price     = $selling_price;
        	    $pd->discount_type     = $discount_type;
        	    $pd->discount_value    = $discount_value;
        	    $pd->discount_amount   = $discount_amount;
        	    $pd->buying_price      = $buying_price;
        	    $pd->status 			= 'P';
        	    $pd->valid_until 		= Carbon::now()->addDay();
        	    $pd->save();


        	    $total_gross       += $selling_price;
        	    $total_discount    += $discount_amount;
        	    $total_net         += $buying_price;
        	}
        	//=============================================== 

        	//check if the e money is enough to purchase this transaction 
        	if($user->customer->wallet < $total_net){
        		DB::rollback();
        		return response()->json([
		        	'success' 	=> false,
		        	'status' 	=> 401,
		        	'message' 	=> 'Your wallet is not enough to purchase this order!'
		        ]);
        		//throw new Exception;
        	}

        	//update purchase header for total;
        	$ph->gross_amount 		= $total_gross;
        	$ph->total_discount 	= $total_discount;
        	$ph->net_amount 		= $total_net;
        	$ph->save();

        	//update wallet
        	$customer = Customer::find($user->customer->id);
        	$customer->wallet = $customer->wallet - $total_net;
        	$customer->save();

            //earned points 
            $ept    = new  EarnPointTransactionServices;
            $ep     = $ept->earnPoints($ph->id, $total_net);
            $eps    = $ept->save();

            //update customer points for new earned point
            $customer->points   = $customer->points + $eps->earned_points;
            $customer->save();

        	//remove cart from this current branch
        	$cart = Cart::removeCartByUserID($user->id);
 
		    DB::commit();
		    // all good
		} catch (\Exception $e) {
		    DB::rollback();
		    // something went wrong 
		} 

        return response()->json([
        	'success' 	=> true,
        	'status' 	=> 200,
        	'message' 	=> 'success'
        ]);
    }
}
