<?php

namespace App\Http\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class CommandsConversation extends Conversation
{
    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
    	$this->say('__________________________');
    	$this->say('You can chat with me freely and if I can\'t understand you, feel free to type the following commands');
    	$this->say('"help":-- Give a simple guide, but does not stops a coversation');
    	$this->say('"stop":-- Stops a conversation');
    	$this->say('"contact staff":-- chose this to send a message directly to a member of staff');
    }
}
  