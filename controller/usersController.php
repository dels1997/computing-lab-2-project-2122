<?php

require_once __DIR__ . '/../model/teamupservice.class.php';

class usersController
{
    public function index()
    {
        $users = TeamUpService::getAllUsers();
        $admins = TeamUpService::getAllAdmins();

        require_once __DIR__ . '/../view/users_index.php';
    }
};

?>