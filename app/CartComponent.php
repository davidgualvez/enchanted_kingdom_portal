<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CartComponent extends Model
{
    //
    protected $table    = 'carts_component';

    /**
     * Relationship
     */
    public function baseProduct()
    {
        return $this->belongsTo('App\SitePart', 'base_product_id')
            ->where('branch_id', config('app.branch_id'));
    }

    public function product()
    {
        return $this->belongsTo('App\SitePart', 'product_id')
            ->where('branch_id', config('app.branch_id'));
    }

    public function cart(){
        return $this->belongsTo('App\Cart','cart_id');
    }

    /**
     * Logic
     */
    public static function findByIdAndCartId($id,$cart_id){
        return static::where('id',$id)
            ->where('cart_id', $cart_id)
            ->first();
    }

}

