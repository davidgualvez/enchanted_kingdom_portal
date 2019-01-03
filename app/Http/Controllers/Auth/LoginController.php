<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use App\User;
use Auth,Session,Validator, Hash;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
 

    
    public function showLogin(){
        return view('pages.customers.login');
    }

    public function login(Request $request){
        //validation
        $data = $request->only('mobile_number','password');
        $rules = [ 
            'mobile_number'     => 'required', 
            'password'          => 'required|min:6',
        ];
        $result = Validator::make($data,$rules);
        if($result->fails()){
             
             return back()->withInput()->withErrors($result);
        }
        //check mobile_number
        $mobile = str_replace('-','',$request->mobile_number);

        //regex
        if( !preg_match("/^(09|\+639)\d{9}$/",$mobile) ) {
            return back()->withInput()->withErrors( array('Invalid Mobile Number! Please try again.') );
        }

        $user = User::findByMobile($mobile);
        if(!$user){
            return back()->withInput()->with('error', 'Mobile number not found!');
        } 
 
        // if (!Hash::check($request->password, $user->password)) {
        //     // The passwords match...
        //     return back()->withInput()->with('error', 'Invalid Password!');
        // }
         

        if ( md5($request->password) != $user->password) {
            // The passwords match...
            return back()->withInput()->with('error', 'Invalid Password!');
        }
        
        Auth::login($user, true);
        return redirect('/store');
    }

    public function logout(){ 
        Auth::logout();
        //return redirect('/'); 
        return redirect( config('cpp.portal_logout') );
    }
 
}
