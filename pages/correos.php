<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
<style>
.datepicker{z-index:1151 !important;}
</style>
<?php
$id=mysqli_real_escape_string($link,$_GET['id']);
$result=mysqli_query($link,"SELECT * FROM clientes WHERE id='$id'");
$row=mysqli_fetch_array($result);

$result2=mysqli_query($link,"SELECT * FROM planes");
$row2=mysqli_fetch_array($result2);

if(empty($_POST['name'])){
  echo "<body onLoad=$('#myModal').modal('show')>";
}else
?>
 <?php
if(isset($_POST['simpan'])){
  $nombre=mysqli_real_escape_string($link,$_POST['name']);
  $ip=mysqli_real_escape_string($link,$_POST['ip']);
  $plan=mysqli_real_escape_string($link,$_POST['plan']);
  $telefono=mysqli_real_escape_string($link,$_POST['telefono']);
  $correo=mysqli_real_escape_string($link,$_POST['correo']);
  $usuario=mysqli_real_escape_string($link,$_POST['cedula']);
  $cedula=$usuario;
  $ciudad=mysqli_real_escape_string($link,$_POST['ciudad']);
  $direccion=mysqli_real_escape_string($link,$_POST['direccion']);
  $contra=mysqli_real_escape_string($link,$_POST['contrasena']);
  $antena=mysqli_real_escape_string($link,$_POST['antena']);
  $macantena=mysqli_real_escape_string($link,$_POST['macantena']);
  $serialantena=mysqli_real_escape_string($link,$_POST['serialantena']);
  $router=mysqli_real_escape_string($link,$_POST['router']);
  $macrouter=mysqli_real_escape_string($link,$_POST['macrouter']);
  $serialrouter=mysqli_real_escape_string($link,$_POST['serialrouter']);
  $contra2=md5($contra);
  $tipo_u=mysqli_real_escape_string($link,$_POST['tipo_u']);


  	//Query ver velocidad plan
  	$query_vel_plan=mysqli_query($link,"SELECT * FROM planes WHERE nombre='$plan'");
  	$row_query_vel_plan=mysqli_fetch_array($query_vel_plan);
  
  
  	//Modificaciones a Mikrotik
	$QUEUES = $API->comm("/queue/simple/set", array(
    "numbers"			=> $row['nombre'],
    "name"				=> $nombre,
    "max-limit"			=> $row_query_vel_plan['vel'],
    "parent"			=> $plan,
    "target"			=> $ip));

    $query=mysqli_query($link,"UPDATE clientes SET nombre='$nombre',ip='$ip',plan='$plan',telefono='$telefono',correo='$correo',usuario='$usuario',ciudad='$ciudad',direccion='$direccion',contrasena='$contra2',password='$contra',cedula='$cedula',tipo_u='$tipo_u',antena='$antena',macantena='$macantena',serialantena='$serialantena',router='$router',macrouter='$macrouter',serialrouter='$serialrouter',precio='$row_query_vel_plan[precio]' WHERE id='$id'");
  $error=mysqli_error($link);
  if($error){
    echo $error;
  }else{
    $name2=$row['nombre'];
    echo "<br><br><br><br><br><br><br><br>";
    echo '<div align="center">';
    echo "<h1>Se ha modificado correctamente el usuario <font color=red><b>$name2</b></font></h1>";
    echo "</div>";
    echo "</br></br></br></br></br></br></br></br>";

  }
  echo "<script>setTimeout(\"location.href = '?page=clientes';\", 3000);</script>";
}else
{
  
}

  ?>
  
  <?php
$result = mysqli_query($link,"SELECT * FROM clientes WHERE id='$id' order by id");
$row = mysqli_fetch_array($result)
?>

<?php
$result2 = mysqli_query($link,"SELECT nombre FROM planes ORDER BY id asc");
$row2 = mysqli_fetch_array($result2)
?>
 <!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close"><a onclick="history.back(-1)">&times;</a></button>
        <h4 class="modal-title">Modificar a <?=$row['nombre'];?></h4>
      </div>
	<div class="modal-body">
		<form id="modal-form" action="" method="post">
			<div class="form-group">
				<label for="recipient-name" class="control-label">Nombre:</label>
				<input type="text" class="form-control" value="<?=$row['nombre'];?>" name="name">
			</div>
			<div class="form-group">
				<label for="recipient-name" class="control-label">Cedula/NIT:</label>
				<input type="text" class="form-control" value="<?=$row['cedula'];?>" name="cedula">
			</div>
			<div class="form-group">
				<label for="recipient-name" class="control-label">No. celular:</label>
				<input type="text" class="form-control" value="<?=$row['telefono'];?>" name="telefono">
			</div>
			<div class="form-group">
				<label for="recipient-name" class="control-label">Correo electronico:</label>
				<input type="text" class="form-control" value="<?=$row['correo'];?>" name="correo">
			</div>
			<div class="form-group">
				<label for="recipient-name" class="control-label">Ciudad de residencia:</label>
				<select id="ciudad" name="ciudad" class="form-control">
					<option value="Plateado, Argelia C">Plateado, Argelia C</option>
					<option value="Timbiqui Cauca">Timbiqui Cauca</option>
				</select>
			</div>
			<div class="form-group">
				<label for="recipient-name" class="control-label"><b>Dirección de residencia *:</b></label>
				<select id="direccion" name="direccion" class="form-control">
					<option value="">-- Seleccionar --</option>
					<option value="Barrio, 7 De Agosto">7 De Agosto</option>
					<option value="Barrio, Brisas del Rio">Brisas del Rio</option>
					<option value="Barrio, El Artesano">El Artesano</option>
					<option value="Barrio, El Jardín">El Jardín</option>
					<option value="Barrio, El Modelo">El Modelo</option>
					<option value="Barrio, El Poblado">El Poblado</option>
					<option value="Barrio, El Polideportivo">El Polideportivo</option>
					<option value="Barrio, La Paz">La Paz</option>
					<option value="Barrio, Olímpica">Olímpica</option>
					<option value="Barrio, Pueblo Nuevo">Pueblo Nuevo</option>
				</select>
			</div>
			<div class="form-group">
				<label for="recipient-name" class="control-label">Tipo de usuario:</label>
				<select id="tipo_u" name="tipo_u" class="form-control">
				<option value="Residencial">Residencial</option>
				<option value="Comercial">Comercial</option>
				</select>
			</div>
			<div class="form-group">
				<label for="recipient-name" class="control-label">Plan:</label>
				<select id="plan" name="plan" class="form-control">
				<?php				
					do 
					{
						if($row2['nombre']==$row['plan']){
							echo "<option selected=selected>$row[plan]</option>";
						}
						?>
						<option value="<?php echo $row2['nombre']?>">
						<?php echo $row2['nombre']; ?>
						</option>
						<?php
					}while ($row2 = $result2->fetch_assoc())   ?>   
				</select>
			</div>
			<div class="form-group">
				<label for="recipient-name" class="control-label">Dirección Ip:</label>
				<input type="text" class="form-control" name="ip" value="<?=$row['ip'];?>">
			</div>
			<div class="form-group">
				<div class="col-sm-6">
					<label for="recipient-name" class="control-label">Antena instalada:</label>
					<select id="antena" name="antena" class="form-control">
					<option value="">-- Seleccionar --</option>
					<option value="LiteBeam M5 23 dbi">LiteBeam M5 23 dbi</option>
					<option value="Nano Station Loco M5">Nano Station Loco M5</option>
					<option value="PowerBeam M5 300">PowerBeam M5 300</option>
					<option value="PowerBeam M5 400">PowerBeam M5 400</option>
					</select>
				</div>
				<div class="col-sm-6">
					<label for="recipient-name" class="control-label">Router instalado:</label>
					<select id="Router" name="router" class="form-control">
					<option value="">-- Seleccionar --</option>
					<option value="Tenda 150 Mbps">Tenda 150 Mbps</option>
					<option value="Tenda 300 Mbps">Tenda 300 Mbps</option>
					<option value="TP-Link 150 Mbps">TP-Link 150 Mbps</option>
					<option value="TP-Link 300 Mbps">TP-Link 300 Mbps</option>
					</select>
				</div>
			</div>  
			<div class="form-group">
				<div  class="col-sm-6">
					<label for="recipient-name" class="control-label">MAC antena:</label>
					<input type="text" class="form-control" name="macantena" value="<?=$row['macantena'];?>">
				</div> 
				<div  class="col-sm-6">
					<label for="recipient-name" class="control-label">Mac router:</label>
					<input type="text" class="form-control" name="macrouter" value="<?=$row['macrouter'];?>">
				</div> 
			</div> 
			<div class="form-group">
				<div  class="col-sm-6">
					<label for="recipient-name" class="control-label">Serial antena :</label>
					<input type="text" class="form-control" name="serialantena" value="<?=$row['serialantena'];?>">
				</div> 
				<div  class="col-sm-6">
					<label for="recipient-name" class="control-label">Serial router:</label>
					<input type="text" class="form-control" name="serialrouter" value="<?=$row['serialrouter'];?>">
				</div> 
			</div>   
			<div class="form-group">
				<label for="recipient-name" class="control-label">Contraseña panel cliente:</label>
				<input type="text" class="form-control" name="contrasena" value="<?=$row['password'];?>">
			</div>                 
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary" name="simpan" value="Sign up">Guardar cambios</button>
				<td><a class="btn btn-default" href="index.php?page=clientes">Cerrar</a></td>
			</div>
    </div>

  </div>
</div>
</div>