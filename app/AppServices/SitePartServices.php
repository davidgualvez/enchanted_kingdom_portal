<?php 

namespace App\AppServices; 
use App\Part;
use App\SitePart;
use App\Transformers\SitePartTransformer;

class SitePartServices { 

	public function getPartPerBranch($search='', $categories=array(), $limit=10){   

                $part = new SitePart; 
		$p = null;
		if( is_null($categories[0]) || $categories[0] == '' ){ 
                        $p = $part->where('branch_id',config('cpp.branch_id'))
                                //->where('pre_part_no', 0)
                                //->where('is_unli',0)
                                ->where('srp','>',0)
                                ->where('product_name','LIKE','%'.$search.'%')
                                ->orderBy('sitepart_id','desc')
                                ->simplePaginate($limit); 
                }else{
                        $p = $part->where('branch_id',config('cpp.branch_id'))
                                //->where('pre_part_no', 0)
                                //->where('is_unli', 0)
                                ->where('srp', '>', 0)
                                ->where('product_name','LIKE','%'.$search.'%')
                                ->whereIn('group_id', $categories)
                                ->orderBy('sitepart_id','desc')
                                ->simplePaginate($limit);
                }
        
                $spt = new SitePartTransformer; 
        
                return $spt->list($p);
	}
}