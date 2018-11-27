<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 

use App\AppServices\SitePartServices;
use Carbon\Carbon;

class PartController extends Controller 
{
    //
    public function lists(Request $request){
        $now        = Carbon::now();
        $search     = $request->search;
         
        $sps    = new SitePartServices;
        $result = $sps->getPartPerBranch($search);


        return response()->json([ 
            'success'   => true,
            'status'    => 200,
            'data'      => $result
        ]); 
    }
}
