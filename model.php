<?php
require_once 'connection.php';

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
