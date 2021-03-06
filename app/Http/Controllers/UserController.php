<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Customer; 
  
use App\UserRole;
use Auth, Hash;
use Validator;

class UserController extends Controller
{
	/**
     * Create a new controller instance.  
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
	}
	

    //
    public function info(){
    	$user =  Auth::user(); 
    	return view('pages.customers.me', compact('user'));
    } 

    public function updateInfo(Request $request){
    	//dd($request);
    	$data = $request->only('full_name','email','mobile_number','email_notification');
    	$rules = [
    	    'full_name'         => 'required',
    	    //'email'             => 'required',
    	    'mobile_number'     => 'required|min:10|max:10', 
    	    //'password'          => 'required|min:6',
    	    'email_notification'=> 'required'
    	];
    	$result = Validator::make($data,$rules);
    	if($result->fails()){
    	    return response()->json([
    	    	'success' 	=> false,
    	    	'status' 	=> 422,
    	    	'message' 	=> $result
    	    ]);
    	}

    	$user = Auth::user();

    	//scan emails
    	$ifEmailExist = User::where('email', $request->email)
    						->where('email' , '!=', $user->email)
    						->first();
    	if($ifEmailExist){
    		return response()->json([
    	    	'success' 	=> false,
    	    	'status' 	=> 422,
    	    	'message' 	=> 'Email is already taken.'
    	    ]);
    	}

    	//scan for mobile
    	$ifMobileExist = User::where('mobile_number', $request->mobile)
    						->where('mobile_number' , '!=', $user->mobile_number)
							->first();
							
    	if($ifMobileExist){
    		return response()->json([
    	    	'success' 	=> false,
    	    	'status' 	=> 422,
    	    	'message' 	=> 'Mobile is already taken.'
    	    ]);
    	} 


    	//save/update customer
        Customer::where('CUSTOMERID', $user->customer->CUSTOMERID) 
                      ->update([
                        'NAME'          => $request->full_name,
                        'mobile_number' => $request->mobile_number
                    ]);

    	// $customer 	= $user->customer;
    	// $customer->full_name 	= $request->full_name;
    	// $customer->save();

    	//save/update user
    	$user->email 			= $request->email;
    	$user->is_subscribe 	= $this->trueOrFalse($request->email_notification);
    	$user->mobile_number 	= $request->mobile_number;
        if(count($request->password) > 0){
            $user->password         = md5($request->password);
        } 
    	$user->save();

    	return response()->json([
    	    	'success' 	=> true,
    	    	'status' 	=> 200,
    	    	'message' 	=> 'Profile has been updated.'
    	]);
    }

    private function trueOrFalse($boolean){
    	//dd($boolean);
    	if($boolean == 'true' || $boolean == false){
    		return 1;
    	} 
    	return 0;
    }
}
