<?php
if(isset($_POST['agregar'])){
  $nombre=mysqli_real_escape_string($link,$_POST['nombre']);
  $usuario=mysqli_real_escape_string($link,$_POST['usuario']);
  $pass=md5(mysqli_real_escape_string($link,$_POST['pass']));
  $priv=mysqli_real_escape_string($link,$_POST['priv']);
  $area=mysqli_real_escape_string($link,$_POST['area']);
  $cargo=mysqli_real_escape_string($link,$_POST['cargo']);
  $cedula=mysqli_real_escape_string($link,$_POST['cedula']);
  $tel=mysqli_real_escape_string($link,$_POST['tel']);
  $correo=mysqli_real_escape_string($link,$_POST['correo']);



  mysqli_query($link,"INSERT INTO user(name_user,username,password,priv,area,cargo,cedula,telefono,correo) VALUES('$nombre','$usuario','$pass','$priv','$area','$cargo','$cedula','$tel','$correo')");

  echo '<div class="alert alert-success" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <strong>¡Todo correcto!</strong> Se ha añadido correctamente el usuario.</div>';

}
$QueryUsuario=mysqli_query($link,"SELECT * FROM user");
$RowUsuario=mysqli_fetch_array($QueryUsuario);
?>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Nuevo usuario</h4>
      </div>
      <div class="modal-body">
      	 <form id="modal-form" action="" method="post">
      	 	<div class="form-group">
      	 		<label for="recipient-name" class="control-label"><b>Nombre completo *:</b></label>
                <input type="text" class="form-control" name="nombre" required>
			</div>
			<div class="form-group">
      	 		<label for="recipient-name" class="control-label"><b>Usuario *:</b></label>
                <input type="text" class="form-control" name="usuario" required>
			</div>
			<div class="form-group">
      	 		<label for="recipient-name" class="control-label"><b>Contraseña *:</b></label>
                <input type="password" class="form-control" name="pass" required>
			</div>
       <div class="form-group">
                  <label for="recipient-name" class="control-label"><b>Privilegios *:</b></label>
                    <select id="ti" name="priv" class="form-control">
                      <option value="">-- Seleccionar --</option>
                      <option value="0">Administrador</option>
                      <option value="2">Usuario común</option>
                    </select>
      </div>
            <div class="form-group">
                <label for="recipient-name" class="control-label"><b>Area de trabajo *:</b></label>
                <input type="text" class="form-control" name="area" required>
            </div>
			<div class="form-group">
      	 		<label for="recipient-name" class="control-label"><b>Cargo *:</b></label>
                 <input type="text" class="form-control" name="cargo" required>
			</div>
            <div class="form-group">
                <label for="recipient-name" class="control-label"><b>Documento de identidad *:</b></label>
                <input type="text" class="form-control" name="cedula" required>
            </div>
			<div class="form-group">
      	 		<label for="recipient-name" class="control-label"><b>Telefono *:</b></label>
                <input type="text" class="form-control" name="tel" required>
			</div>
      <div class="form-group">
            <label for="recipient-name" class="control-label"><b>Correo electronico *:</b></label>
                <input type="text" class="form-control" name="correo" required>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
		<button type="submit" class="btn btn-primary" name="agregar">Agregar</button>
      </div>
    </div>
    </form>
  </div>
</div>


<div class="row">
    <div class="col-sm-12">
        <section class="panel">
            <header class="panel-heading">
                Usuarios
            </header>
            <section class="panel">
                        <div class="panel-body"> 
                          <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Agregar usuario</button>
                            <hr/>
                            <div class="table-responsive">
                                <table  class="display table table-bordered table-striped" id="dynamic-table">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Usuario</th>
                                            <th>Privilegio</th>
                                            <th>Cargo</th>
                                            <th>Telefono</th>
                                            <th>Correo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach( $QueryUsuario as $RowUsuario => $field ) : ?> <!-- Mulai loop -->
                                        <tr class="text-besar">
                                            <td><?php echo $field['name_user']; ?></td>
                                            <td><?php echo $field['username']; ?></td>
                                            <td><?php echo $field['priv']; ?></td>
                                            <td><?php echo $field['cargo']; ?></td>
                                            <td><?php echo $field['telefono']; ?></td>
                                            <td><?php echo $field['correo']; ?></td>                                             
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
