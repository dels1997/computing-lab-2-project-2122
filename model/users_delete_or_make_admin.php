<?php

require_once __DIR__ . '/freedivingservice.class.php';

function send_JSON_and_exit($message)
{
    header('Content-type:application/json;charset=utf-8');
    echo json_encode($message);
    flush();
    exit(0);
}

$msg = [];

if($_GET['action'] === 'd')
{
    $msg['val'] = FreeDivingService::deleteUserByID($_GET['id']);
}
else if($_GET['action'] === 'a')
{
    $msg['val'] = FreeDivingService::makeUserAdminByID($_GET['id']);
    $msg['username'] = FreeDivingService::getUserByID($_GET['id'])->username;
}
else
{
    $msg['val'] = false;
}

send_JSON_and_exit($msg);
?>