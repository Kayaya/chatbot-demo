<?php

namespace App\Http\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

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
		//Set forward email and other info
    	$this->to = 'tiagokayaya21@gmail.com';
    	$this->from = $this->name;
    	$this->subject = 'Unknown question';
    	//send the email
   		$this->mail = @mail($this->to, $this->subject, $this->message, $this->from);

	   	if($this->mail){
	   		$this->say('Your name:! '.$this->from. ' Email: '.$this->email);
	   		$this->say('Your message:! '.$this->message);
	   		$this->say('Your request has been sent sucessfully. A member of staff will be in touch with you shortly!');
	   	}
	    else{
	    	$this->say('Email not sent!');
	    }
    }

    protected function askImage(){
        $this->askForImages('Please upload a picture.', function ($images){
            $this->say('I received '.count($images).' image(s).');
            //ask the function to send an email
            $this->sendMail();
        }, function(){
            $this->repeat('Hmmmm this does not look like an image to me. Please try again');
        });
    }
    
    protected function askAudio(){
        $this->askForAudio('Please upload an audio.', function ($audio){
            $this->say('I received '.count($audio).' audio(s).');
            //ask the function to send an email
            $this->sendMail();
        }, function(){
            $this->repeat('Hmmmm this does not look like an audio to me. Please try again');
        });
    }

    protected function askVideo(){
        $this->askForVideos('Please upload a video.', function ($videos){
            $this->say('I received '.count($videos).' video(s).');
            //ask the function to send an email
            $this->sendMail();
        }, function(){
            $this->repeat('Hmmmm this does not look like a video to me. Please try again');
        });
    }


    protected function getAttachment(){
        $question = Question::create('Select the type of file(s) you would like to send.')
            ->addButtons([
                Button::create('Images')->value('image'),
                Button::create('Audio')->value('audio'),
                Button::create('Video')->value('video')
            ]);
         $this->ask($question, function ($answer){
            if ($answer->isInteractiveMessageReply()) {
                if($answer->getValue() === 'image'){
                    //get the image
                    $this->askImage();
                }
                else if($answer->getValue() === 'audio'){
                    //get the audio
                    $this->askAudio();
                }
                else if($answer->getValue() === 'video'){
                    //get the video
                    $this->askVideo();                                
                }  
            }
            else{
                return $this->repeat('Please select one of the buttons above.');
            }               
        });
    }


    protected function askFile(){
        $question = Question::create('Would you like to attach a file to your message?')
            ->addButtons([
                Button::create('Yes, please')->value('yes'),
                Button::create('No, thank you')->value('no')
            ]);
        $this->ask($question, function ($answer){
            if ($answer->isInteractiveMessageReply()) {
                if($answer->getValue() === 'yes'){
                    //Check type of file
                    $this->getAttachment();
                }
                else if($answer->getValue() === 'no'){
                    $this->say($answer->getValue());
                    $this->sendMail();
                }
            }
            else{
                return $this->repeat('Please select one of the buttons above.');
            } 
        });
        
    }   

    protected function askMessage(){
    	//ask user a for his question
    	 $this->ask('What is your message?', function($answer) {
    	 	//Get the user input
    		$value2 = $answer->getText();
    		//check if message at least 5 characters long
    		if(strlen($value2) < 5){
    			//ask user to try again
    			return $this->repeat('Your message is too short. Please try again.');
    		}
    		//Allocate the message
    		$this->message = $value2;
    		//Check if user wants to attach a file
        	$this->askFile();
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
        	$this->askMessage();
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
        $this->say('__________________________');
        //call Ask user's name function
        $this->askName();
        //$this->getAttachment();
    }

}
