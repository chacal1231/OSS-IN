<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
<?php
if (isset($_POST['post'])) {
	//POST

	$ref 		=	mysqli_real_escape_string($link,$_POST['ref']);
	$des 		=	mysqli_real_escape_string($link,$_POST['des']);
	$marca 		=	mysqli_real_escape_string($link,$_POST['marca']);
	$fecha_c 	=	mysqli_real_escape_string($link,$_POST['fecha_c']);
	$unid 		=	mysqli_real_escape_string($link,$_POST['unid']);
	$nota 		=	mysqli_real_escape_string($link,$_POST['nota']);
	$proyecto	=	"Gen";

	//Mysql
	$result     = 	mysqli_query($link,"SELECT * FROM herra order by id DESC");
    $row        = 	mysqli_fetch_array($result);            
    $id         =	($row["id"]+1);
	mysqli_query($link,"INSERT INTO herra(id,ref,marca,fecha_c,unid,nota,proyecto) VALUES('$id','$des','$marca','$fecha_c','$unid','$nota','$proyecto')");
	$my_error = mysqli_error($link);
	if(empty($my_error)){
			echo '<div class="alert alert-success" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <strong>¡Todo correcto!</strong> Se ha añadido correctamente el producto.</div>';
    }else{
    	echo $my_error;
    }   
}
//Query tabla
$QueryTabla =	mysqli_query($link,"SELECT * FROM herra ORDER BY id DESC");
$RowTabla	=	mysqli_fetch_array($QueryTabla);
?>

<div class="row">
    <div class="col-sm-12">
        <section class="panel">
            <header class="panel-heading">
                Inventario de herramientas 
            </header>
            <section class="panel">
                        <div class="panel-body"> 
                        	<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Agregar herramientas</button>
                            <hr/>
                            <div class="table-responsive">
                                <table  class="display table table-bordered table-striped" id="dynamic-table">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Referencia</th>
                                            <th>Marca</th>
                                            <th>Fecha compra</th>
                                            <th>Unidades</th>
                                            <th>Descripción</th>
                                            <th>Eliminar stock</th>
                                            <th>Agregar stock</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach( $QueryTabla as $RowTabla => $field ) : ?> <!-- Mulai loop -->
                                        <tr class="text-besar">
                                            <td><?php echo $field['id']; ?></td>
                                            <td><?php echo $field['ref']; ?></td>
                                            <td><?php echo $field['marca']; ?></td>
                                            <td><?php echo $field['fecha_c']; ?></td>
                                            <td><?php echo $field['unid']; ?></td>
                                            <td><?php echo $field['nota']; ?></td>
                                            <td>
                                                <a class="btn btn-danger btn-xs" href="?page=eli_sc&id=<?php echo $field['frec']; ?>" title="Eliminar" onClick="return confirm('Do you want delete this?')">
                                                    <i class="fa fa-trash-o"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="btn btn-success btn-xs" href="?page=eli_sc&id=<?php echo $field['frec']; ?>" title="Eliminar" onClick="return confirm('Do you want delete this?')">
                                                    <i class="fa fa-plus"></i>
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


<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Nueva herramienta</h4>
      </div>
      <div class="modal-body">
      	 <form id="modal-form" action="" method="post">
      	 	<div class="form-group">
      	 		<label for="recipient-name" class="control-label"><b>Referencia *:</b></label>
                <input type="text" class="form-control" name="ref" required>
			</div>
			<div class="form-group">
      	 		<label for="recipient-name" class="control-label"><b>Descripción *:</b></label>
                <input type="text" class="form-control" name="des" required>
			</div>
			<div class="form-group">
      	 		<label for="recipient-name" class="control-label"><b>Marca *:</b></label>
                <input type="text" class="form-control" name="marca" required>
			</div>
			<div class="form-group">
      	 		<label for="recipient-name" class="control-label"><b>Fecha compra *:</b></label>
                <input type="text" class="form-control" required id="datepicker" data-date-format="yyyy-mm-dd" readonly="readonly" name="fecha_c" required>
			</div>
			<div class="form-group">
      	 		<label for="recipient-name" class="control-label"><b>Unidades *:</b></label>
                <input type="text" class="form-control" name="unid" required>
			</div>
			<div class="form-group">
      	 		<label for="recipient-name" class="control-label"><b>Nota *:</b></label>
                <textarea class="form-control" rows="5" name="nota"></textarea>
			</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
		<button type="submit" class="btn btn-primary" name="post" value="Sign up">Agregar</button>
      </div>
    </div>
    </form>
  </div>
</div>