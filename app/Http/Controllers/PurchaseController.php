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
use App\Purchase; 
use App\PurchaseDetail;
use App\PurchaseTransaction;
use App\PurchaseTransactionDetail;
use App\TurnSite;

use App\Transformers\CartTransformer;
use App\Transformers\PurchaseTransformer;

use App\AppServices\EarnPointTransactionServices;
use App\AppServices\BranchLastIssuedNumberServices;
use App\AppServices\TaxServices;
use App\AppServices\CustomerServices;
use App\AppServices\TurnSiteServices;
 
use DB;
use App\KitchenOrder;
use App\AppServices\Helper; 

class PurchaseController extends Controller 
{
    //
    public function checkout(Request $request){

        // points payment initialization
        $points_payment = null;
        if (is_null($request->points_payment)) {
            $points_payment = 0;
        } else {
            $points_payment = $request->points_payment;
        }

        try{

            DB::beginTransaction();

            $helper = new Helper;

            $blin = new BranchLastIssuedNumberServices;
            $blin->findOrCreate();

            $user = Auth::user();
            $customer = new CustomerServices($user->customer);
            $carts = Cart::findByUserAndType($user->id, 'wallet');   

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
            $ph->branch_id              = config('app.branch_id');
            $ph->sales_order_id         = $new_sales_order_id;
            $ph->customer_id            = $user->customer->customer_id;
            $ph->transaction_type_id    = 1;
            $ph->save();

            /**
             * create new invoice number and transaction number
             */
            $blin->getNewIdForInvoice();
            $blin->getNewIdForTransaction();
            
            /**
             * Create Purchase Transaction
             */
            $now    = Carbon::now();
            $pt     = new PurchaseTransaction; 
            $pt->sales_order_id             = $new_sales_order_id;
            $pt->created_at                 = $now;
            $pt->transaction_no             = $blin->getTransaction();
            $pt->invoice_no                 = $blin->getInvoice();
            $pt->customer_id                = $user->customer->customer_id;
            $pt->customer_code              = $user->customer->customer_code;
            $pt->customer_name              = $user->customer->name;
            $pt->customer_type              = $user->customer->customer_type;
            $pt->customer_id_number         = $user->customer->scpwd_id; 
            $pt->customer_address           = $user->customer->address;
            $pt->save();

            if (!$pt ) {
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'status' => 401,
                    'message' => 'Error saving in Purchase Transaction.'
                ]);
            } 


            $ts = new TaxServices;
            $result = $ts->items($carts, $customer, $user);

            // from array to object
            $result = (object)$result; 
            
            /**
             * Saving the items one by one into its designated details
             */
            foreach ($result->items as $key => $item) {
                # code... 
                # cast $item array into object
                $item = (object)$item; 

                /**
                 * FOR POSTMIX BUT NOT FOOD
                 */
                if ($item->is_postmix == 1 && $item->is_food == 0) {
                   
                    $postmix = Postmix::where('parent_id', $item->product_id)->get();
                    
                    for ($i = 0; $i < $item->qty; $i++) {

                        /**
                         * Read each postmix item for saving
                         */
                        foreach ($postmix as $pm) {
                            
                            $sitepart = SitePart::where('branch_id', config('app.branch_id'))
                                ->where('sitepart_id', $pm->PARTSID)
                                ->first();
                            /**
                             * if the product is a group of wallet
                             * do not allow to proceed and display a warning message
                             */ 
                            if ($sitepart->group_id == config('app.group_wallet_id')) {
                                DB::rollback();
                                return response()->json([
                                    'success' => false,
                                    'status' => 401,
                                    'message' => 'You cannot purchase Load using a Load-Wallet'
                                ]);
                            }

                            /**
                             * save to sales order details
                             */
                            if( !$this->saveToSalesOrderDetail($user, $sitepart, (int)$pm->QUANTITY, $new_sales_order_id) ){
                                DB::rollback();
                                return response()->json([
                                    'success' => false,
                                    'status' => 401,
                                    'message' => 'Error saving in Sales Order Detail.'
                                ]); 
                            }
                            
                        }
                    } 
                }

                /**
                 * FOR KITCHEN 
                 */
                if ($item->is_postmix == 1 && $item->is_food == 1){

                    /**
                     * if the product is a group of wallet
                     * do not allow to proceed and display a warning message
                     */
                    if ($item->group_id == config('app.group_wallet_id')) {
                        DB::rollback();
                        return response()->json([
                            'success' => false,
                            'status' => 401,
                            'message' => 'You cannot purchase Load using a Load-Wallet'
                        ]);
                    }

                    /**
                     * save to sales order details
                     */
                    $arr_sitepart = [
                        'sitepart_id'   => $item->product_id,
                        'product_name'  => $item->product_name,
                        'srp'           => $item->price,
                        'is_unli'       => $item->is_unli,
                        'is_food'       => $item->is_food
                    ];
                    $obj_sitepart = (object)$arr_sitepart;
                    $sod = $this->saveToSalesOrderDetail($user, $obj_sitepart, $item->qty, $new_sales_order_id);
                    if (!$sod) {
                        DB::rollback();
                        return response()->json([
                            'success' => false,
                            'status' => 401,
                            'message' => 'Error saving in Sales Order Detail.'
                        ]);
                    };

                    // dd($sod);

                    //$ifPartsTypeIsY = trim(strtolower($item->cart->product->parts_type));
                    //if($ifPartsTypeIsY == null || $ifPartsTypeIsY == '') {
                        // save all components to kitchen
                        //saving to kitchen 
                        // $item->cart->product->components->each(function ($v, $k) use ($blin, $pt, $sod, $item, $helper, $now) {
                        //     if ($v->modifiable != 1) { 
                        //      // saving none modifiable item into kitchen 
                        //         $ko = new KitchenOrder;
                        //         $ko->branch_id = config('app.branch_id');
                        //         $ko->ko_id = $blin->getNewIdForKitchenOrder();
                        //         $ko->transact_type = 2;
                        //         $ko->header_id = $pt->sales_order_id;
                        //         $ko->detail_id = $sod;
                        //         $ko->part_id = $item->product_id;
                        //         $ko->comp_id = (int)$v->product_id;
                        //         $ko->location_id = $v->componentProduct->kitchen_loc;
                        //         $ko->qty = (int)$v->quantity;
                        //         $ko->balance = (int)$v->quantity;
                        //         $ko->status = 'P';
                        //         $ko->created_at = $now;
                        //         $ko->created_date = $helper->getClarionDate($now);
                        //         $ko->created_time = $helper->getClarionTime($now);
                        //         $ko->save();
                        //     }
                        // }); 
                            
                         // save base sitepart to the kitchen
                        $ko = new KitchenOrder;
                        $ko->branch_id = config('app.branch_id');
                        $ko->ko_id = $blin->getNewIdForKitchenOrder();
                        $ko->transact_type = 2;
                        $ko->header_id = $pt->sales_order_id;
                        $ko->detail_id = $sod;
                        $ko->part_id = $item->cart->product->sitepart_id;
                        $ko->comp_id = $item->cart->product->sitepart_id;
                        $ko->location_id = $item->cart->product->kitchen_loc;
                        $ko->qty = (int)$item->qty;
                        $ko->balance = (int)$item->qty;
                        $ko->status = 'P';
                        $ko->created_at = $now;
                        $ko->created_date = $helper->getClarionDate($now);
                        $ko->created_time = $helper->getClarionTime($now);
                        $ko->save();

                        //saving cart component to the kitchen
                        $item->cart->components->each(function ($v, $k) use ($blin, $pt, $sod, $item, $helper, $now) {
                            if((int)$v->qty > 0){
                                $ko = new KitchenOrder;
                                $ko->branch_id          = config('app.branch_id');
                                $ko->ko_id              = $blin->getNewIdForKitchenOrder();
                                $ko->transact_type      = 2;
                                $ko->header_id          = $pt->sales_order_id;
                                $ko->detail_id          = $sod;
                                $ko->part_id            = $item->product_id;
                                $ko->comp_id            = (int)$v->product_id;
                                $ko->location_id        = $v->product->kitchen_loc;
                                $ko->qty                = (int)$v->qty;
                                $ko->balance            = (int)$v->qty;
                                $ko->status             = 'P';
                                $ko->created_at         = $now;
                                $ko->created_date       = $helper->getClarionDate($now);
                                $ko->created_time       = $helper->getClarionTime($now);
                                $ko->save();
                            } 
                        });
                    //}

                    // if ( $ifPartsTypeIsY == 'y') {
                    //     // save base sitepart to the kitchen
                    //     $ko = new KitchenOrder;
                    //     $ko->branch_id          = config('app.branch_id');
                    //     $ko->ko_id              = $blin->getNewIdForKitchenOrder();
                    //     $ko->transact_type      = 2;
                    //     $ko->header_id          = $pt->sales_order_id;
                    //     $ko->detail_id          = $sod;
                    //     $ko->part_id            = $item->cart->product->sitepart_id;
                    //     $ko->comp_id            = $item->cart->product->sitepart_id;
                    //     $ko->location_id        = $item->cart->product->kitchen_loc;
                    //     $ko->qty                = (int)$item->qty;
                    //     $ko->balance            = (int)$item->qty;
                    //     $ko->status             = 'P';
                    //     $ko->created_at         = $now;
                    //     $ko->created_date       = $helper->getClarionDate($now);
                    //     $ko->created_time       = $helper->getClarionTime($now);
                    //     $ko->save();
                    // }
                    
                }

                /**
                 * FOR NONE POSTMIX BUT FOOD
                 */
                if ($item->is_postmix == 0 && $item->is_food == 1) {
                    /**
                     * if the product is a group of wallet
                     * do not allow to proceed and display a warning message
                     */
                    if ($item->group_id == config('app.group_wallet_id')) {
                        DB::rollback();
                        return response()->json([
                            'success' => false,
                            'status' => 401,
                            'message' => 'You cannot purchase Load using a Load-Wallet'
                        ]);
                    }

                    /**
                     * save to sales order details
                     */
                    $arr_sitepart = [
                        'sitepart_id' => $item->product_id,
                        'product_name' => $item->product_name,
                        'srp' => $item->price,
                        'is_unli' => $item->is_unli,
                        'is_food' => $item->is_food
                    ];
                    $obj_sitepart = (object)$arr_sitepart;
                    $sod = $this->saveToSalesOrderDetail($user, $obj_sitepart, $item->qty, $new_sales_order_id);
                    if (!$sod) {
                        DB::rollback();
                        return response()->json([
                            'success' => false,
                            'status' => 401,
                            'message' => 'Error saving in Sales Order Detail.'
                        ]);
                    };

                    $ko = new KitchenOrder;
                    $ko->branch_id          = config('app.branch_id');
                    $ko->ko_id              = $blin->getNewIdForKitchenOrder();
                    $ko->transact_type      = 2;
                    $ko->header_id          = $pt->sales_order_id;
                    $ko->detail_id          = $sod;
                    $ko->part_id            = $item->product_id;
                    $ko->comp_id            = $item->product_id;
                    $ko->location_id        = $item->cart->product->kitchen_loc;
                    $ko->qty                = (int)$v->qty;
                    $ko->balance            = (int)$v->qty;
                    $ko->status             = 'P';
                    $ko->created_at         = $now;
                    $ko->created_date       = $helper->getClarionDate($now);
                    $ko->created_time       = $helper->getClarionTime($now);
                    $ko->save();
                }

                /**
                 * FOR NONE POSTMIX AND NONE FOOD
                 */
                if ($item->is_postmix == 0 && $item->is_food == 0) {

                    /**
                     * if the product is a group of wallet
                     * do not allow to proceed and display a warning message
                     */
                    if ($item->group_id == config('app.group_wallet_id')) {
                        DB::rollback();
                        return response()->json([
                            'success' => false,
                            'status' => 401,
                            'message' => 'You cannot purchase Load using a Load-Wallet'
                        ]);
                    }

                    /**
                     * save to sales order details
                     */
                    $arr_sitepart = [
                        'sitepart_id'   => $item->product_id,
                        'product_name'  => $item->product_name,
                        'srp'           => $item->price,
                        'is_unli'       => $item->is_unli,
                        'is_food'       => $item->is_food
                    ];
                    $obj_sitepart = (object)$arr_sitepart;
                    if( !$this->saveToSalesOrderDetail($user, $obj_sitepart, $item->qty, $new_sales_order_id) ) {
                        DB::rollback();
                        return response()->json([
                            'success' => false,
                            'status' => 401,
                            'message' => 'Error saving in Sales Order Detail.'
                        ]); 
                    };

                }


                /**
                 * Create Purchase Transaction Detail
                 */ 
                if( !$this->saveToPurchaseTransactionDetail($item, $pt, $key + 1) ){
                    DB::rollback();
                    return response()->json([
                        'success'   => false,
                        'status'    => 401,
                        'message'   => 'Error saving in Purchase Detail Transaction.'
                    ]); 
                } 
                
            }
            // dd($result->vat_exempt_sales);
            $pt->gross_total                = $result->gross_amount;
            $pt->scpwd_discount             = $result->scpwd_discount;
            $pt->net_total                  = $result->net_amount;
            $pt->vatable_sales_total        = $result->vatable_sales;
            $pt->vat_exempt_sales_total     = $result->vat_exempt_sales;
            $pt->vat_zerorated_sales_total  = $result->vat_zerorated_sales;
            $pt->vat_amount_total           = $result->vat_amount;
            $pt->r_vat_amount_total         = $result->r_vat_amount;
            $pt->admission_sales_total      = $result->admission_sales;
            //$pt->amusement_tax_exempt_sales_total   = $result->amusement_tax_exempt_sales;
            //$pt->amusement_zerorated_sales_total    = $result->amusement_zerorated_sales;
            $pt->amusement_tax_amount_total     = $result->amusement_tax_amount;
            $pt->r_amusement_tax_amount_total   = $result->r_amusement_tax_amount; 
            // dd($pt);
            $pt->save();
 
            //=========================================================================
            
            /**
             * Applying Wallet/Points for the Net Amount
             */

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
            if ($points_payment > $result->net_amount) {
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'status' => 401,
                    'message' => 'The points you entered as payment must be equal or less than the NET Amount!'
                ]);
            }

            $wallet_payment = $result->net_amount - $points_payment;

            //check if the e money is enough to purchase this transaction 
            if ($user->customer->wallet < $wallet_payment) {
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'status' => 401,
                    'message' => 'Your wallet is not enough to purchase this order!'
                ]); 
            } 

            //earned points 
            $ept = new EarnPointTransactionServices;
            $ep = $ept->earnPoints(
                $new_sales_order_id,
                $result->net_amount,
                $user->customer->customer_id,
                $points_payment //this will be the amount excemption for points earning
            );
            $eps = $ept->save();

            $virtual_points -= $points_payment;
            $virtual_wallet -= $wallet_payment; 

             //adding earned point to virtual points
            $virtual_points += $eps->earned_points;

            //update purchase header for total;
            // dd(
            //     $result
            // );
            Purchase::where('sales_order_id', $new_sales_order_id)
                ->update([
                    'total_amount'      => $result->gross_amount,
                    'total_discount'    => $result->scpwd_discount,
                    'net_amount'        => $result->net_amount,
                    'used_wallet'       => $wallet_payment,
                    'used_points'       => $points_payment,
                    'added_points'      => $eps->earned_points,
                    'wallet_balance'    => $virtual_wallet,
                    'points_balance'    => $virtual_points
                ]);

            /**
             * Turn Site
             */
            $tss = new TurnSiteServices;
            $tss = $tss->findOrCreate(); 
            $tsss = TurnSite::where('STATIONCODE', $tss->station_code)
                    ->where('TURNOVERID', $tss->turn_over_id)
                    ->update([
                    'TOTALSALE'   => $tss->total_sales + $result->net_amount,
                    'PO'          => $tss->po + $points_payment,
                    'PREPAY'      => $tss->prepay + $wallet_payment
                    ]);
        

            //update customer points for new earned point
            $customerrr = Customer::where('CUSTOMERID', $user->customer->customer_id)
                ->update([
                    'wallet' => $virtual_wallet,
                    'points' => $virtual_points,
                ]); 

            //remove cart from this current branch 
            foreach($result->items as $item){
                $item = (object)$item;
                $item->cart->components->each( function($component){
                    $component->delete();
                });
                $item->cart->delete();
            }
            // $cart = Cart::removeCartByUserIDAndType($user->id, 'wallet');

            /**
             * Committing all changes in the database
             */
            DB::commit();

            /**
             * @return Success
             */
            return response()->json([
                'success'   => true,
                'status'    => 200,
                'message'   => 'success'
            ]);

        }catch (\Exception $e) {
            // if something went wrong
            DB::rollback(); 
            return response()->json([
                'success'   => false,
                'status'    => 500,
                //'message'   => 'Something went wrong. \nPlease try again.'
                 'message' => $e->getMessage()
            ]); 
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

        $purchase = Purchase::with(['details'])
                ->where('customer_id', $user->customer->CUSTOMERID) 
                ->orderBy('created_at', 'desc')
                ->simplePaginate(10);

        $pt         = new PurchaseTransformer;
        $pt->purchaseHistory($purchase);

        //dd($purchase);

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
        
        return $new_sales_order_detail_id;
    }

    private function saveToPurchaseTransactionDetail($item, $pt, $sequence){
  
        $now = Carbon::now();
        $ptd = new PurchaseTransactionDetail;
        $ptd->purchase_transaction_id           = $pt->id;
        $ptd->sequence                          = $sequence;
        $ptd->sitepart_id                       = $item->product_id;
        $ptd->product_name                      = $item->product_name;
        $ptd->cost                              = $item->cost;
        $ptd->retail_price                      = $item->price;
        $ptd->qty                               = $item->qty;
        $ptd->is_postmix                        = $item->is_postmix;
        $ptd->is_vat                            = $item->is_vat;
        $ptd->is_food                           = $item->is_food;
        $ptd->vatable_sales                     = $item->vatable_sales;
        $ptd->vat_exempt_sales                  = $item->vat_exempt_sales;
        $ptd->vat_zerorated_sales               = $item->vat_zerorated_sales;
        $ptd->zerorated_vat_amount              = $item->zerorated_vat_amount;
        $ptd->vat_amount                        = $item->vat_amount;
        $ptd->r_vat_amount                      = $item->r_vat_amount;
        $ptd->is_admission                      = $item->is_admission;
        $ptd->admission_sales                   = $item->admission_sales;
        // $ptd->amusement_tax_exempt_sales        = $item->amusement_tax_exempt_sales;
        // $ptd->amusement_zerorated_sales         = $item->amusement_zerorated_sales;
        // $ptd->amusement_zerorated_amount        = $item->amusement_zerorated_amount;
        $ptd->amusement_tax_amount              = $item->amusement_tax_amount;
        $ptd->r_amusement_tax_amount            = $item->r_amusement_tax_amount;
        $ptd->gross_amount                      = $item->gross_amount;
        //$ptd->promo_code                        = $item->promo_code;
        $ptd->discount_rate                     = 0;
        $ptd->discount_value                    = $item->scpwd_discount;
        $ptd->discount_amount                   = $item->scpwd_discount;
        $ptd->net_amount                        = $item->net_amount;
        $ptd->created_at                        = $now;
        $ptd->save();
        
        return $ptd;
    }

    public function show(){
        return view('pages.customers.purchase_history'); 
    }
}
