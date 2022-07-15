<?php

require_once __DIR__ . '/freedivingservice.class.php';
require_once __DIR__ . '/webshopservice.class.php';

function send_JSON_and_exit($message)
{
    header('Content-type:application/json;charset=utf-8');
    echo json_encode($message);
    flush();
    exit(0);
}

$msg = [];

$name = $_POST['name'];
$description = $_POST['description'];
$price = (int)$_POST['price'];
$number_available = (int)$_POST['number_available'];
$username = $_POST['username'];

if($price < 1 || $price > 1000 || $number_available < 1 || $number_available > 100)
{
    $msg['val'] = false;
    send_JSON_and_exit($msg);
}
if(strlen($name) < 1 || strlen($name) > 20 || strlen($description) < 1 || strlen($description) > 100)
{
    $msg['val'] = false;
    send_JSON_and_exit($msg);
}

$user = FreeDivingService::getUserByName($username);

$msg['val'] = WebshopService::addProduct($user->id, $name, $description, $price, $number_available);

send_JSON_and_exit($msg);
?>