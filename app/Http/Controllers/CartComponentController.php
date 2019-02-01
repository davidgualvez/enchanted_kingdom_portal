<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth,Storage;
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
  
        $cc_others = $cc->cart->components->filter(function ($v) use ($cc) {
            if (
                $cc->base_product_id == $v->base_product_id &&
                $cc->product_id != $v->product_id
            ){
                return $v;
            }
        });
        $cc_others->transform( function($v){
            $base_component_url = Storage::url($v->product->img_url);
            return (object)[
                'id'            => $v->id,
                'description'   => $v->product->product_description,
                'price'         => $v->price,
                'qty'           => $v->qty,
                'image'         => $base_component_url
            ];
        });
        
        //dd($cc_others);

        $spt = new SitePartTransformer;

        $base_component_url = Storage::url($cc->product->img_url);
        $base_component = (object)[
            'id'            => $cc->id,
            'description'   => $cc->product->product_description,
            'price'         => $cc->price,
            'qty'           => $cc->qty,
            'image'         => $base_component_url
        ]; 

        // // if same base product id in product id set the price to zero
        // if($cc->base_product_id == $cc->product_id){
        //     $cc_detail->srp = 0;
        // }else{
        //     /**
        //      * if not the same base product id
        //      * get the difference price if the new product price is higher
        //      */ 
        //     if($cc->product->srp > $cc->baseProduct->srp  ){ 
        //         $cc_detail->srp = ($cc->product->srp - $cc->baseProduct->srp);
        //     } 
        // } 
         

        /**
         * get products with same category except this component id
         * @opwsc Other Products with same category
         */
        $opwsc = SitePart::getbyCategory($cc->baseProduct->category_id)
            ->where('product_id', '!=', $cc->product->sitepart_id)
            ->get(); 

        $bp = $cc->baseProduct;
        $opwsc = $spt->cartComponentCategoryProducts($opwsc,$bp); 

        return view(
            'pages.customers.cart_component',
            compact('base_component','cc_detail', 'opwsc','cc', 'cc_others')
        );

    }

    public function patch(Request $request, $id,$cc_id){
        $cc = CartComponent::findByIdAndCartId($cc_id, $id);

        // dd(
        //     $id, $cc_id, $request->pid, $cc
        // );

        if (is_null($cc)) {
            abort(404);
        }

        $user = Auth::user();
        if ($cc->cart->user->id != $user->id) {
            abort(404);
        }

        // setting additional cost
        $sp = SitePart::findByIdAndBranch($request->pid); // selected product id to be added
        $additional_cost = 0;
        if ($sp->srp > $cc->baseProduct->srp) {
            $additional_cost = ($sp->srp - $cc->baseProduct->srp);
        }

        if($cc->qty <= 0){
            return response()->json([
                'success' => false,
                'status' => 401,
                'message' => 'No available quantity to be added as exchange for existing modifiable food.'
            ]); 
        }


        // adding new component to cart component
        $new_cc = CartComponent::where('cart_id', $cc->cart_id)
                        ->where('base_product_id', $cc->base_product_id)
                        ->where('product_id', $request->pid)
                        ->first(); 

        if( is_null($new_cc) ){
            $new_cc = new CartComponent;
            $new_cc->cart_id            = $id;
            $new_cc->base_product_id    = $cc->base_product_id;
            $new_cc->base_qty           = $cc->base_qty;
            $new_cc->product_id         = $request->pid;
            $new_cc->qty                = 1;
            $new_cc->price              = $additional_cost;
            $new_cc->save();
        }else{
            $new_cc->qty    += 1;
            $new_cc->price  = $additional_cost;
            $new_cc->save();
        }

        $cc->qty -= 1;
        $cc->save();

        // $cc->product_id      = $request->pid;
        // $cc->price           = $additional_cost;
        // $cc->save();

        // $cc->cart->additional_cost = $cc->qty * $cc->price;
        // $cc->cart->save();

        return response()->json([
            'success'=>true,
            'status'=>200,
            'message'=>'success'
        ]); 
    }

    public function destroy(Request $request, $id, $cc_id){
        //dd($request->cc_id , $id, $cc_id);
        $cc = CartComponent::findByIdAndCartId($cc_id, $id);

        $ccc = CartComponent::find($request->cc_id);
        if( is_null($ccc) ){ 
            return response()->json([
                'success' => false,
                'status' => 401,
                'message' => 'no record'
            ]);  
        }

        //check qty 
        $cc->qty += 1;
        $cc->save();

        $ccc->qty -= 1;
        $ccc->save();

        if($ccc->qty == 0){
            $ccc->delete();
        }

        return response()->json([
            'success'   => true,
            'status'    => 200,
            'message'   => 'success'
        ]);  
    }
}
