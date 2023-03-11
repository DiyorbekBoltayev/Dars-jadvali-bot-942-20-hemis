<?php
require_once 'vendor/autoload.php';
require_once 'model.php';
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
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
    $request = new Request('POST', $_ENV['HEMIS_LOGIN_URL']);
    $res = $client->sendAsync($request, $options)->wait();

    setToken(json_decode($res->getBody())->data->token,date('Y-m-d'));

}

function getData()
{
    $client = new Client(['verify' => false]);
    $headers = [
        'Authorization' => 'Bearer ' . getTokenDB(),

    ];
    $request = new Request('GET', $_ENV['HEMIS_SCHEDULE_URL'], $headers);
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
function getCurrentWeekLessons(): array
{
    $lessons = getFormatedData();
    $week = startAndEndOfWeek();
    $currentWeekLessons = [];
    foreach ($lessons as $lesson) {
        if (strtotime($lesson['date']) >= strtotime($week[0]) && strtotime($lesson['date']) <= strtotime($week[1])) {
            $currentWeekLessons[dayName(strtotime($lesson['date']))][]=$lesson;
        }
    }
    return $currentWeekLessons;
}
function dayByDayLessons():array
{
    $lessons = getCurrentWeekLessons();
    $days=['dushanba'=>[],'seshanba'=>[],'chorshanba'=>[],'payshanba'=>[],'juma'=>[],'shanba'=>[],'yakshanba'=>[]];

    foreach ($lessons as $key=>$lesson){
        $days[$key]=$lesson;
    }
    return $days;
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