<?php require_once __DIR__ . '/_header.php'; ?>

<div class="okolina" style="width: 500px; height: 100%; display: flex; justify-content: left; align-items: center;">
    <div class="two-button-frame" style="float: left; display: block; width: max-content; margin-bottom: 3rem; margin-top: 3rem;">
        <button name="o2-btn" id="o2-btn" class="btn btn-big btn-white">One breath mode</button>
    </div>

    <div id="notification" style="display: none;">
        <span class="dismiss">X</span>
    </div>
    <div id="stopwatch" class="stopwatch btn btn-white btn-big" style="display: block; float: left; margin: 7.5% 7.5%;">
            00:00:00
    </div>
    <div id="upravljanje" style="display: block; float: left; margin: 5% 0%;">
        <button id="btn-play" style="width: 2rem; height: 2rem;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path d="M361 215C375.3 223.8 384 239.3 384 256C384 272.7 375.3 288.2 361 296.1L73.03 472.1C58.21 482 39.66 482.4 24.52 473.9C9.377 465.4 0 449.4 0 432V80C0 62.64 9.377 46.63 24.52 38.13C39.66 29.64 58.21 29.99 73.03 39.04L361 215z"/></svg></button>
        </br></br><button id="btn-pause" style="width: 2rem; height: 2rem;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M272 63.1l-32 0c-26.51 0-48 21.49-48 47.1v288c0 26.51 21.49 48 48 48L272 448c26.51 0 48-21.49 48-48v-288C320 85.49 298.5 63.1 272 63.1zM80 63.1l-32 0c-26.51 0-48 21.49-48 48v288C0 426.5 21.49 448 48 448l32 0c26.51 0 48-21.49 48-48v-288C128 85.49 106.5 63.1 80 63.1z"/></svg></button>
        </br></br><button id="btn-stop" style="width: 2rem; height: 2rem;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path d="M384 128v255.1c0 35.35-28.65 64-64 64H64c-35.35 0-64-28.65-64-64V128c0-35.35 28.65-64 64-64H320C355.3 64 384 92.65 384 128z"/></svg></button>
    </div>

    <audio id="breathe" style="display: none;"><source type="audio/ogg" src="sounds/breathe.mp3"></audio>
    <audio id="hold" style="display: none;"><source type="audio/ogg" src="sounds/hold.mp3"></audio>
    <?php echo '<div id="u" style="display: none;">' . $_SESSION['username'] . '</div>'; ?>
</div>
<div class="one-button-frame" style="float: left; display: block; width: max-content; margin-bottom: 1.5rem; margin-top: 0.5rem;">
    <button name="o2-btn" id="o2-btn" class="btn btn-big btn-white">Best time: <?php echo $best_time_onebreath; ?></button>
</div>
<audio id="hold" style="display: none;"><source type="audio/ogg" src="sounds/hold.mp3"></audio>

<?php
for($i = 1; $i < 10; ++$i)
{
    echo '<audio id="minutes-' . $i . '" style="display: none;"><source type="audio/ogg" src="sounds/minutes-' . $i . '.mp3"></audio>';
}
?>

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
    $('#btn-play').on('click', function()
    {
        start_or_continue_timer();
    });

    $('#btn-pause').on('click', function()
    {
        pause_timer();
    });

    $('#btn-stop').on('click', function()
    {
        finish_and_save_training(m * 60 + s, 'b');
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

    let seconds = m * 60 + s;
    let minutes = seconds / 60;
    if(seconds === 0)
        $('#hold')[0].play();
    if(seconds % 60 === 0 && minutes > 0 && minutes < 10)
        $('#minutes-' + minutes)[0].play();
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