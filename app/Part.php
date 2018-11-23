<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Part extends Model
{
    //   
    //protected $table 		= 'partss';
    protected $table 	 	= 'parts';
    protected $primaryKey 	= 'PRODUCT_ID';
    public $timestamps 		= false;


    
    //
    public function activePromo(){
    	$now = Carbon::now();
    	return $this->hasOne('App\ProductPromotion','part_id')
    				->where('start_at','<=',$now)
    				->where('end_at', '>=', $now);
    }
 
}
