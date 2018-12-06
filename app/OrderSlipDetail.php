<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence; // base trait
use Sofa\Eloquence\Mappable; // extension trait

class OrderSlipDetail extends Model
{
	use Eloquence, Mappable;
    //
    protected $table 		= 'OrderSLipDetails';
    public $timestamps 		= false;

    //model mapping
    protected $maps = [
      	// implicit relation mapping:
      	//'profile' => ['first_name', 'last_name'],

     	// explicit relation mapping:
      	//'picture' => 'profile.picture_path',

      	// simple alias
    	'branch_id' 			=> 'BRANCHID', 
    	'orderslip_detail_id' 	=> 'ORDERSLIPDETAILID', 
     	'orderslip_header_id' 	=> 'ORDERSLIPNO', 
    	'product_id' 			=> 'PRODUCT_ID', 
    	'part_number'			=> 'PARTNO' , 
    	'product_group_id'		=> 'PRODUCTGROUP' , 
    	'qty' 					=> 'QUANTITY' , 
    	'srp' 					=> 'RETAILPRICE',
    	'amount' 				=> 'AMOUNT' ,
    ];
}
