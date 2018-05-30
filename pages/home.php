<script type="text/javascript" src="http://static.fusioncharts.com/code/latest/fusioncharts.js"></script>
<?php
class FusionCharts {
        
        private $constructorOptions = array();
        private $constructorTemplate = '
        <script type="text/javascript">
            FusionCharts.ready(function () {
                new FusionCharts(__constructorOptions__);
            });
        </script>';
        private $renderTemplate = '
        <script type="text/javascript">
            FusionCharts.ready(function () {
                FusionCharts("__chartId__").render();
            });
        </script>
        ';
        // constructor
        function __construct($type, $id, $width = 400, $height = 300, $renderAt, $dataFormat, $dataSource) {
            isset($type) ? $this->constructorOptions['type'] = $type : '';
            isset($id) ? $this->constructorOptions['id'] = $id : 'php-fc-'.time();
            isset($width) ? $this->constructorOptions['width'] = $width : '';
            isset($height) ? $this->constructorOptions['height'] = $height : '';
            isset($renderAt) ? $this->constructorOptions['renderAt'] = $renderAt : '';
            isset($dataFormat) ? $this->constructorOptions['dataFormat'] = $dataFormat : '';
            isset($dataSource) ? $this->constructorOptions['dataSource'] = $dataSource : '';
            $tempArray = array();
            foreach($this->constructorOptions as $key => $value) {
                if ($key === 'dataSource') {
                    $tempArray['dataSource'] = '__dataSource__';
                } else {
                    $tempArray[$key] = $value;
                }
            }
            
            $jsonEncodedOptions = json_encode($tempArray);
            
            if ($dataFormat === 'json') {
                $jsonEncodedOptions = preg_replace('/\"__dataSource__\"/', $this->constructorOptions['dataSource'], $jsonEncodedOptions);
            } elseif ($dataFormat === 'xml') { 
                $jsonEncodedOptions = preg_replace('/\"__dataSource__\"/', '\'__dataSource__\'', $jsonEncodedOptions);
                $jsonEncodedOptions = preg_replace('/__dataSource__/', $this->constructorOptions['dataSource'], $jsonEncodedOptions);
            } elseif ($dataFormat === 'xmlurl') {
                $jsonEncodedOptions = preg_replace('/__dataSource__/', $this->constructorOptions['dataSource'], $jsonEncodedOptions);
            } elseif ($dataFormat === 'jsonurl') {
                $jsonEncodedOptions = preg_replace('/__dataSource__/', $this->constructorOptions['dataSource'], $jsonEncodedOptions);
            }
            $newChartHTML = preg_replace('/__constructorOptions__/', $jsonEncodedOptions, $this->constructorTemplate);
            echo $newChartHTML;
        }
        // render the chart created
        // It prints a script and calls the FusionCharts javascript render method of created chart
        function render() {
           $renderHTML = preg_replace('/__chartId__/', $this->constructorOptions['id'], $this->renderTemplate);
           echo $renderHTML;
        }
    }

//Query total item
$QueryItems=mysqli_query($link,"SELECT * FROM inventario");
$Items=mysqli_num_rows($QueryItems);

//Query Requisiciones
$QueryRe=mysqli_query($link,"SELECT * FROM req");
$Req=mysqli_num_rows($QueryRe);

//Item asignados
$QueryAsi=mysqli_query($link,"SELECT * FROM asignados");
$Asig=mysqli_num_rows($QueryAsi);

//QueryStock
$QueryStock=mysqli_query($link,"SELECT * FROM inventario WHERE unid<=5 ORDER BY unid DESC");
$RowStock=mysqli_fetch_array($QueryStock);

?>


<div class="row">
    <div class="col-md-3">
        <div class="mini-stat clearfix">
            <span class="mini-stat-icon orange"><i class="fa fa-users"></i></span>
            <div class="mini-stat-info">
                <b><font size="3">Total items&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></b>
                <button type="button" class="btn btn-info btn-xs"><h5><?=$Items;?> Item's</h5></button><br>
                </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mini-stat clearfix">
            <span class="mini-stat-icon blue"><i class="fa fa-money"></i></span>
            <div class="mini-stat-info">
                <b><font size="3">requisiciones</font></b>
                 <br> 
                <button type="button" class="btn btn-danger btn-xs"><h5><b><?=$Req;?> Pendientes</b></h5></button>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mini-stat clearfix">
            <span class="mini-stat-icon green"><i class="fa fa-money"></i></span>
            <div class="mini-stat-info">
                <b><font size="2">Items asignados</font></b>
                 <br> 
                <button type="button" class="btn btn-success btn-xs"><h5><b><?=$Asig;?> Item's</b></h5></button>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mini-stat clearfix">
            <span class="mini-stat-icon pink"><i class="fa fa-building-o"></i></span>
            <div class="mini-stat-info">
                <b><font size="2">Tareas pendientes&nbsp;</font></b>
                <button type="button" class="btn btn-info btn-xs"><?=$sucur;?></button>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <section class="panel">
            <header class="panel-heading">
               Items con bajo stock
            </header>
            <div class="panel-body"> 
                <div class="table-responsive">
                                <table  class="display table table-bordered table-striped" id="dynamic-table">
                                    <thead>
                                        <tr>
                                            <th>Descripción</th>
                                            <th>Referencia</th>
                                            <th>Precio</th>
                                            <th>Unidades</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach( $QueryStock as $RowStock => $field ) : ?> <!-- Mulai loop -->
                                        <tr class="text-besar">
                                            <td><?php echo $field['des']; ?></td>
                                            <td><?php echo $field['ref']; ?></td>
                                            <td><?php echo $field['precio']; ?></td>
                                            <td><?php echo $field['unid']; ?></td>	         
                                        </tr>
                                        <?php endforeach; ?> <!-- Selesai loop -->                                  
                                    </tbody>
                                </table>
                            </div>
             </div>
        </section>
    </div>


        <div class="col-sm-6">
        <section class="panel">
            <header class="panel-heading">
                Compras mensuales
            </header>
            <div class="panel-body"> 
                <div class="table-responsive">
                    <table class="display table table-bordered table-striped">
                    <?php
                    $result_graph_mes=mysqli_query($link,"SELECT mes,sum(total_p) as vendido FROM inventario GROUP BY mes");
                    $arrData = array(
                        "chart" => array(
                        "caption" => "Compras mensuales",
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
                        "numberSuffix"=> " COP",
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
                    $columnChart_1 = new FusionCharts("doughnut2d", "Ventas_M", "100%", 256, "chart-2", "json", $jsonEncodedData2);
                    $columnChart_1->render();
                    ?>
                    <div id="chart-2"><!-- Fusion Charts will render here--></div>
                    </table>
                </div>
            </div>
        </section>
</div>

