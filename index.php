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

try {


    function sendWeekLessons($day){
        $lessons=getCustomDayLessons($day);
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
    function sendLessons()
    {
        $lessons = getFormatedData();

        $today = date('d.m.Y');
        $todayLessons = '';
        foreach ($lessons as $lesson) {
            if ($lesson['date'] == $today) {
                $todayLessons .=
                    "📘 " .
                    $lesson['name'] . PHP_EOL .
                    '🏷 ' . $lesson['type'] . PHP_EOL .
                    '🏛 ' . $lesson['room'] . PHP_EOL .
                    '👤 ' . $lesson['teacher'] . PHP_EOL .
                    '⏰ ' . $lesson['start'] .
                    '-' . $lesson['end'] . PHP_EOL . PHP_EOL;
            }
        }
        sendText($todayLessons);


    }

    function dayName($date){
        return match (date('l',$date))
        {
            'Monday' => 'dushanba',
            'Tuesday' => 'seshanba',
            'Wednesday' => 'chorshanba',
            'Thursday' => 'payshanba',
            'Friday' => 'juma',
            'Saturday' => 'shanba',
            'Sunday' => 'yakshanba',
        };

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
    }elseif (str_contains($req, '/yakshanba')){
        sendWeekLessons('yakshanba');
    }elseif (str_contains($req, '/ertaga')){
        sendWeekLessons(dayName(strtotime('+3 day')));
    }
    elseif (
        str_contains($req, '/dars') or
        str_contains($req, 'dars jadvali') or
        str_contains($req, 'darsnerda') or
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
        sendWeekLessons(dayName(strtotime('today')));
    }

} catch (Exception $e) {
    $telegram->sendMessage([
        'chat_id' => $chat_id,
        'text' => $e->getMessage(),
        'reply_to_message_id' => $telegram->MessageID()
    ]);
}