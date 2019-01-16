<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    //
    protected $table    = 'password_resets';
    public $timestamps  = false;



    public function webUser(){
        return $this->belongsTo('App\User', 'web_user_id');
    }

    /**
     * Logic 
     *
     * 
     */
    public static function findByCode($val)
    {
        return static::where('code', $val)->first();
    }
}
