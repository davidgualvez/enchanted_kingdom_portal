<?php 
namespace App\Transformers;  

class SitePartTransformer {

	public function list($data){
		//dd($data);
		$data->getCollection()->transform(function ($value) { 
            $nValue = [
               'id'     		=> $value->PRODUCT_ID,
               'branch_id'      => $value->ARNOC,
               'name'           => $value->SHORTCODE,
               'description'    => $value->DESCRIPTION,
               'srp'            => $value->RETAIL,
               'category_id'    => $value->CATEGORY, 
               'group_id' 		=> $value->groupp->GROUPCODE,
               'group_name'		=> ucfirst( strtolower($value->groupp->DESCRIPTION) ),
               'image'          => $value->IMAGE,
               'promo' 			=> $value->activePromo
            ];
            return $nValue;
        });
        return $data;
	}
	
}