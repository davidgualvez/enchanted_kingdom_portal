<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Postmix;
use App\SitePart;
use App\Transformers\SitePartTransformer;

//use App\Transformers\PostmixTransformer;

class PostmixController extends Controller
{
    //
    public function show(Request $request){
        
        $parent_id  = $request->parent_id;
        
        $pm = Postmix::where('parent_id', $parent_id)
            ->where('modifiable', 1)
            ->get();
        
        // data transformation
        $pm->transform(function ($v) {
            return [
                'parent_id'     => $v->parent_id,
                'component_id'  => $v->product_id,
                'comp_cat_id'   => $v->comp_cat_id,
                'is_free'       => $v->is_free,
                'description'   => $v->description,
            ];
        });
        
        // if empty
        if($pm->isEmpty()){
            return response()->json([
                'success'   => false,
                'status'    => 200,
                'message'   => 'No Side Dish has been found', 
            ]);
        }

        return response()->json([
            'success'   => true,
            'status'    => 200,
            'message'   => 'Success',
            'data'  => $pm
        ]);
    }

    public function sideDishes(Request $request){

        $parent_id          = $request->parent_id;
        $component_id       = $request->component_id;
        $comp_category_id   = $request->comp_cat_id;
        
        $pms = SitePart::where('category_id', $comp_category_id)
                ->where('sitepart_id', '!=', $component_id)
                ->get();

        

        return response()->json([
            'success'   => true,
            'status'    => 200,
            'message'   => 'Success',
            'data'      => $pms
        ]);
    }
}
