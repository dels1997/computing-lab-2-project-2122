<?php require_once __DIR__ . '/_header.php'; ?>

<div class="okolina" style="width: 500px;">
    <div class="two-button-frame" style="float: left; display: block; width: max-content; margin-bottom: 0.5rem;">
        <button name="o2-btn" id="o2-btn" class="btn btn-big btn-white">O2 table history</button>
        <button name="co2-btn" id="co2-btn" class="btn btn-big btn-white">CO2 table history</button>
    </div>

    <div id="control-display" style="display: none; float: left; width: max-content; margin-bottom: 0.5rem;">
        <button id="btn-table-display" style="width: 2rem; height: 2rem;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M88 48C101.3 48 112 58.75 112 72V120C112 133.3 101.3 144 88 144H40C26.75 144 16 133.3 16 120V72C16 58.75 26.75 48 40 48H88zM480 64C497.7 64 512 78.33 512 96C512 113.7 497.7 128 480 128H192C174.3 128 160 113.7 160 96C160 78.33 174.3 64 192 64H480zM480 224C497.7 224 512 238.3 512 256C512 273.7 497.7 288 480 288H192C174.3 288 160 273.7 160 256C160 238.3 174.3 224 192 224H480zM480 384C497.7 384 512 398.3 512 416C512 433.7 497.7 448 480 448H192C174.3 448 160 433.7 160 416C160 398.3 174.3 384 192 384H480zM16 232C16 218.7 26.75 208 40 208H88C101.3 208 112 218.7 112 232V280C112 293.3 101.3 304 88 304H40C26.75 304 16 293.3 16 280V232zM88 368C101.3 368 112 378.7 112 392V440C112 453.3 101.3 464 88 464H40C26.75 464 16 453.3 16 440V392C16 378.7 26.75 368 40 368H88z"/></svg></button>
        <button id="btn-graph-display" style="width: 2rem; height: 2rem;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M64 400C64 408.8 71.16 416 80 416H480C497.7 416 512 430.3 512 448C512 465.7 497.7 480 480 480H80C35.82 480 0 444.2 0 400V64C0 46.33 14.33 32 32 32C49.67 32 64 46.33 64 64V400zM342.6 278.6C330.1 291.1 309.9 291.1 297.4 278.6L240 221.3L150.6 310.6C138.1 323.1 117.9 323.1 105.4 310.6C92.88 298.1 92.88 277.9 105.4 265.4L217.4 153.4C229.9 140.9 250.1 140.9 262.6 153.4L320 210.7L425.4 105.4C437.9 92.88 458.1 92.88 470.6 105.4C483.1 117.9 483.1 138.1 470.6 150.6L342.6 278.6z"/></svg></button>
    </div>

    <div id="u" style="display: none;"><?php echo $_SESSION['username'];?></div>

    <div id="wrapper1" style="display: flex;width: 500px;justify-content: space-around; float:right;flex-direction: row;flex-wrap: nowrap;align-content: center;align-items: center;">
        <?php
        if(count($o2_trainings) === 0)
            echo '<div id="o2-table-history" style="display: none;">No trainings of this kind yet!</div>';
        else {
            echo '<table id="o2-table-history" class="styled-table" style="display: none; height: 100%; width: 35%; float: left;">' .
                '<thead><tr><th>Date</th><th>Duration</th></tr></thead>';

            $i = 0;
            foreach($o2_trainings as $o2_training)
            {
                echo '<tr>';
                echo '<td id="td-o' . $i . '">' . $o2_training->date . '</td>';
                echo '<td id="td-o' . $i . '">' . $o2_training->duration . '</td>';
                echo '</tr>';
                ++$i;
            }
            echo '</table>';
        }

        if(count($co2_trainings) === 0)
            echo '<div id="co2-table-history" style="display: none;">No trainings of this kind yet!</div>';
        else {
            echo '<table id="co2-table-history" class="styled-table" style="display: none; height: 100%; width: 35%; float: left;">' .
                '<thead><tr><th>Date</th><th>Duration</th></tr></thead>';

            $i = 0;
            foreach($co2_trainings as $co2_training)
            {
                echo '<tr>';
                echo '<td id="td-o' . $i . '">' . $co2_training->date . '</td>';
                echo '<td id="td-o' . $i . '">' . $co2_training->duration . '</td>';
                echo '</tr>';
                ++$i;
            }
            echo '</table>';
        }
        ?>

        
    </div>

    <div id="wrapper2" style="display: flex;width: 500px;justify-content: space-around; float:right;flex-direction: row;flex-wrap: nowrap;align-content: center;align-items: center;">
        <div id="o2-graph-history" style="display: inline;">
            <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
            <div class="container">
                <canvas id="myChart1" width="400" height="400"></canvas>
            </div>

            <script type="text/javascript">
                $(document).ready(function(){
                    $.ajax(
                    {
                        url: "model/tables_index_server.php",
                        data:
                        {
                            type: 'o',
                            username: $('#u').html()
                        },
                        type: "GET",
                        dataType: "json", // očekivani povratni tip podatka
                        success: function( json ) {
                            trainings = json['trainings'];
                            if(trainings.length === 0)
                            {
                                $('#o2-graph-history').html('No trainings of this kind yet!');
                            }
                            else
                            {
                                let dates = [];
                                let durations = [];
                                for(let i = 0; i < trainings.length; ++i)
                                {
                                    dates.push(trainings[i][0]);
                                    durations.push(trainings[i][1]);
                                }

                                var ctx = document.getElementById ('myChart1').getContext('2d');
                                var myChart = new Chart(ctx, {
                                    type: 'line',
                                    data: {
                                        labels: dates,
                                        datasets: [{
                                            label: 'Duration',
                                            data: durations,
                                            backgroundColor: "rgba(169, 223, 122, 0.4)"
                                        }]
                                    }
                                });
                            }                          
                        },
                        error: function( xhr, status, errorThrown ) { },
                        complete: function( xhr, status ) {  }
                    });
                });
            </script>
        </div>
        
        <div id="co2-graph-history" style="display: inline;">
            <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
            <div class="container">
                <canvas id="myChart2" width="400" height="400"></canvas>
            </div>

            <script type="text/javascript">
                $(document).ready(function(){
                    $.ajax(
                    {
                        url: "model/tables_index_server.php",
                        data:
                        {
                            type: 'c',
                            username: $('#u').html()
                        },
                        type: "GET",
                        dataType: "json", // očekivani povratni tip podatka
                        success: function( json ) {
                            trainings = json['trainings'];
                            if(trainings.length === 0)
                            {
                                $('#co2-graph-history').html('No trainings of this kind yet!');
                            }
                            else
                            {
                                let dates = [];
                                let durations = [];
                                for(let i = 0; i < trainings.length; ++i)
                                {
                                    dates.push(trainings[i][0]);
                                    durations.push(trainings[i][1]);
                                }

                                var ctx = document.getElementById ('myChart2').getContext('2d');
                                var myChart = new Chart(ctx, {
                                    type: 'line',
                                    data: {
                                        labels: dates,
                                        datasets: [{
                                            label: 'Duration',
                                            data: durations,
                                            backgroundColor: "rgba(169, 223, 122, 0.4)"
                                        }]
                                    }
                                });
                            }
                        },
                        error: function( xhr, status, errorThrown ) { },
                        complete: function( xhr, status ) {  }
                    });
                });
            </script>
        </div>
    </div>
</div>

<script type="text/javascript">
var data_type = 'table';
var training_type = 'x';
$(document).ready(function()
{
    $('#o2-graph-history').css('visibility', 'hidden');
    $('#co2-graph-history').css('visibility', 'hidden');

    $('#o2-btn').on('click', show_o2);

    $('#co2-btn').on('click', show_co2);

    $('#btn-table-display').on('click', function() {
        data_type = 'table';
        if(training_type === 'o')
            show_o2();
        else if(training_type === 'c')
            show_co2();
    });

    $('#btn-graph-display').on('click', function() {
        data_type = 'graph';
        if(training_type === 'o')
            show_o2();
        else if(training_type === 'c')
            show_co2();
    });
});


function show_o2() {
    $('#control-display').css('display', 'block');
    training_type = 'o';
    if(data_type === 'table')
    {
        $('#co2-table-history').css('display', 'none');
        $('#co2-graph-history').css('display', 'none');
        $('#o2-graph-history').css('display', 'none');
        $('#control-graph').css('display', 'none');
        $('#o2-graph-history').css('visibility', 'visible');
        $('#co2-graph-history').css('visibility', 'visible');    

        $('#o2-table-history').css('display', 'table');
    }
    else
    {
        $('#co2-table-history').css('display', 'none');
        $('#co2-graph-history').css('display', 'none');
        $('#o2-table-history').css('display', 'none');
        $('#control-table').css('display', 'none');
        $('#o2-graph-history').css('visibility', 'visible');
        $('#co2-graph-history').css('visibility', 'visible');    

        $('#o2-graph-history').css('display', 'block');
    }
}
function show_co2() {
    $('#control-display').css('display', 'block');
    training_type = 'c';
    if(data_type === 'table')
    {
        $('#o2-table-history').css('display', 'none');
        $('#o2-graph-history').css('display', 'none');
        $('#co2-graph-history').css('display', 'none');
        $('#control-graph').css('display', 'none');
        $('#o2-graph-history').css('visibility', 'visible');
        $('#co2-graph-history').css('visibility', 'visible');    

        $('#co2-table-history').css('display', 'table');
    }
    else
    {
        $('#o2-table-history').css('display', 'none');
        $('#o2-graph-history').css('display', 'none');
        $('#co2-table-history').css('display', 'none');
        $('#control-table').css('display', 'none');
        $('#o2-graph-history').css('visibility', 'visible');
        $('#co2-graph-history').css('visibility', 'visible');    

        $('#co2-graph-history').css('display', 'block');
    }
}
</script>


<?php require_once __DIR__ . '/_footer.php'; ?>