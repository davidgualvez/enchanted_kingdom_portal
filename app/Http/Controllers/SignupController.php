<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User; 
use App\Customer;
use App\UserRole;
use Auth, Hash;
use Validator;

class SignupController extends Controller
{
    //
    public function signup(Request $request){
        //validation
        $data = $request->only('name','email','mobile_number','password');
        $rules = [
            'name'              => 'required',
            'email'             => 'required',
            'mobile_number'     => 'required', 
            'password'          => 'required|min:6'
        ];
        $result = Validator::make($data,$rules);
        if($result->fails()){
             return back()->withInput()->withErrors($result);
        }

        //check email
        $c_email = User::findByEmail($request->email);
        if($c_email){
            return  back()->withInput()->with('error', 'Email already in used.');
        }

        //check mobile_number
        $mobile = str_replace('-','',$request->mobile_number);
        $c_mobile = User::findByMobile($mobile);
        if($c_mobile){
            return back()->withInput()->with('error', 'Mobile number already in used.');
        } 

        $user = new User;
        $user->name             = $request->name;
        $user->email            = $request->email;
        $user->mobile_number    = $mobile;
        $user->password         = Hash::make($request->password);
        $user->save();

        $customer = new Customer;
        $customer->user_id = $user->id;
        $customer->full_name = $user->name;
        $customer->save();

        $role = new UserRole;
        $role->user_id = $user->id;
        $role->role_id = 2;
        $role->save();

        Auth::login($user, true);

        return redirect('/');
    }

    public function create(){
        return view('pages.customers.signup');
    }
}
