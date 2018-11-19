<?php 

namespace App\AppServices;
use App\GlobalSetting;
use App\EarnPointTransaction;

class EarnPointTransactionServices {

	private $computed_point;
	private $purchase_id;
	private $net_amount;


	public function earnPoints($purchase_id, $net_amount){
		$this->computed_point 	= $net_amount / $this->getAmountConversion();
		$this->purchase_id 		= $purchase_id;
		$this->net_amount 		= $net_amount; 
	} 

	private function getAmountConversion(){
		$gs = GlobalSetting::first();
		$conversion = $gs->amount_to_point_conversion;
		if(is_null($conversion)){
			$conversion = 0;
		}
		return $conversion;
	}

	public function save(){
		$ept = new EarnPointTransaction;
		$ept->purchase_id 	= $this->purchase_id;
		$ept->net_amount 	= $this->net_amount;
		$ept->earned_points = $this->computed_point;
		$ept->save();
		
		return $ept;
	}
	
}