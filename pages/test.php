<?php
	
	$result = mysqli_query($link,"SELECT * FROM user WHERE id_user='2'");
	$row = mysqli_fetch_array($result);
	require 'inc/PHPMailer/PHPMailerAutoload.php';

	$mail = new PHPMailer;
    //$mail->SMTPDebug = SMTP::DEBUG_CONNECTION;
    //$mail->Debugoutput = 'html';
    $mail->isSMTP();
    $mail->CharSet = 'UTF-8';
    $mail->Host = $smtp;
    $mail->SMTPAuth = $SMTPAuth;
    $mail->Username = $usuarioSmtp;
    $mail->Password = $contraseñaSmtp;
    $mail->SMTPSecure = $SMTPSecure;
    $mail->SMTPAuth   = true;
    $mail->Port = $port;


	foreach ($result as $row => $value) {
		$mail->setFrom('sistemas@oss.com.co', 'OSS Sistema');
    	$mail->addAddress("$value[correo]");
    	$mail->Subject = "Credenciales de acceso al sistema de inventario y requisiciones";
    	$mail->Body = "Hola $value[name_user], OSS le informa que las credenciales de acceso para acceder al sistema de inventario son las siguientes.


    Usuario: $value[username]
    Contraseña: $value[password]
    para acceder al sistema debe ingresar a inventario.oss.com.co.

Atentamente.

David Macmahon
Administrador del sistema OSS";  
    	$mail->send();
		echo "$value[correo]";
		echo "<br>"; 		
}


?>