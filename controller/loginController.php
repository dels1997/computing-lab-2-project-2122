<?php

require_once __DIR__ . '/../model/freedivingservice.class.php';

class loginController
{
    public function index()
    {
        $title = 'Welcome!';

        $rez = FreeDivingService::processLoginOrRegister();

        if($rez && !isset($_POST['register']))
        {
            $user = FreeDivingService::getUserByName($_SESSION['username']);
        
            $o2_trainings = FreeDivingService::getMyO2Trainings($user);
    
            $co2_trainings = FreeDivingService::getMyCO2Trainings($user);
    
            $title = $user->username;
    
            require_once __DIR__ . '/../view/tables_index.php';
        }
        else
            require_once __DIR__ . '/../view/login_index.php';
    }

    function finishRegistration()
    {

        $sequence = $_GET['sequence'] ?? null;
        $sequence = rtrim($sequence, "/");

        $user = FreeDivingService::getUserByRegistrationSequence($sequence);

        FreeDivingService::addHasRegistered($user);
        $_SESSION['username'] = $user->username;
        $_SESSION['admin'] = $user->admin;

        require_once __DIR__ . '/../view/login_index.php';
    }
};

?>
