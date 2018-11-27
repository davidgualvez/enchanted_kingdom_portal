<?php 
namespace App\Transformers;  

class RewardTransformer {

	public function list($data){
		//dd($data);
		$data->getCollection()->transform(function ($value) { 
            $nValue = [
               'id'     		=> $value->id,
               'branch_id'      => $value->branch_id,
               'name'           => $value->reward_name,
               'description'    => $value->reward_description,
               'points'         => $value->required_points,
               'image'          => $value->img_url,
               'category'       => $value->category
            ];
            return $nValue;
        });
        return $data;
	}
	
}