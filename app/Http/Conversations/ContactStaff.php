<?php

namespace App\Http\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;

class ContactStaff extends Conversation
{
    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public $name;
    protected $email;

	protected function validateEmail($email){
	    if (!preg_match("/^[^@]{1,64}@[^@]{1,255}$/", $email)) {
	        return false;
	    }
	    $email_array = explode("@", $email);
	    $local_array = explode(".", $email_array[0]);
	    for ($i = 0; $i < sizeof($local_array); $i++) {
	        if (!preg_match("/^(([A-Za-z0-9!#$%&'*+\/=?^_`{|}~-][A-Za-z0-9!#$%&'*+\/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/", $local_array[$i])) {
	            return false;
	        }
	    }
	    if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) { 
	        $domain_array = explode(".", $email_array[1]);
	        if (sizeof($domain_array) < 2) {
	            return false;
	        }
	        for ($i = 0; $i < sizeof($domain_array); $i++) {
	            if (!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/", $domain_array[$i])) {
	                return false;
	            }
	        }
	    }

	    return true;
	    
	}

	protected function sendMail(){
    	$to = 'tiagokayaya21@gmail.com';
    	$from ="drvictor.costa@gmail.com";
    	$subject = 'sujeito';
    	$msg ='message';
    	
   		$mail = mail($to, $subject, $msg, 'From:' . $from);

	   	if($mail){
	   		$this->say('Email sent sucessfully!');
	   	}
	    else{
	    	$this->say('Email not sent!');
	    }
    }	

	protected function askEmail(){
    	$this->ask('What is your email?', function($answer) {
    		$value1 = $answer->getText();

    		$check = $this->validateEmail($value1);

    		if(!$check){
    			return $this->repeat('This does not look like a real email. Please provide your email.');
    		}

    		$this->email = $value1;
        	$this->say('Your email is: '.$this->email);

        	$this->sendMail();
        });
    }

    protected function askName(){
    	$this->ask('Please provide your name.', function($answer) {
    		//check if name is correct
    		$value = $answer->getText();
    		if((trim($value) === '') || (strlen($value) < 2)){
    			return $this->repeat('This does not look like a real name. Please provide your name.');
    		}
    		$this->name = $value;
	        $this->say('Nice to meet you, '.$this->name);
	        //Call the fucntion to ask for user's name
	        $this->askEmail();
        
        });
    }

    

    
    public function run()
    {
        //call Ask user's name function
        $this->askName();
    }



}
