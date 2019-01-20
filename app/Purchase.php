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


    //ISBUFFER == 0 / 1 for unli or not unli

    /**
     * relationship
     */
    public function details(){
    	return $this->hasMany('App\PurchaseDetail','sales_order_id');
    }

    public function transType(){
    	return $this->belongsTo('App\TransactionType', 'transaction_type_id');
    }

    public function purchaseTransaction(){
        return $this->hasOne('App\PurchaseTransaction','sales_order_id')->with('details');
    }
    
}
