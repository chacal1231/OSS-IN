<?php 
	//Configuración servidor Mysql
	$host = "localhost"; 
	$user = "root";
	$pass = "jesus00**"; 
	$database ="inventory";
	$link=mysqli_connect($host,$user,$pass,$database); 
	$tanggal=date("d/m/Y");

	//Variables sistema
	$fechaCorte="2";

	//Configuración factura empresa
	$nombre_empresa="Grupo SITEL S.A.S";
	$direccion_empresa="Carrera 28 # 6-9";
	$ciudad_empresa="Popayan";
	$pais_empresa="Colombia";
	$telefono_empresa="310 393 3042";
	$nit_empresa="1058671453-3";
	$correo_empresa="ventas@grupositelsas.com.co";
	$paginaweb_empresa="http://www.grupositelsas.com";
	$iva_empresa="16%";
	
	//Correo 
	$usuarioSmtp="recordatorio@oss.com.co";
	$contraseñaSmtp="1&m(O0_th.H@";
	$smtp="host5.latinoamericahosting.com";
	$SMTPSecure="tls";
	$port="587";
	$SMTPAuth=true;
?>