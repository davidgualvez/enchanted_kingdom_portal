<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ap\User;
use App\Customer;
use Auth;

class UserController extends Controller
{
    //
    public function info(){
    	$user =  Auth::user(); 
    	return view('pages.customers.me', compact('user'));
    }

    public function updateInfo(Request $request){
    	dd($request);
    }
}
