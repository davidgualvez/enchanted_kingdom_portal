<?php 

namespace App\AppServices;

use Log;

class TaxServices
{

    public function items($carts, $customer, $user){

        //================================================================
        $carts->transform(function ($v) use ($customer) {
            $price = $v->product->srp * $v->qty;
            $priceWithDiscount = 0;

            $vatable_sales  = 0;
            $vat_amount     = 0;
            $r_vat_amount   = 0; // removed vat

            $vat_exempt_sales = 0;
            $sc_pwd_discount = 0;

            $zero_rated_sales = 0;
            $zero_rated_vat_amount = 0;

            $admission_fee = 0;
            $admission_tax_amount = 0;
            $r_admission_tax_amount = 0; // removed admission tax amount

            // for zero rated
            if ($customer->getType()['type'] == 'ZERO-RATED') {
                $price = $price / 1.12;
            }

            // for admission 
            $is_admission = 0;
            $priceWithoutAdmission = $price;
            if ($v->product->admission_fee > 0) {

                $is_admission = 1;

                $af = $v->product->admission_fee * $v->qty;

                $priceWithoutAdmission -= $af;
                $admission_fee = ($af) / 1.10;
                $admission_tax_amount = $admission_fee * .10;
            }

            // for tax computation with non SC/PWD 
            if ($v->product->is_vat == 1) {
                if ($customer->getType()['type'] == 'NORMAL') {
                    $newPrice = $priceWithoutAdmission;
                    $vatable_sales = $newPrice / 1.12;
                    $vat_amount = $vatable_sales * .12;
                }

                if (($customer->getType()['type'] == 'SENIOR' ||
                    $customer->getType()['type'] == 'PWD') &&
                    $v->product->is_food == 0) {
                    if ($v->product->admission_fee <= 0) {
                        $newPrice = $priceWithoutAdmission;
                        $vatable_sales = $newPrice / 1.12;
                        $vat_amount = $vatable_sales * .12;
                    }

                }

                if ($customer->getType()['type'] == 'ZERO-RATED') {
                    $newPrice = $priceWithoutAdmission;
                    $zero_rated_sales = $newPrice / 1.12;
                    $zero_rated_vat_amount = $zero_rated_sales * .12;
                }
            }

            // for PWD/SC Discount
            if (($customer->getType()['type'] == 'SENIOR' ||
                $customer->getType()['type'] == 'PWD') && ($v->product->is_food == 1 || $v->product->admission_fee > 0)) {
                $newPrice = $priceWithoutAdmission;
                $vat_exempt_sales = $newPrice / 1.12;
                $vat_amount = ($vat_exempt_sales * .12);

                //getting the discount
                $sc_pwd_discount = (($price - ($admission_tax_amount + $vat_amount)) * .20);

                $r_vat_amount = $vat_amount;
                $vat_amount = 0;
                $r_admission_tax_amount = $admission_tax_amount;
                $admission_tax_amount = 0;

            }

            // price without discount
            $price = $price - ($r_vat_amount + $r_admission_tax_amount);

            // price with discount
            $priceWithDiscount = $price - $sc_pwd_discount;

            return [
                'cart_id' => $v->id,
                'qty' => $v->qty,
                'group_id' => $v->product->group_id,
                'product_id' => $v->product->sitepart_id,
                'product_name' => $v->product->product_name,
                'cost'  => $v->product->cost,
                'price' => $price,
                'is_postmix' => $v->product->postmix,
                'is_vat' => $v->product->is_vat,
                'is_food' => $v->product->is_food,
                'is_unli' => $v->product->is_unli,
                'vatable_sales' => $vatable_sales,
                'vat_exempt_sales' => $vat_exempt_sales,
                'vat_zerorated_sales' => $zero_rated_sales,
                'zerorated_vat_amount' => $zero_rated_vat_amount,
                'vat_amount' => $vat_amount,
                'r_vat_amount' => $r_vat_amount,
                //'is_admission' => $v->product->pre_part_no,
                'is_admission' => $is_admission,
                'admission_fee' => $v->product->admission_fee,
                'admission_sales' => $admission_fee,
                'amusement_tax_amount' => $admission_tax_amount,
                'r_amusement_tax_amount' => $r_admission_tax_amount,
                'gross_amount'  => $price,
                'scpwd_discount' => $sc_pwd_discount,
                'net_amount' => $priceWithDiscount
            ];
        });
        //======================================================================
        $result = [
            'ct' => $user->customer->customer_type,
            'customer_type' => $customer->getType()['type'],
            'items' => $carts,
            'vatable_sales' => $carts->sum('vatable_sales'),
            'vat_exempt_sales' => $carts->sum('vat_exempt_sales'),
            'vat_zerorated_sales' => $carts->sum('vat_zerorated_sales'),
            'vat_amount' => $carts->sum('vat_amount'),
            'r_vat_amount' => $carts->sum('r_vat_amount'),
            'admission_sales' => $carts->sum('admission_sales'),
            'amusement_tax_amount' => $carts->sum('amusement_tax_amount'),
            'r_amusement_tax_amount' => $carts->sum('r_admission_tax_amount'),
            'gross_amount' => $carts->sum('gross_amount'),
            'scpwd_discount' => $carts->sum('scpwd_discount'),
            'net_amount' => $carts->sum('net_amount')
        ];
        return $result;
    } 
}