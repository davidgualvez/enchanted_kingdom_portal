<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;


class ForgotPasswordController extends Controller
{
    //
    public function index(){
        return view('pages.customers.forgot-password');
    }

    public function checkMobileIfValid(Request $request){

        $user = User::findByMobile($request->mobile_number);

        if(is_null($user) || !$user){
            return response()->json([
                'success'   => false,
                'status'    => 200,
                'message'   => 'Mobile number not found!'
            ]);
        }

        //generate code
        

        //return user with 1st code
        return response()->json([
            'success'   => true,
            'status'    => 200,
            'data'      => $user
        ]);
    }
}
