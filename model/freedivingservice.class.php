<?php

require_once __DIR__ . '/../app/database/db.class.php';
require_once __DIR__ . '/user.class.php';
require_once __DIR__ . '/member.class.php';
require_once __DIR__ . '/project.class.php';
require_once __DIR__ . '/training.class.php';
require_once __DIR__ . '/table.class.php';

class FreeDivingService
{
    public static function getUserByID($id_user)
    {
        $db = DB::getConnection();
        $st = $db->prepare('SELECT * FROM users WHERE id=:id');
        $st->execute(['id' => $id_user]);

        $row = $st->fetch();

        return new User($row['id'], $row['username'], $row['password_hash'], $row['email'], $row['registration_sequence'], $row['has_registered'], $row['admin']);    
    }

    public static function getUserByName($username)
    {
        $db = DB::getConnection();
        $st = $db->prepare('SELECT * FROM users WHERE username=:username');
        $st->execute(['username' => $username]);

        if($row = $st->fetch())
            return new User($row['id'], $row['username'], $row['password_hash'], $row['email'], $row['registration_sequence'], $row['has_registered'], $row['admin']);
        else
            return null; 
    }

    public static function getAuthorByProjectID($id_project)
    {
        $db = DB::getConnection();
        $st = $db->prepare('SELECT * FROM products WHERE id=:id');
        $st->execute(['id' => $id_project]);

        $row = $st->fetch();
        $id_user = $row['id_user'];

        $st = $db->prepare('SELECT * FROM users WHERE id=:id');
        $st->execute(['id' => $id_user]);

        $row = $st->fetch();

        return new User($row['id'], $row['username'], $row['password_hash'], $row['email'], $row['registration_sequence'], $row['has_registered'], $row['admin']);    
    }
// ovo maknit--------------------------------
    public static function getMyProjects($user)
    {
        $db = DB::getConnection();
        $st = $db->prepare('SELECT * FROM members WHERE id_user=:id_user');
        $st->execute(['id_user' => $user->id]);

        $projects = [];
        while($row = $st->fetch())
        {
            $id_project = $row['id_project'];
            $project = FreeDivingService::getProjectByID($id_project);
            $user = FreeDivingService::getAuthorByProjectID($project->id);
            $projects[] = [$project, $user->username, null];
        }

        return $projects;
    }
//--------------------------------
    public static function getMyTables($user)
    {
        $db = DB::getConnection();
        $st = $db->prepare('SELECT * FROM tables WHERE id_user=:id_user');
        $st->execute(['id_user' => $user->id]);

        $row = $st->fetch();

        $o2_table = $row['o2_table'];

        $o2 = [];
        $co2 = [];

        $co2_table = $row['co2_table'];

        while(strlen($o2_table) >= 4)
        {
            $vrijeme1 = substr($o2_table, 0, 4);
            $vrijeme2 = substr($co2_table, 0, 4);
            $o2[] = $vrijeme1;
            $co2[] = $vrijeme2;
            $o2_table = substr($o2_table, 4);
            $co2_table = substr($co2_table, 4);
        }

        return [$o2, $co2];
    }

    public static function changeMyTables($user, $o2_breathe, $o2_hold, $co2_breathe, $co2_hold)
    {
        $db = DB::getConnection();

        $o2_breathe_minutes = floor($o2_breathe / 60);
        $o2_breathe_seconds = $o2_breathe % 60;
        $o2_breathe_string = $o2_breathe_minutes . ':' . sprintf('%02d', $o2_breathe_seconds);
        $o2_hold_minutes = floor($o2_hold / 60);
        $o2_hold_seconds = $o2_hold % 60;
        $o2_hold_string = $o2_hold_minutes . ':' . sprintf('%02d', $o2_hold_seconds);
        $o2_string = $o2_breathe_string . $o2_hold_string;

        $st = $db->prepare('UPDATE tables SET o2_table=:o2_table WHERE id_user=:id_user');

        $val1 = $st->execute(['id_user' => $user->id, 'o2_table' => str_repeat($o2_string, 3)]);

        $co2_breathe_minutes = floor($co2_breathe / 60);
        $co2_breathe_seconds = $co2_breathe % 60;
        $co2_breathe_string = $co2_breathe_minutes . ':' . sprintf('%02d', $co2_breathe_seconds);
        $co2_hold_minutes = floor($co2_hold / 60);
        $co2_hold_seconds = $co2_hold % 60;
        $co2_hold_string = $co2_hold_minutes . ':' . sprintf('%02d', $co2_hold_seconds);
        $co2_string = $co2_breathe_string . $co2_hold_string;

        $st = $db->prepare('UPDATE tables SET co2_table=:co2_table WHERE id_user=:id_user');

        $val2 = $st->execute(['id_user' => $user->id, 'co2_table' => str_repeat($co2_string, 3)]);
        return $val1 && $val2;
    }

    public static function getMyTrainings($user, $type)
    {
        $db = DB::getConnection();
        $st = $db->prepare('SELECT * FROM trainings WHERE id_user=:id_user and type=:type');
        $st->execute(['id_user' => $user->id, 'type' => $type]);

        $row = $st->fetch();

        $trainings = [];

        while($row = $st->fetch())
        {
            $trainings[] = new Training($row['id'], $row['id_user'], $row['type'], $row['duration'], $row['date']);
        }

        return $trainings;
    }

    public static function getMyTrainingDatesAndDurations($user, $type)
    {
        $db = DB::getConnection();
        $st = $db->prepare('SELECT * FROM trainings WHERE id_user=:id_user and type=:type');
        $st->execute(['id_user' => $user->id, 'type' => $type]);

        $row = $st->fetch();

        $trainings = [];

        while($row = $st->fetch())
        {
            $trainings[] = [$row['date'], $row['duration']];
        }

        return $trainings;
    }

    public static function getMyBestTime($user, $type)
    {
        $db = DB::getConnection();
        $st = $db->prepare('SELECT * FROM trainings WHERE id_user=:id_user and type=:type');
        $st->execute(['id_user' => $user->id, 'type' => $type]);

        $row = $st->fetch();

        $duration = 0;

        while($row = $st->fetch())
        {
            if($row['duration'] > $duration) $duration = $row['duration'];
        }

        return $duration;
    }

    public static function getProjectByID($id_project)
    {
        $db = DB::getConnection();
        $st = $db->prepare('SELECT * FROM products WHERE id=:id');
        $st->execute(['id' => $id_project]);

        $row = $st->fetch();

        return new Project($row['id'], $row['id_user'], $row['title'], $row['abstract'], $row['number_of_members'], $row['status']);   
    }

    public static function getMyInvitations($id_user)
    {
        $db = DB::getConnection();
        $st = $db->prepare('SELECT * FROM members WHERE id_user=:id_user');
        $st->execute(['id_user' => $id_user]);

        $row = $st->fetch();

        $invitations = [];

        while($row = $st->fetch())
        {
            if($row['member_type'] == 'invitation_pending' || $row['member_type'] == 'invitation_accepted' || $row['member_type'] == 'invitation_rejected')
                $invitations[] = new Member($row['id'], $row['id_project'], $row['id_user'], $row['member_type']);
        }

        return $invitations;
    }

    public static function getMyApplications($id_user)
    {
        $db = DB::getConnection();
        $st = $db->prepare('SELECT * FROM members WHERE id_user=:id_user');
        $st->execute(['id_user' => $id_user]);

        $row = $st->fetch();

        $applications = [];

        while($row = $st->fetch())
        {
            if($row['member_type'] == 'application_pending' || $row['member_type'] == 'application_accepted' || $row['member_type'] == 'application_rejected')
                $applications[] = new Member($row['id'], $row['id_project'], $row['id_user'], $row['member_type']);
        }

        return $applications;
    }

    public static function getProjectMembersByID($id_project)
    {
        $db = DB::getConnection();
        $st = $db->prepare('SELECT * FROM members WHERE id_project=:id_project');
        $st->execute(['id_project' => $id_project]);
        
        $project = FreeDivingService::getProjectByID($id_project);

        $user = FreeDivingService::getUserByID($project->id_user);

        $memberlist = [];
        while($row = $st->fetch())
        {
            if($row['member_type'] === 'member' || $row['member_type'] === 'invitation_accepted' || $row['member_type'] === 'application_accepted')
            {
                $id_member = $row['id_user'];
                if($id_member !== $user->id)
                    $memberlist[] = FreeDivingService::getUserByID($id_member);
            }
        }

        return $memberlist;
    }

    public static function isMemberAppliedInvitedTo($id_user, $id_project)
    {
        $db = DB::getConnection();
        $st = $db->prepare('SELECT * FROM members WHERE id_project=:id_project');
        $st->execute(['id_project' => $id_project]);
        
        while($row = $st->fetch())
        {
            if($row['member_type'] === 'member' || $row['member_type'] === 'invitation_accepted' || $row['member_type'] === 'application_accepted'  || $row['member_type'] === 'application_pending'  || $row['member_type'] === 'invitation_pending'  || $row['member_type'] === 'invitation_rejected'  || $row['member_type'] === 'application_rejected')
            {
                if($row['id_user'] === $id_user)
                    return True;
            }
        }
        return False;
    }

    public static function projectFull($id_project)
    {
        
        $db = DB::getConnection();
        $st = $db->prepare('SELECT * FROM members WHERE id_project=:id_project');
        $st->execute(['id_project' => $id_project]);
        
        $broj_clanova = 0;
        while($row = $st->fetch())
        {
            if($row['member_type'] === 'member' || $row['member_type'] === 'invitation_accepted' || $row['member_type'] === 'application_accepted')
                ++$broj_clanova;
        }

        $project = FreeDivingService::getProjectByID($id_project);
        $dopusteni_broj_clanova = $project->number_of_members;

        return $broj_clanova >= $dopusteni_broj_clanova;
    }

    public static function closeProject($id_project)
    {
        $db = DB::getConnection();
        $st = $db->prepare('UPDATE products SET status=:status WHERE id=:id_project');

        $st->execute(['status' => 'closed', 'id_project' => $id_project]);
    }

    public static function closeProjectIfNeeded($id_project)
    {
        $db = DB::getConnection();
        $st = $db->prepare('SELECT * FROM members WHERE id_project=:id_project');

        $st->execute(['id_project' => $id_project]);

        $koliko = 0;
        while($row = $st->fetch())
        {
            if($row['member_type'] === 'member' || $row['member_type'] === 'invitation_accepted' || $row['member_type'] === 'application_accepted')
                $koliko++;
        }

        $st = $db->prepare('SELECT * FROM projects WHERE id=:id_project');
        $st->execute(['id_project' => $id_project]);
        $row = $st->fetch();

        $max_number_of_members = $row['number_of_members'];
        if($koliko >= $max_number_of_members)
        {
            FreeDivingService::closeProject($id_project);
        }
    }

    public static function addAuthorAsMember($id_user, $id_project)
    {
        $db = DB::getConnection();
        $st = $db->prepare('INSERT INTO members (id_project, id_user, member_type) VALUES (:id_project, :id_user, :member_type)');

        $st->execute(['id_project' => $id_project, 'id_user' => $id_user, 'member_type' => 'member']);
    }

    public static function findAddedProjectID($id_user)
    {
        $db = DB::getConnection();
        
        
        $st = $db->prepare('SELECT * FROM members WHERE id_user=:id_user');
        
        $st->execute(['id_user' => $id_user]);
        
        $project_ids = [];
        while($row = $st->fetch())
        {
            $project_ids[] = $row['id_project'];
        }
        
        $st = $db->prepare('SELECT * FROM projects WHERE id_user=:id_user');
        $st->execute(['id_user' => $id_user]);

        $project_id = null;

        while($row = $st->fetch())
        {
            if(!in_array($row['id'], $project_ids))
            {
                $project_id = $row['id'];
            }
        }

        return $project_id;
    }

    public static function createProject($id_user, $project_title, $project_abstract, $project_number_of_members)
    {
        $db = DB::getConnection();
        $st = $db->prepare('INSERT INTO projects (id_user, title, abstract, number_of_members, status) VALUES (:id_user, :title, :abstract, :number_of_members, :status)');

        $success = False;
        if($project_number_of_members > 1)
            $success = $st->execute(['id_user' => $id_user, 'title' => $project_title, 'abstract' => $project_abstract, 'number_of_members' => $project_number_of_members, 'status' => 'open']);
        else
            $success = $st->execute(['id_user' => $id_user, 'title' => $project_title, 'abstract' => $project_abstract, 'number_of_members' => $project_number_of_members, 'status' => 'closed']);
        
        if($success)
        {
            $added_project_id = FreeDivingService::findAddedProjectID($id_user);
            FreeDivingService::addAuthorAsMember($id_user, $added_project_id);
        }
        return $success;
    }

    public static function applyToProject($id_user, $id_project)
    {
        $db = DB::getConnection();
        $st = $db->prepare('INSERT INTO members (id_project, id_user, member_type) VALUES (:id_project, :id_user, :member_type)');
        $st->execute(['id_project' => $id_project, 'id_user' => $id_user, 'member_type' => 'application_pending']);
    }

    public static function acceptInvitation($id_user, $id_project)
    {
        $db = DB::getConnection();
        $st = $db->prepare('UPDATE members SET member_type=:member_type WHERE id_project=:id_project AND id_user=:id_user');

        $st->execute(['member_type' => 'invitation_accepted', 'id_project' => $id_project, 'id_user' => $id_user]);

        FreeDivingService::closeProjectIfNeeded($id_project);
    }

    public static function rejectInvitation($id_user, $id_project)
    {
        $db = DB::getConnection();
        $st = $db->prepare('UPDATE members SET member_type=:member_type WHERE id_project=:id_project AND id_user=:id_user');
        $st->execute(['member_type' => 'invitation_rejected', 'id_project' => $id_project, 'id_user' => $id_user]);
    }

    public static function inviteMember($id_invited_user, $id_project)
    {
        $db = DB::getConnection();
        $st = $db->prepare('SELECT * FROM members WHERE id_project=:id_project AND id_user=:id_user AND (member_type=:member_type1 OR member_type=:member_type2 OR member_type=:member_type3 OR member_type=:member_type4)');
        $st->execute(['member_type1' => 'invitation_pending', 'member_type2' => 'invitation_rejected',  'member_type3' => 'application_pending', 'member_type4' => 'application_rejected', 'id_project' => $id_project, 'id_user' => $id_invited_user]);

        if(!$st->fetch())
        {
            $st = $db->prepare('INSERT INTO members (id_project, id_user, member_type) VALUES (:id_project, :id_user, :member_type)');
            $st->execute(['id_project' => $id_project, 'id_user' => $id_invited_user,  'member_type' => 'invitation_pending']);
            return True;
        }
        else
        {
            return False;
        }
    }

    public static function getProjectApplicationsByID($id_project)
    {
        $db = DB::getConnection();
        $st = $db->prepare('SELECT * FROM members WHERE id_project=:id_project');
        $st->execute(['id_project' => $id_project]);
        
        $applications = [];
        while($row = $st->fetch())
        {
            if($row['member_type'] === 'application_pending')
                $applications[] = [new Member($row['id'], $row['id_project'], $row['id_user'], $row['member_type']), FreeDivingService::getUserByID($row['id_user'])];
        }

        return $applications;
    }

    public static function acceptApplication($id_user, $id_project)
    {
        $db = DB::getConnection();
        $st = $db->prepare('UPDATE members SET member_type=:member_type WHERE id_project=:id_project AND id_user=:id_user');

        $st->execute(['member_type' => 'application_accepted', 'id_project' => $id_project, 'id_user' => $id_user]);

        FreeDivingService::closeProjectIfNeeded($id_project);
    }

    public static function rejectApplication($id_user, $id_project)
    {
        $db = DB::getConnection();
        $st = $db->prepare('UPDATE members SET member_type=:member_type WHERE id_project=:id_project AND id_user=:id_user');
        $st->execute(['member_type' => 'application_rejected', 'id_project' => $id_project, 'id_user' => $id_user]);
    }

    public static function addUser($user)
    {
        $db = DB::getConnection();
        $st = $db->prepare('INSERT INTO users (username, password_hash, email, registration_sequence, has_registered, admin) VALUES
            (:username, :password_hash, :email, :registration_sequence, :has_registered, :admin)');
        $st->execute(['username' => $user->username, 'password_hash' => $user->password_hash, 'email' => $user->email, 'registration_sequence' => $user->registration_sequence, 'has_registered' => $user->has_registered, 'admin' => $user->admin]);
    }

    public static function processLogout()
    {
        if(isset($_SESSION))
        {
            session_unset();
            session_destroy();
        }
    }

    // public static function getAllProjects()
    // {
    //     $projects = [];
    //     $users = [];
    //     $db = DB::getConnection();

    //     $st = $db->prepare('SELECT * FROM products');

    //     $st->execute([]);

    //     while($row = $st->fetch())
    //     {
    //         $project = new Project($row['id'], $row['id_user'], $row['title'], $row['abstract'], $row['number_available'], $row['status']);
    //         $username = FreeDivingService::getUserByID($row['id_user'])->username;
    //         $projects[] = [$project, $username];
    //     }

    //     return $projects;
    // }

    public static function isAdminByName($username)
    {
        $db = DB::getConnection();

        $st = $db->prepare('SELECT * FROM users WHERE username=:username');

        $st->execute(['username' => $username]);

        $row = $st->fetch();

        return $row['admin'];
    }

    public static function getAllUsers()
    {
        $users = [];
        $db = DB::getConnection();

        $st = $db->prepare('SELECT * FROM users');

        $st->execute([]);

        while($row = $st->fetch())
        {
            $users[] = [$row['id'], $row['username']];
        }
        return $users;
    }

    public static function getAllAdmins()
    {
        $admins = [];
        $db = DB::getConnection();

        $st = $db->prepare('SELECT * FROM users WHERE admin=:admin');

        $st->execute(['admin' => 1]);

        while($row = $st->fetch())
        {
            $admins[] = [$row['id'], $row['username']];
        }
        return $admins;
    }

    public static function deleteUserByID($id)
    {
        $db = DB::getConnection();

        $del = $db->prepare('DELETE FROM users WHERE id=:id');

        $del->execute(['id' => $id]);

        $count = $del->rowCount();

        $st = $db->prepare('DELETE FROM products WHERE id_user=:id');

        $st->execute(['id' => $id]);

        $st = $db->prepare('DELETE FROM tables WHERE id_user=:id');

        $st->execute(['id' => $id]);

        $st = $db->prepare('DELETE FROM trainings WHERE id_user=:id');

        $st->execute(['id' => $id]);

        return $count;
    }

    public static function makeUserAdminByID($id)
    {
        $db = DB::getConnection();

        $st = $db->prepare('SELECT * FROM users WHERE id=:id');

        $st->execute(['id' => $id]);

        $row = $st->fetch();

        if(!$row || $row['admin'] === '1')
            return false;

        $st = $db->prepare('UPDATE users SET admin=:admin WHERE id=:id');

        return $st->execute(['id' => $id, 'admin' => 1]);
    }

    public static function getUserByRegistrationSequence($sequence)
    {
        $db = DB::getConnection();

        $st = $db->prepare('SELECT * FROM users WHERE registration_sequence=:registration_sequence');

        $st->execute(['registration_sequence' => $sequence]);

        $row = $st->fetch();

        return FreeDivingService::getUserByID($row['id']);
    }

    public static function addHasRegistered($user)
    {
        $db = DB::getConnection();

        $st = $db->prepare('UPDATE users SET has_registered=:has_registered WHERE id=:id_user');

        $st->execute(['has_registered' => 1, 'id_user' => $user->id]);

        $default_table = '1:001:001:001:001:001:00';

        $st = $db->prepare( 'INSERT INTO tables (id_user, o2_table, co2_table) VALUES (:id_user, :o2_table, :co2_table)' );

        $st->execute(['id_user' => $user->id, 'o2_table' => $default_table, 'co2_table' => $default_table]);
    }

    public static function convertTimesToDuration($times)
    {
        $duration = 0;
        while(strlen($times) >= 4)
        {
            $time_string = substr($times, 0, 4);
            $time_array = explode(':', $time_string);
            $duration = $duration + 60 * (int)$time_array[0] + (int)$time_array[1];

            $times = substr($times, 4);
        }
        return $duration;
    }

    public static function addO2Training($user, $duration)
    {
        $db = DB::getConnection();
        $st = $db->prepare('INSERT INTO trainings (id_user, type, duration, date) VALUES
            (:id_user, :type, :duration, CURDATE())');
        return $st->execute(['id_user' => $user->id, 'type' => 'o', 'duration' => $duration]);
    }

    public static function addCO2Training($user, $duration)
    {
        $db = DB::getConnection();
        $st = $db->prepare('INSERT INTO trainings (id_user, type, duration, date) VALUES
            (:id_user, :type, :duration, CURDATE())');
        return $st->execute(['id_user' => $user->id, 'type' => 'c', 'duration' => $duration]);
    }

    public static function addOneBreathTraining($user, $duration)
    {
        $db = DB::getConnection();
        $st = $db->prepare('INSERT INTO trainings (id_user, type, duration, date) VALUES
            (:id_user, :type, :duration, CURDATE())');
        return $st->execute(['id_user' => $user->id, 'type' => 'b', 'duration' => $duration]);
    }

    public static function processLoginOrRegister()
    {
        // echo 'u processloginorregister smo prije return</br>';
        
        // Provjeri sastoji li se ime samo od slova; ako ne, crtaj login formu.
		if( !isset( $_POST['username'] ) || !isset($_POST['password'])// || !preg_match( '/^[a-zA-Z ,-.]+$/', $_POST['username'] )
        )
		{
            require_once __DIR__ . '/../view/login_index.php';
			return False;
		}
        
        // echo 'u processloginorregister smo nakon return</br>';
        if(isset($_POST['login']))
        {
            // echo 'login set';
            // return FreeDivingService::getAllProjects();
    
            // require_once __DIR__ . '/../view/projects_index.php';
    
            // Sve je OK, provjeri jel ga ima u bazi.
            $db = DB::getConnection();
    
            try
            {
                $st = $db->prepare( 'SELECT * FROM users WHERE username=:username' );
                $st->execute( array( 'username' => $_POST["username"] ) );
            }
            catch( PDOException $e ) { require_once __DIR__ . '/../view/login_index.php'; return False; }
    
            $row = $st->fetch();
    
            if( $row === false )
            {
                // Taj user ne postoji, upit u bazu nije vratio ništa.
                require_once __DIR__ . '/../view/login_index.php';
                return False;
            }
            else
            {
                // Postoji user. Dohvati hash njegovog passworda.
                $hash = $row[ 'password_hash'];
                $id_user = $row['id'];
                // Da li je password dobar?
                if( password_verify( $_POST['password'], $hash ) )
                {
                    // Dobar je. Ulogiraj ga.
                    //crtaj_uspjesnoUlogiran( $_POST['username' ] );
                    $_SESSION['login'] = $_POST['username'] . ',' . $hash . ',' . $id_user;
                    $_SESSION['username'] = $_POST['username'];
                    $_SESSION['admin'] = FreeDivingService::isAdminByName($_POST['username']);
                    // require_once __DIR__ . '/../freediving.php?rt=tables/index';//&id_user=' . $id_user;
                    // return FreeDivingService::getAllProjects();/*?rt=products/index*/
                    return True;
                }
                else
                {
                    // Nije dobar. Crtaj opet login formu s pripadnom porukom.
                    require_once __DIR__ . '/../view/login_index.php';
                    return False;
                }
            }
            // return FreeDivingService::getAllProjects();
        }
        else
        {
            // echo 'register post';
            $email = $_POST["username"] ?? null;
            // Koristimo built-in funkcionalnosti da se riješimo eventualnog smeća u unosu.
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            // $username = $_POST["username"] ?? null;
            $username = strtok($_POST["username"], '@') ?? null;

            // provjera postoji li već taj user
            $db = DB::getConnection();
    
            try
            {
                $st = $db->prepare( 'SELECT * FROM users WHERE username=:username' );
                $st->execute( array( 'username' => $username ) );
            }
            catch( PDOException $e ) { require_once __DIR__ . '/../view/login_index.php'; return False; }
    
            $row = $st->fetch();
    
            if( !($row === false) )
            {
                echo 'Existing username!'; 
                // Taj user ne postoji, upit u bazu nije vratio ništa.
                require_once __DIR__ . '/../view/login_index.php';
                return False;
            }
            else
            {
                $password = $_POST["password"] ?? null;
                if (!$email || !$username || !$password)
                {
                    $_SESSION["registerErrorMessage"] = "Enter all the fields!";
                    // header('Location: ' . __SITE_URL .'/hotels');
                }
                // elseif (User::where("username", $username))
                // {
                //     $_SESSION["registerErrorMessage"] = "Username already exists!";
                    // header('Location: ' . __SITE_URL .'/hotels');
                // }
                else
                {
                    // echo 'tusmo';
                    $link = '<a href = "http://' . $_SERVER["HTTP_HOST"] . __SITE_URL . "/freediving.php?rt=login/finishRegistration&sequence=";
                    $sequence = "";

                    // U svrhu sigurnosti, niz za potvrdu registracije generira se nasumično.
                    for ($i = 0; $i < 3; $i++) $sequence .= chr(random_int(97, 122));
                    $link .= $sequence . '">link</a>';

                    $user = new User(-1, $username, password_hash($password, PASSWORD_DEFAULT), $email, $sequence, 0, 0);

                    FreeDivingService::addUser($user);
                    $subject = "Registration for freeDiving";
                    $body = "Click on the following " . $link . " to finish your registration for freeDiving by dels!";
                    $headers = "Content-type: text/html\r\n";
                    $headers .= "To: " . $email . "\r\n";
                    $headers .= 'From: freeDiving <dels@freediving.com>' . "\r\n";
                    if (mail($email, $subject, $body, $headers))
                    {
                        echo "Check your mail to finish registration and join us!";
                        return;
                    } else "Something's wrong: " . var_dump(error_get_last());
                }
            }
        }
    }
}

?>
