<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductPromotion extends Model
{
    //

    public function product(){ 
        return $this->belongsTo('App\Part','part_id');
    }

    public function promotion(){
    	return $this->belongsTo('App\Promotion','promotion_id');
    }
}
