<?php

require_once __DIR__ . '/../model/freedivingservice.class.php';

class logoutController
{
    public function index()
    {
        $title = 'Thank You for using our service!';

        FreeDivingService::processLogout();

        require_once __DIR__ . '/../view/login_index.php';
    }
};

?>