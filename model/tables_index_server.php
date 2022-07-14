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

// $o2_breathe = (int)$_POST['o2_breathe'];
// $o2_hold = (int)$_POST['o2_hold'];
// $co2_breathe = (int)$_POST['co2_breathe'];
// $co2_hold = (int)$_POST['co2_hold'];

// if(not_in_range($o2_breathe) || not_in_range($o2_hold) || not_in_range($co2_breathe) || not_in_range($co2_hold))
// {
//     $msg['val'] = false;
//     send_JSON_and_exit($msg);
// }

$training_type = $_GET['type'];


$user = FreeDivingService::getUserByName($_GET['username']);

if($training_type === 'o')
{
    $msg['trainings'] = FreeDivingService::getMyTrainingDatesAndDurations($user, 'o');
}
else if($training_type === 'c')
{
    $msg['trainings'] = FreeDivingService::getMyTrainingDatesAndDurations($user, 'c');
}
else if($training_type === 'b')
{
    $msg['trainings'] = FreeDivingService::getMyTrainingDatesAndDurations($user, 'b');
}

send_JSON_and_exit($msg);
?>