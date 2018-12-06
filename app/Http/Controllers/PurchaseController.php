<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth, Validator; 
use Carbon\Carbon;
use App\User;
use App\Customer;
use App\Cart;

use App\Reward;
use App\Part;
use App\ProductPromotion;
use App\Promotion;
use App\Transformers\CartTransformer;
use App\Transformers\PurchaseTransformer;

use App\Purchase;
use App\PurchaseDetail;

use App\AppServices\EarnPointTransactionServices;
use App\AppServices\BranchLastIssuedNumberServices;

use App\Redemption;
use App\RedemptionDetail;
use DB;

class PurchaseController extends Controller
{
    //
    public function checkout(Request $request){
         
        $points_payment = null;
        if(is_null($request->points_payment)){
            $points_payment = 0;
        }else{
            $points_payment = $request->points_payment;
        }

        DB::beginTransaction();

        $blin = new BranchLastIssuedNumberServices;
        $blin->findOrCreate();    

        $user   = Auth::user();
        $carts  = Cart::findByUserAndType($user->id,'wallet');  

        try {  

        	//create a purchase header
            $new_sales_order_id         = $blin->getNewIdForSalesOrderHeader();
        	$ph = new Purchase;
        	$ph->branch_id 		        = config('cpp.branch_id');
            $ph->sales_order_id         = $new_sales_order_id;
        	$ph->customer_id	        = $user->customer->CUSTOMERID;
        	$ph->transaction_type_id    = 1;
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

                //dd( $this->ifMultiTicketRides($part) );
                if( $this->ifMultiTicketRides($part) == true ){
                    // dd('TRUEEEE');
                    for($x = 0 ; $x < $value->qty; $x++){
                                //logic
                                $product_id            = $part->PRODUCT_ID;
                                $name                  = $part->SHORTCODE;
                                $description           = '';
                                $qty                   = 1;
                                $srp                   = $part->RETAIL;
                                $product_promotion_id  = null;
                                $discount_type         = 0;
                                $discount_value        = 0;
                                $discount_amount       = 0;
                                $selling_price         = $qty * $srp;
                                $buying_price          = 0;
                                $is_unli               = $part->SSBUFFER;
                                
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

                                //create purchase details
                                $now = Carbon::now();
                                //$dd = \Carbon\Carbon::create($d->year,$d->month,$d->day,23,59,59); 
                                $validity = Carbon::create($now->year, $now->month, $now->day, 23, 59, 59); 
                                $new_sales_order_detail_id = $blin->getNewIdForSalesOrderDetails();


                                $pd = new PurchaseDetail;
                                $pd->branch_id           = config('cpp.branch_id');
                                $pd->sales_order_detail_id = $new_sales_order_detail_id;
                                $pd->sales_order_id      = $new_sales_order_id; 
                                $pd->sitepart_id         = $product_id; 
                                //$pd->product_promotion_id     = $product_promotion_id;
                                $pd->part_description    = $description;
                                $pd->qty                 = $qty;
                                $pd->qty_remaining       = $qty;
                                $pd->srp                 = $srp;
                                $pd->amount              = $selling_price;
                                $pd->discount_ispercent  = $discount_type; 
                                $pd->discount_rate       = $discount_value;
                                $pd->discount_amount     = $discount_amount;
                                $pd->net_amount          = $buying_price;
                                $pd->status              = 'P';
                                $pd->valid_at            = $validity;
                                $pd->customer_id         = $user->customer->CUSTOMERID;
                                $pd->customer_number     = $user->mobile_number;
                                $pd->barcode             = $new_sales_order_detail_id.'-'.$product_id;
                                dd($pd->barcode);
                                if($is_unli == 1){
                                    $pd->is_unli         = 1;
                                }

                                //saving
                                $pd->save(); 

                                $total_gross       += $selling_price;
                                $total_discount    += $discount_amount;
                                $total_net         += $buying_price;
                    }

                }else{

                    //logic
                    $product_id            = $part->PRODUCT_ID;
                    $name                  = $part->SHORTCODE;
                    $description           = '';
                    $qty                   = $value->qty;
                    $srp                   = $part->RETAIL;
                    $product_promotion_id  = null;
                    $discount_type         = 0;
                    $discount_value        = 0;
                    $discount_amount       = 0;
                    $selling_price         = $qty * $srp;
                    $buying_price          = 0;
                    $is_unli                = $part->SSBUFFER;
                    
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

                    //create purchase details
                    $now = Carbon::now();
                    //$dd = \Carbon\Carbon::create($d->year,$d->month,$d->day,23,59,59); 
                    $validity = Carbon::create($now->year, $now->month, $now->day, 23, 59, 59); 
                    $new_sales_order_detail_id = $blin->getNewIdForSalesOrderDetails();


                    $pd = new PurchaseDetail;
                    $pd->branch_id           = config('cpp.branch_id');
                    $pd->sales_order_detail_id = $new_sales_order_detail_id;
                    $pd->sales_order_id      = $new_sales_order_id; 
                    $pd->sitepart_id         = $product_id; 
                    //$pd->product_promotion_id     = $product_promotion_id;
                    $pd->part_description    = $description;
                    $pd->qty                 = $qty;
                    $pd->qty_remaining       = $qty;
                    $pd->srp                 = $srp;
                    $pd->amount              = $selling_price;
                    $pd->discount_ispercent  = $discount_type; 
                    $pd->discount_rate       = $discount_value;
                    $pd->discount_amount     = $discount_amount;
                    $pd->net_amount          = $buying_price;
                    $pd->status              = 'P';
                    $pd->valid_at            = $validity;
                    $pd->customer_id         = $user->customer->CUSTOMERID;
                    $pd->customer_number     = $user->mobile_number;
                    $pd->transaction_type    = 'WEB';
                    $pd->barcode             = $new_sales_order_detail_id.'-'.$product_id;
                    

                    if($is_unli == 1){
                        $pd->is_unli         = 1;
                    }

                    //saving
                    $pd->save();


                    $total_gross       += $selling_price;
                    $total_discount    += $discount_amount;
                    $total_net         += $buying_price;

                }
        	    
        	}
        	//===============================================  

            $virtual_wallet     = $user->customer->wallet;
            $virtual_points     = $user->customer->points; 

            $wallet_payment     = $total_net - $points_payment;

            // check the points entered as payment if greater than the customer wallet
            if($points_payment > $user->customer->points){
                DB::rollback();
                return response()->json([
                    'success'   => false,
                    'status'    => 401,
                    'message'   => 'The points you entered as payment is greater than your actual points!'
                ]);
            } 

        	//check if the e money is enough to purchase this transaction 
        	if($user->customer->wallet < $wallet_payment ){
        		DB::rollback();
        		return response()->json([
		        	'success' 	=> false,
		        	'status' 	=> 401,
		        	'message' 	=> 'Your wallet is not enough to purchase this order!'
		        ]);
        		//throw new Exception;
        	} 

            //-------------------------
            //earned points 
            $ept    = new  EarnPointTransactionServices;
            $ep     = $ept->earnPoints($new_sales_order_id, $total_net, $user->customer->CUSTOMERID);
            $eps    = $ept->save();


            $virtual_points -= $points_payment;
            $virtual_wallet -= $wallet_payment; 

            //adding earned point to virtual points
            $virtual_points += $eps->earned_points;

        	//update purchase header for total;
            Purchase::where('sales_order_id', $new_sales_order_id) 
                      ->update([
                        'total_amount'        => $total_gross,
                        'total_discount'      => $total_discount,
                        'net_amount'          => $total_net,
                        'used_wallet'         => $wallet_payment,
                        'used_points'         => $points_payment,
                        'added_points'        => $eps->earned_points,
                        'wallet_balance'      => $virtual_wallet,
                        'points_balance'      => $virtual_points
                    ]);

        	// $ph->TOTALAMOUNT 		= $total_gross;
        	// $ph->DISCOUNT 	        = $total_discount;
        	// $ph->NETAMOUNT 		    = $total_net;
        	// $ph->update($ph->ORDERSLIPNO);
  

            //update customer points for new earned point
            $customerrr = Customer::where('CUSTOMERID',$user->customer->CUSTOMERID)
                            ->update([
                            'wallet'    => $virtual_wallet,
                            'points'    => $virtual_points, 
                        ]); 
            // $customer->points   = $customer->points + $eps->earned_points;
            // $customer->save();

        	//update wallet
        	// $customer = Customer::where('CUSTOMERID',$user->customer->CUSTOMERID)
         //                    ->first();
         //    $customerr = Customer::where('CUSTOMERID',$user->customer->CUSTOMERID)
         //                    ->update([
         //                    'wallet'    => $virtual_wallet,
         //                    'points'    => $virtual_points
         //                ]); 

         //    $customer->wallet = $customer->wallet - $total_net;
        	// $customer->save(['CUSTOMERID' => $user->customer->CUSTOMERID]); 
 
        	//remove cart from this current branch
        	$cart = Cart::removeCartByUserIDAndType($user->id, 'wallet');
 
		    DB::commit();
		    // all good
            return response()->json([
                'success'   => true,
                'status'    => 200,
                'message'   => 'success'
            ]);

		} catch (\Exception $e) {
		    DB::rollback();
            //dd($e);
            return response()->json([
                'success'   => false,
                'status'    => 500,
                'message'   => $e->getMessage()
            ]);
		    // something went wrong 
		}  
    } 

    private function ifMultiTicketRides(Part $part){
        if($part->SSBUFFER == 1 && $part->GROUP == 30301){
            return true;
        }
        return false;
    }

    private function saveMultiTicketIfRides(Part $part){
    }

    public function checkoutReward(Request $request){ 

        DB::beginTransaction();

        $blin = new BranchLastIssuedNumberServices;
        $blin->findOrCreate();    

        $user   = Auth::user();
        $carts  = Cart::findByUserAndType($user->id,'points');  

        try { 

            // dd(
            //     config('cpp.branch_id'), 
            //     $blin->getNewIdForSalesOrderHeader(),
            //     $user->customer->id,
            //     1
            // );

            //create a purchase header
            $new_redemption_id     = $blin->getNewIdForRedemptionHeader();
            $ph = new Redemption;
            $ph->branch_id              = config('cpp.branch_id');
            $ph->redemption_header_id   = $new_redemption_id;
            $ph->customer_id            = $user->customer->CUSTOMERID;
            //$ph->transaction_type_id    = 1;
            $ph->save();  
            
            //=============================================== 
            // $total_gross = 0;
            // $total_discount = 0;
            $total_points = 0;
            $cartList = [];
            foreach ($carts as $key => $value) {
                # code... 
                $rewardd = new Reward;
                $reward = $rewardd->where('branch_id', config('cpp.branch_id'))
                            ->where('id', $value->product_id)
                            ->first();
                
                $subTotalPoints = $value->qty * $reward->required_points;
                //create purchase details
                $validity = Carbon::now()->addDay();
                $new_redemption_detail_id = $blin->getNewIdForRedemptionDetails();
                $pd = new RedemptionDetail;
                $pd->branch_id           = config('cpp.branch_id');
                $pd->redemption_detail_id = $new_redemption_detail_id;
                $pd->redemption_header_id = $new_redemption_id; 
                $pd->reward_id          = $reward->id;  
                $pd->qty                 = $value->qty;
                $pd->qty_remaining       = $value->qty;
                $pd->points              = $reward->required_points; 
                $pd->total_points        = $subTotalPoints;
                $pd->status              = 0; 
                $pd->customer_id         = $user->customer->CUSTOMERID; 
                $pd->save();
 
                $total_points         += $subTotalPoints;
            }
            //=============================================== 

            //check if the e money is enough to purchase this transaction 
            if($user->customer->points < $total_points){
                DB::rollback();
                return response()->json([
                    'success'   => false,
                    'status'    => 401,
                    'message'   => 'Your points is not enough to redeem this item!'
                ]);
                //throw new Exception;
            }

            //update purchase header for total;
            $customer = Customer::where('CUSTOMERID',$user->customer->CUSTOMERID)
                            ->first();

            Redemption::where('redemption_header_id', $new_redemption_id) 
                      ->update([
                        'total_points'        => $total_points, 
                    ]);  

            //update customer points for redemption
            $customerrr = Customer::where('CUSTOMERID',$user->customer->CUSTOMERID)
                            ->update([
                            'points'    => ($customer->points - $total_points), 
                        ]); 
            // $customer->points   = $customer->points + $eps->earned_points;
            // $customer->save();

            //remove cart from this current branch
            $cart = Cart::removeCartByUserIDAndType($user->id,'points');
 
            DB::commit();
            // all good
            return response()->json([
                'success'   => true,
                'status'    => 200,
                'message'   => 'success'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            //dd($e);
            return response()->json([
                'success'   => false,
                'status'    => 500,
                'message'   => $e->getMessage()
            ]);
            // something went wrong 
        }  
    }

    public function customerHistory(Request $request){
        $now  = Carbon::now();
        $user = Auth::user();

        $purchase = Purchase::with('details')
                ->where('customer_id', $user->customer->CUSTOMERID) 
                ->orderBy('created_at', 'desc')
                ->simplePaginate(10);

        $pt         = new PurchaseTransformer;
        $pt->purchaseHistory($purchase);

        return response()->json([ 
            'success'       => true,
            'status'        => 200,
            'data'          => $purchase,
            // 'pt'            => $pt
        ]);
    }

    
}
