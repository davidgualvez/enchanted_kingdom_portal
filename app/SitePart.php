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
      //'profile' => ['first_name', 'last_name'],

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
      	'img_url' 				=> 'IMAGE'
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

}
