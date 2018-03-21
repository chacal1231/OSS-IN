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


foreach($ConsultaCorreos as $RowCorreos => $field){
  //PHPMailer clase
  $mail = new PHPMailer;
  //$mail->SMTPDebug = 3;
  $mail->isSMTP();
  $mail->Host = $smtp;
  $mail->SMTPAuth = $SMTPAuth;
  $mail->Username = $usuarioSmtp;
  $mail->Password = $contraseñaSmtp;
  $mail->SMTPSecure = $SMTPSecure;
  $mail->Port = $port;
 
  $mail->setFrom('grupositelsas@gmail.com', 'Grupo SITEL S.A.S');
  $mail->addAddress($field['email']);
  $mail->Subject = "Reporte semanal Autoquarium";
  $message  = "<html><body>";
  $message .= "<table width='100%' bgcolor='#ffffff' cellpadding='0' cellspacing='0' border='0'>";
  $message .= "<tr><td>";
  $message .= "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' style='max-width:650px; background-color:#fff; font-family:Verdana, Geneva, sans-serif;'>";
  $message .= "<thead>
  <tr height='80'>
  <th colspan='4' style='background-color:#f5f5f5; border-bottom:solid 1px #bdbdbd; font-family:Verdana, Geneva, sans-serif; color:#333; font-size:34px;'><img src='https://bitbucket.org/repo/aoGrEE/images/999623540-Screenshot_20170315_193744.png' style='width:520px; height:120px;'></th>
  </tr>
             </thead>";
  $message .= "<tbody>
             <tr align='center' height='50' style='font-family:Verdana, Geneva, sans-serif;'>
       <td style='background-color:#00a2d1; text-align:center;'><a href='' style='color:#fff; text-decoration:none;'></a></td>
       <td style='background-color:#00a2d1; text-align:center;'><a href='' style='color:#fff; text-decoration:none;'></a></td>
       <td style='background-color:#00a2d1; text-align:center;'><a href='' style='color:#fff; text-decoration:none;'></a></td>
       <td style='background-color:#00a2d1; text-align:center;'><a href='' style='color:#fff; text-decoration:none;'></a></td>
       </tr>
      
       <tr>
       <td colspan='4' style='padding:15px;'>
       <p style='font-size:20px;'>Hola,</p>
       <hr />
       <p style='font-size:20px;'>Este es el reporte semanal de su pecera.
       <br>
       <br>
       Total comida consumida: &nbsp;&nbsp;&nbsp;&nbsp; ".$RowComida[0]." Gramos
       <br>
       Total energía consumida:&nbsp;&nbsp;&nbsp;&nbsp;  ".round($RowEnergia[0])." kW/h
       <br>
       pH promedio de la pecera: &nbsp; ".round($RowpH[0])." 
       <br>
       Nivel de comida actual: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='red'><b>".$RowComidaAC[0]."</font></b> Gramos
       <br>
       </p>
       </td>
       </tr>     
              </tbody>";
  $message .= "</table>";
  $message .= "</td></tr>";
  $message .= "</table>";
  $message .= "</body></html>";
  $mail->Body = $message;
  $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
  $mail->isHTML(true);
  $mail->send();
}
?>