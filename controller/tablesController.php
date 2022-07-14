<?php

require_once __DIR__ . '/../model/freedivingservice.class.php';

class TablesController
{
    public function index()
    {
        $user = FreeDivingService::getUserByName($_SESSION['username']);
        
        $o2_trainings = FreeDivingService::getMyO2Trainings($user);

        $co2_trainings = FreeDivingService::getMyCO2Trainings($user);

        $title = '';

        require_once __DIR__ . '/../view/tables_index.php';
    }

    public function start()
    {
        $projectlist = null;

        $user = FreeDivingService::getUserByName($_SESSION['username']);

        $tables = FreeDivingService::getMyTables($user);

        $title = '';

        require_once __DIR__ . '/../view/tables_start.php';
    }
    
    public function edit()
    {
        $projectlist = null;

        $user = FreeDivingService::getUserByName($_SESSION['username']);

        $tables = null;

        $title = '';

        require_once __DIR__ . '/../view/tables_edit.php';
    }

    public function owned()
    {
        $user = FreeDivingService::getUserByName($_SESSION['username']);
        
        $projectlist = FreeDivingService::getMyProjects($user);
        $title = 'List of my projects';

        require_once __DIR__ . '/../view/tables_index.php';
    }

    public function show()
    {
        $id_project = $_GET['id_project'];

        $project_current = FreeDivingService::getProjectByID($id_project);

        $id_user = $project_current->id_user;

        $user = FreeDivingService::getUserByID($id_user);

        $memberlist = FreeDivingService::getProjectMembersByID($id_project);

        $projectlist = [];
        $projectlist[] = [$project_current, $user->username];

        $logged_user = FreeDivingService::getUserByName($_SESSION['username']);

        $project_full = FreeDivingService::projectFull($id_project);

        $iAmAuthor = ($id_user === $logged_user->id);

        $member_applied_invited_already = FreeDivingService::isMemberAppliedInvitedTo($logged_user->id, $id_project);

        $title = 'Info about project ' . $project_current->title . '</br>';

        $applicationlist = FreeDivingService::getProjectApplicationsByID($id_project);

        require_once __DIR__ . '/../view/projects_show.php';
    }

    public function apply()
    {
        $id_project = $_GET['id_project'];

        $project_current = FreeDivingService::getProjectByID($id_project);

        $logged_user = FreeDivingService::getUserByName($_SESSION['username']);

        $id_user = $project_current->id_user;

        $user = FreeDivingService::getUserByID($id_user);

        $memberlist = FreeDivingService::getProjectMembersByID($id_project);

        $member_applied_invited_already = FreeDivingService::isMemberAppliedInvitedTo($logged_user->id, $id_project);
        
        $project_full = FreeDivingService::projectFull($id_project);

        $application_successfull = False;

        if(!($member_applied_invited_already || $project_full))
        {
            FreeDivingService::applyToProject($logged_user->id, $id_project);
            $application_successfull = True;
        }

        // $projectlist = [];
        // $projectlist[] = [$project_current, $user->username];

        // $title = 'Info about project ' . $project_current->title . '</br>';
        $title = 'Application status';

        require_once __DIR__ . '/../view/projects_apply.php';
    }

    public function invite()
    {
        $id_project = $_GET['id_project'];

        $invited_user = FreeDivingService::getUserByName($_POST['person_to_invite']);

        if($invited_user)
        {
            $logged_user = FreeDivingService::getUserByName($_SESSION['username']);
    
            // $id_user = $project_current->id_user;
    
            // $user = FreeDivingService::getUserByID($id_user);
    
            // $memberlist = FreeDivingService::getProjectMembersByID($id_project);
    
            $member_already = FreeDivingService::isMemberAppliedInvitedTo($invited_user->id, $id_project);
    
            $project_full = FreeDivingService::projectFull($id_project);
    
            $invitation_successfull = False;
    
            if(!($member_already || $project_full))
            {
                $invitation_successfull = FreeDivingService::inviteMember($invited_user->id, $id_project);
            }
        }
        else
        {
            $invitation_successfull = False;
        }

        $title = 'Invitation status';

        require_once __DIR__ . '/../view/projects_invite.php';
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

        $id_project = $_GET['id_project'];

        $application_accepted = False;
        if(isset($_POST['accept']))
        {
            $current_user = FreeDivingService::getUserByName($_SESSION['username']);
            FreeDivingService::acceptApplication($_POST['id_user'], $_GET['id_project']);
            $application_accepted = True;
        }
        else
        {
            $current_user = FreeDivingService::getUserByName($_SESSION['username']);
            FreeDivingService::rejectApplication($_POST['id_user'], $_GET['id_project']);
        }

        $title = 'Application status';

        require_once __DIR__ . '/../view/projects_decision.php';
    }
};

?>