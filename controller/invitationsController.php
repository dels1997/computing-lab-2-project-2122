<?php

require_once __DIR__ . '/../model/freedivingservice.class.php';

class invitationsController
{
    public function index()
    {
        $user = FreeDivingService::getUserByName($_SESSION['username']);
        $invitationlist = FreeDivingService::getMyInvitations($user->id);

        $projects = [];
        if(!empty($invitationlist))
        {
            foreach($invitationlist as $invitation)
            {
                $project = FreeDivingService::getProjectByID($invitation->id_project);
                $inviting_user = FreeDivingService::getUserByID($project->id_user);
                $projects[] = [FreeDivingService::getProjectByID($invitation->id_project), $inviting_user, $invitation->member_type];
            }
        }

        $title = 'List of my pending invitations';

        require_once __DIR__ . '/../view/invitations_index.php';
    }

    public function decision()
    {
        // $user = FreeDivingService::getUserByName($_SESSION['username']);
        // $invitationlist = FreeDivingService::getMyInvitations($user->id);

        // $projects = [];
        // if(!empty($invitationlist))
        // {
        //     foreach($invitationlist as $invitation)
        //     {
        //         $project = FreeDivingService::getProjectByID($invitation->id_project);
        //         $inviting_user = FreeDivingService::getUserByID($project->id_user);
        //         $projects[] = [FreeDivingService::getProjectByID($invitation->id_project), $inviting_user, $invitation->member_type];
        //     }
        // }

        $invitation_accepted = 0;
        if(isset($_POST['accept']))
        {
            $current_user = FreeDivingService::getUserByName($_SESSION['username']);
            FreeDivingService::acceptInvitation($current_user->id, $_GET['id_project']);
            $invitation_accepted = 1;
        }
        else
        {
            $current_user = FreeDivingService::getUserByName($_SESSION['username']);
            FreeDivingService::rejectInvitation($current_user->id, $_GET['id_project']);
        }

        $title = 'Invitation status';

        require_once __DIR__ . '/../view/invitations_decision.php';
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