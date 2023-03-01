<?php
require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
require_once 'Telegram.php';

$telegram=new Telegram($_ENV['TELEGRAM_BOT_TOKEN']);
$chat_id=$telegram->ChatID();
try {


    $telegram->sendMessage([
        'chat_id' => $chat_id,
        'text' => 'ishlamoqda'
    ]);


    try {
        $text = getData();
    } catch (Exception $e) {
        $text = $e->getMessage();
    }


    $telegram->sendMessage([
        'chat_id' => $chat_id,
        'text' => json_encode($text)
    ]);

} catch (Exception $e) {
    $telegram->sendMessage([
        'chat_id' => $chat_id,
        'text' => $e->getMessage()
    ]);
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

    $data = json_decode($res->getBody())->data;

    if (empty($data)) {
        throw new Exception('Error fetching data from API');
    }

    return $data;

}
