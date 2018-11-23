<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    //
    protected $table 	 	= 'groups'; 
    protected $primaryKey 	= 'GROUPCODE';
    public $timestamps 		= false;

    //custom variable used in table
    public $group_code 		= 'GROUPCODE';
    public $bs_unit_code 	= 'BSUNITCODE';
    public $master_code 	= 'MASTERCODE';
    public $description 	= 'DESCRIPTION';
    public $metro_markup 	= 'METROMARKUP';
    public $prov_markup 	= 'PROVMARKUP';
}
