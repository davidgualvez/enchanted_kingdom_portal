<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Part;

class Cart extends Model
{
    //
    /*----------  relationship  ----------*/ 
    // public function part(){
    //     $part = new Part;
    //     return $part->where($part->branch_id, config('cpp.branch_id'))
    //             ->where($part->product_id, "{$this->product_id}")
    //             ->first();

    //     //return $this->belongsTo('App\Part','product_id');
    // }
    
    /*----------  logic  ----------*/
    public static function findByUserAndProduct($user_id,$product_id){
    	return  static::where('branch_id', config('cpp.branch_id') )
                    ->where('user_id',$user_id) 
    				->where('product_id',$product_id) 
                    ->first();
    }

    public static function findByUser($user_id){
    	// return  static::with('part','part.activePromo','part.activePromo.promotion')
     //                ->where('branch_id', config('cpp.branch_id'))
     //                ->where('user_id',$user_id)  
     //                ->get();
        return  static::where('branch_id', config('cpp.branch_id'))
                    ->where('user_id',$user_id)  
                    ->get();
    }

    public static function removeCartByUserID($user_id){
    	return static::where('branch_id', config('cpp.branch_id'))
                    ->where('user_id', $user_id)->delete();
    }
}
