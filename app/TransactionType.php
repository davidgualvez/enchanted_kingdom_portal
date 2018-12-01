<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionType extends Model
{
    //
    protected $table 		= 'TransactType';
    protected $primaryKey 	= 'TRANSACTTYPEID';

    public $timestamps 		= false;
}
