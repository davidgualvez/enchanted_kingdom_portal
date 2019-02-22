<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Sofa\Eloquence\Eloquence;   // base trait
use Sofa\Eloquence\Mappable;    // extension trait
use Sofa\Eloquence\Mutable;     // extension trait

class ProductStock extends Model
{
    //
    use Eloquence, Mappable, Mutable;

    protected $table    = 'StockStatus'; 
    public $timestamps  = false;

    /**
     * MAPPING
     */
    protected $maps = [
        'branch_id'         => 'Branch',
        'product_id'        => 'Productid',
        'total_in'          => 'TotIn',
        'total_out'         => 'TotOut',
        'balance'           => 'BALANCE'
    ];
    protected $getterMutators = [ 
        
    ];

    /**
     * LOGIC
     */
    public function getStock( $branch_id, $product_id){
        return static::where('branch_id', $branch_id)
            ->where('Productid', $product_id)
            ->first();
    }
}
