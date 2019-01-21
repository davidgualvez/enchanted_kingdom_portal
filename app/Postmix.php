<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Sofa\Eloquence\Eloquence; // base trait
use Sofa\Eloquence\Mappable; // extension trait
use Sofa\Eloquence\Mutable; // extension trait

class Postmix extends Model
{
    use Eloquence, Mappable, Mutable;
    // 
    protected $table 		= 'Postmix';
    public $timestamps 		= false;

    //model mapping 
    protected $maps = [

        'sitePart' => [
            'sitepart_id',
            'product_name',
            'srp',
            'group_id',
            'pre_part_no', // this is use to identify the admission 0|1  to exclude from the list
            'admission_fee',
            'is_food', 
            'is_unli',
        ],
        
        //'custom_column_name' => 'db_column_name'
        'parent_id'        => 'PRODUCT_ID',
        'product_id'       => 'PARTSID',
        'quantity'         => 'QUANTITY',
        'unit_cost'        => 'UNITCOST',
        'extend_cost'      => 'EXTENDCOST',
        'type'             => 'TYPE',
        'description'      => 'DESCRIPTION',
        'partno'           => 'PARTNO',
        'yield'            => 'YIELD'
    ];

    protected $getterMutators = [
        //'custom_column_name => 'string_manipulation'
        'parent_id'        => 'PRODUCT_ID',
        'product_id'       => 'PARTSID',
        'quantity'         => 'QUANTITY',
        'unit_cost'        => 'UNITCOST',
        'extend_cost'      => 'EXTENDCOST',
        'type'             => 'TYPE',
        'description'      => 'trim',
        'partno'           => 'trim',
        'yield'            => 'YIELD'
    ];

    //relationship
    public function sitePart(){
        return $this->belongsTo('App\SitePart', 'PARTSID');
    }
}
