<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence; // base trait
use Sofa\Eloquence\Mappable; // extension trait

class TransactionType extends Model
{
	use Eloquence, Mappable;
    //
    protected $table 		= 'TransactType';
    protected $primaryKey 	= 'TRANSACTTYPEID'; 
    public $timestamps 		= false;

    //model mapping
    protected $maps = [  
      // simple alias
	    'id'		=> 'TRANSACTTYPEID',
		  'desc' 		=> 'DESCRIPTION', 
    ];
    protected $getterMutators = [

    ];
}
