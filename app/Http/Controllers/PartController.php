<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 

use App\Transformers\GroupTransformer;
use App\AppServices\SitePartServices;
use App\Group;
use Carbon\Carbon;

class PartController extends Controller 
{
    //
    public function lists(Request $request){
        $now        = Carbon::now();
        $search     = $request->search; 
        $categories = $request->categories; 
          
        $sps    = new SitePartServices;
        $result = $sps->getPartPerBranch($search, $categories);


        return response()->json([ 
            'success'   => true,
            'status'    => 200,
            'data'      => $result
        ]); 
    }

    public function groups(Request $request){


        $val = config('app.group_not_to_display'); 
        $val = explode(',',$val); 

        $arr = [
            '1','2','3'
        ];

        //dd($val, $arr);

        $groups = Group::whereNotIn('group_code', $val)
                    ->orderBy('description')
                    ->get();

        $gt = new GroupTransformer;
        $gt->groups($groups); 

        return response()->json([ 
            'success'   => true,
            'status'    => 200,
            'data'      => $groups
        ]); 
    }
}
