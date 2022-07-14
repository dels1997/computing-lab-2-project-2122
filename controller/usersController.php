<?php

require_once __DIR__ . '/../model/freedivingservice.class.php';

class usersController
{
    public function index()
    {
        $users = FreeDivingService::getAllUsers();
        $admins = FreeDivingService::getAllAdmins();

        require_once __DIR__ . '/../view/users_index.php';
    }
};

?>