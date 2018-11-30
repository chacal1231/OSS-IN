<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
<?php
if (isset($_POST['post'])) {
	//POST

	$ref         =	mysqli_real_escape_string($link,$_POST['ref']);
	$des 	 	     =	mysqli_real_escape_string($link,$_POST['des']);
	$marca 	     =	mysqli_real_escape_string($link,$_POST['marca']);
  $ti          =  mysqli_real_escape_string($link,$_POST['ti']);
  $prov        =  mysqli_real_escape_string($link,$_POST['prov']);
	$fecha_c 	   =	mysqli_real_escape_string($link,$_POST['fecha_c']);
  $valor       =  mysqli_real_escape_string($link,$_POST['valor']);
	$unid 		   =	mysqli_real_escape_string($link,$_POST['unid']);
	$nota 		   =	mysqli_real_escape_string($link,$_POST['nota']);
  $estado      =  mysqli_real_escape_string($link,$_POST['estado']);
  $total_p     =  ($unid*$valor);
  $asig        =  "No";
  $proyecto    =  "NA";
  $motivoas    =  "NA";

  $mes = date("F");
  if ($mes=="January") $mes="Enero";
  if ($mes=="February") $mes="Febrero";
  if ($mes=="March") $mes="Marzo";
  if ($mes=="April") $mes="Abril";
  if ($mes=="May") $mes="Mayo";
  if ($mes=="June") $mes="Junio";
  if ($mes=="July") $mes="Julio";
  if ($mes=="August") $mes="Agosto";
  if ($mes=="September") $mes="Septiembre";
  if ($mes=="October") $mes="Octubre";
  if ($mes=="November") $mes="Noviembre";
  if ($mes=="December") $mes="Diciembre";

	//Mysql
	$result     = 	mysqli_query($link,"SELECT * FROM inventario ORDER BY id DESC");
  $row        = 	mysqli_fetch_array($result);            
  $id         =	($row["id"]+1);
	mysqli_query($link,"INSERT INTO inventario(id,ref,des,marca,ti,prov,fecha_c,mes,precio,total_p,unid,asig,proyecto,locacion,equipo,motivoas,estado) VALUES('$id','$ref','$des','$marca','$ti','$prov','$fecha_c','$mes','$valor','$total_p','$unid','$asig','$proyecto','$proyecto','$proyecto','$motivoas','$estado')");
  $my_error = mysqli_error($link);
    echo $my_error;
	
    //Actualizar registro
    $fecha          =   date('Y-m-d');
    $hora           =   date('h:i:s A');
    $result_reg     =   mysqli_query($link,"SELECT * FROM registro ORDER BY id DESC");
    $row_reg        =   mysqli_fetch_array($result_reg);            
    $id_reg         =   ($row_reg["id"]+1);
    $des            =   "$_SESSION[name] agregó el producto $des al inventario";
    mysqli_query($link,"INSERT INTO registro(id,fecha,hora,des,ref,total) VALUES('$id_reg','$fecha','$hora','$des','$ref','$unid')");
    $my_error = mysqli_error($link);
    echo $my_error;
    if(empty($my_error)){
            echo '<div class="alert alert-success" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <strong>¡Todo correcto!</strong> Se ha añadido correctamente el producto.</div>';
    }else{
        echo $my_error;
    }
}
//Query tabla
$QueryTabla =	mysqli_query($link,"SELECT * FROM inventario ORDER BY id DESC");
$RowTabla	=	mysqli_fetch_array($QueryTabla);
?>

<div class="row">
    <div class="col-sm-12">
        <section class="panel">
            <header class="panel-heading">
                Inventario OSS
            </header>
            <section class="panel">
                        <div class="panel-body"> 
                        	<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Agregar item</button>
                            <hr/>
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
                        </div>
                    </section>
        </div>
    </section>
</div>

<script>
$(document).ready(function(){
 
 $('#prov').typeahead({
  source: function(query, result)
  {
   $.ajax({
    url:"fetch.php",
    method:"POST",
    data:{query:query},
    dataType:"json",
    success:function(data)
    {
     result($.map(data, function(item){
      return item;
     }));
    }
   })
  }
 });
 
});
</script>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Nuevo repuesto</h4>
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
                  <label for="recipient-name" class="control-label"><b>Tipo de item *:</b></label>
                                <select id="ti" name="ti" class="form-control">
                                <option value="">-- Seleccionar --</option>
                                <option value="Activo fijo">Activo fijo</option>
                                <option value="Materiales y herramientas">Materiales y herramientas</option>
                                <option value="Consumibles">Consumibles</option>
                                <option value="Equipo de oficina">Equipo de oficina</option>
                                </select>
                            </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label"><b>Proveedor *:</b></label>
                <input type="text" class="form-control" name="prov" id="prov" required>
            </div>
			<div class="form-group">
      	 		<label for="recipient-name" class="control-label"><b>Fecha compra *:</b></label>
                <input type="text" class="form-control" required id="datepicker" data-date-format="yyyy-mm-dd" readonly="readonly" name="fecha_c" required>
			</div>
            <div class="form-group">
                <label for="recipient-name" class="control-label"><b>Valor producto *:</b></label>
                <input type="text" class="form-control" name="valor" required>
            </div>
			<div class="form-group">
      	 		<label for="recipient-name" class="control-label"><b>Unidades *:</b></label>
                <input type="text" class="form-control" name="unid" required>
			</div>
      <div class="form-group">
            <label for="recipient-name" class="control-label"><b>Estado *:</b></label>
                <select id="estado" name="estado" class="form-control">
                <option value="">-- Seleccionar --</option>
                <option value="Operacional">Operacional</option>
                <option value="En reparacion">En reparación</option>
                <option value="Stock">Stock</option>
                </select>
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