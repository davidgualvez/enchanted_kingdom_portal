<?php 

namespace App\AppServices; 
use App\Part;
use App\Transformers\SitePartTransformer;

class SitePartServices { 

	public function getPartPerBranch($search='', $categories=array(), $limit=10){   

		$part = new Part; 
		$p = null;
		if( is_null($categories[0]) || $categories[0] == '' ){ 
        	$p = $part->where($part->branch_id,config('cpp.branch_id'))
                ->where($part->name,'LIKE','%'.$search.'%')
                ->orderBy('PRODUCT_ID','desc')
                ->simplePaginate($limit);
        }else{
        	$p = $part->where($part->branch_id,config('cpp.branch_id'))
                ->where($part->name,'LIKE','%'.$search.'%')
                ->whereIn('GROUP', $categories)
                ->orderBy('PRODUCT_ID','desc')
                ->simplePaginate($limit);
        }

        
        $spt = new SitePartTransformer; 
        
        return $spt->list($p);
	}
}