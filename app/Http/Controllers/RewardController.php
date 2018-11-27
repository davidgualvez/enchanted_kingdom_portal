<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\AppServices\RewardServices;
use Carbon\Carbon;

class RewardController extends Controller
{
    // 
    public function index(Request $request){

    	$now = \Carbon\Carbon::now();

		$dash = \App\Dashboard::where('start_at' , '<=', $now)
				->where('end_at', '>=', $now)
				->get(); 

    	return view('pages.customers.rewards', compact('dash')); 
    }


    public function lists(Request $request){
    	$now        = Carbon::now();
        $search     = $request->search;
         
        $sps    = new RewardServices;
        $result = $sps->getRewardPerBranch($search);


        return response()->json([ 
            'success'   => true,
            'status'    => 200,
            'data'      => $result
        ]); 
    }

}
