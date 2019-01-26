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

   public function products($data)
   {
		//dd($data);
      $data->transform(function ($value) {

         $url = Storage::url($value->IMAGE);
         return (object)[
            'id'           => $value->sitepart_id,
            'branch_id'    => $value->branch_id,
            'name'         => $value->product_name,
            'description'  => $value->product_description,
            'srp'          => $value->srp,
            'category_id'  => $value->category_id,
            'group_id'     => $value->group_id,
            'group_name'   => $value->group->description,
            'image'        => $url,
               //'promo'      => $value->activePromo 
         ];
      });

      return $data;
   }
   
   public function singleProduct($product){
      $url = Storage::url($product->IMAGE);
      return [
         'id'           => $product->sitepart_id,
         'branch_id'    => $product->branch_id,
         'name'         => $product->product_name,
         'description'  => $product->product_description,
         'srp'          => $product->srp,
         'category_id'  => $product->category_id,
         'group_id'     => $product->group_id,
         'group_name'   => $product->group->description,
         'image'        => $url
      ];
   }

   public function cartComponentCategoryProducts($products,$bp){
      $products->transform(function ($v) use ($bp) {
         $srp = 0;
         if ($v->srp > $bp->srp) {
            $srp = $v->srp - $bp->srp;
         }
         
         $url = Storage::url($v->img_url);

         return (object)[
            'id' => $v->sitepart_id,
            'branch_id' => $v->branch_id,
            'name' => $v->product_name,
            'description' => $v->product_description,
            'category_id' => $v->category_id,
            'image' => $url,
            'srp' => $srp
         ];
      });

      return $products;
   }
	 
}