<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
<style>
.datepicker{z-index:1151 !important;}
</style>
<?php
// data from module enviar datos
$Command=$_GET['c'];
// form submitted
 
// where is the socket server?
$host="127.0.0.1";
$port = 7775;
//echo $Command;
$message="C=". $Command . "\r\n";
 
// create socket
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Could not create socket\n");
 
// connect to server
$result = socket_connect($socket, $host, $port) or die("Could not connect to server\n");
 
//socket_read ($socket, 1024) or die("Could not read server response\n");
 
// send string to server
socket_write($socket, $message, strlen($message)) or die("Could not send data to server\n");
 
// get server response
//$result = socket_read ($socket, 1024) or die("Could not read server response\n");
 
// end session
//socket_write($socket, "exit", 3) or die("Could not end session\n");
 
// close socket
socket_close($socket);
 
// clean up result
//$result = trim($result);
//$result = substr($result, 0, strlen($result)-1);
 
// print result to browser

    echo "<br><br><br><br><br><br><br><br>";
    echo '<div align="center">';
    echo "<h1>Fishes successfully fed!</h1>";
    echo "</div>";
    echo "</br></br></br></br></br></br></br></br>";
	echo "<script>setTimeout(\"location.href = '../index.php';\", 3000);</script>";
?>