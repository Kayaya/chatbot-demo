<?php

namespace App\Http\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
  
class WelcomeConversation extends Conversation
{
    /**
     * Start the conversation.
     *
     * @return mixed
     */
    protected $isEmpty;

    protected function askName(){
    	   $this->ask('What is your name?', function($answer) {
            $this->name = $answer->getText();
        	$this->say('Nice to meet you, '.$this->name);
            $this->say('How can I help you today?');
           
        });
    }

    public function run()
    {
    	//Greet the user 
        $this->say('Hello');
        //call Ask user's name function
        //$this->askName();
    }

}
