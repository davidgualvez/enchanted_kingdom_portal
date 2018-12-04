<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Banner;
use Carbon\Carbon;

class BannerController extends Controller
{
    //

    public function todaySpecial(){

    	$now 		= Carbon::now(); 
    	$banners 	= Banner::where('start_at' , '<=', $now)
    				->where('end_at', '>=', $now)
    				->get(); 

    	return response()->json([
			'success' 		=> true,
			'status' 		=> 200,
			'data' 			=> $banners
		], 200);
    }
}
