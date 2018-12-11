<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    //
    protected $table 		= 'banners';
   	public $timestamps 		= false;

   	public function details(){
   		return $this->hasMany('App\BannerDetail','banner_id');
   	}
}
