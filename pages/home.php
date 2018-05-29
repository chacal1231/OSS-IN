<?php

?>


<div class="row">
    <div class="col-md-3">
        <div class="mini-stat clearfix">
            <span class="mini-stat-icon orange"><i class="fa fa-users"></i></span>
            <div class="mini-stat-info">
                <b><font size="3">Total items&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></b><button type="button" class="btn btn-info btn-xs"><?=$u_activos;?></button><br>
                </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mini-stat clearfix">
            <span class="mini-stat-icon blue"><i class="fa fa-money"></i></span>
            <div class="mini-stat-info">
                <b><font size="3">Requisisiones pendientes</font></b>
                 <br> 
                <button type="button" class="btn btn-danger btn-xs"><h5><b><?=$total_m_m; echo " $moneda";?></b></h5></button>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mini-stat clearfix">
            <span class="mini-stat-icon green"><i class="fa fa-money"></i></span>
            <div class="mini-stat-info">
                <b><font size="3">Items asignados</font></b>
                 <br> 
                <button type="button" class="btn btn-danger btn-xs"><h5><b><?=$total2; echo " $moneda";?></b></h5></button>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mini-stat clearfix">
            <span class="mini-stat-icon pink"><i class="fa fa-building-o"></i></span>
            <div class="mini-stat-info">
                <b><font size="2">Sucursales&nbsp;</font></b>
                <button type="button" class="btn btn-info btn-xs"><?=$sucur;?></button>
                <b><font size="2">Administradores&nbsp;</font></b>
                <button type="button" class="btn btn-info btn-xs"><?=$admin;?></button>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <section class="panel">
            <header class="panel-heading">
               Items con bajo inventario
            </header>
            <div class="panel-body"> 
                <div class="table-responsive">
                    <table class="display table table-bordered table-striped">
                    <?php
                    $result_graph=mysqli_query($link,"SELECT * FROM user ORDER BY total DESC");
                     if ($result_graph) {
                        $arrData2 = array(
                            "chart" => array(
                            "caption" => "Ventas totales",
                            "renderAt" => 'chart-container',
                            "paletteColors" => "#CEF6F5,#DF0101,#2E2EFE,#64FE2E,#F7FE2E,#F5BCA9",
                            "bgColor" => "#ffffff",
                            "borderAlpha"=> "20",
                            "canvasBorderAlpha"=> "0",
                            "usePlotGradientColor"=> "0",
                            "plotBorderAlpha"=> "10",
                            "showXAxisLine"=> "1",
                            "xAxisLineColor" => "#999999",
                            "showValues" => "1",
                            "divlineColor" => "#999999",
                            "divLineIsDashed" => "1",
                            "showAlternateHGridColor" => "0",
                            "formatnumberscale" => "0",
                            "thousandSeparator" => ".",
                            "numberSuffix"=> " $moneda",
                            "rotateValues" => "1",
                            "placevaluesInside" => "0",
                            )
                        );
                        $arrData2["data"] = array();
                        while($row_while = mysqli_fetch_array($result_graph)) {
                            array_push($arrData2["data"], array(
                                "label" => $row_while["name_user"],
                                "value" => $row_while["total"],
                                )
                            );
                        }
                        $jsonEncodedData = json_encode($arrData2);
                        $columnChart = new FusionCharts("column2D", "Ventas_T" , "100%", "250%", "chart-1", "json", $jsonEncodedData);
                        $columnChart->render();
                    }
                    ?>
                    <div id="chart-1"></div>
                    </table>
                    </div>
                    </div>
                    </section>
                    </div>

        <div class="col-sm-6">
        <section class="panel">
            <header class="panel-heading">
                Ventas mensuales
            </header>
            <div class="panel-body"> 
                <div class="table-responsive">
                    <table class="display table table-bordered table-striped">
                    <?php
                    $result_graph_mes=mysqli_query($link,"SELECT mes,sum(precio) as vendido FROM usuarios GROUP BY mes");
                    $arrData = array(
                        "chart" => array(
                        "caption" => "Ventas mensuales",
                        "bgColor" => "#ffffff",
                        "startingangle" => "120",
                        "showlabels" => "1",
                        "showlegend" => "1",
                        "enablemultislicing" => "0",
                        "slicingdistance" => "15",
                        "showpercentvalues" => "1",
                        "showpercentintooltip" => "0",
                        "formatnumberscale" => "0",
                        "thousandSeparator" => ".",
                        "numberSuffix"=> " $moneda",
                        )
                        );
                    $arrData["data"] = array();
                    while($row_while = mysqli_fetch_array($result_graph_mes)) {
                        array_push($arrData["data"], array(
                        "label" => $row_while["mes"],
                        "value" => $row_while["vendido"],
                        )
                        );
                    }
                    $jsonEncodedData2 = json_encode($arrData);
                    $columnChart_1 = new FusionCharts("doughnut2d", "Ventas_M", "100%", "250%", "chart-2", "json", $jsonEncodedData2);
                    $columnChart_1->render();
                    ?>
                    <div id="chart-2"><!-- Fusion Charts will render here--></div>
                    </table>
                </div>
            </div>
        </section>
</div>

<div class="col-sm-6">
        <section class="panel">
            <header class="panel-heading">
                Ventas Semanales
            </header>
            <div class="panel-body"> 
                <div class="table-responsive">
                    <table class="display table table-bordered table-striped">
                    <?php
                    $result_graph_semana=mysqli_query($link,"SELECT sem,sum(precio) AS total FROM usuarios GROUP BY sem");
                     if ($result_graph_semana) {
                        $arrData3 = array(
                            "chart" => array(
                            "caption" => "Ventas semanales",
                            "renderAt" => 'chart-container',
                            "paletteColors" => "#F8E0EC,#F6CECE,#D8F6CE,#CEE3F6,#BCA9F5,#F5BCA9",
                            "bgColor" => "#ffffff",
                            "borderAlpha"=> "20",
                            "canvasBorderAlpha"=> "0",
                            "usePlotGradientColor"=> "0",
                            "plotBorderAlpha"=> "10",
                            "showXAxisLine"=> "1",
                            "xAxisLineColor" => "#999999",
                            "showValues" => "1",
                            "divlineColor" => "#999999",
                            "divLineIsDashed" => "1",
                            "showAlternateHGridColor" => "0",
                            "formatnumberscale" => "0",
                            "thousandSeparator" => ".",
                            "numberSuffix"=> " $moneda",
                            "rotateValues" => "1",
                            "placevaluesInside" => "0",
                            )
                        );
                        $arrData3["data"] = array();
                        while($row_while_3 = mysqli_fetch_array($result_graph_semana)) {
                            array_push($arrData3["data"], array(
                                    "label" => "Semana $row_while_3[sem]",
                                    "value" => $row_while_3["total"],
                                    )
                                );
                        }
                        
                        $jsonEncodedData3 = json_encode($arrData3);
                        $columnChart_2 = new FusionCharts("column2D", "Ventas_S" , "100%", "250%", "chart-3", "json", $jsonEncodedData3);
                        $columnChart_2->render();
                    }
                    ?>
                    <div id="chart-3"></div>
                    </table>
                </div>
            </div>
        </section>
</div>

<div class="col-sm-6">
        <section class="panel">
            <header class="panel-heading">
                Ventas diarias
            </header>
            <div class="panel-body"> 
                <div class="table-responsive">
                    <table class="display table table-bordered table-striped">
                        <?php
                        $fecha = date ('Y-m-d');
                    $result_graph_diaria=mysqli_query($link,"SELECT usuario_creador,sum(precio) AS total FROM usuarios WHERE fecha='$fecha' GROUP BY usuario_creador ORDER BY total DESC");
                     if ($result_graph_diaria) {
                        $arrData4 = array(
                            "chart" => array(
                            "caption" => "Ventas diarias",
                            "renderAt" => 'chart-container',
                            "paletteColors" => "#F5BCA9,#E0F2F7,#F8E0F7,#E6E0F8,#EFFBFB,#F8FBEF,",
                            "bgColor" => "#ffffff",
                            "borderAlpha"=> "20",
                            "canvasBorderAlpha"=> "0",
                            "usePlotGradientColor"=> "0",
                            "plotBorderAlpha"=> "10",
                            "showXAxisLine"=> "1",
                            "xAxisLineColor" => "#999999",
                            "showValues" => "1",
                            "divlineColor" => "#999999",
                            "divLineIsDashed" => "1",
                            "showAlternateHGridColor" => "0",
                            "formatnumberscale" => "0",
                            "thousandSeparator" => ".",
                            "numberSuffix"=> " $moneda",
                            "rotateValues" => "1",
                            "placevaluesInside" => "0",
                            )
                        );
                        $arrData4["data"] = array();
                        while($row_while_3 = mysqli_fetch_array($result_graph_diaria)) {
                            array_push($arrData4["data"], array(
                                "label" => $row_while_3["usuario_creador"],
                                "value" => $row_while_3["total"],
                                )
                            );
                        }
                        $jsonEncodedData4 = json_encode($arrData4);
                        $columnChart_3 = new FusionCharts("column2D", "Ventas_D" , "100%", "250%", "chart-4", "json", $jsonEncodedData4);
                        $columnChart_3->render();
                    }
                    ?>
                    <div id="chart-4"></div>
                    </table>
                </div>
            </div>
        </section>
</div>