<?php 

namespace App\AppServices;  
use Log;
use Mail;
use Illuminate\Mail\Message; 

class MailServices { 

	public function welcomeMessage($email, $name){
		try{

			//welcome message goes here
			$message = 'Welcome to Enchanted Kingdom! You are now a Loyalty Member. You may now load up your wallet to purchase anything from the store and earn a points for every purchase. Enjoy!';
			
			//
			$email_subject = "Enchanted Kingdom | Welcome"; 
    		Mail::send('mails.welcome',[
	            //pass a variable into blade here
	            'name' 	=> $name
	        ],function($mail) use ($email_subject,$email){
	            $mail->from('test@test.com','Enchanted Kingdom');
	            $mail->to($email); 
	            $mail->subject($email_subject);
	        });
			return true;
		}catch(\Exception $e){
			return false;
		}
	}

}