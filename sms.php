<?php
include (__DIR__.'/../inc/smsGateway.php');
require (__DIR__.'/../inc/config.php');
//SmsGateway
$UsuarioSms="jedmacmahonve@unal.edu.co";
$PasswordSms="jesus00";
$DeviceId="44705";
$options = ['expires_at' => strtotime('+1 hour')];
$smsGateway = new SmsGateway($UsuarioSms, $PasswordSms);
$result2 = $smsGateway->getDevice($DeviceId);
//Calidad
$QueryCalidad=mysqli_query($link,"SELECT * FROM ph order by fecha DESC");
$rowCalidad=mysqli_fetch_array($QueryCalidad);
$pH=$rowCalidad['P'];
if(ph >= 0 AND ph < 5){
		$message="¡Alerta, Autoquarium le informa que el nivel de pH en su pecera es critico!";
		$result = $smsGateway->sendMessageToNumber($value['3214262616'] , $message, $DeviceId, $options);
}
?>