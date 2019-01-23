<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence; // base trait
use Sofa\Eloquence\Mappable; // extension trait
use Sofa\Eloquence\Mutable; // extension trait

class TurnSite extends Model
{
    use Eloquence, Mappable, Mutable;
    //
    protected $table = 'TurnSite';
    public $timestamps = false;

    //model mapping 
    protected $maps = [
        'station_code'      => 'STATIONCODE',
        'turn_over_id'      => 'TURNOVERID',    //sequencial per station code
        'clarion_date_now'  => 'DATE',          // clarion date
        'created_at'        => 'SQLDATE',       //
        'shift'             => 'SHIFT',
        'employee_number'   => 'EMPNO',
        'pos_number'        => 'POSNUMBER',
        'type_number'       => 'TYPENO',
        'total_sales'       => 'TOTALSALE',     // net amount of sales
        'po'                => 'PO',            // used points
        'prepay'            => 'PREPAY'         // used wallet
    ];

    protected $getterMutators = [ 

    ];
}
