<?php

namespace App\Http\Conversations;
 
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class GreetingsConversation extends Conversation
{
    /**
     * Start the conversation.
     *
     * @return mixed
     */
    protected function askquestion(){
    	$question = Question::create('And you?')
        	->addButtons([
        		Button::create('I am fine too')->value('fine'),
        		Button::create('I am not well')->value('not')

        	]);
        $this->ask($question, function ($answer){
        	if ($answer->isInteractiveMessageReply()) {
        		if($answer->getValue() === 'fine'){
        			$option = $answer->getText();
        			$this->say('Nice to hear that...');
        		}
        		else if($answer->getValue() === 'not'){
        			$option = $answer->getText();
        			$this->say('Get well soon!');
        		}
        	}
        	else{
        		return $this->repeat('Please select one of the options.');
        	}	
        });
        
    }

    public function run()
    {
    	 $this->say('I am very well, thank you.');
    	 $this->askquestion();
    }
}
