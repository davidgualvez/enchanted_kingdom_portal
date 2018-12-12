<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence; // base trait
use Sofa\Eloquence\Mappable; // extension trait

class Message extends Model
{
    //
    protected $table 		= 'Message';
    protected $primaryKey 	= 'ID';
    public $timestamps 		= false;

    //model mapping
    protected $maps = [
      	// implicit relation mapping:
      	//'profile' => ['first_name', 'last_name'],

     	// explicit relation mapping:
      	//'picture' => 'profile.picture_path',

      	// simple alias
    	'message' 		=> 	'Message',
    	'number' 		=> 	'Number',
    	'status' 		=> 	'Status'
    ];
}
