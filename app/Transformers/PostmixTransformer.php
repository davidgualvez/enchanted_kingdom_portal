<?php 

namespace App\Transformers;

use Carbon\Carbon;

//models

class PostmixTransformer
{ 
    public function postMixModifiable($data)
    {
        $data->transform(function ($value) {  
            return [
                'parent_id'
            ];
        });

        return $data;
    }
}