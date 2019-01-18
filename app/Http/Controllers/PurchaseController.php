<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth, Validator; 
use Carbon\Carbon;
use App\User;
use App\Customer;
use App\Cart;
use App\Postmix;
use App\SiteTerminal;
 
use App\Part;
use App\SitePart;
use App\ProductPromotion;
use App\Promotion;
use App\Transformers\CartTransformer;
use App\Transformers\PurchaseTransformer;

use App\Purchase;
use App\PurchaseDetail;

use App\AppServices\EarnPointTransactionServices;
use App\AppServices\BranchLastIssuedNumberServices;
use App\AppServices\TaxServices;
 
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

            //check if the cart has not contain an item 
            if($carts->isEmpty()){
                DB::rollback();
                return response()->json([
                    'success'   => false,
                    'status'    => 401,
                    'message'   => 'Please maintain atleast 1 item from your cart to continue.'
                ]);
            }

        	//create a purchase header
            $new_sales_order_id         = $blin->getNewIdForSalesOrderHeader();
        	$ph = new Purchase;
        	$ph->branch_id 		        = config('app.branch_id');
            $ph->sales_order_id         = $new_sales_order_id;
        	$ph->customer_id	        = $user->customer->customer_id;
        	$ph->transaction_type_id    = 1;
        	$ph->save();  
             
            //===============================================  
            $st = new SiteTerminal;
            $st = $st->terminalSetting();
            
            $tax = new TaxServices(
                $user->customer->specialDiscount, 
                $user->customer->is_zero_rated,
                $st->amusement_tax
            );

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

                //----------------------
                $x = [
                    'ref_id' => $new_sales_order_id,
                    'price' => $part->srp,
                    'qty'   => $value->qty,
                    'is_vat' => $part->is_vat,
                    'is_admission'  => $part->pre_part_no,
                    'admission_fee' => $part->admission_fee,
                    'amusement_tax' => $part->amusement_tax,
                ];
                $tax->getItem($x);
                //----------------------
 
                if($part->postmix == 1 && $part->is_food == 0){ 

                    // the all the postmix items at postmix
                    $postmix = Postmix::where('parent_id', $part->sitepart_id)
                                ->get();

                    for($i = 0 ; $i < $value->qty; $i++){
                        foreach($postmix as $pm){  

                            $ppart = SitePart::where('branch_id', config('app.branch_id'))
                                    ->where('sitepart_id', $pm->PARTSID)
                                    ->first();

                            //if the product is a group of wallet
                            // do not allow to proceed and display a warning message
                            if($ppart->group_id == config('app.group_wallet_id')){
                                DB::rollback();
                                return response()->json([
                                    'success'   => false,
                                    'status'    => 401,
                                    'message'   => 'You cannot purchase e-Wallet using a e-Wallet'
                                ]);
                            }  

                            $result = $this->saveToSalesOrderDetail($user, $ppart, (int)$pm->QUANTITY, $new_sales_order_id);

                        } 
                    }
                    
                    //logic
                    $product_id            = $part->sitepart_id;
                    $name                  = $part->product_name;
                    $description           = '';
                    $qty                   = $value->qty;
                    $srp                   = $part->srp;
                    $product_promotion_id  = null;
                    $discount_type         = 0;
                    $discount_value        = 0;
                    $discount_amount       = 0;
                    $selling_price         = $qty * $srp;
                    $buying_price          = 0;
                    $is_unli               = $part->is_unli;

                    //get the buying price
                    $buying_price   = ($selling_price) - $discount_amount;

                    $total_gross       += $selling_price;
                    $total_discount    += $discount_amount;
                    $total_net         += $buying_price;

                }else{

                    //if the product is a group of wallet
                    // do not allow to proceed and display a warning message
                    if($part->group_id == config('app.group_wallet_id')){
                        DB::rollback();
                        return response()->json([
                            'success'   => false,
                            'status'    => 401,
                            'message'   => 'You cannot purchase e-Wallet using a e-Wallet'
                        ]);
                    }  

                    $result = $this->saveToSalesOrderDetail($user, $part, $value->qty, $new_sales_order_id);

                    $total_gross       += $result['selling_price'];
                    $total_discount    += $result['discount_amount'];
                    $total_net         += $result['buying_price'];
                } 
        	}
        	//===============================================  

            //--
            DB::rollback();
            dd($tax->result() );
            return response()->json([
                'success' => false,
                'status' => 500,
                'data' => $tax->result()
            ]); 
            //--

            $virtual_wallet     = $user->customer->wallet;
            $virtual_points     = $user->customer->points;  

            // check the points entered as payment if greater than the customer wallet
            if($points_payment > $user->customer->points){
                DB::rollback();
                return response()->json([
                    'success'   => false,
                    'status'    => 401,
                    'message'   => 'The points you entered as payment is greater than your actual points!'
                ]);
            } 

            // check and not allow if the points payment is greater than the total net
            if($points_payment > $total_net){
                DB::rollback();
                return response()->json([
                    'success'   => false,
                    'status'    => 401,
                    'message'   => 'The points you entered as payment must be equal or less than the NET Amount!'
                ]);
            }

            $wallet_payment     = $total_net - $points_payment;

        	//check if the e money is enough to purchase this transaction 
        	if($user->customer->wallet < $wallet_payment ){
        		DB::rollback();
        		return response()->json([
		        	'success' 	=> false,
		        	'status' 	=> 401,
		        	'message' 	=> 'Your wallet is not enough to purchase this order!'
                ]);
                
        	} 

            //-------------------------
            //earned points 
            $ept    = new  EarnPointTransactionServices;
            $ep     = $ept->earnPoints(
                            $new_sales_order_id, 
                            $total_net, 
                            $user->customer->customer_id, 
                            $points_payment //this will be the amount excemption for points earning
                        );
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

            //update customer points for new earned point
            $customerrr = Customer::where('CUSTOMERID',$user->customer->customer_id)
                            ->update([
                            'wallet'    => $virtual_wallet,
                            'points'    => $virtual_points, 
                        ]); 
                        
 
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
            dd($e);
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
        ]);
    } 

    private function saveToSalesOrderDetail($user, $part, $qty, $new_sales_order_id){ 

        //logic
        $product_id            = $part->sitepart_id;
        $name                  = $part->product_name;
        $description           = '';
        $qty                   = $qty;
        $srp                   = $part->srp;
        $product_promotion_id  = null;
        $discount_type         = 0;
        $discount_value        = 0;
        $discount_amount       = 0;
        $selling_price         = $qty * $srp;
        $buying_price          = 0;
        $is_unli               = $part->is_unli;

        //get the buying price
        $buying_price   = ($selling_price) - $discount_amount;

        //totals 
        

        //create purchase details
        $now = Carbon::now();
        //$dd = \Carbon\Carbon::create($d->year,$d->month,$d->day,23,59,59); 
        $validity = Carbon::create($now->year, $now->month, $now->day, 23, 59, 59); 

        // get a new id for sales order details
        $blins = new BranchLastIssuedNumberServices;
        $new_sales_order_detail_id = $blins->getNewIdForSalesOrderDetails();
        
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
        $pd->customer_id         = $user->customer->customer_id;
        $pd->customer_number     = $user->mobile_number;
        $pd->transaction_type    = 'WEB';
        $pd->barcode             = $new_sales_order_detail_id.'-'.$product_id;
        $pd->is_food             = $part->is_food;

        if($is_unli == 1){
            $pd->is_unli         = 1;
        }

        //saving
        $pd->save();

        return [
            'selling_price'     => $selling_price,
            'discount_amount'   => $discount_amount,
            'buying_price'      => $buying_price
        ];
    }

    public function show(){
        return view('pages.customers.purchase_history'); 
    }
}
