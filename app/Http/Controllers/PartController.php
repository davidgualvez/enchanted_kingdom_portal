<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Part;
use Carbon\Carbon;

class PartController extends Controller
{
    //
    public function lists(Request $request){
        $now        = Carbon::now();
        $search     = $request->search; 

        $p = Part::where('branch_id',config('cpp.branch_id'))
                ->where('name','LIKE','%'.$search.'%')         
                ->simplePaginate(12);
 
        return response()->json([ 
            'success'   => true,
            'status'    => 200,
            'data'      => $p
        ]); 
    }
}
