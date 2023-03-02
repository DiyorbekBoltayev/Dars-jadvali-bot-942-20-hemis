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


    function getToken()
    {


        $client = new Client(['verify' => false]);
        $options = [
            'multipart' => [
                [
                    'name' => 'login',
                    'contents' => $_ENV['HEMIS_LOGIN']
                ],
                [
                    'name' => 'password',
                    'contents' => $_ENV['HEMIS_PASSWORD']
                ]
            ]];
        $request = new Request('POST', 'https://student.ubtuit.uz/rest/v1/auth/login');
        $res = $client->sendAsync($request, $options)->wait();

        setToken(json_decode($res->getBody())->data->token,date('Y-m-d'));

    }

    function getData()
    {
        $client = new Client(['verify' => false]);
        $headers = [
            'Authorization' => 'Bearer ' . getTokenDB(),

        ];
        $request = new Request('GET', 'https://student.ubtuit.uz/rest/v1/education/schedule', $headers);
        $res = $client->sendAsync($request)->wait();

        return json_decode($res->getBody())->data;


    }
    function getFormatedData()
    {
        $data = getData();
        $lessons = [];
        foreach ($data as $datum) {
            $lesson = [];
            $lesson['name'] = $datum->subject->name;
            $lesson['type'] = $datum->trainingType->name;
            $lesson['room'] = $datum->auditorium->name;
            $lesson['teacher'] = $datum->employee->name;
            $lesson['start'] = $datum->lessonPair->start_time;
            $lesson['end'] = $datum->lessonPair->end_time;
            $lesson['date'] = date('d.m.Y', $datum->lesson_date);
            $lessons[] = $lesson;
        }
        return $lessons;
    }


    function startAndEndOfWeek():array
    {
        $today = new DateTime();
        $today->setTimezone(new DateTimeZone('Asia/Tashkent'));
        $startOfWeek = clone $today;
        $startOfWeek->modify('this week');
        $endOfWeek = clone $today;
        $endOfWeek->modify('this week +6 days');
        $startOfWeekStr = $startOfWeek->format('Y-m-d');
        $endOfWeekStr = $endOfWeek->format('Y-m-d');
        return [$startOfWeekStr, $endOfWeekStr];
    }
    function getCurrentWeekLessons(): void
    {
        $lessons = getFormatedData();
        $week = startAndEndOfWeek();
        $currentWeekLessons = 'darslar'.PHP_EOL;
        foreach ($lessons as $lesson) {
            if (strtotime($lesson['date']) >= strtotime($week[0]) && strtotime($lesson['date']) <= strtotime($week[1])) {
                $currentWeekLessons .=
                    "📘 " .
                    $lesson['name'] . PHP_EOL .
                    '🏷 ' . $lesson['type'] . PHP_EOL .
                    '🏛 ' . $lesson['room'] . PHP_EOL .
                    '👤 ' . $lesson['teacher'] . PHP_EOL .
                    '⏰ ' . $lesson['start'] .
                    '-' . $lesson['end'] . PHP_EOL
                    . $lesson['date'] . PHP_EOL . PHP_EOL;
            }
        }
        echo "<pre>";
       var_dump($currentWeekLessons);
        echo "</pre>";
//        sendText($currentWeekLessons);
    }
    getCurrentWeekLessons();

    function sendText($text)
    {
        global $telegram, $chat_id;
        $telegram->sendMessage([
            'chat_id' => $chat_id,
            'text' => $text,
            'reply_to_message_id' => $telegram->MessageID()
        ]);
    }

    if ($telegram->text() == '/start') {
        $content = ['chat_id' => $chat_id, 'text' => 'Assalomu alaykum, Bu bot yordamida 942-20 guruxi talabalarining joriy sanadagi dars jadvalini olishingiz mumkin, /dars deb yozing yoki shu manodagi matn yuboring, masalan: dars jadvali, qaysi xona, dars neda, dars nerda, novi dars '];
        $telegram->sendMessage($content);
    } elseif (
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
        sendLessons();
    }

} catch (Exception $e) {
    $telegram->sendMessage([
        'chat_id' => $chat_id,
        'text' => $e->getMessage(),
        'reply_to_message_id' => $telegram->MessageID()
    ]);
}