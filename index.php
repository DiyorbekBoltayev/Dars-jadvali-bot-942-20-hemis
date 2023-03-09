<?php
require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
require_once 'Telegram.php';
require_once 'model.php';

$telegram=new Telegram($_ENV['TELEGRAM_BOT_TOKEN']);
$chat_id=$telegram->ChatID();
$req=$telegram->Text();
$req=strtolower($req);
echo "Botdan foydalanish "."<a href='https://t.me/Dars_Jadvali_942_20_HEMIS_bot'>https://t.me/Dars_Jadvali_942_20_HEMIS_bot</a>";
//TODO yakshanba kuni ertaga buyrug'ida xato natija qaytaradi
try {


    function sendWeekLessons($day){
        $lessons=getCustomDayLessons($day);
        if (count($lessons)==0){
            sendText($day.' da dars yo\'q');
        }else{

        $text=$lessons[0]['date'].' '.strtoupper($day)." kuni dars jadvali".PHP_EOL;
        foreach ($lessons as $lesson){
            $text.=
                "📘 " .
                $lesson['name'] . PHP_EOL .
                '🏷 ' . $lesson['type'] . PHP_EOL .
                '🏛 ' . $lesson['room'] . PHP_EOL .
                '👤 ' . $lesson['teacher'] . PHP_EOL .
                '⏰ ' . $lesson['start'] .
                '-' . $lesson['end'] . PHP_EOL . PHP_EOL;
        }
        sendText($text);
        }

    }




    function sendText($text)
    {
        global $telegram, $chat_id;
        $telegram->sendMessage([
            'chat_id' => $chat_id,
            'text' => $text,
            'reply_to_message_id' => $telegram->MessageID()
        ]);
    }

    if ($req == '/start') {
        $content = ['chat_id' => $chat_id, 'text' => 'Assalomu alaykum, Bu bot yordamida 942-20 guruxi talabalarining joriy sanadagi dars jadvalini olishingiz mumkin, /dars deb yozing yoki shu manodagi matn yuboring, masalan: dars jadvali, qaysi xona, dars neda, dars nerda, novi dars '];
        $telegram->sendMessage($content);
    }elseif (str_contains($req, '/dushanba')){
        sendWeekLessons('dushanba');
    }elseif (str_contains($req, '/seshanba')){
        sendWeekLessons('seshanba');
    }elseif (str_contains($req, '/chorshanba')){
        sendWeekLessons('chorshanba');
    }elseif (str_contains($req, '/payshanba')){
        sendWeekLessons('payshanba');
    }elseif (str_contains($req, '/juma')){
        sendWeekLessons('juma');
    }elseif (str_contains($req, '/shanba')){
        sendWeekLessons('shanba');
    }elseif (str_contains($req, '/ertaga')){
        if (dayName(strtotime('+1 day'))=='yakshanba')
            sendText('Ertaga yakshanba, dars yo\'q');
        else
            sendWeekLessons(dayName(strtotime('+1 day')));
    }
    elseif (
        str_contains($req, '/dars') or
        str_contains($req, 'dars jadvali') or
        str_contains($req, 'darsnerda') or
        str_contains($req, 'para novi') or
        str_contains($req, 'para nerda') or
        str_contains($req, 'para novvi') or
        str_contains($req, 'par nerda') or
        str_contains($req, 'par novvi') or
        str_contains($req, 'par novi') or
        str_contains($req, 'pora nerda') or
        str_contains($req, 'pora novi') or
        str_contains($req, 'pora novvi') or
        str_contains($req, 'dars yoqmi') or
        str_contains($req, 'qaysi xona') or
        str_contains($req, 'qaysi dars') or
        str_contains($req, 'dars neda') or
        str_contains($req, 'dars nerda') or
        str_contains($req, 'nerda dars') or
        str_contains($req, 'kimni darsi') or
        str_contains($req, 'dars qatta') or
        str_contains($req, 'novvi dars') or
        str_contains($req, 'dars nichchada') or
        str_contains($req, 'dars nichada') or
        str_contains($req, 'dars qachon') or
        str_contains($req, 'bomi dars') or
        str_contains($req, 'dars bomi') or
        str_contains($req, 'dars boma') or
        str_contains($req, 'dars bormi') or
        str_contains($req, 'dars borma') or
        str_contains($req, 'para nerda') or
        str_contains($req, 'novi dars')


    ) {
        if (dayName(strtotime('today'))=='yakshanba')
            sendText('Bugun yakshanba, dars yo\'q');
        else
            sendWeekLessons(dayName(strtotime('today')));
    }elseif (str_contains($req, 'ertang') and str_contains($req, 'dars')
    or str_contains($req, 'ertanga') and str_contains($req, 'dars')
    ) {
        if (dayName(strtotime('+1 day')) == 'yakshanba')
            sendText('Ertaga yakshanba, dars yo\'q');
        else
            sendWeekLessons(dayName(strtotime('today')));
    }
} catch (Exception $e) {
    $telegram->sendMessage([
        'chat_id' => $chat_id,
        'text' => $e->getMessage(),
        'reply_to_message_id' => $telegram->MessageID()
    ]);
}