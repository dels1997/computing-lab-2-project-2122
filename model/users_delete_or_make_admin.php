<?php

require_once __DIR__ . '/teamupservice.class.php';

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
    $msg['val'] = TeamUpService::deleteUserByID($_GET['id']);
}
else if($_GET['action'] === 'a')
{
    $msg['val'] = TeamUpService::makeUserAdminByID($_GET['id']);
    $msg['username'] = TeamUpService::getUserByID($_GET['id'])->username;
}
else
{
    $msg['val'] = false;
}

send_JSON_and_exit($msg);
?>