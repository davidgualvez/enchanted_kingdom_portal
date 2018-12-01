<?php 

namespace App\Transformers;
use App\Part;
use Carbon\Carbon;

class ActivePurchaseTransformer {

	public function activePurchase($data){
		$newValue = [];
		foreach ($data as $key => $value) {
			# code...

			$p = Part::where('ARNOC', config('cpp.branch_id'))
					->where('PRODUCT_ID', $value->sitepart_id)
					->first();

			$d = Carbon::parse($value->valid_at);
			 
			array_push($newValue, [
				'purchase_detail_id' 	=> $value->sales_order_detail_id,
				'product_name' 			=> $p->SHORTCODE,
				'group_name' 			=> $p->groupp->DESCRIPTION,
				'image' 				=> $p->IMAGE,
				'valid_until'	 		=> $d->toFormattedDateString() ,
				'remaining_qty' 		=> $value->qty_remaining
			]);
		}

		return $newValue;
	}

}