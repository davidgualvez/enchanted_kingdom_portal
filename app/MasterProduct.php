<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence; // base trait
use Sofa\Eloquence\Mappable; // extension trait

class MasterProduct extends Model
{
    //
    use Eloquence, Mappable;
    //
    protected $table 		= 'Parts';
    protected $primaryKey = 'PRODUCT_ID';
    public $timestamps 		= false;

    //model mapping
    protected $maps = [
        'product_id'    => 'PRODUCT_ID',
        'is_ticket'     => 'PREPARTNO'
    ];
}
