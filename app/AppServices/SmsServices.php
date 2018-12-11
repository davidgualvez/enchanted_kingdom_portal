<?php 

namespace App\AppServices;  
use Log;
class SmsServices { 

	// -- Successful registration -- 
	// Welcome to Enchanted Kingdom! This number have successfully registered to our SIMS System. Your loyalty points and load wallet has been activated.
	// Your may now load up your wallet and use it for shopping in any of our stores. Enjoy shopping...

	// -- load wallet added -- 

	// As of 2018-12-07 19:27:34 :  
	// Hooray! You have received PHP1,000 load wallet on your account from the SIMS System of enchanted kingdom. Your available balance is PHP1,100.00.

	public function sendWelcome($mobile_number){

		try{

			$message = 'Welcome to Enchanted Kingdom! You are now a Loyalty Member. You may now load up your wallet to purchase anything from the store and earn a points for every purchase. Enjoy!';

			Log::debug('recepient: '.$mobile_number.', message: '.$message);
			return true;

		}catch(\Exception $e){
			return false;
		}
		
	}
}