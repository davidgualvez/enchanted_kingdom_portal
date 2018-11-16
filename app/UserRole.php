<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    //
    /**
     * Relationship
     *
     *  
     */
    public function role(){
        return $this->belongsTo('App\Role','role_id');
    }
}
