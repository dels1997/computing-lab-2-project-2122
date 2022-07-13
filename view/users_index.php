<?php require_once __DIR__ . '/_header.php'; ?>

<div class="okolina" style="width: 500px;">
    <div class="two-button-frame" style="float: left; display: block; width: max-content; margin-bottom: 0.5rem;">
        <button name="admins-btn" id="admins-btn" class="btn btn-big btn-white">Admins</button>
        <button name="users-btn" id="users-btn" class="btn btn-big btn-white">Users</button>
    </div>

    <?php
        echo '<table id="table-users" class="styled-table" style="display: none;">';
        echo '<thead><th>User ID</th><th>Username</th><th>Ban</th><th>Promote</th></thead>';
        foreach($users as $user)
        {
            echo '<tr>';
            echo '<td>' . $user[0] . '</td><td>' . $user[1] . '</td>';
            if($user[1] !== $_SESSION['username'])
            {
                echo '<td><button class="btn btn-white delete-user" id="delete-' . $user[0] . '">Delete user</button></td>';
                echo '<td><button class="btn btn-white make-admin" id="makeadmin-' . $user[0] . '">Make admin</button></td>';
            }
            echo '</tr>';
        }
        echo '</table>';

        echo '<table id="table-admins" class="styled-table" style="display: none;">';
        echo '<thead><th>User ID</th><th>Username</th></thead>';
        foreach($admins as $admin)
        {
            echo '<tr>';
            echo '<td id="admin-' . $admin[0] . '">' . $admin[0] . '</td><td>' . $admin[1] . '</td>';
            echo '</tr>';
        }
        echo '</table>';
        echo '<div id="notification" style="display: none;">' .
        '<span class="dismiss">X</span>' .
        '</div>';
    ?>
        
    </div>

</div>

<script type="text/javascript">
$(document).ready(function()
{
    $('#admins-btn').on('click', show_admins);
    
    $('#users-btn').on('click', show_users);

    $('.delete-user').on('click', function(){
        let array = $(this).attr('id').split('-');
        let id = parseInt(array[1]);

        $.ajax(
        {
            url: "model/users_delete_or_make_admin.php",
            data:
            {
                action: 'd',
                id: id
            },
            type: "GET",
            dataType: "json", // očekivani povratni tip podatka
            success: function( json ) {
                $('#notification').html('');
                if(json['val'])
                {
                    console.log('uspjeh')
                    $("#notification").fadeIn("slow").append('User deleted successfully!');
                    $("#notification").click(function() {
                        $("#notification").fadeOut("slow");
                        $('#notification').html('');
                    });
                    let button = $('#delete-' + id);
                    button.parent().parent().remove();
                    let row = $('#admin-' + id);
                    row.parent().remove();
                }
                else
                {
                    $("#notification").fadeIn("slow").append('User NOT deleted!');
                    $("#notification").click(function() {
                        $("#notification").fadeOut("slow");
                        $('#notification').html('');
                    });
                }
            },
            error: function( xhr, status, errorThrown ) { console.log(xhr.responseText); },
            complete: function( xhr, status ) {  }
        });
    });

    $('.make-admin').on('click', function(){
        let array = $(this).attr('id').split('-');
        let id = parseInt(array[1]);

        $.ajax(
        {
            url: "model/users_delete_or_make_admin.php",
            data:
            {
                action: 'a',
                id: id
            },
            type: "GET",
            dataType: "json", // očekivani povratni tip podatka
            success: function( json ) {
                console.log('json' + json);
                $('#notification').html('');
                if(json['val'])
                {
                    $("#notification").fadeIn("slow").append('User promoted successfully!');
                    $("#notification").click(function() {
                        $("#notification").fadeOut("slow");
                        $('#notification').html('');
                    });
                    let table = $('#table-admins');
                    table.append('<tr><td id="admin-' + id + '">' + id + '</td>' + '<td>' + json['username'] + '</td></tr>');
                }
                else
                {
                    $("#notification").fadeIn("slow").append('User NOT promoted!');
                    $("#notification").click(function() {
                        $("#notification").fadeOut("slow");
                        $('#notification').html('');
                    });
                }
            },
            error: function( xhr, status, errorThrown ) { console.log(xhr.responseText); },
            complete: function( xhr, status ) {  }
        });
    });
});

function show_admins() {
    $('#table-users').css('display', 'none');
    $('#table-admins').css('display', 'table');
}
function show_users() {
    $('#table-admins').css('display', 'none');
    $('#table-users').css('display', 'table');
}
</script>


<?php require_once __DIR__ . '/_footer.php'; ?>