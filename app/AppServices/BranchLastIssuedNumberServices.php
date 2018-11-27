<?php 

namespace App\AppServices;
use App\BranchLastIssuedNumber;

class BranchLastIssuedNumberServices {

	public $id 		= null;
	public $new_id	= null;
	private $blin; //model

	public function __construct(){
		$this->blin = new BranchLastIssuedNumber; 
	}

	public function findOrCreate(){ 
		$r = $this->blin->where('branch_id', config('cpp.branch_id') )->first();

		if( is_null($r) ){
			$this->create();
			return;
		}  

		$this->blin = $r;
	}

	public function create(){
		$this->blin->branch_id 				= config('cpp.branch_id');
		$this->blin->order_slip_header_no	= 0;
		$this->blin->order_slip_detail_no  	= 0;
		$this->blin->sales_order_header_no  = 0;
		$this->blin->sales_order_details_no = 0;
		$this->blin->customer_no 			= 0;
		$this->blin->save();  
	}

	public function getNewIdForSalesOrderHeader(){
		$this->blin->sales_order_header_no += 1;
		$this->blin->save();
		return $this->blin->sales_order_header_no;
	}

	public function getNewIdForSalesOrderDetails(){
		$this->findOrCreate();
		$this->blin->sales_order_details_no += 1;
		$this->blin->save(); 
		return $this->blin->sales_order_details_no;
	}

	public function getNewIdForCustomer(){
		$this->findOrCreate();
		$this->blin->customer_no += 1;
		$this->blin->save(); 
		return $this->blin->customer_no;
	}

	public function getNewIdForRedemptionHeader(){
		$this->findOrCreate();
		$this->blin->redemption_header_no += 1;
		$this->blin->save(); 
		return $this->blin->redemption_header_no;
	}

	public function getNewIdForRedemptionDetails(){
		$this->findOrCreate();
		$this->blin->redemption_details_no += 1;
		$this->blin->save(); 
		return $this->blin->redemption_details_no;
	}

}