<?php
include 'admin/inc/conn.php';
  /* =================Recolección datos POST======================== */
if(isset($_POST['Ver'])){
  $NombreU=mysqli_real_escape_string($link,$_POST['NombreU']);
  $QueryTabla =	mysqli_query($link,"SELECT * FROM inventario WHERE proyecto='$NombreU' ORDER BY id DESC");
  $RowTabla	=	mysqli_fetch_array($QueryTabla);
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
                          Ver inventario
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


        <div class="table-responsive">
                                <table  class="display table table-bordered table-striped" id="dynamic-table">
                                    <thead>
                                        <tr>
                                            <th>Referencia</th>
                                            <th>Marca</th>
                                            <th>Proveedor</th>
                                            <th>Unidades</th>
                                            <th>Descripción</th>
                                            <th>Estado del producto</th>
                                            <th>Ver producto</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach( $QueryTabla as $RowTabla => $field ) : ?> <!-- Mulai loop -->
                                        <tr class="text-besar">
                                            <td><?php echo $field['ref']; ?></td>
                                            <td><?php echo $field['marca']; ?></td>
                                            <td><?php echo $field['prov']; ?></td>
                                            <td><?php echo $field['unid']; ?></td>
                                            <td><?php echo $field['des']; ?></td>
                                            <td><?php echo $field['estado']; ?></td>
                                            <td>
                                                <a class="btn btn-success btn-xs" target="_blank" href="?page=producto&ref=<?php echo $field['ref']; ?>" title="Ver">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>                                               
                                        </tr>
                                        <?php endforeach; ?> <!-- Selesai loop -->                                  
                                    </tbody>
                                </table>
                            </div>