<?php 

namespace App\Transformers;

use App\Part;
use App\ProductPromotion;
use App\Promotion;

class CartTransformer {

	public function myCart($data){
		$this->returns([
			'data' 	=> $data
		]);
		
		$gross = 0;
        $newList = [];
        foreach ($data as $key => $value) {
            # code... 
            array_push($newList, [
                'part_id'   => $value->part->id,
                'promo'     => $value->part->activePromo
            ]);

            $gross += $value->qty * $value->part->srp;
        }
	}

	function returns($array){
		return response()->json($array);
	}
}