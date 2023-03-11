<?php
require_once 'index.php';

if (dayName(strtotime('today')) == 'yakshanba' || count(getCustomDayLessons(dayName(strtotime('today'))))==0 ){
    exit();
}else{

    $lessons=getCustomDayLessons(dayName(strtotime('today')));
    $text=$lessons[0]['date'].' '.strtoupper(dayName(strtotime('today')))." kuni dars jadvali".PHP_EOL;
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
        $chat_id = -1001417980834;
        $telegram->sendMessage([
            'chat_id' => $chat_id,
            'text' => $text
        ]);



}


