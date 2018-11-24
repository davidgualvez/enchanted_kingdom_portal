<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    //
    //id
    //[ BRANCHID , ORDERSLIPNO ]
    protected $table 			= 'OrderSlipHeader';
    public $timestamps 			= false;

    //custom variable used in table 
    public $branch_id 			= 'BRANCHID';
    public $id 					= 'ORDERSLIPNO';
    public $customer_id			= 'CUSTOMER_ID';
    public $gross_amount 		= 'TOTALAMOUNT';
    public $total_discount 		= 'DISCOUNT';
    public $net_amount 			= 'NETAMOUNT';
    public $type 				= 'TRANSACTTYPEID';
    public $type_details 		= 'TRANSACTTYPEDETAILSID'; 

    
}
