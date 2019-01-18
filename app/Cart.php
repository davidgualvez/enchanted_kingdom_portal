<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Part;

class Cart extends Model
{

    /**
     * Relationshio
     */
    public function product(){
        return $this->belongsTo('App\SitePart','product_id');
    }
    
    /*----------  logic  ----------*/
    public static function findByUserAndProduct($user_id,$product_id){
    	return  static::where('branch_id', config('cpp.branch_id') )
                    ->where('user_id',$user_id) 
    				->where('product_id',$product_id) 
                     ->where('type', 'wallet')
                    ->first();
    }

    public static function findByUserAndProductPoints($user_id,$product_id){
        return  static::where('branch_id', config('cpp.branch_id') )
                    ->where('user_id',$user_id) 
                    ->where('product_id',$product_id)
                    ->where('type', 'points')
                    ->first();
    }

    public static function findByUserAndType($user_id,$type=''){
    	// return  static::with('part','part.activePromo','part.activePromo.promotion')
     //                ->where('branch_id', config('cpp.branch_id'))
     //                ->where('user_id',$user_id)  
     //                ->get();
        return  static::where('branch_id', config('cpp.branch_id'))
                    ->where('user_id',$user_id)  
                     ->where('type', $type)
                    ->get();
    }

    public static function removeCartByUserIDAndType($user_id,$type=''){
    	return static::where('branch_id', config('cpp.branch_id'))
                    ->where('user_id', $user_id)
                    ->where('type', $type)
                    ->delete();
    }
}
