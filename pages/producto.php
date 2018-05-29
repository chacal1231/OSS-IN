<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
<?php
//GET
$ref	        =   mysqli_real_escape_string($link,$_GET['ref']);
//Mysql
$QueryId	=	mysqli_query($link,"SELECT * FROM inventario WHERE ref='$ref' ORDER BY id DESC");
$RowId		=	mysqli_fetch_array($QueryId);
//Modificar
if (isset($_POST['stock'])) {
  if($_SESSION['priv']!=0){
    echo '<div class="alert alert-danger" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <strong>¡Usted no tiene privilegios suficientes para continuar con la operación!</strong></div>';
  }else{
    $unid   = mysqli_real_escape_string($link,$_POST['unid']);

    //Query actualizar unidades
    $unidades_totales = ($RowId['unid']+$unid);
    mysqli_query($link,"UPDATE inventario SET unid='$unidades_totales' WHERE ref='$ref'");

    //Query actualizar registro
    $fecha          =   date('Y-m-d');
    $hora           =   date('h:i:s A');
    $result_reg     =   mysqli_query($link,"SELECT * FROM registro ORDER BY id DESC");
    $row_reg        =   mysqli_fetch_array($result_reg);            
    $id_reg         =   ($row_reg["id"]+1);
    $des            =   "$_SESSION[name] agregó $unid items al stock";
    mysqli_query($link,"INSERT INTO registro(id,fecha,hora,des,ref,total) VALUES('$id_reg','$fecha','$hora','$des','$ref','$unid')");

    //Mensaje
    echo '<div class="alert alert-success" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <strong>¡Todo correcto!</strong> Se han modificado correctamente el producto.</div>';
    echo "<script>setTimeout(\"location.href = '?page=producto&ref=$ref';\", 3000);</script>";
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
    //Actualizar inventario query
    mysqli_query($link,"UPDATE inventario SET proyecto='$proyecto',locacion='$locacion',equipo='$equipo',unid='$unidades_totales',motivoas='$motivoas',asig='Si' WHERE ref='$ref'");

    //Actualizar registro
    $fecha          =   date('Y-m-d');
    $hora           =   date('h:i:s A');
    $result_reg     =   mysqli_query($link,"SELECT * FROM registro ORDER BY id DESC");
    $row_reg        =   mysqli_fetch_array($result_reg);            
    $id_reg         =   ($row_reg["id"]+1);
    $des            =   "$_SESSION[name] asignó $unidades items al proyecto $proyecto en la locación $locacion al equipo $equipo";
    mysqli_query($link,"INSERT INTO registro(id,fecha,hora,des,ref,total) VALUES('$id_reg','$fecha','$hora','$des','$ref','$unidades')");

    //Query para popular asignación
    $result_asig      =   mysqli_query($link,"SELECT * FROM asignados ORDER BY id DESC");
    $row_asig         =   mysqli_fetch_array($result_asig);            
    $id_asig          =   ($row_asig["id"]+1);
    mysqli_query($link,"INSERT INTO asignados(id,ref,unid,motivoas,proyecto,locacion,equipo) VALUES('$id_asig','$ref','$unidades','$motivoas','$proyecto','$locacion','$equipo')");
    $my_error = mysqli_error($link);
    echo $my_error;


    echo '<div class="alert alert-success" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <strong>¡Exito!</strong> Se asignó correctamente el item</div>';
                     echo "<script>setTimeout(\"location.href = '?page=producto&ref=$ref';\", 3000);</script>";
  }
  
  
}if (isset($_POST['des'])) {
  if($RowId['ti']=='Consumible' OR $RowId['ti']=='Aceite' OR $RowId['ti']=='Lubricante'){
    echo '<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Error!</strong> No se puede Des-asignar este tipo de item.</div>';
  }else{
    //Mysql para agregar
    mysqli_query($link,"INSET");

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
                       
											           <a href="#myModal2" data-toggle="modal" class="btn btn-info" title="Agregar stock"> <i class="glyphicon glyphicon-plus"></i> Agregar stock </a>	

                                 <a href="#myModal3" data-toggle="modal" class="btn btn-success" title="Asignar"> <i class="glyphicon glyphicon-arrow-right"></i> Asignar a proyecto </a> 

                                 <!--<a href="#myModal4" data-toggle="modal" class="btn btn-warning" title="Re-asignar"> <i class="glyphicon glyphicon-arrow-left"></i>  Re-asignar a proyecto </a>  -->
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


<!-- Modal agregar -->
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
                <label for="recipient-name" class="control-label"><b>Unidades *:</b></label>
                <input type="text" class="form-control" name="unid" required>
            </div>
            
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" name="stock" value="Sign up">Agregar stock</button>
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
            <option value="No Asignado">No asignado</option>
                <?php
                do{
                    ?>
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


<?php
$QueryProyecto1  = mysqli_query($link,"SELECT * FROM proyectos ORDER BY id ASC");
$RowProyecto1  = mysqli_fetch_array($QueryProyecto1);
?>
<!-- Modal Desasignar -->
<div class="modal fade" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
        <div class="form-group">
          <label for="recipient-name" class="control-label"><b>El item reposará en la base*:</b></label>
            <select id="plan" name="proyecto" class="form-control">
                <?php
                do{
                    ?>
                      <option value="<?php echo $RowProyecto1['nombre']?>">
                          <?php echo $RowProyecto1['nombre']; ?>
                        </option>
                        <?php
                        }while ($RowProyecto1 = $QueryProyecto1->fetch_assoc())   ?>
            </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" name="des" value="Sign up">Des-asignar Item</button>
         </form>
      </div>
    </div>
  </div>
</div>