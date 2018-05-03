<?php

namespace App\Http\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class HelpButtons extends Conversation
{
    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
        $question = Question::create('See my commands or send a message to a member of staff')
        	->addButtons([
        		Button::create('See commands')->value('commands'),
        		Button::create('Contact staff')->value('staff'),
        		Button::create('Continue chatting')->value('chat')
        	]);
        $this->ask($question, function ($answer){
        	if ($answer->isInteractiveMessageReply()) {
        		if($answer->getValue() === 'commands'){
        			$this->say($answer->getValue());
        		}
        		else if($answer->getValue() === 'staff'){
        			$this->say('You selected to contact a member of '.$answer->getValue());
        			$this->bot->startConversation(new App\Http\Conversations\ContactStaff); 
        		}
        		else if($answer->getValue() === 'chat'){
        			$this->say('What else would you like to know?');

        		}
        	}
        	else{
        		return $this->repeat('Please select one of the options.');
        	}
        	
        });
        
    }
}
