<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Carbon\Carbon;
use App\AppServices\SmsServices;
use App\AppServices\MailServices;
use App\PasswordReset;


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
        $now    = Carbon::now();
        $str    = $user->mobile_number.$now;
        $code   = bin2hex(mhash(MHASH_CRC32B, $str));

        //save code in the database
        $expires_at         = Carbon::now()->addMinutes(5);
        $pr = new PasswordReset;
        $pr->web_user_id    = $user->id;
        $pr->code           = $code;
        $pr->expires_at     = $expires_at;
        $pr->created_at     = $now;
        $pr->save();

        //send sms to user
        $sms = new SmsServices;
        $sms->sendForgotPasswordCode($user->mobile_number,$code);

        //send welcome email here
        if ($user->email != null || $user->email != '') {
            $_email = new MailServices;
            $_email->sendForgotPasswordCode($user->email, $code);
        }

        //return user with 1st code
        return response()->json([
            'success'   => true,
            'status'    => 200,
            'message'   => 'A Code has been sent to your mobile and email. and it will only last for 5min.'
        ]);
    }

    public function verifyCode(Request $request){

        if($request->code == '' || $request->code == null){
            return response()->json([
                'success'   => false,
                'status'    => 200,
                'message'   => 'Code is required!'
            ]);
        }

        $result = PasswordReset::findByCode($request->code);
        if (is_null($result) || !$result) {
            return response()->json([
                'success'   => false,
                'status'    => 200,
                'message'   => 'Invalid Code!'
            ]);
        }

        $now = Carbon::now();
        if($result->expires_at < $now){
            return response()->json([
                'success' => false,
                'status' => 200,
                'message' => 'Code is already expired!'
            ]);
        }
  
        return response()->json([
            'success' => true,
            'status' => 200,
            'message' => 'Success'
        ]);
    }

    public function changePassword(Request $request){
        $code           = $request->code;
        $password       = $request->password;
        $repassword     = $request->repassword;

        // if greater than 6

        // if match
        if( $password != $repassword ){
            return response()->json([
                'success'   => false,
                'status'    => 200,
                'message'   => 'Password not match!'
            ]);
        }

        // if code is valid
        $pass_reset = PasswordReset::findByCode($request->code);
        if (is_null($pass_reset) || !$pass_reset) {
            return response()->json([
                'success'   => false,
                'status'    => 200,
                'message'   => 'Invalid Code!'
            ]);
        }
        
        // if expired
        $now = Carbon::now();
        if ($pass_reset->expires_at < $now) {
            return response()->json([
                'success' => false,
                'status' => 200,
                'message' => 'Code is already expired!'
            ]);
        }

        $user = $pass_reset->webUser;
        $user->password = md5($password);
        $user->save();

        return response()->json([
            'success'   => true,
            'status'    => 200,
            'message'   => 'Password has been change successfully!'
        ]);

    }
}
