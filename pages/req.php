<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
<?php
if(isset($_POST['post'])){
	$can   =   mysqli_escape_string($link,$_POST['can']);
    $um    =   mysqli_escape_string($link,$_POST['um']);
    $ref   =   mysqli_escape_string($link,$_POST['ref']);
    $des   =   mysqli_escape_string($link,$_POST['des']);
    $tp    =   mysqli_escape_string($link,$_POST['tp']);

}
?>

<div class="row">
    <div class="col-sm-12">
        <section class="panel">
            <header class="panel-heading">
                Requisiciones
            </header>
            <section class="panel">
                        <div class="panel-body"> 
                            <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Nueva requisición</button>
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



<script type="text/javascript">
function add_row()
{
 $rowno=$("#employee_table tr").length;
 $rowno=$rowno+1;
 $("#employee_table tr:last").after("<tr id='row"+$rowno+"'><td><input type='text' class='form-control' name='can[]' required></td><td><input type='text' class='form-control' name='um[]' required></td><td><input type='text' name='ref[]' class='form-control' required></td><td><input type='text' name='des[]' class='form-control' required ></td><<td><select id='direccion' name='tp[]' class='form-control'><option value=''>----</option><option value=1>Consumible</option><option value=2>Inventario</option><option value=3>Servicio</option></select></td><td><input type='button' value='-' onclick=delete_row('row"+$rowno+"')></td></tr>");
}
function delete_row(rowno)
{
 $('#'+rowno).remove();
}
</script>
<style type="text/css">
    .custom-height-modal {
         width: 900px;
    }
} 
</style>


<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Nueva requisición</h4>
      </div>
        <div class="modal-body custom-height-modal">
            <form id="modal-form" action="" method="post">
            <label for="recipient-name" class="control-lab  el"><b>Prioridad *:</b></label>
            <select id="direccion" name="direccion" class="form-control">
                <option value="">-- Seleccionar --</option>
                <option value="1">Alta</option>
                <option value="2">Media</option>
                <option value="3">Baja</option>
            </select>

            <br>
            <div class="table-responsive">
                                <table  class="table table-bordered table-hover" id="employee_table">
                                    <thead>
                                        <tr class="custom">
                                            <th>Cantidad</th>
                                            <th>Unidad de medida</th>
                                            <th>Referencia/Talla</th>
                                            <th>Descripción</th>
                                            <th>Tipo de compra</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="row1">
                                            <td><input type="text" class="form-control" name="can[]" required></td>
                                            <td><input type="text" class="form-control" name="um[]" required></td>
                                            <td><input type="text" class="form-control" name="ref[]" required></td>
                                            <td><input type="text" class="form-control" name="des[]" required></td>
                                            <td><select id="direccion" name="tp[]" class="form-control"><option value="">----</option><option value="1">Consumible</option><option value="2">Inventario</option><option value="3">Servicio</option></select></td>
                                        </tr>                                
                                    </tbody>
                                </table>
                            </div>
                            <br>
                 <input type="button" class="btn btn-success" onclick="add_row();" value="Agregar item">
                 <br>
                 <br>
        <div class="form-group">
                <label for="recipient-name" class="control-label"><b>Observaciones *:</b></label>
                <textarea class="form-control" rows="5" name="obs"></textarea>
        </div>

<div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" name="post" value="Sign up">Agregar</button>
      </div>
            </form>
        </div>
     </div>
    </div>
</div>