<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence; // base trait
use Sofa\Eloquence\Mappable; // extension trait
use Sofa\Eloquence\Mutable; // extension trait

class PartLocation extends Model
{
    //
    use Eloquence, Mappable, Mutable;
    //
    protected $table 		= 'PartsLocation'; 
    public $timestamps 		= false;

    //model mapping 
    protected $maps = [ 

    ];

    protected $getterMutators = [
        
    ];
}
