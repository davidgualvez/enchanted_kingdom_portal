<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    // 
    //id
    //[ BRANCHID , ORDERSLIPNO ] 
    protected $table 			= 'sales_order_details';
    protected $primaryKey       = 'sales_order_detail_id';
    //public $timestamps 			= false;
  
}
