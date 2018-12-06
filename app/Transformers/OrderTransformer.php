<?php 

namespace App\Transformers;
use Carbon\Carbon;

//models
use App\SitePart;
use App\OrderSlipDetail;

class OrderTransformer {

	public function orderHistory($data){
		$data->getCollection()->transform(function ($value) {  

			$osd 	= new OrderSlipDetail;
			$details= $osd->where('orderslip_header_id', $value->orderslip_header_id)
						->get();

			$details->transform(function ($x){
				$part = SitePart::where('sitepart_id', $x->product_id)
							->where('branch_id', $x->branch_id)
							->first();

				return [
					'branch_id' 			=> $x->branch_id, 
			    	'orderslip_detail_id' 	=> $x->orderslip_detail_id , 
			     	'orderslip_header_id' 	=> $x->orderslip_header_id, 
			    	'product_id' 			=> $x->product_id,
			    	'product_name' 			=> $part->product_name,
			    	'part_number'			=> $x->part_number, 
			    	'product_group_id'		=> $x->product_group_id , 
			    	'qty' 					=> $x->qty, 
			    	'srp' 					=> $x->srp, 
			    	'amount' 				=> $x->amount,
				];
			});

			return [
				'orderslip_header_id'	=> $value->orderslip_header_id,
				'branch_id' 			=> $value->branch_id, 
				'transaction_type_id'	=> $value->transaction_type_id,
				'total_amount'			=> $value->total_amount,
				'discount_amount'		=> $value->discount_amount,
				'net_amount' 			=> $value->net_amount,
				'status' 				=> $value->status,
				'customer_id' 			=> $value->customer_id,
				'mobile_number' 		=> $value->mobile_number,
				'customer_name' 		=> $value->customer_name,
				'details' 				=> $details
			];
		});
		return $data;
	}
}