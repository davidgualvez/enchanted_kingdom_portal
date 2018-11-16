<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'web_users';

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','provider','provider_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Relationship
     *
     * 
     */
    public function userRoles(){
        return $this->hasMany('App\UserRole','user_id');
    }

    public function customer(){
        return $this->hasOne('App\Customer','user_id');
    }

    /**
     * Logic
     *
     * 
     */
    public static function findByEmail($val){
        return static::where('email',$val)->first();
    }

    public static function findByMobile($val){
        return static::where('mobile_number',$val)->first();
    }
}
