<?php 

namespace App\AppServices;
use App\GlobalSetting;
use App\EarnPointTransaction;

class EarnPointTransactionServices {

	private $computed_point;
	private $purchase_id;
	private $net_amount;
	private $customer_id;
	private $amount_excemption;

	public function earnPoints($purchase_id = null, $net_amount = null, $customer_id = null, $amount_excemption = null){
		
		$newTotalAmountWithExcemption = $net_amount - $amount_excemption;

		$this->computed_point 		= $newTotalAmountWithExcemption / $this->getAmountConversion();
		$this->purchase_id 			= $purchase_id;
		$this->net_amount 			= $net_amount;  
		$this->customer_id 			= $customer_id;
		$this->amount_excemption	= $amount_excemption;
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
		$ept->transaction_id 	= $this->purchase_id;
		$ept->net_amount 		= $this->net_amount;
		$ept->earned_points 	= $this->computed_point;
		$ept->customer_id 		= $this->customer_id;
		$ept->transaction_type_id = 1;
		$ept->save();
		
		return $ept;
	}
	
}