<?php require_once __DIR__ . '/_header.php'; ?>

<div class="okolina" id="okolina" style="width: 500px;">
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
            echo '<tr class="product" id="product-row-' . $productInfo[4] . '">';
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
            echo '<tr class="product" id="product-row-' . $productInfo[4] . '">';
            echo '<td>' .  $productInfo[0] . '</td><td>' . $productInfo[1] . '</td><td>' . $productInfo[2] . '</td><td>' . $productInfo[3] . '</td>';
            echo '</tr>';
        }
        echo '</table>';

        echo '<table class="styled-table" id="bought-products" style="display: none;">';
        echo '<thead><th>Name</th><th>Description</th><th>Price</th><th>Comment</th></thead>';
        foreach($boughtProductsInfo as $productInfo)
        {
            echo '<tr id="product-row-' . $productInfo[4] . '">';
            echo '<td class="product" id="product-row-' . $productInfo[4] . '">'  . $productInfo[0] . '</td><td class="product" id="product-row-' . $productInfo[4] . '">' . $productInfo[1] . '</td><td class="product" id="product-row-' . $productInfo[4] . '">' . $productInfo[2] . '</td>';
            if($productInfo[5][0])
                echo '<td><button id="comment-product-' . $productInfo[4] . '" class="comment-btn btn btn-white">Add comment</button>';
            else
                echo '<td>' . $productInfo[5][1] . '</td>';
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

    $('.product').on('click', function() {
        let ovaj = $(this).attr('id');
        let id_product = $(this).attr('id').split('-')[2];

        $.ajax(
        {
            url: "model/product_info_server.php",
            data:
            {
                id_product: id_product
                // ,
                // username: $('#u').html()
            },
            type: "GET",
            dataType: "json", // očekivani povratni tip podatka
            success: function( json ) {
                $('.product-info').remove();
                comments_and_ratings_array = json['comments_and_ratings'];
                let new_element = $('<div class="product-info" style="float: left;" id="product-info-' + id_product + '">');

                if(comments_and_ratings_array.length === 0)
                {
                    new_element.append($('<p>No comments yet!</p>'));
                }
                else
                {
                    new_element.append($('<p>Comments:</p>'));
                    for(let i = 0; i < comments_and_ratings_array.length; ++i)
                    {
                        new_element.append($('<p>' + comments_and_ratings_array[i][0] + '</p><p>' + comments_and_ratings_array[i][1] + '</p>'));
                    }
                    rating = json['rating'];
                    new_element.append($('<p>Rating: ' + rating + '</p>'));
                }
                $('#okolina').append(new_element);
            },
            error: function( xhr, status, errorThrown ) { console.log(errorThrown); },
            complete: function( xhr, status ) {  }
        });
    });

    $('.comment-btn').on('click', function() {
        $('.comment-area').remove();

        id_product = $(this).attr('id').split('-')[2];
        console.log(id_product);

        let new_element = $('<div class="comment-area" style="float: left;" id="comment-area-' + id_product + '">');

        let div = $('<div class="field-custom">');
        div.append($('<input type="text" name="comment" id="comment" class="input-custom" placeholder="">'));
        div.append($('<label for="comment" class="label1">Comment</label>'));
        new_element.append(div);
        div = $('<div class="field-custom">');
        div.append($('<input type="number" name="rating" id="rating" min=1 max=5 class="input-custom" placeholder="">'));
        div.append($('<label for="rating" class="label1">Rating</label>'));
        new_element.append(div);
        let add_button = $();
        new_element.append('<button id="send" class="btn btn-white btn-big">Send!');
        $('#okolina').append(new_element);
        
        $('#send').on('click', function() {
            $('.product-info').remove();
            $.ajax(
            {
                url: "model/add_comment_server.php",
                data:
                {
                    id_product: id_product,
                    username: $('#u').html(),
                    comment: $('#comment').val(),
                    rating: $('#rating').val()
                },
                type: "GET",
                dataType: "json", // očekivani povratni tip podatka
                success: function( json ) {
                    let product_row = $('#comment-product-' + id_product).parent().parent().children().eq(4);
                    product_row.html(json['comment']);
                    $('#notification').html('');

                    $('.comment-area').remove();

                    if(json['val'])
                    {
                        $('#product-' + id_product).parent().html('');
                        $("#notification").fadeIn("slow").append('Comment added successfully!');
                        $("#notification").click(function() {
                            $("#notification").fadeOut("slow");
                            $('#notification').html('');
                        });
                    }
                    else
                    {
                        $("#notification").fadeIn("slow").append('Commend NOT added!');
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
});

function show_all()
{
    $('#all-products').css('display', 'block');
    $('#my-products').css('display', 'none');
    $('#bought-products').css('display', 'none');
    $('.product-info').remove();
}
function show_my()
{
    $('#all-products').css('display', 'none');
    $('#my-products').css('display', 'block');
    $('#bought-products').css('display', 'none');
    $('.product-info').remove();
}
function show_bought()
{
    $('#all-products').css('display', 'none');
    $('#my-products').css('display', 'none');
    $('#bought-products').css('display', 'block');
    $('.product-info').remove();
}
</script>
<?php require_once __DIR__ . '/_footer.php'; ?>