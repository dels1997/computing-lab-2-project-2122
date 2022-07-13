<?php require_once __DIR__ . '/_header.php'; ?>

<?php    
    echo '<div class="custom-form">';

    echo '<div class="field"><label for="o2-breathe" class="label-custom">O2 breathe</label>';
    echo '<input type="number" id="o2-breathe" class="input1" name="o2-breathe" min="1" max="599" required></div>';

    echo '<div class="field"><label for="o2-hold" class="label-custom">O2 hold</label>';
    echo '<input type="number" id="o2-hold" class="input1" name="o2-hold" min="1" max="599" required></div>';

    echo '<div class="field"><label for="co2-breathe" class="label-custom">CO2 breathe</label>';
    echo '<input type="number" id="co2-breathe" class="input1" name="co2-breathe" min="1" max="599" required></div>';

    echo '<div class="field"><label for="co2-hold" class="label-custom">CO2 hold</label>';
    echo '<input type="number" id="co2-hold" class="input1" name="co2-hold" min="1" max="599" required></div></br></br>';

    echo '<button id="edit" class="btn btn-white btn-big" name="edit">Edit my tables!</button>';

    echo '<div id="u" style="display: none;">' . $_SESSION['username'] . '</div>';

    echo '</div>';

    echo '<div id="notification" style="display: none;">' .
        '<span class="dismiss">X</span>' .
        '</div>';
?>

<script type="text/javascript">
$(document).ready(function()
{
    $('#edit').on('click', function()
    {
        $.ajax(
        {
            url: "model/tables_edit_server.php",
            data:
            {
                o2_breathe: $('#o2-breathe').val(),
                o2_hold: $('#o2-hold').val(),
                co2_breathe: $('#co2-breathe').val(),
                co2_hold: $('#co2-hold').val(),
                username: $('#u').html()
            },
            type: "POST",
            dataType: "json", // očekivani povratni tip podatka
            success: function( json ) {
                $('#notification').html('');
                $('#o2-breathe').val(''); $('#o2-hold').val(''); $('#co2-breathe').val(''); $('#co2-hold').val('');
                if(json['val'])
                {
                    $("#notification").fadeIn("slow").append('Tables updated successfully!');
                    $("#notification").click(function() {
                        $("#notification").fadeOut("slow");
                        $('#notification').html('');
                    });
                }
                else
                {
                    $("#notification").fadeIn("slow").append('Tables NOT updated!');
                    $("#notification").click(function() {
                        $("#notification").fadeOut("slow");
                        $('#notification').html('');
                    });
                }
            },
            error: function( xhr, status, errorThrown ) { console.log(errorThrown); },
            complete: function( xhr, status ) {  }
        });
    });
});
</script>

<?php require_once __DIR__ . '/_footer.php'; ?>