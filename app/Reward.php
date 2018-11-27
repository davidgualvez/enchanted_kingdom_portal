<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    //
    protected $table 		= 'rewards'; 

    //custom variable used in table 
    // public $id      			= 'id';
    // public $branch_id       	= 'branch_id';
    // public $reward_name     	= 'reward_name';
    // public $reward_description  = 'reward_description';


    //relationship
    public function category(){
    	return $this->belongsTo('App\RewardCategory','reward_category_id');
    }
}
