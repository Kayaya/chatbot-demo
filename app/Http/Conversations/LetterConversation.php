<?php

namespace App\Http\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class LetterConversation extends Conversation
{
    /**
     * Start the conversation.
     *
     * @return mixed
     */

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
		//Set forward email and other info
    	$this->to = 'tiagokayaya21@gmail.com';
    	$this->from = $this->name;
    	$this->subject = $this->letter;;
    	//send the email
   		$this->mail = @mail($this->to, $this->subject, $this->info, $this->from);

	   	if($this->mail){
	   		$this->say('Your name: '.$this->from. ' -- Email: '.$this->email);
	   		$this->say('Request:! '.$this->letter);
	   		$this->say('Additional info/requirements:! '.$this->info);
	   		$this->say('Your request has been sent sucessfully. Your letter will be ready in 4-5 working days!');
	   	}
	    else{
	    	$this->say('Email not sent!');
	    }
    }	

    protected function askAddInfo(){
    	//ask user a for his question
    	 $this->ask('Additional information/requirements', function($answer) {
    	 	//Get the user input
    		$value2 = $answer->getText();
    		//check if message at least 5 characters long
    		if(strlen($value2) < 4){
    			//ask user to try again
    			return $this->repeat('Your message is too short. Please try again.');
    		}
    		//Allocate the message
    		$this->info = $value2;
    		//Check if user wants to attach a file
        	$this->sendMail();
        });
    }	

	protected function askEmail(){
    	$this->ask('What is your email?', function($answer) {
    		$value1 = $answer->getText();
    		//check if email is valid
    		$check = $this->validateEmail($value1);
    		//whether the email is invalid ask user to try again
    		if(!$check){
    			return $this->repeat('This does not look like a real email. Please provide your email.');
    		}
    		//Get the correct email
    		$this->email = $value1;
    		//call ask message function
        	$this->askAddInfo();
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
	        //Call the fucntion to ask for user's name
	        $this->askEmail();
        
        });
    }

    protected function askForLetter(){
    	$question = Question::create('You can request the following letters:')
        	->addButtons([
        		Button::create('to whom it may concern letters')->value('general'),
        		Button::create('council tax exemption certificates')->value('tax'),
        		Button::create('bank letters')->value('bank'),
        		Button::create('transcript of results')->value('results')
        	]);
        $this->ask($question, function ($answer){
        	if ($answer->isInteractiveMessageReply()) {
        		if($answer->getValue() === 'general'){
        			$this->letter = 'to who it may concern letter';
        			$this->say($this->letter);
        			$this->askName();
        		}
        		else if($answer->getValue() === 'tax'){
        			$this->letter = 'council tax exemption certificate';
        			$this->say($this->letter);
        			$this->askName();
        		}
        		else if($answer->getValue() === 'bank'){
        			$this->letter = 'bank letter';
        			$this->say($this->letter);
        			$this->askName();
        		}
        		else if($answer->getValue() === 'results'){
        			$this->letter = 'transcript of results';
        			$this->say($this->letter);
        			$this->askName();
        		}
        	}
        	else{
        		return $this->repeat('Please select one of the options above.');
        	}
        });
        
    }

    public function run()
    {
        //
        $this->say('Letter!');
        $this->askForLetter();
    }
}
