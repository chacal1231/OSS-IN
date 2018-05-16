<?php
?><link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
<?php
if (isset($_POST['post'])) {
    //POST

    $proyecto        =   mysqli_real_escape_string($link,$_POST['proyecto']);
    $locacion        =   mysqli_real_escape_string($link,$_POST['locacion']);
    $tipo            =   mysqli_real_escape_string($link,$_POST['tipo']);
    $serial          =   mysqli_real_escape_string($link,$_POST['serial']);
    $estado          =   mysqli_real_escape_string($link,$_POST['estado']);

    //Mysql
    $result     =   mysqli_query($link,"SELECT * FROM locacion ORDER BY id DESC");
    $row        =   mysqli_fetch_array($result);            
    $id         =   ($row["id"]+1);
    $fecha_c	= 	date('Y-m-d');
    mysqli_query($link,"INSERT INTO equipos(id,proyecto,locacion,tipo,serial,estado) VALUES('$id','$proyecto','$locacion','$tipo','$serial','$estado')");
    $my_error = mysqli_error($link);
    if(empty($my_error)){
            echo '<div class="alert alert-success" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <strong>¡Todo correcto!</strong> Se ha añadido correctamente el equipo.</div>';
    }else{
        echo $my_error;
    }
}
//Query tabla
$QueryTabla =   mysqli_query($link,"SELECT * FROM equipos ORDER BY id ASC");
$RowTabla   =   mysqli_fetch_array($QueryTabla);
?>


<script>
$(document).ready(function(){
 
 $('#country').typeahead({
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

<div class="row">
    <div class="col-sm-12">
        <section class="panel">
            <header class="panel-heading">
                Equipos
            </header>
            <section class="panel">
                        <div class="panel-body"> 
                            <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Agregar nuevo equipo</button>
                            <hr/>
                            <div class="table-responsive">
                                <table  class="display table table-bordered table-striped" id="dynamic-table">
                                    <thead>
                                        <tr>
                                            <th>Nombre proyecto</th>
                                            <th>Locación</th>
                                            <th>Tipo de equipo</th>
                                            <th>Serial equipo</th>
                                            <th>Estado del equipo</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach( $QueryTabla as $RowTabla => $field ) : ?> <!-- Mulai loop -->
                                        <tr class="text-besar">
                                            <td><?php echo $field['proyecto']; ?></td>
                                            <td><?php echo $field['locacion']; ?></td>
                                            <td><?php echo $field['tipo']; ?></td>
                                            <td><?php echo $field['serial']; ?></td>
                                            <td><?php echo $field['estado']; ?></td>
                                            <td>
                                                <a class="btn btn-success btn-xs" target="_blank" href="?page=gen/producto&ref=<?php echo $field['ref']; ?>&mod=<?php echo $modulo; ?>" title="Editar">
                                                    <i class="fa fa-pencil"></i>
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

<?php
$QueryProyecto	= mysqli_query($link,"SELECT * FROM proyectos ORDER BY id ASC");
$RowProyecto	= mysqli_fetch_array($QueryProyecto);
?>

<?php
$QueryLocacion  = mysqli_query($link,"SELECT * FROM locacion ORDER BY id ASC");
$RowLocacion  = mysqli_fetch_array($QueryLocacion);
?>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Nuevo equipo</h4>
      </div>
      <div class="modal-body">
         <form id="modal-form" action="" method="post">
          <div class="form-group">
          	<label for="recipient-name" class="control-label"><b>Proyecto *:</b></label>
          	<select id="plan" name="proyecto" class="form-control">
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
                      <option value="<?php echo $RowLocacion['locacion']?>">
                          <?php echo $RowLocacion['locacion']; ?>
                        </option>
                        <?php
                        }while ($RowLocacion = $QueryLocacion->fetch_assoc())   ?>
            </select>
         </div>
             <div class="form-group">
                <label for="recipient-name" class="control-label"><b>Tipo de equipo *:</b></label>
                <select id="direccion" name="tipo" class="form-control">
                	<option value="">-- Seleccionar --</option>
                	<option value="Bomba">Bomba</option>
					        <option value="Medidor multifasico">Medidor multifasico</option>
                  <option value="Unidad de cementación">Unidad de cementación</option>
				        </select>
             </div>
         <div class="form-group">
                <label for="recipient-name" class="control-label"><b>Serial del equipo *:</b></label>
                <input type="text" class="form-control" name="serial" required>
         </div>
         <div class="form-group">
                <label for="recipient-name" class="control-label"><b>Estado del equipo *:</b></label>
                <select id="direccion" name="estado" class="form-control">
                  <option value="">-- Seleccionar --</option>
                  <option value="Funcional">Funcional</option>
          <option value="No funcional">No funcional</option>
        </select>
             </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" name="post" value="Sign up">Agregar</button>
      </div>
    </form>
  </div>
</div>