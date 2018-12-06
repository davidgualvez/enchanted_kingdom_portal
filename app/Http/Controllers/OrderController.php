<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use Carbon\Carbon;

//models 
use App\Cart;
use App\SitePart;
use App\OrderSlipHeader;
use App\OrderSlipDetail;

//services
use App\AppServices\BranchLastIssuedNumberServices;

//transformer
use App\Transformers\OrderTransformer;

class OrderController extends Controller
{
    //
    public function order(Request $request){

    	

    	try{
    		//begin
    		DB::beginTransaction();

    		//logic 
    		$user  = Auth::user(); 
    		$carts = Cart::findByUserAndType($user->id, 'wallet');
            $now   = Carbon::now();

    		$total_amount 	= 0;
    		$total_discount = 0;
    		$total_net 		= 0;

    		$blin = new BranchLastIssuedNumberServices;
        	$blin->findOrCreate();

    		$osh = new OrderSlipHeader;
    		$osh->orderslip_header_id	= $blin->getNewIdForOrderSlipHeader();
	      	$osh->branch_id 			= config('cpp.branch_id');
	      	$osh->transaction_type_id	= 1;
	      	$osh->total_amount			= 0;
	      	$osh->discount_amount		= 0;
	      	$osh->net_amount 			= 0;
	      	$osh->status 				= 'P'; //Pending
	      	$osh->customer_id 			= $user->customer->CUSTOMERID;
	      	$osh->mobile_number 		= $user->mobile_number;
	      	$osh->customer_name 		= $user->customer->NAME;
            $osh->created_at            = $now;
 	      	$osh->save(); 
	      	 

    		//loop the cart
    		foreach ($carts as $key => $value) {
    			# code...

    			//read the sitepart
    			$sp 	= SitePart::findByIdAndBranch($value->product_id);
    			
    			$amount = $value->qty * $sp->srp;
    			//discount
    			$discount 		= 0;
    			//total sub amount
    			$sub_amount 	= $amount - $discount;

    			//get the totals
    			$total_amount 	+= $amount;
    			$total_discount += $discount;
    			$total_net 		+= $sub_amount;

    			//save each of the item in OrderSlip
    			$osd = new OrderSlipDetail;
    			$osd->orderslip_detail_id 		= $blin->getNewIdForOrderSlipDetails();
    			$osd->orderslip_header_id 		= $osh->orderslip_header_id;
    			$osd->branch_id 				= config('cpp.branch_id');
    			$osd->product_id 				= $sp->sitepart_id;
    			$osd->part_number 				= $sp->part_no;
    			$osd->product_group_id 			= $sp->group_id;
    			$osd->qty 						= $value->qty;
    			$osd->srp 						= $sp->srp;
    			$osd->amount 					= $amount;
    			$osd->save();
 
    		} 

    		//save the total into OrderSlipHeader
    		$update_osh = new OrderSlipHeader;
    		$update_osh_map = $update_osh->getMaps();
    		//dd($update_osh_map);
    		$update_osh->where('orderslip_header_id', $osh->orderslip_header_id) 
                ->update([
                    $update_osh_map['total_amount'] 		=> $total_amount,
         			$update_osh_map['discount_amount']		=> $total_discount,
         			$update_osh_map['net_amount']	 		=> $total_net
                ]); 

            //remove cart from this current user and branch
        	Cart::removeCartByUserIDAndType($user->id, 'wallet');

    		//end
    		DB::commit();
    		return response()->json([
                'success'   => true,
                'status'    => 200,
                'message'   => 'success'
            ]);

    	}catch(\Exception $e){
    		DB::rollback();
    		return response()->json([
                'success'   => false,
                'status'    => 500, 
                'message'   => $e->getMessage()
            ]);
    	}
    	
    }

    public function customerHistory(Request $request){
        $now    = Carbon::now();
        $user   = Auth::user();

        $osh    = OrderSlipHeader::with('transType')
                    ->where('customer_id', $user->customer->CUSTOMERID)
                    ->orderBy('orderslip_header_id', 'desc')
                    ->simplePaginate(10);

        $ot     = new OrderTransformer;
        $history= $ot->orderHistory($osh);

        return response()->json([ 
            'success'       => true,
            'status'        => 200,
            'data'          => $osh, 
        ]);
    }
}
