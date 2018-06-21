<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
<?php
if (isset($_POST['post'])) {
    //POST

    $proyecto        =   mysqli_real_escape_string($link,$_POST['proyecto']);
    $ciudad          =   mysqli_real_escape_string($link,$_POST['ciudad']);
    $estado          =   mysqli_real_escape_string($link,$_POST['estado']);

    //Mysql
    $result     =   mysqli_query($link,"SELECT * FROM proyectos ORDER BY id DESC");
    $row        =   mysqli_fetch_array($result);            
    $id         =   ($row["id"]+1);
    $fecha_c	= 	date('Y-m-d');
    mysqli_query($link,"INSERT INTO proyectos(id,nombre,ciudad,fecha_c,estado) VALUES('$id','$proyecto','$ciudad','$fecha_c','$estado')");
    $my_error = mysqli_error($link);
    if(empty($my_error)){
            echo '<div class="alert alert-success" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <strong>¡Todo correcto!</strong> Se ha añadido correctamente el proyecto.</div>';
    }else{
        echo $my_error;
    }
}
//Query tabla
$QueryTabla =   mysqli_query($link,"SELECT * FROM proyectos ORDER BY id ASC");
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
                Proyectos-Bases/Centros de costos
            </header>
            <section class="panel">
                        <div class="panel-body"> 
                            <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Agregar nuevo Proyecto/Base/Centros de costos</button>
                            <hr/>
                            <div class="table-responsive">
                                <table  class="display table table-bordered table-striped" id="dynamic-table">
                                    <thead>
                                        <tr>
                                            <th>Nombre proyecto/Centro de costo</th>
                                            <th>Ubicación/Ciudad</th>
                                            <th>Estado</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach( $QueryTabla as $RowTabla => $field ) : ?> <!-- Mulai loop -->
                                        <tr class="text-besar">
                                            <td><?php echo $field['nombre']; ?></td>
                                            <td><?php echo $field['ciudad']; ?></td>
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


<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Nuevo proyecto/Base/Centros de costos</h4>
      </div>
      <div class="modal-body">
         <form id="modal-form" action="" method="post">
            <div class="form-group">
                <label for="recipient-name" class="control-label"><b>Nombre de proyecto/Base/centros de costo *:</b></label>
               <input type="text" class="form-control" name="proyecto" required>
            </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label"><b>Ubicación (Ciudad) *:</b></label>
                <input type="text" class="form-control" name="ciudad" required>
            </div>
             <div class="form-group">
                <label for="recipient-name" class="control-label"><b>Estado del proyecto *:</b></label>
                <select id="direccion" name="estado" class="form-control">
                	<option value="">-- Seleccionar --</option>
                	<option value="Activo">Activo</option>
					<option value="Inactivo">Inactivo</option>
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