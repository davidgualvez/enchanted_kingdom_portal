<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    //
    //id
    //[ BRANCHID , ORDERSLIPNO ]
    protected $table 			= 'sales_order_header';
    protected $primaryKey       = 'sales_order_id';
    //public $timestamps 			= false; 
}
