<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User; 
use App\Customer;
use App\UserRole;
use Auth, Hash, DB;
use Validator;

use App\AppServices\BranchLastIssuedNumberServices;
use App\AppServices\SmsServices;
use App\AppServices\MailServices;
use Carbon\Carbon;

class SignupController extends Controller
{
    //
    public function signup(Request $request){
        //validation

        $data = $request->only('name','mobile_number','password','birthdate');
        $rules = [
            'name'              => 'required',
            // 'email'             => 'required',
            'mobile_number'     => 'required', 
            'password'          => 'required|min:6',
            'birthdate'         => 'required'
        ];

        $result = Validator::make($data,$rules);
        if($result->fails()){
            return back()->withInput()->withErrors($result);
        }

        //check email
        if($request->email != '' || $request->email != null){
            $c_email = User::findByEmail($request->email);
            if($c_email){
                return  back()->withInput()->with('error', 'Email already in used.');
            }
        } 

        //check mobile_number
        $mobile = str_replace('-','',$request->mobile_number);
        $c_mobile = User::findByMobile($mobile);
        if($c_mobile){
            return back()->withInput()->with('error', 'Mobile number already in used.');
        } 

        
        try{
            //begin
            DB::beginTransaction();

            $user = new User;
            $user->name             = $request->name;
            $user->email            = $request->email;
            $user->mobile_number    = $mobile;
            $user->password         = md5($request->password);
            $user->save();

            if(!$user){
                DB::rollback();
                return back()->withInput()->with('error', 'Error saving record. Please try again.');
            }

            $now = Carbon::now();
            $birthdate = Carbon::parse($request->birthdate);
            $age  = $now->year - $birthdate->year;
            if($age > 200){
                DB::rollback();
                return back()->withInput()->with('error', "Opps.. Your're not a human! Please put a valid and correct birthdate.");
            }
            //dd($now->year, $birthdate->year, $age);

            $b = new BranchLastIssuedNumberServices; 
            $customer = new Customer;
            $customer->BRANCHID     = config('cpp.branch_id');
            $customer->CUSTOMERID   = $b->getNewIdForCustomer();
            $customer->user_id      = $user->id;
            $customer->NAME         = $user->name;
            $customer->points       = 0;
            $customer->wallet       = 0;
            $customer->mobile_number= $mobile;
            $customer->birthdate    = $request->birthdate;
            $customer->is_loyalty   = 1;
            $customer->save();

            if(!$customer){
                DB::rollback();
                return back()->withInput()->with('error', 'Error saving record. Please try again.');
            }

            $role = new UserRole;
            $role->user_id = $user->id;
            $role->role_id = 2;
            $role->save();

            Auth::login($user, true); 

            //send welcome sms here 
            $sms = new SmsServices;
            $sms->sendWelcome($user->mobile_number);

            //send welcome email here
            if($request->email != null || $request->email != ''){
                $_email = new MailServices;
                $_email->welcomeMessage($request->email, $request->name);
            }

            //success 
            DB::commit();
            return redirect('/');  

        }catch (\Exception $e){
            //fail
            DB::rollback();
            return back()->withInput()->with('error', $e->getMessage());
        } 
    }

    public function create(){
        return view('pages.customers.signup');
    }
}
