<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    // 
    //id
    //[ BRANCHID , ORDERSLIPNO ] 
    protected $table 			= 'OrderSlipDetails';
    public $timestamps 			= false;

    //custom variable used in table 
    public $id 					= 'ORDERSLIPDETAILID';
    public $purchase_id 		= 'ORDERSLIPNO';
    public $part_id 			= 'PRODUCT_ID';
    public $product_promotion_id= 'product_promotion_id';
    public $description 		= 'description';
    public $qty 				= 'QUANTITY';
    public $srp 				= 'RETAILPRICE';
    public $selling_price 		= 'AMOUNT';
    public $discount_type 		= 'ISPERCENT';
    public $discount_value 		= 'DISCOUNT';
    public $discount_rate 		= 'DISCRATE';
    public $buying_price 		= 'NETAMOUNT';
    public $status 				= 'STATUS';
    public $valid_until 		= 'valid_until';
}
