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

$id_product = $_GET['id_product'];
$comment = $_GET['comment'];
$rating = $_GET['rating'];
$username = $_GET['username'];

$msg['comment'] = $comment;

if(strlen($comment) < 1 || strlen($comment) > 100 || $rating < 0 || $rating > 5 || !isset($rating) || !isset($comment))
{
    $msg['val'] = false;
    send_JSON_and_exit($msg);
}

$user = FreeDivingService::getUserByName($username);

$msg['val'] = WebshopService::addCommentAndRating($user->id, $id_product, $comment, $rating);

send_JSON_and_exit($msg);
?>