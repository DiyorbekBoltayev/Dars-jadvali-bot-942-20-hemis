<?php
//vendor
require 'vendor/autoload.php';
require_once 'Telegram.php';
$telegram=new Telegram($_ENV['TELEGRAM_BOT_TOKEN']);
$chat_id=-1001574854105;

$message="Bu bot juda foydali bo'ldi 32243hhhhhhhhhhhhhhhhhhhhhhhhh34";
$message = $telegram->sendMessage([
    'chat_id' => $chat_id,
    'text' => $message,
    'message_thread_id' => 22,
]);
$telegram->sendMessage([
    'chat_id' => $chat_id,
    'text' => json_encode($message),
]);
$telegram->sendMessage([
    'chat_id' => $chat_id,
    'text' => $message->getMessageId(),
]);
try {
    $a=$telegram->pinChatMessage([
        'chat_id' => $chat_id,
        'message_id' => $message->getMessageId(),
        'disable_notification' => false,
    ]);
    $telegram->sendMessage([
        'chat_id' => $chat_id,
        'text' => json_encode($a),
    ]);
} catch (Exception $e) {

    $telegram->sendMessage([
        'chat_id' => $chat_id,
        'text' => $e->getMessage(),
    ]);
}
