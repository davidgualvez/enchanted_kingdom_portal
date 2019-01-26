<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Cart;
use App\CartComponent;
use App\Transformers\SitePartTransformer;
use App\SitePart;

class CartComponentController extends Controller
{
    //
    public function show($id,$cc_id){
        
        $cc = CartComponent::findByIdAndCartId($cc_id, $id); 
          
        if(is_null($cc)){
            abort(404);
        }

        $user = Auth::user();  
        if($cc->cart->user->id != $user->id){
            abort(404);
        }

        $spt = new SitePartTransformer;  
        $cc_detail = (object)$spt->singleProduct($cc->product);

        // if same base product id in product id set the price to zero
        if($cc->base_product_id == $cc->product_id){
            $cc_detail->srp = 0;
        }else{
            /**
             * if not the same base product id
             * get the difference price if the new product price is higher
             */ 
            if($cc->product->srp > $cc->baseProduct->srp  ){ 
                $cc_detail->srp = ($cc->product->srp - $cc->baseProduct->srp);
            } 
        }

        /**
         * get products with same category except this component id
         * @opwsc Other Products with same category
         */
        $opwsc = SitePart::getbyCategory($cc->product->category_id)
            ->where('product_id', '!=', $cc->product->sitepart_id)
            ->get(); 
        $opwsc = $spt->products($opwsc);

        // $opwsc->transform(function($v) use (){

        //     return (object)[
        //         'id'            => $v->id,
        //         'branch_id'     => $v->branch_id,
        //         'name'          => $v->name,
        //         'description'   => $v->description,
        //         'category_id'   => $v->category_id,
        //         'image'         => $v->image,
        //         'srp'           => 
        //     ];
        // });

        dd($opwsc);
        // dd( $cc_id, $id,
        //     $cc->product->sitepart_id,
        //     (object)$spt->singleProduct($cc->product),
        //     $opwsc);
        return view(
            'pages.customers.cart_component',
            compact('cc_detail', 'opwsc')
        );

    }
}
