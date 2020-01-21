<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PartLocation extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request); 
        return [
            'id'              => $this->PRODUCT_ID,
            'branch_id'       => $this->BRANCHID,
            'name'            => $this->SHORTCODE,
            'description'     => $this->DESCRIPTION ,
            'srp'             => $this->RETAIL,
            'category_id'     => $this->CATEGORY, 
            'group_id'        => $this->GROUP,
//               'group_name'      => $value->group->description,
            'image'           => '/img/default.JPG',
            //'promo'      => $value->activePromo 
        ];
    }
}
