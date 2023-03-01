<?php
require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
require_once 'Telegram.php';

$telegram=new Telegram($_ENV['TELEGRAM_BOT_TOKEN']);
$chat_id=$telegram->ChatID();
try {
    $data=getData();
    $lessons=[];
    foreach ($data as $datum) {
        $lesson=[];
        $lesson['name']=$datum->subject->name;
        $lesson['type']=$datum->trainingType->name;
        $lesson['room']=$datum->auditorium->name;
        $lesson['teacher']=$datum->employee->name;
        $lesson['start']=$datum->lessonPair->start_time;
        $lesson['end']=$datum->lessonPair->end_time;
        $lesson['date']=date('d.m.Y',$datum->lesson_date);
        $lessons[]=$lesson;
    }
    $today=date('d.m.Y');
    $todayLessons='';
    foreach ($lessons as $lesson) {
        if($lesson['date']==$today){
            $todayLessons.=$lesson['name'].' '.$lesson['type'].' '.$lesson['room'].' '.$lesson['teacher'].' '.$lesson['start'].'-'.$lesson['end'].PHP_EOL;
        }
    }
    sendText($todayLessons);


} catch (Exception $e) {
    sendText($e->getMessage());
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

    return json_decode($res->getBody())->data->token;

}

function getData(){
    $client = new Client(['verify' => false]);
    $headers = [
        'Authorization' => 'Bearer '.getToken(),

    ];
    $request = new Request('GET', 'https://student.ubtuit.uz/rest/v1/education/schedule', $headers);
    $res = $client->sendAsync($request)->wait();

    return json_decode($res->getBody())->data;


}
function sendText($text){
    global $telegram,$chat_id;
    $telegram->sendMessage([
        'chat_id' => $chat_id,
        'text' => $text
    ]);
}
