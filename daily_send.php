<?php
require_once 'index.php';

if (dayName(strtotime('today')) == 'yakshanba' || count(getCustomDayLessons(strtotime('today')))==0 ){
    exit();
}else{
    sendWeekLessons(dayName(strtotime('today')));
}


