<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Part extends Model
{
    //  

    //
    public function activePromos(){
    	$now = Carbon::now();
    	return $this->hasMany('App\ProductPromotion','part_id')
    				->where('start_at','<=',$now)
    				->where('end_at', '>=', $now);
    }

    public function activePromo(){
    	return $this->activePromos();
    }
}
