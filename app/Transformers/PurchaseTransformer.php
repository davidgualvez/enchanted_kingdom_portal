<?php 

namespace App\Transformers;
use App\Part;
use Carbon\Carbon;

class PurchaseTransformer {

	public function purchaseHistory($data){

		$data->getCollection()->transform(function ($value) { 


			$dtls = $value->details;

			$dtls->transform( function($tsk){
				return [
					'details_id' 	=> $tsk->sales_order_detail_id,
					'sitepart_id' 	=> $tsk->sitepart_id,
					'qty' 			=> $tsk->srp,
					'srp' 			=> $tsk->srp,
					'amount' 		=> $tsk->amount,
					'discount' 		=> $tsk->discount_amount,
					'net_amount' 	=> $tsk->net_amount,
					'valid_until' 	=> $tsk->valid_at
				];	 
			});

            $nValue = [
               'sales_order_id' 	=> $value->sales_order_id,
               'branch_id' 			=> $value->branch_id,
               'customer_id' 		=> $value->customer_id,
               'total_amount' 		=> $value->total_amount,
               'total_discount' 	=> $value->total_discount,
               'net_amount' 		=> $value->net_amount,
               'used_wallet' 		=> $value->used_wallet,
               'used_points' 		=> $value->used_points,
               'added_points' 		=> $value->added_points,
               'wallet_balance' 	=> $value->wallet_balance,
               'points_balance' 	=> $value->points_balance,
               'details' 			=> $dtls
            ];
            return $nValue;
        });
        return $data;

	} 
}