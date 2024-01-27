<?php
require(__DIR__.'/../src/Twilio/autoload.php');

use Twilio\Rest\Client;

/**$sid = getenv('TWILIO_ACCOUNT_SID');
$token = getenv('TWILIO_AUTH_TOKEN');*/
$sid = getenv("ACbb67e5be3379d7c4985f2ba37a9410bc");
$token = getenv("14e62642752f832c10c85b792eaae915");
$client = new Client($sid, $token);

// Specify the phone numbers in [E.164 format](https://www.twilio.com/docs/glossary/what-e164) (e.g., +16175551212)
// This parameter determines the destination phone number for your SMS message. Format this number with a '+' and a country code
$phoneNumber = "+263783065525";

// This must be a Twilio phone number that you own, formatted with a '+' and country code
$twilioPurchasedNumber = "+263783065525";

// Send a text message
$message = $client->messages->create(
    $phoneNumber,
    [
        'from' => $twilioPurchasedNumber,
        'body' => "Hey Jenny! Good luck on the bar exam!"
    ]
);
print("Message sent successfully with sid = " . $message->sid ."\n\n");

// Print the last 10 messages
$messageList = $client->messages->read([],10);
foreach ($messageList as $msg) {
    print("ID:: ". $msg->sid . " | " . "From:: " . $msg->from . " | " . "TO:: " . $msg->to . " | "  .  " Status:: " . $msg->status . " | " . " Body:: ". $msg->body ."\n");
}