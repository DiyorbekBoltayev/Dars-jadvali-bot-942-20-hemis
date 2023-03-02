<?php
require_once 'connection.php';
require_once 'reader.php';
function setToken($token,$created): void
{
    global $conn;
    $token= mysqli_real_escape_string($conn,$token);
    $created= mysqli_real_escape_string($conn,$created);
    $sql="UPDATE `dars` SET `data` = '$token',`created` = '$created' WHERE `dars`.`day` = 'token'";
    mysqli_query($conn,$sql);
}
function getTokenDB(){
    global $conn;
    $sql="SELECT * FROM `dars` WHERE `day`='token' limit 1";
    $result=mysqli_query($conn,$sql);
    $row=mysqli_fetch_assoc($result);
    $today = new DateTime(date('Y-m-d'));
    $date1 = new DateTime($row['created']);
    $diff = $today->diff($date1)->format('%a');
    if($diff>5){
        getToken();
        return getTokenDB();
    }
    return $row['data'];
}

function storeDataDayByDay(): void
{
    $days = dayByDayLessons();
    foreach ($days as $day => $lessons) {
        storeData($day, $lessons);
    }
}
function storeData($day, $lessons): void
{
    global $conn;
    $day = mysqli_real_escape_string($conn, $day);
    $lessons = mysqli_real_escape_string($conn, json_encode($lessons));
    $sql = "UPDATE `dars` SET `data` = '$lessons' WHERE `dars`.`day` = '$day'";
    mysqli_query($conn, $sql);
}
function getCustomDayLessons($day): array
{
    global $conn;
    $day = mysqli_real_escape_string($conn, $day);
    $sql = "SELECT * FROM `dars` WHERE `day`='$day' limit 1";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return json_decode($row['data'], true);
}

