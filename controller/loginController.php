<?php

require_once __DIR__ . '/../model/teamupservice.class.php';

class loginController
{
    public function index()
    {
        $title = 'Welcome!';

        $rez = TeamUpService::processLoginOrRegister();

        if($rez && !isset($_POST['register']))
        {
            $user = TeamUpService::getUserByName($_SESSION['username']);
        
            $o2_trainings = TeamUpService::getMyO2Trainings($user);
    
            $co2_trainings = TeamUpService::getMyCO2Trainings($user);
    
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
        TeamUpService::addHasRegistered($user);
        $_SESSION['username'] = $user->username;
    }
};

?>