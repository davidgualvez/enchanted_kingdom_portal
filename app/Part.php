<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Part extends Model
{
    //    
    protected $table 	 	= 'SiteParts'; 
    public $timestamps 		= false;

    //custom variable used in table 
    public $product_id      = 'PRODUCT_ID';
    public $branch_id       = 'ARNOC';
    public $name            = 'SHORTCODE';
    public $description     = 'DESCRIPTION';
    public $srp             = 'RETAIL';
    public $category_id     = 'CATEGORY';
    public $group_id        = 'GROUP';
    public $image           = 'IMAGE';

    
    //logic
    public function groupp(){
        return $this->belongsTo('App\Group',$this->group_id)->select('GROUPCODE','DESCRIPTION');
    }

    public function activePromo(){
    	$now = Carbon::now();
    	return $this->hasOne('App\ProductPromotion', 'part_id')
    				->where('start_at','<=',$now)
    				->where('end_at', '>=', $now);
    }

    public static function findByIdAndBranch($product_id){
        return static::where('ARNOC',       config('cpp.branch_id'))
                    ->where('PRODUCT_ID',   $product_id)
                    ->first();
    }
 
}
