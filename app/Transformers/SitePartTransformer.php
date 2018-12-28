<?php 
namespace App\Transformers;  
use Illuminate\Support\Facades\Storage;

class SitePartTransformer {

	public function list($data){
		//dd($data);
		$data->getCollection()->transform(function ($value) { 

            $url = Storage::url($value->IMAGE);
            return [
               'id'              => $value->sitepart_id,
               'branch_id'       => $value->branch_id,
               'name'            => $value->product_name,
               'description'     => $value->product_description,
               'srp'             => $value->srp,
               'category_id'     => $value->category_id, 
               'group_id'        => $value->group_id,
               'group_name'      => $value->group->description,
               'image'           => $url,
               //'promo'      => $value->activePromo 
            ]; 
        });
        return $data;
	}
	
}