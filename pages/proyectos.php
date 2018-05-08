<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
<?php
if (isset($_POST['post'])) {
    //POST

    $nit        =   mysqli_real_escape_string($link,$_POST['nit']);
    $nom        =   mysqli_real_escape_string($link,$_POST['nom']);
    $dir        =   mysqli_real_escape_string($link,$_POST['dir']);
    $ciu        =   mysqli_real_escape_string($link,$_POST['ciu']);
    $tel        =   mysqli_real_escape_string($link,$_POST['tel']);
    $clas       =   mysqli_real_escape_string($link,$_POST['clas']);

    //Mysql
    $result     =   mysqli_query($link,"SELECT * FROM prov ORDER BY id DESC");
    $row        =   mysqli_fetch_array($result);            
    $id         =   ($row["id"]+1);
    mysqli_query($link,"INSERT INTO prov(id,nit,nombre,dir,ciudad,tel,clas) VALUES('$id','$nit','$nom','$dir','$ciu','$tel','$clas')");
    $my_error = mysqli_error($link);
    if(empty($my_error)){
            echo '<div class="alert alert-success" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <strong>¡Todo correcto!</strong> Se ha añadido correctamente el proveedor.</div>';
    }else{
        echo $my_error;
    }
}
//Query tabla
$QueryTabla =   mysqli_query($link,"SELECT * FROM prov ORDER BY id ASC");
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
                Proveedores
            </header>
            <section class="panel">
                        <div class="panel-body"> 
                            <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Agregar proveedor</button>
                            <hr/>
                            <div class="table-responsive">
                                <table  class="display table table-bordered table-striped" id="dynamic-table">
                                    <thead>
                                        <tr>
                                            <th>NIT</th>
                                            <th>Nombre</th>
                                            <th>Dirección</th>
                                            <th>Ciudad</th>
                                            <th>Telefono</th>
                                            <th>Clasificación</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach( $QueryTabla as $RowTabla => $field ) : ?> <!-- Mulai loop -->
                                        <tr class="text-besar">
                                            <td><?php echo $field['nit']; ?></td>
                                            <td><?php echo $field['nombre']; ?></td>
                                            <td><?php echo $field['dir']; ?></td>
                                            <td><?php echo $field['ciudad']; ?></td>
                                            <td><?php echo $field['tel']; ?></td>
                                            <td><?php echo $field['clas']; ?></td>
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
        <h4 class="modal-title">Nuevo aceite/lubricante</h4>
      </div>
      <div class="modal-body">
         <form id="modal-form" action="" method="post">
            <div class="form-group">
                <label for="recipient-name" class="control-label"><b>NIT *:</b></label>
               <input type="text" class="form-control" name="nit" required>
            </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label"><b>Nombre *:</b></label>
                <input type="text" class="form-control" name="nom" required>
            </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label"><b>Dirección *:</b></label>
                <input type="text" class="form-control" name="dir" required>
            </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label"><b>Ciudad *:</b></label>
                <input type="text" class="form-control" name="ciu" required>
            </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label"><b>Telefono *:</b></label>
                <input type="text" class="form-control" name="tel" required>
            </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label"><b>Clase *:</b></label>
                <input type="text" class="form-control" name="clas" required>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" name="post" value="Sign up">Agregar</button>
      </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" name="post" value="Sign up">Agregar</button>
      </div>
    </form>
  </div>
</div>