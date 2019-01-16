<?php 

namespace App\AppServices;  
use Log;
use App\Message;

class SmsServices { 

	// -- Successful registration -- 
	// Welcome to Enchanted Kingdom! This number have successfully registered to our SIMS System. Your loyalty points and load wallet has been activated.
	// Your may now load up your wallet and use it for shopping in any of our stores. Enjoy shopping...

	// -- load wallet added -- 

	// As of 2018-12-07 19:27:34 :  
	// Hooray! You have received PHP1,000 load wallet on your account from the SIMS System of enchanted kingdom. Your available balance is PHP1,100.00.

	public function sendWelcome($mobile_number){

		try{

			$message = "Welcome to Enchanted Kingdom! You are now a Loyalty Member. You may now load up your wallet to purchase anything from the store. Enjoy!";

			// Log::debug('recepient: '.$mobile_number.', message: '.$message); 
			$msg = new Message;
			$msg->number 	= $this->numberFormater($mobile_number);
			$msg->message 	= $message;
			$msg->status 	= 0;
			$msg->save(); 
			return true; 
		}catch(\Exception $e){ 
			Log::debug('SMS :'.$e->getMessage() );
			return false;
		}
		
	}

	public function sendForgotPasswordCode($mobile_number,$code)
	{

		try {

			$message = 'Good day! Your forgot password code is "'.$code.'". This Code will only last for 5min.';

			// Log::debug('recepient: '.$mobile_number.', message: '.$message); 
			$msg = new Message;
			$msg->number = $this->numberFormater($mobile_number);
			$msg->message = $message;
			$msg->status = 0;
			$msg->save();
			return true;
		} catch (\Exception $e) {
			Log::debug('SMS :' . $e->getMessage());
			return false;
		}

	}

	private function numberFormater($number){
		$to_replace = '+63'; 
		// $hooked_value = substr($number ,0, 3); 
		// if($to_replace != $hooked_value){
		// 	$to_be_append = substr($number ,1, strlen($number) - 1);
		// 	return ''.$to_replace.$to_be_append; 
		// } 
		return ''.$to_replace.$number;
	}
}