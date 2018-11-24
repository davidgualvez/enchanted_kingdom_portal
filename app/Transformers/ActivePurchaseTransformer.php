<?php 

namespace App\Transformers;
use App\Part;

class ActivePurchaseTransformer {

	public function activePurchase($data){
		$newValue = [];
		foreach ($data as $key => $value) {
			# code...

			$p = Part::where('ARNOC', config('cpp.branch_id'))
					->where('PRODUCT_ID', $value->PRODUCT_ID)
					->first();

			array_push($newValue, [
				'purchase_detail_id' 	=> $value->ORDERSLIPDETAILID,
				'product_name' 			=> $p->SHORTCODE,
				'group_name' 			=> $p->groupp->DESCRIPTION,
				'image' 				=> $p->IMAGE,
				'valid_until'	 		=> $value->valid_at,
				'remaining_qty' 		=> $value->qty_remaining
			]);
		}

		return $newValue;
	}

}