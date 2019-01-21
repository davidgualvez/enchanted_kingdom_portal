<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence; // base trait
use Sofa\Eloquence\Mappable; // extension trait
use Sofa\Eloquence\Mutable; // extension trait

class Group extends Model
{
    use Eloquence, Mappable, Mutable;
    //
    protected $table 	 	= 'groups'; 
    protected $primaryKey 	= 'GROUPCODE';
    public $timestamps 		= false; 

    //model mapping
    protected $maps = [ 
        // simple alias
        'group_code'        =>  'GROUPCODE',
        'bs_unit_code'      =>  'BSUNITCODE',
        'master_code'       =>  'MASTERCODE',
        'description'       =>  'DESCRIPTION',
        'metro_markup'      =>  'METROMARKUP',
        'prov_markup'       =>  'PROVMARKUP'
    ];


    /**
     * Attributes getter mutators @ Eloquence\Mutable
     *
     * @var array
     */
    protected $getterMutators = [ 
        'bs_unit_code'      =>  'trim', 
        'description'       =>  'trim|strtoupper', 
    ];

    /**
     * Attributes setter mutators @ Eloquence\Mutable
     *
     * @var array
     */
    protected $setterMutators = [ 
        'bs_unit_code'      =>  'trim', 
        'description'       =>  'trim', 
    ];


}
