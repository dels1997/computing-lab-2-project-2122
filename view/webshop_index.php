<?php require_once __DIR__ . '/_header.php'; ?>

<div class="okolina" style="width: 500px;">
    <div class="two-button-frame" style="float: left; display: block; width: max-content; margin-bottom: 0.5rem;">
        <button name="all-btn" id="all-btn" class="btn btn-big btn-white">All products</button></br>
        <button name="my-btn" id="my-btn" class="btn btn-big btn-white">My products</button></br>
        <button name="bought-btn" id="bought-btn" class="btn btn-big btn-white">Products I bought</button>
    </div>
    <div id="notification" style="display: none;">
        <span class="dismiss">X</span>
    </div>
    
    <?php
        echo '<div id="u" style="display: none;">' . $_SESSION['username'] . '</div>';

        echo '<table class="styled-table" id="all-products" style="display: none;">';
        echo '<thead><th>Name</th><th>Description</th><th>Price</th><th>Owner</th><th>Quantity</th><th>Purchase</th></thead>';
        foreach($allProductsInfo as $productInfo)
        {
            echo '<tr>';
            echo '<td>' .  $productInfo[0] . '</td><td>' . $productInfo[1] . '</td><td>' . $productInfo[2] . '</td><td>' . $productInfo[3] . '</td><td>' . $productInfo[6] . '</td>';
            if($productInfo[5])
                echo '<td><button id="product-' . $productInfo[4] . '" class="buy-btn btn btn-white">Buy!</button>';
            else
                echo '<td></td>';
            echo '</tr>';
        }
        echo '</table>';

        echo '<table class="styled-table" id="my-products" style="display: none;">';
        echo '<thead><th>Name</th><th>Description</th><th>Price</th><th>Quantity</th></thead>';
        foreach($myProductsInfo as $productInfo)
        {
            echo '<tr>';
            echo '<td>' .  $productInfo[0] . '</td><td>' . $productInfo[1] . '</td><td>' . $productInfo[2] . '</td><td>' . $productInfo[3] . '</td>';
            echo '</tr>';
        }
        echo '</table>';

        echo '<table class="styled-table" id="bought-products" style="display: none;">';
        echo '<thead><th>Name</th><th>Description</th><th>Price</th><th>Quantity</th></thead>';
        foreach($boughtProductsInfo as $productInfo)
        {
            echo '<tr>';
            echo '<td>' .  $productInfo[0] . '</td><td>' . $productInfo[1] . '</td><td>' . $productInfo[2] . '</td><td>' . $productInfo[3] . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    ?>    

    <div id="u" style="display: none;"><?php echo $_SESSION['username'];?></div>

</div>

<script type="text/javascript">
$(document).ready(function() {
    
    $('#all-btn').on('click', show_all);
    $('#my-btn').on('click', show_my);
    $('#bought-btn').on('click', show_bought);

    $('.buy-btn').on('click', function() {
        id_product = $(this).attr('id').split('-')[1];
        $.ajax(
        {
            url: "model/buy_product_server.php",
            data:
            {
                id_product: id_product,
                username: $('#u').html()
            },
            type: "GET",
            dataType: "json", // očekivani povratni tip podatka
            success: function( json ) {
                let product_row = $('#product-' + id_product).parent().parent().children();
                product_row.eq(4).html(json['quantity']);
                $('#notification').html('');

                if(json['val'])
                {
                    $('#product-' + id_product).parent().html('');
                    $('#bought-products').append($('<tr><td>' +  product_row.eq(0).html() + '</td><td>' +  product_row.eq(1).html() + '</td><td>' + product_row.eq(2).html() + '</td><td>' + product_row.eq(3).html() + '</td></tr>'));
                    $("#notification").fadeIn("slow").append('Product bought successfully!');
                    $("#notification").click(function() {
                        $("#notification").fadeOut("slow");
                        $('#notification').html('');
                    });
                }
                else
                {
                    $("#notification").fadeIn("slow").append('Product NOT bought!');
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

function show_all()
{
    $('#all-products').css('display', 'block');
    $('#my-products').css('display', 'none');
    $('#bought-products').css('display', 'none');
}
function show_my()
{
    $('#all-products').css('display', 'none');
    $('#my-products').css('display', 'block');
    $('#bought-products').css('display', 'none');
}
function show_bought()
{
    $('#all-products').css('display', 'none');
    $('#my-products').css('display', 'none');
    $('#bought-products').css('display', 'block');
}
</script>
<?php require_once __DIR__ . '/_footer.php'; ?>