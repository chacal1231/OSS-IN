<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
<?php
//GET
$ref	        =   mysqli_real_escape_string($link,$_GET['ref']);
//Mysql
$QueryId	=	mysqli_query($link,"SELECT * FROM inventario WHERE ref='$ref' ORDER BY id DESC");
$RowId		=	mysqli_fetch_array($QueryId);
//Modificar
if (isset($_POST['modi'])) {
  if($_SESSION['priv']!=0){
    echo '<div class="alert alert-danger" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <strong>¡Usted no tiene privilegios suficientes para continuar con la operación!</strong></div>';
  }else{
    $ref_m      = mysqli_real_escape_string($link,$_POST['ref']);
    $des_m      = mysqli_real_escape_string($link,$_POST['des']);
    $marca_m    = mysqli_real_escape_string($link,$_POST['marca']);
    $fecha_c_m  = mysqli_real_escape_string($link,$_POST['fecha_c']);
    $valor_m    = mysqli_real_escape_string($link,$_POST['valor']);
    $unid_m     = mysqli_real_escape_string($link,$_POST['unid']);
    $asig       = mysqli_real_escape_string($link,$_POST['asig']);
    $proyecto   = mysqli_real_escape_string($link,$_POST['proyecto']);
    $motivoas   = mysqli_real_escape_string($link,$_POST['motivoas']);

    mysqli_query($link,"UPDATE inventario SET ref='$ref_m',des='$des_m',marca='$marca_m',fecha_c='$fecha_c_m',precio='$valor_m',unid='$unid_m',asig='$asig',proyecto='$proyecto',motivoas='$motivoas' WHERE ref='$ref'");
    echo '<div class="alert alert-success" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <strong>¡Todo correcto!</strong> Se han modificado correctamente el producto.</div>';
    echo "<script>setTimeout(\"location.href = '?page=home';\", 3000);</script>";
  }
}
//Eliminar item
if (isset($_POST['remove'])) {
  if($_SESSION['priv']!=0){
    echo '<div class="alert alert-danger" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <strong>¡Usted no tiene privilegios suficientes para continuar con la operación!</strong></div>';
  }else{
    mysqli_query($link,"DELETE FROM inventario WHERE ref='$_POST[remove]'");
    mysqli_query($link,"DELETE FROM registro WHERE ref='$_POST[remove]'");
    echo '<div class="alert alert-success" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <strong>¡Todo correcto!</strong> Se han eliminado correctamente el producto.</div>';
      echo "<script>setTimeout(\"location.href = '?page=home';\", 3000);</script>";
  }
//Asignar item
}if (isset($_POST['asignar'])) {
  $proyecto      = mysqli_real_escape_string($link,$_POST['proyecto']);
  $locacion      = mysqli_real_escape_string($link,$_POST['locacion']);
  $equipo        = mysqli_real_escape_string($link,$_POST['equipo']);
  $unidades      = mysqli_real_escape_string($link,$_POST['unidades']);
  $motivoas      = mysqli_real_escape_string($link,$_POST['motivoas']);

  //query eliminar del stock
  $unidades_totales = ($RowId['unid']-$unidades);
  if ($unidades_totales < 0){
    echo '<div class="alert alert-danger" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <strong>¡Error!</strong> No hay stock suficientes en el inventario.</div>';
  }else{
    mysqli_query($link,"UPDATE inventario SET proyecto='$proyecto',locacion='$locacion',equipo='$equipo',unid='$unidades_totales',motivoas='$motivoas',asig='Si' WHERE ref='$ref'");
    //Actualizar registro
    $fecha          =   date('Y-m-d');
    $hora           =   date('h:i:s A');
    $result_reg     =   mysqli_query($link,"SELECT * FROM registro ORDER BY id DESC");
    $row_reg        =   mysqli_fetch_array($result_reg);            
    $id_reg         =   ($row_reg["id"]+1);
    $des            =   "$_SESSION[name] asignó $unidades_totales items al proyecto $proyecto en la locación $locacion al equipo $equipo";
    mysqli_query($link,"INSERT INTO registro(id,fecha,hora,des,ref,total) VALUES('$id_reg','$fecha','$hora','$des','$ref','$unidades_totales')");

    //Query para popular asignación
    $result_asig      =   mysqli_query($link,"SELECT * FROM asignados ORDER BY id DESC");
    $row_asig         =   mysqli_fetch_array($result_asig);            
    $id_asig          =   ($row_asig["id"]+1);
    mysqli_query($link,"INSERT INTO asignados(id,ref,unid,motivoas,proyecto,locacion,equipo) VALUES('$id','$ref','$unid','$motivoas','$proyecto','$locacion','$equipo')");


    echo '<div class="alert alert-success" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <strong>¡Exito!</strong> Se asignó correctamente el item</div>';
  }
  
  
}

?>

<!DOCTYPE html>
<html lang="en">
<body>
<div class="container">
	<div class="row">
		<div class="col-md-10">
			<div class="panel panel-default">
          				<div class="panel-body">
            					<div class="row">
              						<div class="col-sm-4 col-sm-offset-2 text-center">
                        <form action="" method="post">
                                <button type="submit" class="btn btn-danger" name='remove' value='<?php echo $ref;?>'>
                                    <i class="glyphicon glyphicon-trash"></i> eliminar
                                </button>
                       
											           <a href="#myModal2" data-toggle="modal" class="btn btn-info" title="Editar"> <i class="glyphicon glyphicon-pencil"></i> Editar </a>	

                                 <a href="#myModal3" data-toggle="modal" class="btn btn-success" title="Editar"> <i class="glyphicon glyphicon-arrow-right"></i> Asignar a proyecto </a> 

                                 <a href="#myModal4" data-toggle="modal" class="btn btn-warning" title="Editar"> <i class="glyphicon glyphicon-arrow-left"></i> Desasignar a proyecto </a>  
                          </form>
					
              						</div>
              						<div class="col-sm-4 text-left">
                						<div class="row margin-btm-20">
                    						<div class="col-sm-12">
                      							<b><span class="item-title">Decripción:</b> <?php echo $RowId['des'];?></span>
                    						</div>
                    						<div class="col-sm-12 margin-btm-10">
                      							<b><span class="item-number">Referencia:</b> <?php echo $RowId['ref'];?></span>
                    						</div>
                    						<div class="col-sm-12 margin-btm-10">
                    						</div>
                    						<div class="col-sm-12">
                      							<b><span class="current-stock">Stock disponible:</b> <?php echo $RowId['unid'];?>  Unidades</span>
                    						</div>
										            <div class="col-sm-12">
                      							<b><span class="current-stock">Precio compra</b> <?php echo number_format($RowId['precio'],2);?></span>
                    					</div>                 
                                </div>
            				</div>
            				<br>
            <div class="row">
            	<div class="col-sm-8 col-sm-offset-2 text-left">
                  <div class="row">
					 <table class='table table-bordered'>
						<tr>
							<th class='text-center' colspan=5 >HISTORIAL DE INVENTARIO</th>
						</tr>
						<tr>
							<td>Fecha</td>
							<td>Hora</td>
							<td>Descripción</td>
							<td>Referencia</td>
							<td class='text-center'>Total</td>
						</tr>
						<?php
							$query=mysqli_query($link,"SELECT * FROM registro WHERE ref='$RowId[ref]' ORDER BY id DESC");
							while ($row=mysqli_fetch_array($query)){
								?>
						<tr>
							<td><?php echo date('d/m/Y', strtotime($row['fecha']));?></td>
							<td><?php echo date('H:i:s', strtotime($row['hora']));?></td>
							<td><?php echo $row['des'];?></td>
							<td><?php echo $row['ref'];?></td>
							<td class='text-center'><?php echo $row['total'];?></td>
						</tr>		
								<?php
							}
						?>
					 </table>
                  </div>             
      			</div>
            </div>
          </div>
        </div>
    </div>
</div>
</div>
</body>
</html>


<!-- Modal editar -->
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modificar item <?php echo $RowId['ref'];?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form id="modal-form" action="" method="post">
            <div class="form-group">
                <label for="recipient-name" class="control-label"><b>Referencia *:</b></label>
                <input type="text" class="form-control" name="ref" value="<?php echo $RowId['ref'];?>" required>
            </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label"><b>Descripción *:</b></label>
                <input type="text" class="form-control" name="des" value="<?php echo $RowId['des'];?>" required>
            </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label"><b>Marca *:</b></label>
                <input type="text" class="form-control" name="marca" value="<?php echo $RowId['marca'];?>" required>
            </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label"><b>Fecha compra *:</b></label>
                <input type="text" class="form-control" required id="datepicker" data-date-format="yyyy-mm-dd" value="<?php echo $RowId['fecha_c'];?>" name="fecha_c" required>
            </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label"><b>Valor producto *:</b></label>
                <input type="text" class="form-control" name="valor" value="<?php echo $RowId['precio'];?>" required>
            </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label"><b>Unidades *:</b></label>
                <input type="text" class="form-control" name="unid" value="<?php echo $RowId['unid'];?>" required>
            </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label"><b>Asignado *:</b></label>
                <input type="text" class="form-control" name="asig" value="<?php echo $RowId['asig'];?>" required>
            </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label"><b>Proyecto *:</b></label>
                <input type="text" class="form-control" name="proyecto" value="<?php echo $RowId['proyecto'];?>" required>
            </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label"><b>Locación *:</b></label>
                <input type="text" class="form-control" name="proyecto" value="<?php echo $RowId['proyecto'];?>" required>
            </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label"><b>Equipo *:</b></label>
                <input type="text" class="form-control" name="proyecto" value="<?php echo $RowId['locacion'];?>" required>
            </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label"><b>Motivo de asignación *:</b></label>
                <input type="text" class="form-control" name="motivoas" value="<?php echo $RowId['motivoas'];?>" required>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" name="modi" value="Sign up">Editar item</button>
         </form>
      </div>
    </div>
  </div>
</div>

<?php
$QueryProyecto  = mysqli_query($link,"SELECT * FROM proyectos ORDER BY id ASC");
$RowProyecto  = mysqli_fetch_array($QueryProyecto);
?>

<?php
$QueryLocacion  = mysqli_query($link,"SELECT * FROM locacion ORDER BY id ASC");
$RowLocacion  = mysqli_fetch_array($QueryLocacion);
?>

<?php
$QueryEquipos  = mysqli_query($link,"SELECT * FROM equipos ORDER BY id ASC");
$RowEquipos  = mysqli_fetch_array($QueryEquipos);
?>

<!-- Modal Asignar -->
<div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Asignar item <?php echo $RowId['ref'];?> a un proyecto</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form id="modal-form" action="" method="post">
      <div class="form-group">
      <label for="recipient-name" class="control-label"><b>Proyecto*:</b></label>
            <select id="plan" name="proyecto" class="form-control">
                <?php
                do{
                    ?>
                      <option value="No Asignado">No asignado</option>
                      <option value="<?php echo $RowProyecto['nombre']?>">
                          <?php echo $RowProyecto['nombre']; ?>
                        </option>
                        <?php
                        }while ($RowProyecto = $QueryProyecto->fetch_assoc())   ?>
            </select>
            </div>
      <div class="form-group">
            <label for="recipient-name" class="control-label"><b>Locación *:</b></label>
            <select id="plan" name="locacion" class="form-control">
                <?php
                do{
                    ?>
                      <option value="No Asignado">No asignado</option>
                      <option value="<?php echo $RowLocacion['locacion']?>">
                          <?php echo $RowLocacion['locacion']; ?>
                        </option>
                        <?php
                        }while ($RowLocacion = $QueryLocacion->fetch_assoc())   ?>
            </select>
         </div>
      <div class="form-group">
            <label for="recipient-name" class="control-label"><b>Equipo *:</b></label>
            <select id="plan" name="equipo" class="form-control">
                <?php
                do{
                    ?>
                      <option value="No Asignado">No asignado</option>
                      <option value="<?php echo $RowEquipos['serial']?>">
                          <?php echo $RowEquipos['serial']; ?>
                        </option>
                        <?php
                        }while ($RowEquipos = $QueryEquipos->fetch_assoc())   ?>
            </select>
      </div>
       <div class="form-group">
            <label for="recipient-name" class="control-label"><b>Unidades disponibles</b></label>
            <input type="text" class="form-control" name="unidades" readonly="yes" value="<?=$RowId['unid'];?>">
      </div>
      <div class="form-group">
            <label for="recipient-name" class="control-label"><b>Unidades a asignar *:</b></label>
            <input type="text" class="form-control" name="unidades" required>
      </div>
      <div class="form-group">
            <label for="recipient-name" class="control-label"><b>Motivo de asignación *:</b></label>
            <textarea class="form-control" rows="5" name="motivoas"></textarea>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" name="asignar" value="Sign up">Asignar Item</button>
         </form>
      </div>
    </div>
  </div>
</div>


<!-- Modal Desasignar -->
<div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Des-asignar item <?php echo $RowId['ref'];?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form id="modal-form" action="" method="post">
      <label for="recipient-name" class="control-label"><b>Proyecto*:</b></label>
            <select id="plan" name="proyecto" class="form-control">
                <?php
                do{
                    ?>
                      <option value="No Asignado">No asignado</option>
                      <option value="<?php echo $RowProyecto['nombre']?>">
                          <?php echo $RowProyecto['nombre']; ?>
                        </option>
                        <?php
                        }while ($RowProyecto = $QueryProyecto->fetch_assoc())   ?>
            </select>
            <br>
      <div class="form-group">
            <label for="recipient-name" class="control-label"><b>Locación *:</b></label>
            <select id="plan" name="locacion" class="form-control">
                <?php
                do{
                    ?>
                      <option value="No Asignado">No asignado</option>
                      <option value="<?php echo $RowLocacion['locacion']?>">
                          <?php echo $RowLocacion['locacion']; ?>
                        </option>
                        <?php
                        }while ($RowLocacion = $QueryLocacion->fetch_assoc())   ?>
            </select>
         </div>
         <br>
      <div class="form-group">
            <label for="recipient-name" class="control-label"><b>Equipo *:</b></label>
            <select id="plan" name="equipo" class="form-control">
                <?php
                do{
                    ?>
                      <option value="No Asignado">No asignado</option>
                      <option value="<?php echo $RowEquipos['equipos']?>">
                          <?php echo $RowEquipos['equipos']; ?>
                        </option>
                        <?php
                        }while ($RowEquipos = $QueryEquipos->fetch_assoc())   ?>
            </select>
      </div>
       <div class="form-group">
            <label for="recipient-name" class="control-label"><b>Unidades disponibles</b></label>
            <input type="text" class="form-control" name="unidades" readonly="yes" value="<?=$RowId['unid'];?>">
      </div>
      <div class="form-group">
            <label for="recipient-name" class="control-label"><b>Unidades a asignar *:</b></label>
            <input type="text" class="form-control" name="unidades" required>
      </div>
      <div class="form-group">
            <label for="recipient-name" class="control-label"><b>Motivo de asignación *:</b></label>
            <textarea class="form-control" rows="5" name="motivoas"></textarea>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" name="asignar" value="Sign up">Asignar Item</button>
         </form>
      </div>
    </div>
  </div>
</div>