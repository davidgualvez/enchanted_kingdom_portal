<?php 

namespace App\Transformers;

class GroupTransformer {
	public function groups($data){
		$data->transform(function ($value) {  
			return [
				'group_code' 			=> $value->group_code,
				'bs_unit_code'			=> $value->bs_unit_code,
				'description'			=> $value->description,
				'master_code'			=> $value->master_code
			];
		});
		return $data;
	}
}