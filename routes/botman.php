<?php
use App\Http\Controllers\BotManController;

$botman = resolve('botman');

// 
$botman->fallback(function($bot){
    $bot->typesAndWaits(1);
    $message = $bot->getMessage();
    $bot->reply('Sorry! I don\'t understand: '.$message->getText().'.');
    $bot->startConversation(new App\Http\Conversations\HelpButtons);  
});

//Welcome conversation
$botman->hears('Hi|Hello|Hola', function ($bot) {
    $bot->startConversation(new App\Http\Conversations\WelcomeConversation);  
});
$botman->hears('My name is {myname}', function ($bot, $myname) {
    $bot->userStorage()->save([
        'name' => $myname
    ]);
    $bot->reply('Nice to meet you '.$myname); 
});
$botman->hears('What is my name*|Do you remember my name*|Say my name', function ($bot) {
    $name = $bot->userStorage()->get('name');
    $bot->reply('Your name is'.$name); 
});

$botman->hears('How are you|how r u|how are u|how r you', function ($bot) {
    $bot->startConversation(new App\Http\Conversations\GreetingsConversation);  
});
//About
$botman->hears('Tell me about you|tell me about you|tell me about u|Tell me about u|about u|about you|what are you|what r u|what are u|what r you|who are you|who r u|who are u', function ($bot) {
    //$bot->typesAndWaits(2);
    $bot->reply('I am a chat bot developed to provide information about Solent University!');
});
$botman->hears('What is an helpdesk bot*', function ($bot) {
    $bot->reply('I am an assistant bot designed to answer queries about the University.');
    $bot->reply('I might not be able to answer everything and in this case you can choose to send your query to a member of the University staff.'); 
});
$botman->hears('What can you answer*', function ($bot) {
    $bot->reply('I can answer anything in my Knowledge.'); 
});
$botman->hears('Who created you*', function ($bot) {
    $bot->reply('I was designed by Tiago Kayaya.'); 
});
$botman->hears('What language do you speak*|What language can you understand*', function ($bot) {
    $bot->reply('I can only understand English.'); 
});

/*  ---- Commom questions  ----*/
$botman->hears('Thank you|Thanks', function ($bot) {
    $bot->reply('You are welcome!'); 
});
$botman->hears('Tell me about Solent University|What is Solent University', function ($bot) {
    $bot->reply('Solent University is a public university based in Southampton, United Kingdom. It has approximately 11000 students.'); 
    $bot->reply('Its main campus is located on East Park Terrace near the city centre and the maritime hub of Southampton.');
});
$botman->hears('Who is Tiago*', function ($bot) {
    $bot->reply('A software Engineer student, who created me.'); 
});

$botman->hears('Where do you live*', function ($bot) {
    $bot->reply('I was created at Southampton city.'); 
});
/*  ---- Letter  ----*/
$botman->hears('letter', function ($bot) {
    //$bot->reply('Nice to meet you ');
    $bot->startConversation(new App\Http\Conversations\LetterConversation);

});
//Contact staff conversation
$botman->hears('contact staff', function ($bot) {
    $bot->startConversation(new App\Http\Conversations\ContactStaff);  
});

$botman->hears('Help|help', function ($bot) {
    $bot->typesAndWaits(2);
    $bot->reply('This the helping information!');
    $bot->reply('You can use the following commands:');
    $bot->reply('help: Display chatbot commands');
    $bot->reply('stop: Stops a conversation');
    $bot->reply('contact staff: Send a message directly to a member of staff');

})->skipsConversation();

$botman->hears('Stop|stop', function ($bot) {
    $bot->types();
    $bot->reply('Your conversation has been stopped!');
})->stopsConversation();


//Frequently asked Questions
$botman->hears('What external stakeholders were consulted' , function ($bot) {
    $bot->reply('Key clients/customers and Chamber of Shipping members have been consulted. There has been specific feedback on the need to maintain the quality of the officer cadet experience, the international reputation, standards and outputs, funding for officer cadet provision, and location, which have been taken on board.');
});

$botman->hears('Who has contributed to the maritime strategy', function ($bot) {
    $bot->reply('The development of the maritime strategy has been led by senior managers of the University (Vice-Chancellor\'s Group, senior managers of the school, including Programme Group Leaders) and we have consulted with and taken input from industry leaders.');
});

$botman->hears('The strategy says that the University plans to review the delivery of UK officer cadetships. What does this mean? Are you looking to make changes', function ($bot) {
    $bot->reply('This review is currently underway and has included discussions with MCA/MNTB and training companies to ensure that our officer cadetships continue to meet MNTB approval.');
});

$botman->hears('How much are you planning to invest in delivering this maritime strategy* ', function ($bot) {
    $bot->reply('The actual investment requirements, ie details around new space and associated costings are being addressed and further details will be available at a later date.');
});

$botman->hears('tell me about so* solent uni*', function ($bot) {
    $bot->reply('Solent University is a public university based in Southampton, United Kingdom. It has approximately 11000 students. Its main campus is located on East Park Terrace near the city centre and the maritime hub of Southampton.');
});


/* ----- BYE ---------*/
$botman->hears('Bye|Goodbye|See ya|Good bye', function ($bot) {
    $bot->reply('See you later! '); 
});
$botman->hears('See you later|See u later', function ($bot) {
    $bot->reply('Good bye!'); 
});










