<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence; // base trait
use Sofa\Eloquence\Mappable; // extension trait
use Sofa\Eloquence\Mutable; // extension trait

class KitchenOrder extends Model
{
    // use Eloquence, Mappable, Mutable;
    //
    protected $table    = 'kitchen_orders';
    public $timestamps  = false;

    //model mapping 
    // protected $maps = [
    //   // implicit relation mapping:
    //   //' group' => ['group_code', 'description'],

    //   // explicit relation mapping:
    //   //'picture' => 'profile.picture_path',

    //   // simple alias 
    //   // 'name'  => 'NAME'
    //   'branch_id'       => 'branch_id',
    //   'ko_id'           => 'ko_id',
    //   'transact_type'   => 'transact_type', // [ 1 == from pos/ambulant, 2 == web ]
    //   'header_id'       => 'header_id',
    //   'detail_id'       => 'detail_id',
    //   'part_id'         => 'part_id',
    //   'comp_id'         => 'comp_id',
    //   'location_id'     => 'location_id',
    //   'qty'             => 'qty',
    //   'used'            => 'Used',
    //   'balance'         => 'balance',
    //   'status'          => 'status',
    //   'issued_date'     => 'issued_date',
    //   'issued_time'     => 'issued_time',
    //   'created_at'      => 'created_at',
    //   'created_date'    => 'created_date',
    //   'created_time'    => 'created_time' 
    // ];

    // protected $getterMutators = [
    //     // 'mapped' => 'manipulation'
    // ];

}
