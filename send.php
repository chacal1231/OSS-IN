<?
do{
// form submitted
 
// where is the socket server?
$host="127.0.0.1";
$port = 7778;
//echo $Command;
$message="Send\r\n";
// create socket
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Could not create socket\n");
 
// connect to server
$result = socket_connect($socket, $host, $port) or die("Could not connect to server\n");
 
//socket_read ($socket, 1024) or die("Could not read server response\n");
 
// send string to server
	socket_write($socket, $message, strlen($message)) or die("Could not send data to server\n");	
//get server response
//$result = socket_read ($socket, 1024) or die("Could not read server response\n");
 
// end session
//socket_write($socket, "exit", 3) or die("Could not end session\n");
 
// close socket
socket_close($socket);
 
// clean up result
//$result = trim($result);
//$result = substr($result, 0, strlen($result)-1);
 
// print result to browser
sleep(60);
}while(1);
?>