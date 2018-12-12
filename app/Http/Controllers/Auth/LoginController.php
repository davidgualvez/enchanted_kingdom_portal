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

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->user();
        //dd($user);
        //$authenticateUser = $this->findOrCreateUSer($user, $provider);

        $authenticateUser = User::where('provider_id', $user->id)->first();
        if (!$authenticateUser) { 

            //storing data to session
            // Via the global helper...
            Session::flush();
            session::put('signup_provider_id',      $user->id); 
            session::put('signup_provider',         $provider); 
            session::put('signup_avatar',           $user->avatar); 
            session::put('signup_name',             $user->name); 
            session::put('signup_email',            $user->email); 
            return redirect('/signup');
            //return view('pages.signup',compact('user','provider'));
        }

        $result = Auth::login($authenticateUser, true);

        return redirect($this->redirectTo);
        // $user->token;
    } 

     /**
     * If a user has registered before using social auth, return the user
     * else, create a new user object. 
     * @param  $user Socialite user object
     * @param $provider Social auth provider
     * @return  User
     */
    public function findOrCreateUser($user, $provider)
    { 
        $authUser = User::where('provider_id', $user->id)->first();
        if ($authUser) {
            return $authUser;
        }
        // return User::create([
        //      'name'     => $user->name,
        //      'email'    => $user->email, 
        //      'provider' => $provider,
        //      'provider_id' => $user->id
        // ]); 
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
