<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence; // base trait
use Sofa\Eloquence\Mappable; // extension trait
use Sofa\Eloquence\Mutable; // extension trait

class SitePart extends Model
{
    use Eloquence, Mappable, Mutable;
    //
    protected $table 		= 'SiteParts';
    public $timestamps 		= false;

    //model mapping
    protected $maps = [
      // implicit relation mapping:
      'group' => ['group_code', 'description'],

      // explicit relation mapping:
      //'picture' => 'profile.picture_path',

      // simple alias
      	'sitepart_id' 			=> 'PRODUCT_ID',
      	'branch_id' 			=> 'ARNOC',
      	'product_name'			=> 'SHORTCODE',
      	'product_description' 	=> 'DESCRIPTION',
      	'part_no' 				=> 'PARTNO',
      	'srp' 					=> 'RETAIL',
      	'category_id' 			=> 'CATEGORY',
      	'group_id' 				=> 'GROUP',
        'img_url' 				=> 'IMAGE',
        'pre_part_no'           => 'PREPARTNO', // this is use to identify the admission 0|1  to exclude from the list
        'is_food'               => 'MSGROUP',
        'is_unli'               => 'SSBUFFER'
    ];
    protected $getterMutators = [
    	'product_name' 			=> 'trim',
    	'product_description' 	=> 'trim',
    ];

    //logic
    public static function findByIdAndBranch($product_id){
        return static::where('ARNOC',       config('cpp.branch_id'))
                    ->where('PRODUCT_ID',   $product_id)
                    ->first();
    }

    //relationship
    public function group(){
        return $this->belongsTo('App\Group', 'GROUP');
    }
}
