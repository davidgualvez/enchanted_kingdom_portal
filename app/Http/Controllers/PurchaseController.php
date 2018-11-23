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
use App\AppServices\BranchLastIssuedNumberServices;

use DB;

class PurchaseController extends Controller
{
    //
    public function checkout(Request $request){
        $validity = Carbon::now()->addDay();
        //dd( ''.$validity );
        

        DB::beginTransaction();

        $blin = new BranchLastIssuedNumberServices;
        $blin->findOrCreate();    

        $user   = Auth::user();
        $carts  = Cart::findByUser($user->id);  

        try { 

        	//create a purchase header
        	$ph = new Purchase;
        	$ph->BRANCHID 		= config('cpp.branch_id');
            $ph->ORDERSLIPNO    = $blin->getNewIdForOrderSlipHeader();
        	$ph->CUSTOMER_ID	= $user->customer->id;
        	$ph->TRANSACTTYPEID = 1;
        	$ph->save(); 
        	//=============================================== 
        	$total_gross = 0;
        	$total_discount = 0;
        	$total_net = 0;
        	$cartList = [];
        	foreach ($carts as $key => $value) {
        	    # code... 
                $partt = new Part;
                $part = $partt->where($partt->branch_id, config('cpp.branch_id'))
                            ->where($partt->product_id, $value->product_id)
                            ->first();

        	    //logic
        	    $product_id     = $part->PRODUCT_ID;
        	    $name           = $part->SHORTCODE;
        	    $description    = '';
        	    $qty            = $value->qty;
        	    $srp            = $part->RETAIL;
        	    $product_promotion_id = null;
        	    $discount_type  = 0;
        	    $discount_value = 0;
        	    $discount_amount= 0;
        	    $selling_price  = $qty * $srp;
        	    $buying_price   = 0;

        	    if(is_null($part->activePromo)){
        	        $discount_amount = 0; 
        	        //getting description by part
        	        $description = $part->DESCRIPTION;
        	    }else{
        	    	$product_promotion_id = $part->activePromo->id;
        	        //getting description by promotion
        	        $description = $part->activePromo->description;

        	        if($part->activePromo->promotion->is_percent == 0){
        	            // actual amount to be deduct
        	            $discount_type  = 0; //real
        	            $discount_value = $part->activePromo->promotion->amount;

        	            $discount_amount= $discount_value;
        	        }else if($part->activePromo->promotion->is_percent == 1){
        	            // percent amount to be deduct
        	            $discount_type  = 1; //percent
        	            $discount_value = $part->activePromo->promotion->amount;

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
                $pd->BRANCHID               = config('cpp.branch_id');
                $pd->ORDERSLIPDETAILID      = $blin->getNewIdForOrderSlipDetails();
        	    $pd->ORDERSLIPNO 			= $ph->ORDERSLIPNO; 
        	    $pd->PRODUCT_ID           	= $product_id; 
        	    $pd->product_promotion_id 	= $product_promotion_id;
        	    $pd->description       = $description;
        	    $pd->QUANTITY          = $qty;
                $pd->qty_remaining     = $qty;
        	    $pd->RETAILPRICE       = $srp;
        	    $pd->AMOUNT            = $selling_price;
        	    $pd->ISPERCENT         = $discount_type; 
        	    $pd->DISCRATE          = $discount_value;
        	    $pd->DISCOUNT          = $discount_amount;
        	    $pd->NETAMOUNT         = $buying_price;
        	    $pd->STATUS 			= 'P';
        	    $pd->valid_until 		= ''.$validity;
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
            Purchase::where('ORDERSLIPNO', $ph->ORDERSLIPNO) 
                      ->update([
                        'TOTALAMOUNT'        => $total_gross,
                        'DISCOUNT'           => $total_discount,
                        'NETAMOUNT'          => $total_net,
                    ]);

        	// $ph->TOTALAMOUNT 		= $total_gross;
        	// $ph->DISCOUNT 	        = $total_discount;
        	// $ph->NETAMOUNT 		    = $total_net;
        	// $ph->update($ph->ORDERSLIPNO);

        	//update wallet
        	$customer = Customer::find($user->customer->id);
        	$customer->wallet = $customer->wallet - $total_net;
        	$customer->save();

            //earned points 
            $ept    = new  EarnPointTransactionServices;
            $ep     = $ept->earnPoints($ph->ORDERSLIPNO, $total_net);
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
            dd($e);
		    // something went wrong 
		} 

        return response()->json([
        	'success' 	=> true,
        	'status' 	=> 200,
        	'message' 	=> 'success'
        ]);
    }
}
