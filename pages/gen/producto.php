<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
<?php
//GET
$id	        =   $_GET['id'];
$proyecto   =  "Gen";
//Mysql
$QueryId	=	mysqli_query($link,"SELECT * FROM herra WHERE id='$id' AND proyecto='$proyecto' ORDER BY id DESC");
$RowId		=	mysqli_fetch_array($QueryId);
//POST ADD
if (isset($_POST['add'])) {
  $unidades_a   = mysqli_escape_string($link,$_POST['unidades_a']);
  $new_uni  = $RowId['unid'] + $unidades_a;
  $des_a  = "$_SESSION[name] agregó $unidades_a al stock";
  //Query actualizar producto
  mysqli_query($link,"UPDATE herra SET unid='$new_uni' WHERE proyecto='$proyecto'");
  //Query insertar nuevo registro
  $fecha          =   date('Y-m-d');
  $hora           =   date('h:i:s A');
  $result_reg     =   mysqli_query($link,"SELECT * FROM registro ORDER BY id DESC");
  $row_reg        =   mysqli_fetch_array($result_reg);            
  $id_reg         =   ($row_reg["id"]+1);
  mysqli_query($link,"INSERT INTO registro(id,fecha,hora,des,ref,total) VALUES('$id_reg','$fecha','$hora','$des_a','$RowId[ref]','$new_uni')");
  echo '<div class="alert alert-success" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <strong>¡Todo correcto!</strong> Se han añadido correctamente las unidades al stock</div>';
  header("Refresh:3");
}
//POST DEL
if (isset($_POST['del'])) {
  $unidades_d   = mysqli_escape_string($link,$_POST['unidades_d']);
  $new_uni  = $RowId['unid'] - $unidades_d;
  $des_d  = "$_SESSION[name] eliminó $unidades_d del stock";
  //Query actualizar producto
  mysqli_query($link,"UPDATE herra SET unid='$new_uni' WHERE proyecto='$proyecto'");
  //Query insertar nuevo registro
  $fecha          =   date('Y-m-d');
  $hora           =   date('h:i:s A');
  $result_reg     =   mysqli_query($link,"SELECT * FROM registro ORDER BY id DESC");
  $row_reg        =   mysqli_fetch_array($result_reg);            
  $id_reg         =   ($row_reg["id"]+1);
  mysqli_query($link,"INSERT INTO registro(id,fecha,hora,des,ref,total) VALUES('$id_reg','$fecha','$hora','$des_d','$RowId[ref]','$new_uni')");
  echo '<div class="alert alert-success" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <strong>¡Todo correcto!</strong> Se han eliminado correctamente las unidades al stock</div>';
  header("Refresh:3");
}
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
    $nota_m     = mysqli_real_escape_string($link,$_POST['nota']);
    mysqli_query($link,"UPDATE herra SET ref='$ref_m',des='$des_m',marca='$marca_m',fecha_c='$fecha_c_m',precio='$valor_m',unid='$unid_m',nota='$nota_m',proyecto='$proyecto' WHERE id='$id'");
    echo '<div class="alert alert-success" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <strong>¡Todo correcto!</strong> Se han modificado correctamente el producto.</div>';
    header("Refresh:3");
  }
}
if (isset($_POST['remove'])) {
  if($_SESSION['priv']!=0){
    echo '<div class="alert alert-danger" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <strong>¡Usted no tiene privilegios suficientes para continuar con la operación!</strong></div>';
  }else{
    mysqli_query($link,"DELETE * FROM herra WHERE id='$_POST[remove]' AND proyecto='$proyecto'");
    echo '<div class="alert alert-success" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <strong>¡Todo correcto!</strong> Se han eliminado correctamente el producto.</div>';
      echo "<script>setTimeout(\"location.href = '?page=gen/herramientas';\", 3000);</script>";
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
                                <button type="submit" class="btn btn-danger" name='remove' value='<?php echo $id;?>'>
                                    <i class="glyphicon glyphicon-trash"></i> eliminar
                                </button>
                       
											           <a href="#myModal2" data-toggle="modal" class="btn btn-info" title="Editar"> <i class="glyphicon glyphicon-pencil"></i> Editar </a>	
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
										          <div class="col-sm-12 margin-btm-10">
										          </div>
                    					<div class="col-sm-6 col-xs-6 col-md-4">
                     						<a href="" data-toggle="modal" data-target="#Agregar"><img width="100px"  src="backend/images/stock-in.png"></a>
                    					</div>
                    					<div class="col-sm-6 col-xs-6 col-md-4">
                      						<a href="" data-toggle="modal" data-target="#Eliminar"><img width="100px"  src="backend/images/stock-out.png"></a>
                    					</div>
                    					<div class="col-sm-12 margin-btm-10">
                   	 					</div>                   
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

<!-- Modal -->
<div class="modal fade" id="Agregar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Agregar stock</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form id="modal-form" action="" method="post">
            <div class="form-group">
                  <label for="recipient-name" class="control-label"><b>Unidades a agregar *:</b></label>
                  <input type="text" class="form-control" name="unidades_a" required>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" name="add" value="Sign up">Agregar</button>
         </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="Eliminar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Eliminar stock</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form id="modal-form" action="" method="post">
            <div class="form-group">
                  <label for="recipient-name" class="control-label"><b>Unidades a eliminar *:</b></label>
                  <input type="text" class="form-control" name="unidades_d" required>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" name="del" value="Sign up">Agregar</button>
         </form>
      </div>
    </div>
  </div>
</div>


<!-- Modal -->
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
                <label for="recipient-name" class="control-label"><b>Nota *:</b></label>
                <textarea class="form-control" rows="5" name="nota"><?php echo $RowId['nota'];?></textarea>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" name="modi" value="Sign up">Agregar</button>
         </form>
      </div>
    </div>
  </div>
</div>
