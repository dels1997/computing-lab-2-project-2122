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

$id_product = (int)$_GET['id_product'];
$username = $_GET['username'];

$user = FreeDivingService::getUserByName($username);

$msg['val'] = WebshopService::buyProduct($user->id, $id_product);

$msg['quantity'] = WebshopService::getProductQuantityByID($id_product);

send_JSON_and_exit($msg);
?>