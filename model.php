<?php
require_once 'connection.php';

function setToken($token): void
{
    global $conn;
    $token= mysqli_real_escape_string($conn,$token);
    $sql="UPDATE `dars` SET `data` = '$token' WHERE `dars`.`day` = 'token'";
    mysqli_query($conn,$sql);
}
function getTokenDB(){
    global $conn;
    $sql="SELECT * FROM `dars` WHERE `day`='token' limit 1";
    $result=mysqli_query($conn,$sql);
    $row=mysqli_fetch_assoc($result);
    return $row['data'];
}
