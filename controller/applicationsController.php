<?php

require_once __DIR__ . '/../model/freedivingservice.class.php';

class ApplicationsController
{
    public function index()
    {
        $user = FreeDivingService::getUserByName($_SESSION['username']);
        $applicationlist = FreeDivingService::getMyApplications($user->id);
        
        $projects = [];
        if(!empty($applicationlist))
        {
            foreach($applicationlist as $application)
            {
                $projects[] = [FreeDivingService::getProjectByID($application->id_project), FreeDivingService::getUserByID($application->id_user), $application->member_type];
            }
        }

        $title = 'List of my pending applications';

        require_once __DIR__ . '/../view/applications_index.php';
    }

    // public function owned()
    // {
    //     $user = FreeDivingService::getUserByName($_SESSION['username']);
        
    //     // $user = FreeDivingService::getUserByID($id_user->id);

    //     $projectlist = FreeDivingService::getMyProjects($user);
    //     $title = 'List of my projects';

    //     require_once __DIR__ . '/../view/projects_index.php';
    // }

    // public function searchResults()
    // {
    //     $author = $_POST['author'];

    //     $booklist = LibraryService::getBooksByAuthor($author);
    //     $title = 'Popis svih knjiga autora ' . $author;

    //     require_once __DIR__ . '/../view/books_index.php';
    // }

    // public function list()
    // {
    //     $id_user = $_GET['id_user'];
        
    //     $user = LibraryService::getUserByID($id_user);
        
    //     $booklist = LibraryService::getLoanedBooks($user);

    //     $title = 'Popis svih knjiga koje je posudio ' . $user->name . ' ' . $user->surname;

    //     require_once __DIR__ . '/../view/books_index.php';
    // }
};

?>