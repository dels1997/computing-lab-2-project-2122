<?php require_once __DIR__ . '/_header.php'; ?>

<div class="okolina" style="width: 500px;">
    <div class="two-button-frame" style="float: left; display: block; width: max-content; margin-bottom: 3rem;">
        <button name="o2-btn" id="o2-btn" class="btn btn-big btn-white">O2 table</button>
        <button name="co2-btn" id="co2-btn" class="btn btn-big btn-white">CO2 table</button>
    </div>

    <div id="wrapper" style="display: flex;width: 500px;justify-content: space-around; float:right;flex-direction: row;flex-wrap: nowrap;align-content: center;align-items: center;">
        <table id="o2-table" class="styled-table" style="display: none; height: 100%; width: 35%; float: left;">
        <thead><tr><th>Breathe</th><th>Hold</th></tr></thead>
        <?php

        $o2 = $tables[0];
        $i = 0;
        foreach($o2 as $time)
        {
            if($i % 2 == 0) echo '<tr>';
            echo '<td id="td-o' . $i . '">' . $time . '</td>';
            if($i % 2 == 1) echo '</tr>';
            ++$i;
        }
        ?>
        </table>

        <table id="co2-table" class="styled-table" style="display: none; height: 100%; width: 35%; float: left;">
        <thead><tr><th>Breathe</th><th>Hold</th></tr></thead>
        <?php
        $co2 = $tables[1];
        $i = 0;
        foreach($co2 as $time)
        {
            if($i % 2 == 0) echo '<tr>';
            echo '<td id="td-co' . $i . '">' . $time . '</td>';
            if($i % 2 == 1) echo '</tr>';
            ++$i;
        }
        ?>
        </table>
    

        <div id="upravljanje" style="display: none; float: right; margin: 5% 7.5%;">
            <button style="width: 2rem; height: 2rem;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path d="M361 215C375.3 223.8 384 239.3 384 256C384 272.7 375.3 288.2 361 296.1L73.03 472.1C58.21 482 39.66 482.4 24.52 473.9C9.377 465.4 0 449.4 0 432V80C0 62.64 9.377 46.63 24.52 38.13C39.66 29.64 58.21 29.99 73.03 39.04L361 215z"/></svg></button>
            </br></br><button style="width: 2rem; height: 2rem;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M272 63.1l-32 0c-26.51 0-48 21.49-48 47.1v288c0 26.51 21.49 48 48 48L272 448c26.51 0 48-21.49 48-48v-288C320 85.49 298.5 63.1 272 63.1zM80 63.1l-32 0c-26.51 0-48 21.49-48 48v288C0 426.5 21.49 448 48 448l32 0c26.51 0 48-21.49 48-48v-288C128 85.49 106.5 63.1 80 63.1z"/></svg></button>
            </br></br><button style="width: 2rem; height: 2rem;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path d="M384 128v255.1c0 35.35-28.65 64-64 64H64c-35.35 0-64-28.65-64-64V128c0-35.35 28.65-64 64-64H320C355.3 64 384 92.65 384 128z"/></svg></button>
        </div>
    </div>
</div>

<script type="text/javascript">

var o2_flag = 1;
var co2_flag = 1;
$(document).ready(function()
{

    $('#o2-btn').on('click', show_o2_table);

    $('#co2-btn').on('click', show_co2_table);

});


function show_o2_table() {
    $('#co2-table').css('display', 'none');
    $('#o2-table').css('display', 'table');
    $('#upravljanje').css('display', 'block');
}
function show_co2_table() {
    $('#o2-table').css('display', 'none');
    $('#co2-table').css('display', 'table');
    $('#upravljanje').css('display', 'block');
}
</script>


<?php require_once __DIR__ . '/_footer.php'; ?>