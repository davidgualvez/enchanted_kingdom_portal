<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseTransaction extends Model
{
    //
    protected $table    = 'purchase_transactions';
    public $timestamps  = false;

 
    /**
     * Relationship
     */
    public function details(){
        return $this->hasMany('App\PurchaseTransactionDetail','purchase_transaction_id');
    }
}
