<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Banner;
use App\SitePart;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    //
    public function showDetails($id){
    	$b = Banner::find($id);
    	$bt = $b->details;
  
    	$bt->transform(function($val){
    		$sp = SitePart::findByIdAndBranch($val->product_id);

    		$url = Storage::url($sp->img_url);
			$url = config('app.url').$url;

    		return [
    			'product_id' 	=> $val->product_id,
    			'name' 			=> $sp->product_name,
    			'details' 		=> $sp->product_description,
    			'srp' 			=> $sp->srp,
    			'image' 		=> $url
    		];
    	});

		dd($bt);

    	$banner_img = config('app.url').Storage::url($b->image); 
		return view('pages.customers.today_special', compact('b','bt','banner_img') ); 
    }
}
