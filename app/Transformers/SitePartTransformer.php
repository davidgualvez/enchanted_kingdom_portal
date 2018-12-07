<?php 
namespace App\Transformers;  
use Illuminate\Support\Facades\Storage;

class SitePartTransformer {

	public function list($data){
		//dd($data);
		$data->getCollection()->transform(function ($value) { 

            $url = Storage::url($value->IMAGE);
            return [
               'id'         => $value->PRODUCT_ID,
               'branch_id'      => $value->ARNOC,
               'name'           => $value->SHORTCODE,
               'description'    => $value->DESCRIPTION,
               'srp'            => $value->RETAIL,
               'category_id'    => $value->CATEGORY, 
               'group_id'     => $value->groupp->GROUPCODE,
               'group_name'   => ucfirst( strtolower($value->groupp->DESCRIPTION) ),
               'image'          => $url,
               //'promo'      => $value->activePromo 
            ]; 
        });
        return $data;
	}
	
}