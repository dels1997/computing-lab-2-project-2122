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
// function not_in_range($number)
// {
//     return !(($number >= 1) && ($number <= 599));
// }

$msg = [];

$id_product = $_GET['id_product'];


// $user = FreeDivingService::getUserByName($_GET['username']);

$msg['comments_and_ratings'] = WebshopService::getCommentsAndRatingsByProductID($id_product);

$msg['rating'] = WebshopService::getRatingByProductID($id_product);

// if($training_type === 'o')
// {
//     $msg['trainings'] = FreeDivingService::getMyTrainingDatesAndDurations($user, 'o');
// }
// else if($training_type === 'c')
// {
//     $msg['trainings'] = FreeDivingService::getMyTrainingDatesAndDurations($user, 'c');
// }
// else if($training_type === 'b')
// {
//     $msg['trainings'] = FreeDivingService::getMyTrainingDatesAndDurations($user, 'b');
// }

send_JSON_and_exit($msg);
?>