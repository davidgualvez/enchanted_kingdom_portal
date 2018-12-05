<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    //
    public function order(Request $request){
    	return response()->json([
    		'success' 		=> true, 
    		'status' 		=> 200,
    		'data' 			=> 'test'
    	]);
    }
}
