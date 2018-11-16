<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProductPromotion;
use Carbon\Carbon;

class ProductPromotionController extends Controller
{
    //

    public function activePromo(Request $request){
        $now = Carbon::now();

        //setting the limit
        // $limit          = $request->get('limit');
        // if($limit == null || $limit == ''){
        //     $limit = 5;
        // } 

        $ap = ProductPromotion::with('product')
                ->where('start_at','<=', $now)
                ->where('end_at','>=',$now)            
                ->simplePaginate(3); 
                
        return response()->json([ 
            'success'   => true,
            'status'    => 200,
            'data'      => $ap
        ]); 
    }
}
