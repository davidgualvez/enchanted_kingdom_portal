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

	public function getNewIdForOrderSlipHeader(){
		$this->findOrCreate();
		$this->blin->order_slip_header_no += 1;
		$this->blin->save(); 
		return $this->blin->order_slip_header_no;
	}

	public function getNewIdForOrderSlipDetails(){
		$this->findOrCreate();
		$this->blin->order_slip_detail_no += 1;
		$this->blin->save(); 
		return $this->blin->order_slip_detail_no;
	}

	public function getNewIdForInvoice()
	{
		$this->findOrCreate();
		$this->blin->invoice_no += 1;
		$this->blin->save();
		return $this->blin->invoice_no;
	}

	public function getInvoice(){
		return $this->blin->invoice_no;
	}

	public function getNewIdForNoneInvoice()
	{
		$this->findOrCreate();
		$this->blin->invoice_no += 1;
		$this->blin->save();
		return $this->blin->invoice_no;
	}

	public function increaseInvoiceResetCounter()
	{
		$this->findOrCreate();
		$this->blin->invoice_reset_counter += 1;
		$this->blin->save();
		return true;
	}

	public function getNewIdForTransaction()
	{
		$this->findOrCreate();
		$this->blin->transaction_no += 1;
		$this->blin->save();
		return $this->blin->transaction_no;
	}

	public function getTransaction()
	{
		return $this->blin->transaction_no;
	}

	

}