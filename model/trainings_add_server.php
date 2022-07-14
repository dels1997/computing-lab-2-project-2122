<?php

require_once __DIR__ . '/freedivingservice.class.php';

function send_JSON_and_exit($message)
{
    header('Content-type:application/json;charset=utf-8');
    echo json_encode($message);
    flush();
    exit(0);
}
// function not_in_range($number)
// {
//     return !(($number >= 1) && ($number <= 599));
// }

$msg = [];


$type = $_GET['type'];
$duration = (int)$_GET['duration'];
$username = $_GET['username'];

$user = FreeDivingService::getUserByName($username);

if($type === 'o')
{
    $msg['val'] = FreeDivingService::addO2Training($user, $duration);
}
else if($type === 'c')
{
    $msg['val'] = FreeDivingService::addCO2Training($user, $duration);
}
else if($type === 'b')
{
    $msg['val'] = FreeDivingService::addOneBreathTraining($user, $duration);
}
else
{
    $msg['val'] = false;
}

send_JSON_and_exit($msg);
?>