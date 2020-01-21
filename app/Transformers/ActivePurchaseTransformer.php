<?php 

namespace App\Transformers;
use App\Part;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class ActivePurchaseTransformer {

	public function activePurchase($data){
		$newValue = [];
		foreach ($data as $key => $value) {
			# code...

			$p = Part::where('ARNOC', config('cpp.branch_id'))
					->where('PRODUCT_ID', $value->sitepart_id)
					->first();

			$d = Carbon::parse($value->valid_at);
			 
			// $url = Storage::url($p->IMAGE);
			if($p->IMAGE == '' || $p->IMAGE == null){
				$url = '/img/default.JPG';
			 }else{
				$url = Storage::url($value->IMAGE);
			 }

			array_push($newValue, [
				'purchase_detail_id' 	=> $value->sales_order_detail_id,
				'product_id' 			=> $value->sitepart_id,
				'product_name' 			=> $p->SHORTCODE,
				'group_name' 			=> $p->groupp->DESCRIPTION,
				'image' 				=> $url,
				'valid_until'	 		=> $d->toFormattedDateString() ,
				'remaining_qty' 		=> $value->qty_remaining,
				'is_unli'				=> $value->is_unli
			]);
		}

		return $newValue;
	}

}