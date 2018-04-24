<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
<?php
if(isset($_POST['post'])){
	$can   =   $_POST['can'];
    $um    =   $_POST['um'];
    $ref   =   $_POST['ref'];
    $des   =   $_POST['des'];
    $tp    =   $_POST['tp'];
    $prio  =   $_POST['prio'];
    $obs   =   $_POST['obs'];
    $proy  =   $_POST['proy'];

    //Mysql
    $result     =   mysqli_query($link,"SELECT * FROM req ORDER BY id DESC");
    $row        =   mysqli_fetch_array($result);            
    $id         =   ($row["id"]+1);
    //Productos
    $result_p   =   mysqli_query($link,"SELECT * FROM req_productos ORDER BY id DESC");
    $row_p      =   mysqli_fetch_array($result_p);            
    $id_pp      =   ($row_p["id"]+1);
    $con        =   "OSS-REQ-" .$id;
    $cargo      =   "Auxiliar";
    $nom        =   "David Mcmahon"; 
    $fecha_e    =   date('Y-m-d');
    //Prioridad
    if($prio=="Alta"){
        $fecha_r    =   date('Y-m-d', strtotime($stop_date . ' +1 day'));
    }else if($prio=="Media"){
        $fecha_r    =   date('Y-m-d', strtotime($stop_date . ' +2 day'));
    }else if($prio=="Baja"){
        $fecha_r    =   date('Y-m-d', strtotime($stop_date . ' +3 day'));
    }
    $fecha_res  =   "1970-01-01";
    //Ciclo para obtener datos
    for ($i=0; $i < count($can) ; $i++) {
        mysqli_query($link,"INSERT INTO req_productos(id,con,cant,um,ref,des,tp) VALUES('$id_pp','$con','$can[$i]','$um[$i]','$ref[$i]','$des[$i]','$tp[$i]')");
        $id_pp  =   $id_pp + 1;

    }
    mysqli_query($link,"INSERT INTO req(id,con,nom,cargo,proy,obs,fecha_e,fecha_r,fecha_res,prio,estado) VALUES ('$id','$con','$nom','$cargo','$proy','$obs','$fecha_e','$fecha_r','$fecha_res','$prio','0')");
    //Verificación Mysql
    $my_error = mysqli_error($link);
    if(empty($my_error)){
            echo '<div class="alert alert-success" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <strong>¡Todo correcto!</strong> Se ha añadido correctamente el producto.</div>';
    }else{
        echo $my_error;
    }
}

//Mysql para tabla
$QueryTabla = mysqli_query($link,"SELECT * FROM req ORDER BY id DESC");
$RowTabla = mysqli_fetch_array($QueryTabla);
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
                                            <th>Consecutivo</th>
                                            <th>Nombre del solicitante</th>
                                            <th>Fecha de solicitud</th>
                                            <th>Fecha requerimiento</th>
                                            <th>Priodidad</th>
                                            <th>Estado</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach( $QueryTabla as $RowTabla => $field ) : ?> <!-- Mulai loop -->
                                        <tr class="text-besar">
                                            <td><?php echo $field['con']; ?></td>
                                            <td><?php echo $field['nom']; ?></td>
                                            <td><?php echo $field['fecha_e']; ?></td>
                                            <td><?php echo $field['fecha_r']; ?></td>
                                            <td> <?php if($field['prio'] == 'Alta'){ ?>
                                                <button type="button" class="btn btn-danger">Alta</button>

                                            <?php } elseif($field['prio'] == 'Media') { ?>
                                            
                                                <button type="button" class="btn btn-warning">Media</button>


                                            <?php } elseif($field['prio'] == 'Baja') { ?>
                                            
                                                <button type="button" class="btn btn-info">Baja</button>

                                            <?php } ?>  
                                            </td>
                                            <td> <?php if($field['estado'] == '0'){ ?>
                                                <button type="button" class="btn btn-danger">Sin responder</button>

                                            <?php } elseif($field['estado'] == '1') { ?>
                                            
                                                <button type="button" class="btn btn-success">Respondida</button>

                                            <?php } ?> 
                                            </td> 
                                             <td>
                                                <a class="btn btn-success btn-lg" target="_blank" href="?page=gen/producto&ref=<?php echo $field['ref']; ?>&mod=<?php echo $modulo; ?>" title="Ver">
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
            <select id="direccion" name="prio" class="form-control">
                <option value="">-- Seleccionar --</option>
                <option value="Alta">Alta</option>
                <option value="Media">Media</option>
                <option value="Baja">Baja</option>
            </select>
            <br>
            <div class="form-group">
                <label for="recipient-name" class="control-label"><b>Proyecto *:</b></label>
                <input type="text" class="form-control" name="proy" required>
            </div>

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
            <button type="button" class="btn btn-danger btn-lg" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-success btn-lg" name="post" value="Sign up">Enviar</button>
        </div>
            </form>
        </div>
     </div>
    </div>
</div>