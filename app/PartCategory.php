<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartCategory extends Model
{
    //
    protected $table 	 	= 'Category'; 
    public $timestamps 		= false;


    //custom variable used in table
    public $group_code 		= 'GROUPCODE';
    public $master_code 	= 'MASTERCODE';
    public $category_code 	= 'CATEGORYCODE';
    public $bs_unit_code	= 'BSUNITCODE';
    public $desc 			= 'DESC';
    public $percent 		= 'PERCENT';
    
}
