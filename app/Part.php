<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Part extends Model
{
    //    
    protected $table 	 	= 'SiteParts'; 
    public $timestamps 		= false;

    //custom variable
    public $product_id      = 'PRODUCT_ID';
    public $branch_id       = 'ARNOC';
    public $name            = 'SHORTCODE';
    public $description     = 'DESCRIPTION';
    public $srp             = 'RETAIL';
    public $category_id     = 'CATEGORY';
    public $group_id        = 'GROUP';

    
    //logic
    public function activePromo(){
    	$now = Carbon::now();
    	return $this->hasOne('App\ProductPromotion', $this->product_id)
    				->where('start_at','<=',$now)
    				->where('end_at', '>=', $now);
    }
 
}
