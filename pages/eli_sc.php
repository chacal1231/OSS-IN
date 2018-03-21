<?php
$id=mysqli_real_escape_string($link,$_GET['id']);
mysqli_query($link,"DELETE FROM food where id = '$id'");
if ($id == 0) {
	$frame="cron=E";
}elseif ($id == 1) {
	$frame="cron=W";
}elseif ($id == 2) {
	$frame="cron=M";
}
//Socket
$host="127.0.0.1";
$port = 7775;
$message=$frame . "\r\n";
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Could not create socket\n");
$result = socket_connect($socket, $host, $port) or die("Could not connect to server\n");
socket_write($socket, $message, strlen($message)) or die("Could not send data to server\n");
socket_close($socket);

echo "<script>setTimeout(\"location.href = '?page=home';\", 3000);</script>";
echo "<br><br><br><br><br><br><br><br>";
echo '<div align="center">';
echo "<h1>Cliente <font color=red><b>$name</b></font> eliminado con exito</h1>";
echo "</div>";
echo "</br></br></br></br></br></br></br></br>";
?>