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
      // implicit relation mapping:
      //'profile' => ['first_name', 'last_name'],

      // explicit relation mapping:
      //'picture' => 'profile.picture_path',

      // simple alias
    	'orderslip_header_id'	=> 'ORDERSLIPNO',
      	'branch_id' 			=> 'BRANCHID', 
      	'transaction_type_id'	=> 'TRANSACTTYPEID',
      	'total_amount'			=> 'TOTALAMOUNT',
      	'discount_amount'		=> 'DISCOUNT',
      	'net_amount' 			=> 'NETAMOUNT',
      	'status' 				=> 'STATUS',
      	'customer_id' 			=> 'CUSTOMER_ID',
      	'mobile_number' 		=> 'CELLULARNUMBER',
      	'customer_name' 		=> 'CUSTOMERNAME'
    ];
    protected $getterMutators = [

    ];
}
