<?php 

namespace App\AppServices; 

use App\Reward;
use App\Transformers\RewardTransformer;

class RewardServices { 

	public function getRewardPerBranch($search='', $limit=12){ 

		$reward 	= new Reward;
        $p 		= $reward->where('branch_id',	config('cpp.branch_id'))
                ->where('reward_name',			'LIKE','%'.$search.'%')
                ->simplePaginate($limit);
        
        $spt = new RewardTransformer;
        
        return $spt->list($p); 
	}
}