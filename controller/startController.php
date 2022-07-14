<?php

require_once __DIR__ . '/../model/freedivingservice.class.php';

class StartController
{
    public function index()
    {
        $title = 'Create Your own project!';

        $user = FreeDivingService::getUserByName($_SESSION['username']);

        require_once __DIR__ . '/../view/start_index.php';
    }

    public function create()
    {
        $title = 'Creation status';

        $user = FreeDivingService::getUserByName($_SESSION['username']);

        $project_title = $_POST['title'];
        $project_abstract = $_POST['abstract'];
        $project_number_of_members = $_POST['number_of_members'];

        $creation_successfull = False;

        if(ctype_digit($project_number_of_members) && $project_number_of_members > 0)
        {
            if(FreeDivingService::createProject($user->id, $project_title, $project_abstract, $project_number_of_members))
                $creation_successfull = True;
        }

        require_once __DIR__ . '/../view/start_create.php';
    }
};

?>