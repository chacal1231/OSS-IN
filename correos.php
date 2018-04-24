<?php
require (__DIR__.'/inc/PHPMailer/PHPMailerAutoload.php');
require (__DIR__.'/inc/config.php');
ini_set('max_execution_time', 30000);
//Fecha
$mes=date("F");
if ($mes=="January") $mes="Enero";
if ($mes=="February") $mes="Febrero";
if ($mes=="March") $mes="Marzo";
if ($mes=="April") $mes="Abril";
if ($mes=="May") $mes="Mayo";
if ($mes=="June") $mes="Junio";
if ($mes=="July") $mes="Julio";
if ($mes=="August") $mes="Agosto";
if ($mes=="September") $mes="Septiembre";
if ($mes=="October") $mes="Octubre";
if ($mes=="November") $mes="Noviembre";
if ($mes=="December") $mes="Diciembre";
//Consulta tabla clientes
$ConsultaCorreos=mysqli_query($link,"SELECT * FROM correos");
$RowCorreos=mysqli_fetch_array($ConsultaCorreos);

$QueryComida=mysqli_query($link,"SELECT sum(C) FROM comida");
$RowComida=mysqli_fetch_array($QueryComida);

$QueryEnergia=mysqli_query($link,"SELECT sum(P) FROM potencia");
$RowEnergia=mysqli_fetch_array($QueryEnergia);

$QuerypH=mysqli_query($link,"SELECT AVG(P) FROM ph");
$RowpH=mysqli_fetch_array($QuerypH);

$QueryComidaAC=mysqli_query($link,"SELECT * FROM comida ORDER BY fecha ASC LIMIT 0,1");
$RowComidaAC=mysqli_fetch_array($QueryComidaAC);
$var1 = "David McMahon"; 


foreach($ConsultaCorreos as $RowCorreos => $field){
  //PHPMailer clase
  $mail = new PHPMailer;
  //$mail->SMTPDebug = SMTP::DEBUG_CONNECTION;
  //$mail->Debugoutput = 'html';
  $mail->isSMTP();
  $mail->Host = $smtp;
  $mail->SMTPAuth = $SMTPAuth;
  $mail->Username = $usuarioSmtp;
  $mail->Password = $contraseñaSmtp;
  $mail->SMTPSecure = $SMTPSecure;
  $mail->SMTPAuth   = true;
  $mail->Port = $port;
 
  $mail->setFrom('sistemas@oss.com.co', 'OSS Mantenimiento');
  $mail->addAddress("jedmacmahonve@unal.edu.co");
  $mail->Subject = "Recordatorio mantenimiento";
  $message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<?xml version="1.0" encoding="windows-1252"?><html>
<head>
<title>Notificacion OSS</title>

</head>
<body style="font-family: Helvetica, Arial, sans-serif;">
<div style="color:transparent;visibility:hidden;opacity:0;font-size:0px;border:0;max-height:1px;width:1px;margin:0px;padding:0px;border-width:0px!important;display:none!important;line-height:0px!important;"><img border="0" width="1" height="1" src="http://post.spmailtechnolo.com/q/i_gS1CG1pbpp1ccj2R2ubA~~/AABjOAA~/RgRcexhMPVcDc3BjWAQAAAAAQgoAA0yTmFpHzvILUhlkYXZpZC5tYWNtYWhvbkBvc3MuY29tLmNv"></div>
<table width="595" border="0" align="center" cellpadding="0" cellspacing="0">
<tbody>
<tr>
<td><img src="http://www.oss.com.co/images/nav-logo.png" width="606" height="141" alt="" style=" display:block"></td>
</tr>
<tr>
<td bgcolor="#DEDEDE">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
<tbody>
<tr>
<td bgcolor="#FFFFFF">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td height="39" bgcolor="#337726">
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
<tbody>
<tr>
<td style="font-family: Arial, sans-serif; font-size:20px"><font face="arial" color="#FFFFFF">Aviso de mantenimiento</font></td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr>
<td bgcolor="#FFFFFF">
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
<tbody>
<tr>
<td style="font-family:arial; font-size:16px;padding-top:20px;"><font face="Arial" color="#262626"><br>';
$message .="<strong>OSS le informa que,</strong><br></font></td>";
$message .='</tr>
<tr>
<td height="20"></td>
</tr>
<tr>';
$message .="<td style=font-size:16px><font face=Arial color=#262626>El mantenimiento del generador <b><font color=#A6C307>$gen</font></b> debe realizarse de inmediato.</td>";
$message .= '
</tr>
<tr>
<td>&nbsp;</td>
</tr>
<tr>
<td style="font-family: Arial, sans-serif; font-size:16px; color:#262626">Informaci&oacute;n del mantenimiento:</td>
</tr>
<tr>
<td height="6"></td>
</tr>
<tr>
<td>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
<tbody>
<tr>
<td>
<table width="100%" border="0" cellspacing="1" cellpadding="0">
<tbody>
<tr>
<td width="39%" height="25" bgcolor="#AFAFAF">
<table width="45" border="0" align="left" cellpadding="0" cellspacing="0"></table>
<table width="89%" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td width="15">&nbsp;</td>
<td width="150"><font face="Arial" size="2" color="#FFFFFF">Descripci&oacute;n</font></td>
</tr>
</tbody>
</table>
</td>
<td width="61%" bgcolor="#F5F7F8">
<table width="92%" border="0" align="center" cellpadding="0" cellspacing="0">
<tbody>
<tr>';
$message .="<td><font face=arial size=2 color=#94969B>$Des</font></td>";
$message .='</tr>
</tbody>
</table>
</td>
</tr>
<tr>
<td height="25" bgcolor="#AFAFAF">
<table width="89%" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td width="15">&nbsp;</td>
<td width="150"><font face="Arial" size="2" color="#FFFFFF">Referencia</font></td>
</tr>
</tbody>
</table>
</td>
<td bgcolor="#F5F7F8">
<table width="92%" border="0" align="center" cellpadding="0" cellspacing="0">
<tbody>
<tr>
<td><font face="arial" size="2" color="#94969B">RK3DLYFCVN2PUD6BZJSV</font></td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr>
<td height="25" bgcolor="#AFAFAF">
<table width="89%" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td width="15">&nbsp;</td>
<td width="150"><font face="Arial" size="2" color="#FFFFFF">Valor</font></td>
</tr>
</tbody>
</table>
</td>
<td bgcolor="#F5F7F8">
<table width="92%" border="0" align="center" cellpadding="0" cellspacing="0">
<tbody>
<tr>
<td><font face="arial" size="2" color="#94969B">184078</font></td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr>
<td height="25" bgcolor="#AFAFAF">
<table width="89%" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td width="15">&nbsp;</td>
<td width="150"><font face="Arial" size="2" color="#FFFFFF">Moneda</font></td>
</tr>
</tbody>
</table>
</td>
<td bgcolor="#F5F7F8">
<table width="92%" border="0" align="center" cellpadding="0" cellspacing="0">
<tbody>
<tr>
<td><font face="arial" size="2" color="#94969B">COP</font></td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr>
<td height="25" bgcolor="#AFAFAF">
<table width="89%" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td width="15">&nbsp;</td>
<td width="150"><font face="Arial" size="2" color="#FFFFFF">Fecha</font></td>
</tr>
</tbody>
</table>
</td>
<td bgcolor="#EDF2F2">
<table width="92%" border="0" align="center" cellpadding="0" cellspacing="0">
<tbody>
<tr>
<td><font face="arial" size="2" color="#94969B">2018-03-01 18:56:38</font></td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr>
<td height="25" bgcolor="#AFAFAF">
<table width="89%" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td width="15">&nbsp;</td>
<td width="150"><font face="Arial" size="2" color="#FFFFFF">Medio de Pago/Franquicia</font></td>
</tr>
</tbody>
</table>
</td>
<td bgcolor="#F5F7F8">
<table width="92%" border="0" align="center" cellpadding="0" cellspacing="0">
<tbody>
<tr>
<td><font face="arial" size="2" color="#94969B">PSE</font></td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr>
<td height="30">&nbsp;</td>
</tr>
<tr>
<td>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td bgcolor="#FFFFFF">
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
<tbody>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
</tr>
</tr>
<tr>
<td height="25">&nbsp;</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<p><img border="0" width="1" height="1" alt="" src="http://post.spmailtechnolo.com/q/P4qX1-Sl944A-yH_1gtrIg~~/AABjOAA~/RgRcexhMPlcDc3BjWAQAAAAAQgoAA0yTmFpHzvILUhlkYXZpZC5tYWNtYWhvbkBvc3MuY29tLmNv"></p>
</body>
</html>
';
  $mail->Body = $message;
  $mail->AltBody = 'OSS le informa que se debe realizar mantenimiento a $equipo lo más pronto posible.';
  $mail->isHTML(true);
  $mail->send();
}
?>