<?php require_once __DIR__ . '/_header.php'; ?>

<div class="two-button-frame">
    <button name="o2-table" id="o2-table" class="btn btn-big btn-white">O2 table</button>
    <button name="co2-table" id="co2-table" class="btn btn-big btn-white">CO2 table</button>
</div>

<table class="styled-table">
</table>

<script type="text/javascript">

var o2_flag = 1;
var co2_flag = 1;
$(document).ready(function()
{

    $('#o2-table').on('click', show_o2_table);

    $('#co2-table').on('click', show_co2_table);

});


function show_o2_table() {
    if(o2_flag)
    {
        let table = $('.styled-table');
        let head = $('<thead>');
        let row = $('<tr>');
        row.append($('<th>Breathe</th>'));
        row.append($('<th>Hold</th>'));
        head.append(row);
        table.append(head);
        row = $('<tr>');
        row.append($('<td>1:00</td>'));
        row.append($('<td>2:00</td>'));
        table.append(row);
        row = $('<tr>');
        row.append($('<td>1:00</td>'));
        row.append($('<td>2:00</td>'));
        table.append(row);
        row = $('<tr>');
        // console.log('red' + head.html());
        console.log(head.html());
        o2_flag = 0;
        co2_flag = 1;

        // var tbEvenRows = document.querySelectorAll(".styled-table tbody tr:nth-child(even)");
        // for ( let i = 0; i < tbEvenRows.length; i++) {

        //     tbEvenRoww[i].style.backgroundColor = "#f3f3f3;";
        // }
        // var tbEvenRows = document.querySelectorAll(".styled-table tbody tr:nth-child(odd)");
        // for ( let i = 0; i < tbEvenRows.length; i++) {

        //     tbEvenRoww[i].style.backgroundColor = "#ffffff;";
        // }
    }
}
function show_co2_table() {
    if(co2_flag)
    {
        co2_flag = 0;
        o2_flag = 1;
    }
}
</script>


<?php require_once __DIR__ . '/_footer.php'; ?>