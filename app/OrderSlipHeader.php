<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence; // base trait
use Sofa\Eloquence\Mappable; // extension trait
	
class OrderSlipHeader extends Model
{
	use Eloquence, Mappable;
    //
    protected $table 		= 'OrderSlipHeader';
    public $timestamps 		= false;
 
    //model mapping
    protected $maps = [  
      // simple alias
	    'orderslip_header_id'	=> 'ORDERSLIPNO',
		'branch_id' 			=> 'BRANCHID', 
		'transaction_type_id'	=> 'TRANSACTTYPEID',
		'total_amount'			=> 'TOTALAMOUNT',
		'discount_amount'		=> 'DISCOUNT',
		'net_amount' 			=> 'NETAMOUNT',
		'status' 				=> 'STATUS',
		'customer_id' 			=> 'CUSTOMERCODE',
		'mobile_number' 		=> 'CELLULARNUMBER',
		'customer_name' 		=> 'CUSTOMERNAME',
		'created_at' 			=> 'OSDATE'
    ];
    protected $getterMutators = [

    ];

    //logic
    public function getMaps(){
    	return $this->maps;
    }

    //relationship
    // public function details(){
    // 	return $this->hasMany('App\OrderSlipDetail', $this->maps['orderslip_header_id']);
    // }
}
