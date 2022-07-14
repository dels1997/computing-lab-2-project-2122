<?php require_once __DIR__ . '/_header.php'; ?>

<div class="okolina" style="width: 500px; height: 100%;">
    
    <div class="two-button-frame" style="float: left; display: block; width: max-content; margin-bottom: 3rem;">
        <button name="o2-btn" id="o2-btn" class="btn btn-big btn-white">O2 table</button>
        <button name="co2-btn" id="co2-btn" class="btn btn-big btn-white">CO2 table</button>
    </div>
    <div id="notification" style="display: none;">
        <span class="dismiss">X</span>
    </div>
    <div id="stopwatch" class="stopwatch btn btn-white btn-big" style="display: none; float: left; margin: 0% 7.5%;">
            00:00:00
    </div>

    <div id="wrapper" style="display: flex;width: 500px;justify-content: space-around; float:right;flex-direction: row;flex-wrap: nowrap;align-content: center;align-items: center;">
        <table id="o2-table" class="styled-table" style="display: none; height: 100%; width: 35%; float: left;">
        <thead><tr><th>Hold</th><th>Breathe</th></tr></thead>
        <?php

        $o2 = $tables[0];
        $i = 0;
        foreach($o2 as $time)
        {
            if($i % 2 === 0) echo '<tr>';
            echo '<td id="td-o' . $i . '">' . $time . '</td>';
            if($i % 2 === 1) echo '</tr>';
            ++$i;
        }
        ?>
        </table>

        <table id="co2-table" class="styled-table" style="display: none; height: 100%; width: 35%; float: left;">
        <thead><tr><th>Hold</th><th>Breathe</th></tr></thead>
        <?php
        $co2 = $tables[1];
        $i = 0;
        foreach($co2 as $time)
        {
            if($i % 2 === 0) echo '<tr>';
            echo '<td id="td-co' . $i . '">' . $time . '</td>';
            if($i % 2 === 1) echo '</tr>';
            ++$i;
        }
        ?>
        </table>
    

        <div id="upravljanje" style="display: none; float: right; margin: 5% 7.5%;">
            <button id="btn-play" style="width: 2rem; height: 2rem;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path d="M361 215C375.3 223.8 384 239.3 384 256C384 272.7 375.3 288.2 361 296.1L73.03 472.1C58.21 482 39.66 482.4 24.52 473.9C9.377 465.4 0 449.4 0 432V80C0 62.64 9.377 46.63 24.52 38.13C39.66 29.64 58.21 29.99 73.03 39.04L361 215z"/></svg></button>
            </br></br><button id="btn-pause" style="width: 2rem; height: 2rem;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M272 63.1l-32 0c-26.51 0-48 21.49-48 47.1v288c0 26.51 21.49 48 48 48L272 448c26.51 0 48-21.49 48-48v-288C320 85.49 298.5 63.1 272 63.1zM80 63.1l-32 0c-26.51 0-48 21.49-48 48v288C0 426.5 21.49 448 48 448l32 0c26.51 0 48-21.49 48-48v-288C128 85.49 106.5 63.1 80 63.1z"/></svg></button>
            </br></br><button id="btn-stop" style="width: 2rem; height: 2rem;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path d="M384 128v255.1c0 35.35-28.65 64-64 64H64c-35.35 0-64-28.65-64-64V128c0-35.35 28.65-64 64-64H320C355.3 64 384 92.65 384 128z"/></svg></button>
    </div>
    
    <audio id="breathe" style="display: none;"><source type="audio/ogg" src="sounds/breathe.mp3"></audio>
    <audio id="hold" style="display: none;"><source type="audio/ogg" src="sounds/hold.mp3"></audio>
    <?php echo '<div id="u" style="display: none;">' . $_SESSION['username'] . '</div>'; ?>
</div>

<script type="text/javascript">
var o2_times = [];
var co2_times = [];
var o2_times_length;
var co2_times_length;
var timer;
var ms = 0, s = 0, m = 0;
var stopwatchEL = document.querySelector('.stopwatch');
var inputVal = Number.POSITIVE_INFINITY;
var pozicija_timera = 0;
$(document).ready(function()
{
    let body = $('#o2-table').children().eq(1);
    let body_length = body.children().length;
    for(let i = 0; i < body_length; ++i)
    {
        let tr = body.children().eq(i);
        let tr_length = tr.children().length;
        for(let j = 0; j < tr_length; ++j)
        {
            let time_string = tr.children().eq(j).html();
            let time_array = time_string.split(':');
            let temp = 0;
            if(o2_times.length !== 0)
                temp = o2_times.slice(-1);
                o2_times.push(parseInt(time_array[0]) * 60 + parseInt(time_array[1]) + parseInt(temp));
        }
    }
    o2_times_length = o2_times.length;

    body = $('#co2-table').children().eq(1);
    body_length = body.children().length;
    for(let i = 0; i < body_length; ++i)
    {
        let tr = body.children().eq(i);
        let tr_length = tr.children().length;
        for(let j = 0; j < tr_length; ++j)
        {
            let time_string = tr.children().eq(j).html();
            let time_array = time_string.split(':');
            let temp = 0;
            if(co2_times.length !== 0)
                temp = co2_times.slice(-1);
                co2_times.push(parseInt(time_array[0]) * 60 + parseInt(time_array[1]) + parseInt(temp));
        }
    }
    co2_times_length = co2_times.length;

    $('#o2-btn').on('click', show_o2_table);

    $('#co2-btn').on('click', show_co2_table);

    $('#btn-play').on('click', function()
    {
        $('#o2-btn').off('click');
        $('#co2-btn').off('click');
        start_or_continue_timer();
    });

    $('#btn-pause').on('click', function()
    {
        $('#o2-btn').off('click');
        $('#co2-btn').off('click');
        pause_timer();
    });

    $('#btn-stop').on('click', function()
    {
        $('#o2-btn').on('click', show_o2_table);
        $('#co2-btn').on('click', show_co2_table);
        stop_timer();
    });
});


function start_or_continue_timer()
{
    if(!timer) timer = setInterval(run, 100);
}
function run() {
    stopwatchEL.textContent = getTimer();
    ms += 10;
    
    if(ms === 100) { ms = 0; ++s; }

    if(s === 60) { s = 0; ++m; }
    
    updateTimer();

    if($('#o2-table').css('display') === 'table')
    {
        pozicija_timera = 0;
        let i = 0;
        for( ; i < o2_times.length; ++i)
        {
            if((m * 60 + s) < o2_times[i])
            {
                if(i !== pozicija_timera || i === 0)
                {
                    pozicija_timera = i;
                    oznaci_itu_celiju_u_tablici_o2(i, 'o');
                    break;
                }
            }
        }
        if(((o2_times.includes(m * 60 + s) && ms === 0) || s === 0) && (Math.abs(m * 60 + s - o2_times[o2_times_length - 1]) > 2))
        {
            if(pozicija_timera % 2 === 0)
            {
                $('#hold')[0].play();
            }
            else
            {
                $('#breathe')[0].play();
            }
        }
        if((m * 60 + s) >= o2_times[o2_times_length - 1])
        {
            finish_and_save_training(o2_times[o2_times_length - 1], 'o');
            stop_timer();
            return;
        }
    }
    else if($('#co2-table').css('display') === 'table')
    {
        pozicija_timera = 0;
        let i = 0;
        for( ; i < co2_times.length; ++i)
        {
            if((m * 60 + s) < co2_times[i])
            {
                if(i !== pozicija_timera || i === 0)
                {
                    pozicija_timera = i;
                    oznaci_itu_celiju_u_tablici_co2(i, 'o');
                    break;
                }
            }
        }
        if(((co2_times.includes(m * 60 + s) && ms === 0) || s === 0) && (Math.abs(m * 60 + s - co2_times[co2_times_length - 1]) > 5))
        {
            if(pozicija_timera % 2 === 0)
            {
                $('#hold')[0].play();
            }
            else
            {
                $('#breathe')[0].play();
            }
        }
        if((m * 60 + s) >= co2_times[co2_times_length - 1])
        {
            finish_and_save_training(co2_times[co2_times_length - 1], 'c');
            stop_timer();
            return;
        }
    }
}
function finish_and_save_training(duration, type)
{
    $.ajax(
    {
        url: "model/trainings_add_server.php",
        data:
        {
            duration: duration,
            type: type,
            username: $('#u').html()
        },
        type: "GET",
        dataType: "json", // očekivani povratni tip podatka
        success: function( json ) {
            $('#notification').html('');
            stopwatchEL.textContent = getTimer();
            console.log(json['val']);
            if(json['val'])
            {
                $("#notification").fadeIn("slow").append('Training saved successfully!');
                $("#notification").click(function() {
                    $("#notification").fadeOut("slow");
                    $('#notification').html('');
                });
            }
            else
            {
                $("#notification").fadeIn("slow").append('Training NOT saved!');
                $("#notification").click(function() {
                    $("#notification").fadeOut("slow");
                    $('#notification').html('');
                });
            }
        },
        error: function( xhr, status, errorThrown ) { console.log(errorThrown); },
        complete: function( xhr, status ) {  }
    });
}
function oznaci_itu_celiju_u_tablici_o2(i)
{
    if(i > 0)
        $('#td-o' + (i-1)).css('opacity', '1').css('color', 'white');
    $('#td-o' + i).css('opacity', '0.5').css('color', '#009879');
}
function oznaci_itu_celiju_u_tablici_co2(i)
{
    if(i > 0)
        $('#td-co' + (i-1)).css('opacity', '1').css('color', 'white');
    $('#td-co' + i).css('opacity', '0.5').css('color', '#009879');
}
function pause_timer()
{
    stopTimer();
}
function stop_timer() {
    stopTimer();
    m = 0; ms = 0; s = 0;
    stopwatchEL.textContent = getTimer();
    $('td').each(function() {
        $(this).css('opacity', '1').css('color', 'white');
    });
}
function getTimer() {
    return (m < 10 ? "0" + m:m) + ":" + (s < 10 ? "0" + s:s) + ":" + (ms < 10 ? "0" + ms:ms);
}
function stopTimer() {
    clearInterval(timer);
    timer = false;
}
function updateTimer() {
    if(getTimer() === inputVal) {
        document.getElementById('ado').play();
        pause();
    }
}


function show_o2_table() {
    $('#co2-table').css('display', 'none');
    $('#o2-table').css('display', 'table');
    $('#stopwatch').css('display', 'block');
    $('#upravljanje').css('display', 'block');
}
function show_co2_table() {
    $('#o2-table').css('display', 'none');
    $('#co2-table').css('display', 'table');
    $('#stopwatch').css('display', 'block');
    $('#upravljanje').css('display', 'block');
}
</script>


<?php require_once __DIR__ . '/_footer.php'; ?>