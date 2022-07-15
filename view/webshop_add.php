<?php require_once __DIR__ . '/_header.php'; ?>

<?php    
    echo '<div class="custom-form">';

    echo '<div class="field"><label for="name" class="label-custom">Name</label>';
    echo '<input type="text" id="name" class="input1" name="name" minlength="1" maxlength="20" required></div>';

    echo '<div class="field"><label for="description" class="label-custom">Description</label>';
    echo '<input type="text" id="description" class="input1" name="description" minlength="1" maxlength="100" required></div>';

    echo '<div class="field"><label for="price" class="label-custom">Price</label>';
    echo '<input type="number" id="price" class="input1" name="price" min="1" max="10000" required></div>';

    echo '<div class="field"><label for="number-available" class="label-custom">Number available</label>';
    echo '<input type="number" id="number-available" class="input1" name="number-available" min="1" max="100" required></div>';

    echo '<button id="edit" class="btn btn-white btn-big" name="edit">Add product!</button>';

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
            url: "model/add_product_server.php",
            data:
            {
                name: $('#name').val(),
                description: $('#description').val(),
                price: $('#price').val(),
                number_available: $('#number-available').val(),
                username: $('#u').html()
            },
            type: "POST",
            dataType: "json", // očekivani povratni tip podatka
            success: function( json ) {
                console.log(json);
                $('#notification').html('');
                $('#name').val(''); $('#description').val(''); $('#price').val(''); $('#number-available').val('');
                if(json['val'])
                {
                    $("#notification").fadeIn("slow").append('Product added successfully!');
                    $("#notification").click(function() {
                        $("#notification").fadeOut("slow");
                        $('#notification').html('');
                    });
                }
                else
                {
                    $("#notification").fadeIn("slow").append('Product NOT added!');
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