<?php
include 'admin/inc/conn.php';
  /* =================Recolección datos POST======================== */
if(isset($_POST['Ver'])){
  $NombreU=mysqli_real_escape_string($link,$_POST['NombreU']);
  $QueryConsultaUsuarios=mysqli_query($link,"SELECT SUM(total_p) AS TotalVen FROM inventario WHERE proyecto='$NombreU' AND ti='Consumible'");
    $TotalVen=mysqli_fetch_array($QueryConsultaUsuarios)['TotalVen'];
  /* =============================================================== */
  /* =================Mostrar MODAL================================= */
  echo "<body onLoad=$('#myModalView').modal('show')>";
  /* =============================================================== */
}
?>
<!-- page content -->
        <div class="right_col" role="main">
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <p class="text-muted font-13 m-b-30"><h2>Selecciona un centro de costo para ver el reporte de consumibles.</h2></p>
                    <br />
                    <form action="" class="form-horizontal form-label-left" novalidate method="post">
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Centro de costo:*</label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                        <select name='NombreU' class="select2_single form-control">
                        <?php
                          $result=mysqli_query($link,"SELECT * FROM proyectos");
                        ?>
                          </form><?php while($registros=mysqli_fetch_array($result)){?>
                          <option value="<?php echo $registros["nombre"]; ?>"><?php echo $registros["nombre"] ?></option>
                        </form>
                        <?php } ?>
                      </select> 
                        </div>
                      </div>
					   </div>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                          <button type="submit" name="Ver" class="btn btn-success" alt="Submit Form">
                          <span class="glyphicon glyphicon-search"></span>
                          Ver reporte
                          </button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->

<div class="modal fade" id="myModalView" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><b>A continuación se detallan los resultados de su consulta</b></h4>
            </div>
            <div class="modal-body">          
                    <table id="datatable" class="table table-striped table-bordered">
                      <tbody>
                        <tr>
                          <th>Centro de costo:</th>
                          <td class="red"><b><?php echo $NombreU ?></b></td>
                        </tr>
                        <tr>
                          <th>Total items:</th>
                          <td class="red"><b><?php echo mysqli_num_rows($QueryConsultaUsuarios); echo " item's";?></b></td>
                        </tr>
                        <tr>
                          <th>Total costos:</th>
                          <td class="red"><b><?php echo number_format($TotalVen); echo " Pesos"; ?></b></td>
                        </tr>

                      </tbody>
                    </table>
                    <div class="form-group text-center">
                          <a href="?view=info_ventas" type="button" class="btn btn-danger"><i class="fa fa-close"></i> Cerrar</a>
                          <a href="#" type="submit" class="btn btn-primary"><i class="fa fa-print"></i> Imprimir</a>
                      </div>
            </div> 
        </div>
    </div>
</div>