<?php 

namespace App\Transformers;

use App\Part;
use App\ProductPromotion;
use App\Promotion;

use App\Reward;

class CartTransformer {

	public function myCart($data){
		$total_gross = 0;
        $total_discount = 0;
        $total_net = 0;
        $cartList = [];

        foreach ($data as $key => $value) {
            # code... 
            $partt = new Part;
            $part = $partt->where($partt->branch_id, config('cpp.branch_id'))
            			->where($partt->product_id, $value->product_id)
            			->first();
            
            //logic
            $product_id     = $part->PRODUCT_ID;
            $name           = $part->SHORTCODE;
            $description    = null;
            $qty            = $value->qty;
            $srp            = $part->RETAIL;
            $discount_type  = null;
            $discount_value = null;
            $discount_amount= null;
            $selling_price  = $qty * $srp;
            $buying_price   = null; 

            if(is_null($part->activePromo)){
                $discount_amount = 0; 
                //getting description by part
                $description = $part->DESCRIPTION;
            }else{
                //getting description by promotion
                $description = $part->activePromo->description;

                if($part->activePromo->promotion->is_percent == 0){
                    // actual amount to be deduct
                    $discount_type  = 0; //real
                    $discount_value = $part->activePromo->promotion->amount;

                    $discount_amount= $discount_value;
                }else if($part->activePromo->promotion->is_percent == 1){
                    // percent amount to be deduct
                    $discount_type  = 1; //percent
                    $discount_value = $part->activePromo->promotion->amount;

                    $discount_amount= ($discount_value / 100) * $selling_price;
                }
            }
            //get the buying price
            $buying_price   = ($selling_price) - $discount_amount;

            //totals
            array_push($cartList, [
                'cart_id'           => $value->id,
                'part_id'           => $product_id,
                'name'              => $name,
                'description'       => $description,
                'qty'               => $qty,
                'srp'               => $srp,
                'selling_price'     => $selling_price,
                'discount_type'     => $discount_type,
                'discount_value'    => $discount_value,
                'discount_amount'   => $discount_amount, 
                'buying_price'      => $buying_price
            ]);

            $total_gross += $selling_price;
            $total_discount += $discount_amount;
            $total_net += $buying_price;
        }

        return [
        	'total_gross' 		=> $total_gross,
	        'total_discount' 	=> $total_discount,
	        'total_net' 		=> $total_net,
	        'cartList' 			=> $cartList
        ];
	}

    public function myCartReward($data){
        $total_points = 0;
        $cartList = [];
        foreach ($data as $key => $value) {
            # code... 
            $rewardd    = new Reward;
            $reward     = $rewardd->where('branch_id', config('cpp.branch_id'))
                        ->where('id', $value->product_id)
                        ->first();
            
            //logic 
            $subTotalPoints     = $value->qty * $reward->required_points;

            //get the buying price


            //totals
            array_push($cartList, [
                'id'            => $value->id,
                'name'          => $reward->name,
                'qty'           => $value->qty,
                'points'        => $reward->required_points,
                'total_points'  => $subTotalPoints
            ]);

            $total_points += $subTotalPoints; 
        }

        return [
            'total_points'      => $total_points, 
            'cartList'          => $cartList
        ];
    }

	function returns($array){
		return response()->json($array);
	}
}