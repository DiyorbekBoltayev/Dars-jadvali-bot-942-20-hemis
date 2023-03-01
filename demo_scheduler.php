<?php
//vendor
require 'vendor/autoload.php';
require_once 'Telegram.php';
$telegram=new Telegram($_ENV['TELEGRAM_BOT_TOKEN']);
$chat_id=1490424185;
$text="Bu matn".date('d-m-Y-H-i-s')." da yuborildi";