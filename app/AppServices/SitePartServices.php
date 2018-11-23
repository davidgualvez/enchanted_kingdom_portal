<?php 

namespace App\AppServices; 
use App\Part;
use App\Transformers\SitePartTransformer;

class SitePartServices {

	public function getPartPerBranch($search='', $limit=12){ 

		$part = new Part; 
        $p = $part->where($part->branch_id,config('cpp.branch_id'))
                ->where($part->name,'LIKE','%'.$search.'%')         
                ->simplePaginate($limit); 
        $spt = new SitePartTransformer;
        
        return $spt->list($p);
	}
}