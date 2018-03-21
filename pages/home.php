<?php
if(isset($_POST['simpan'])){
    //id query
    $result = mysqli_query($link,"SELECT * FROM food order by id DESC");
    $row = mysqli_fetch_array($result);            
    $id=($row["id"]+1);
    //POST Data
    $food       = mysqli_real_escape_string($link,$_POST['type']);
    $time       = mysqli_real_escape_string($link,$_POST['time']);
    $frec       = mysqli_real_escape_string($link,$_POST['frec']);
    $grs        = mysqli_real_escape_string($link,$_POST['amount']);
    //Query
    mysqli_query($link,"INSERT INTO food(id,food,time,frec,grs) VALUES('$id','$food','$time','$frec','$grs')");
    //Message
    echo '<div class="alert alert-success" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <strong>Every is ok!</strong> Added scheduler. </div>';
    //Frame 
    $frame="time=$time;frec=$frec;grs=$grs";
    //Socket
    $host="127.0.0.1";
    $port = 7775;
    $message=$frame . "\r\n";
    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Could not create socket\n");
    $result = socket_connect($socket, $host, $port) or die("Could not connect to server\n");
    socket_write($socket, $message, strlen($message)) or die("Could not send data to server\n");
    socket_close($socket);
}
?>

<?php
$Date = date ('Y-m-d');
#Temperature query
$TempQuery = mysqli_query($link,"SELECT TMP,hour from tmp ORDER BY id DESC");
$TMP = mysqli_fetch_row($TempQuery);

#Waterflow query
$WaterQuery = mysqli_query($link,"SELECT ph,hour FROM ph ORDER BY id DESC");
$ph = mysqli_fetch_row($WaterQuery);

#OilFlow query
//$OilQuery = mysqli_query($link,"SELECT OFR,hour FROM minutedata ORDER BY hour DESC");
//$OFR = mysqli_fetch_row($OilQuery);

#GasFlow query
//$GasQuery = mysqli_query($link,"SELECT GFR,hour FROM minutedata ORDER BY hour DESC");
//$GFR = mysqli_fetch_row($GasQuery);
?>

<div class="row">
    <div class="col-md-3">
        <div class="mini-stat clearfix">
            <font size="1"><b>Temp</b></font>
            <span class="mini-stat-icon green"><i class="fa fa-thermometer-half"></i></span>
            <div class="mini-stat-info">
               <button type="button" class="btn btn-danger btn-xs"><?=$TMP[0]?> °C</button><font size="2"></font><br>
                </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mini-stat clearfix">
            <font size="1"><b>pH</b></font>
            <span class="mini-stat-icon tar"><i class="fa fa-shower"></i></span>
            <div class="mini-stat-info">
                <button type="button" class="btn btn-info btn-xs"><?=$ph[0]?> </button><font size="2"></font><br>
             
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mini-stat clearfix">
            <font size="1"><b>Ammonium</b></font> 
            <span class="mini-stat-icon pink"><i class="fa fa-tint"></i></span>
            <div class="mini-stat-info">
            <button type="button" class="btn btn-info btn-xs"><?=$OFR[0]?></button><font size="2"> (µmol/L)</font><br>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mini-stat clearfix">
            <font size="1"><b>Nitrates</b></font>
            <span class="mini-stat-icon green"><i class="fa fa-soundcloud"></i></span>
            <div class="mini-stat-info">
                <button type="button" class="btn btn-info btn-xs"><?=$GFR[0]?></button><font size="2"> (mg/l)</font>
                <br>
            </div>
        </div>
    </div>
</div>
<?php
$QueryFood = mysqli_query($link,"SELECT * FROM food ORDER BY id DESC");
$row = mysqli_fetch_array($QueryFood);
?>
 <div class="row">
    <div class="col-sm-12">
        <section class="panel">
            <header class="panel-heading">
                Feed scheduler 
            </header>
            <section class="panel">
                        <div class="panel-body"> 
                            <td ><a target="_blank" href="pages/Enviar.php?c=1" class="btn btn-info" title="Feed fishes now!">Feed now</a></td>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                                <i class="fa fa-plus-square"></i> Add schedule 
                            </button>
                            <hr/>
                            <div class="table-responsive">
                                <table  class="display table table-bordered table-striped" id="dynamic-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Feed type</th>
                                            <th>When</th>
                                            <th>Repeat</th>
                                            <th>Amount (Grs)</th>
                                            <th><i class="fa fa-trash-o"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach( $QueryFood as $row => $field ) : ?> <!-- Mulai loop -->
                                        <tr class="text-besar">
                                            <td><?php echo $field['id']; ?></td>
                                            <td><?php echo $field['food']; ?></td>
                                            <td><?php echo $field['time']; ?></td>
                                            <td><?php echo $field['frec']; ?></td>
                                            <td><?php echo $field['grs']; ?></td> <i class="fa fa-pencil"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="btn btn-danger btn-xs" href="?page=eli_sc&id=<?php echo $field['frec']; ?>" title="Eliminar" onClick="return confirm('Do you want delete this?')">
                                                    <i class="fa fa-trash-o"></i>
                                                </a>
                                            </td>
                                                
                                        </tr>
                                        <?php endforeach; ?> <!-- Selesai loop -->                                  
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
        </div>
    </section>
</div>
    <div class="col-sm-6">
        <section class="panel">
            <header class="panel-heading">
               Temperature
            </header>
            <div class="panel-body"> 
                <div class="table-responsive">
                    <table class="display table table-bordered table-striped">
                    <!DOCTYPE HTML>
<html>
<head>
<script>
window.onload = function () {
                var dataLength = 0;
                var data = [];
                var updateInterval = 500;
                updateChart();
                function updateChart() {
                    $.getJSON("pages/jsonchar.php", function (result) {
                        if (dataLength !== result.length) {
                            for (var i = dataLength; i < result.length; i++) {
                                data.push({
                                    label: (result[i].valorx),
                                    y: parseFloat(result[i].valory)
                                });
                            }
                            dataLength = result.length;
                            chart.render();

                        }
                    });
                }
                var chart = new CanvasJS.Chart("chart", {
                    zoomEnabled: true,
                    title: {
                        text: "Temperature"
                    },
                    axisX: {
                        title: "chart updates every " + 60 + " secs"
                    },
                    axisY: {
                        title: "Temperature",

                        suffix: " °C",

                    },
                    data: [{type: "line",
                            toolTipContent: "{label} : {y} °C",
                             lineColor: "red", 
                            dataPoints: data}],
                });
                setInterval(function () {
                    updateChart()
                }, updateInterval);

                //Chart 2

                var dataLength2 = 0;
                var data2 = [];
                var updateInterval2 = 500;
                updateChart2();
                function updateChart2() {
                    $.getJSON("pages/jsoncharoil.php", function (result2) {
                        if (dataLength2 !== result2.length) {
                            for (var i = dataLength2; i < result2.length; i++) {
                                data2.push({
                                    label: (result2[i].valorx),
                                    y: parseFloat(result2[i].valory)
                                });
                            }
                            dataLength2 = result2.length;
                            chart2.render();
                        }
                    });
                }
                var chart2 = new CanvasJS.Chart("chart2", {
                    zoomEnabled: true,
                    title: {
                        text: "Nitrates "
                    },
                    axisX: {
                        title: "chart updates every " + 60 + " secs"
                    },
                    axisY: {
                        title: "mg/l",

                        suffix: " mg/l",

                    },
                    data: [{type: "line",
                             toolTipContent: "{label} : {y} mg/l",
                             lineColor: "red", 

                            dataPoints: data2}],
                });
                setInterval(function () {
                    updateChart2()
                }, updateInterval);


}
</script>
</head>
<body>
<div id="chart" style="height: 370px; width: 100%;"></div>
<script src="backend/js/canvasjs.min.js"></script>
</body>
</head>
</html>     
                    </table>
                </div>
            </div>
        </section>
    </div>

        <div class="col-sm-6">
        <section class="panel">
            <header class="panel-heading">
                Nitrates 
            </header>
            <div class="panel-body"> 
                <div class="table-responsive">
                    <div id="chart2" style="height: 370px; width: 100%;""></div>
                    <script src="backend/js/canvasjs.min.js"></script>
                </table>
            </div>
        </div>
    </section>
</div>


 <!-- Modal -->
           
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">New scheldue</h4>
                            </div>
                        <div class="modal-body">
                        <form id="modal-form" action="" method="post">
                            <div class="form-group">
                                <label for="recipient-name" class="control-label"><b>Type food *:</b></label>
                                <input type="text" class="form-control" name="type" placeholder="" required>
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="control-label"><b>Start hour *:</b></label>
                                <select id="repeat" name="time" class="form-control">
                                    <option value="">-- Seleccionar --</option>
                                    <option value="0">00:00</option>
                                    <option value="1">01:00</option>
                                    <option value="2">02:00</option>
                                    <option value="3">03:00</option>
                                    <option value="4">04:00</option>
                                    <option value="5">05:00</option>
                                    <option value="6">06:00</option>
                                    <option value="7">07:00</option>
                                    <option value="8">08:00</option>
                                    <option value="9">09:00</option>
                                    <option value="10">10:00</option>
                                    <option value="11">11:00</option>
                                    <option value="12">12:00</option>
                                    <option value="13">13:00</option>
                                    <option value="14">14:00</option>
                                    <option value="15">15:00</option>
                                    <option value="16">16:00</option>
                                    <option value="17">17:00</option>
                                    <option value="18">18:00</option>
                                    <option value="19">19:00</option>
                                    <option value="20">20:00</option>
                                    <option value="21">21:00</option>
                                    <option value="22">22:00</option>
                                    <option value="23">23:00</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="control-label"><b>Repeat *:</b></label>
                                <select id="repeat" name="frec" class="form-control">
                                    <option value="">-- Seleccionar --</option>
                                    <option value="0">Everyday</option>
                                    <option value="1">Weekends</option>
                                    <option value="2">Monday to Friday</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="control-label"><b>Amount in gr *:</b></label>
                                <input type="correo" class="form-control" name="amount" required/>
                            </div>
                            <h3><b><p class="alert alert-danger">* Obligatory fields</b></h3>
                            <div class="text-right">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" name="simpan" value="Sign up">Add</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div> 

            </head>
</html>