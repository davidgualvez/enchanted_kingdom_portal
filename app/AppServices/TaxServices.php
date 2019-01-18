<?php 

namespace App\AppServices;

use Log; 

class TaxServices{

   
    private $vat_sales=0;
    private $vat_exempt_sales = 0;
    private $vat_zerorated_sales = 0;
    private $vat_amount = 0;
    private $admission_fee = 0;
    private $amusement_tax = 0;
    private $amusement_tax_amount = 0;
    private $is_zero_rated = 0;
    private $special_discount = [];
    private $discount = 0;

    private $items = [];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct($special_discount,$zero_rated,$amusement_tax)
    {
        $this->special_discount = $special_discount; // array( isTrue, name)
        $this->is_zero_rated    = $zero_rated;
        $this->amusement_tax    = $amusement_tax;
    }

    public function getIsScPwd(){
        if( $this->is_special_discount == false ){
            return false;
        }
        return true;
    }
    /**
     * Get item and append to $item array
     * 
     * @param item[ref_id,price,qty,is_vat,admission_fee,amusement_tax,is_vat_exc]
     */
    public function getItem($item){
        $itemPrice          = $item['price'];
        $itemQty            = $item['qty'];
        $itemTotalAmount    = $itemPrice*$itemQty;
        $admission_fee      = $item['admission_fee'] * $itemQty;
 
        $_admission_fee         = 0;
        $_amusement_tax_amount  = 0;
        $_vat_sales             = 0;
        $_vat_amount            = 0;
        // admission
        if ($item['is_admission'] == 1) {
            //$this->admissionFeeComputation($item['qty'], $item['admission_fee']);
            $_admission_fee = ($admission_fee) / 1.1;
            $_amusement_tax_amount = $_admission_fee * .1;

            $itemTotalAmount -= $admission_fee;
        }


        // identify if items is Vatable or nonVatable
        if($item['is_vat']==1 && $this->is_zero_rated == 0){
            $_vat_sales     = $itemTotalAmount / 1.12;
            $_vat_amount    = $_vat_sales * .12;
        }


        // identify the customer for special discounting
        // 
        
        array_push($this->items, [ 
                'item' => $item,
                'admission_fee' => $_admission_fee,
                'amusement_tax_amount' => $_amusement_tax_amount,
                'vat_sales' => $_vat_sales,
                'vat_amount'=>$_vat_amount
            ]
        );
    }

 
    public function result(){ 
        return [
            'items'                 => $this->items,
            'vat_sales'             => $this->vat_sales,
            'vat_exempsales'        => $this->vat_exempt_sales,
            'vat_zerorated_sales'   => $this->vat_zerorated_sales,
            'vat_amount'            => $this->vat_amount,
            'admission_fee'         => $this->admission_fee,
            'amusement_tax'         => $this->amusement_tax,
            'amusement_tax_amount'  => $this->amusement_tax_amount,
            'is_zero_rated'         => $this->is_zero_rated,
            'special_discount'      => $this->special_discount,
            'discounts'             => $this->discount,
            'total_payable'         => 0,
            'cash'                  => 0,
            'change'                => 0,
        ];
    }




}