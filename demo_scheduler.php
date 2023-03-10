<?php
//vendor
require 'vendor/autoload.php';
require_once 'Telegram.php';
$telegram=new Telegram($_ENV['TELEGRAM_BOT_TOKEN']);
$chat_id=-1001574854105;
$message="Bu bot juda foydali bo'ldi 3224334";
$message = $telegram->sendMessage([
    'chat_id' => $chat_id,
    'text' => $message,
    'message_thread_id' => 22,
]);

$telegram->pinChatMessage([
    'chat_id' => $chat_id,
    'message_id' => $message->getMessageId(),
    'message_thread_id' => 22,
]);