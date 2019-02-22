<?php 

namespace App\AppServices;

use Log; 
use App\ProductStock;

class ProductStockServices
{

    protected $model;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = new ProductStock;
    }

    public function getAll(){
        return $this->model->all();
    }

    public function getStock($branch_id, $product_id){
        $result = $this->model
            ->where('branch_id', $branch_id)
            ->where('Productid', $product_id)
            ->first();

        if( is_null($result) ){
            return false;
        } 
    }

}