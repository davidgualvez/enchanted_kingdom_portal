<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 

use App\Transformers\GroupTransformer;
use App\AppServices\SitePartServices;
use App\Group;
use Carbon\Carbon;

use App\PartLocation;
use App\Http\Resources\PartLocation as PartLocationResource;
use App\Http\Resources\PartLocationCollection;

class PartController extends Controller 
{
    //
    public function lists(Request $request){

        $now        = Carbon::now();
        $search     = $request->search; 
        $categories = $request->categories; 
          
        // $sps    = new SitePartServices; 
        // $result = $sps->getPartPerBranch($search, $categories); 
        // return response()->json([ 
        //     'success'   => true,
        //     'status'    => 200,
        //     'data'      => $result
        // ]);  
        
        $pl = null;
        if( is_null($categories[0]) || $categories[0] == '' ){ 
            $pl = PartLocation::where('BRANCHID',config('cpp.branch_id'))
                ->where('OUTLETID', config('cpp.outlet_id'))
                //->where('pre_part_no', 0)
                //->where('is_unli',0)
                ->where('RETAIL','>',0)
                ->where('SHORTCODE','LIKE','%'.$search.'%')
                // ->orderBy('sitepart_id','desc')
                ->simplePaginate(); 

        }else{
            $pl = PartLocation::where('BRANCHID',config('cpp.branch_id'))
                ->where('OUTLETID', config('cpp.outlet_id'))
                //->where('pre_part_no', 0)
                //->where('is_unli',0)
                ->where('RETAIL','>',0)
                ->where('SHORTCODE','LIKE','%'.$search.'%')
                ->whereIn('GROUP', $categories)
                // ->orderBy('sitepart_id','desc')
                ->simplePaginate(); 
        } 
        return new PartLocationCollection($pl);

       
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
