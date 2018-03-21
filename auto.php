<?php
//Configuración servidor Mysql
$host = "localhost"; 
$user = "root";
$pass = "2*b**:E82JZ=93L|c0Tw"; 
$database ="auto";
$link=mysqli_connect($host,$user,$pass,$database);

include (__DIR__.'/inc/smsGateway.php');
require (__DIR__.'/inc/config.php');
//SmsGateway
$UsuarioSms="jedmacmahonve@unal.edu.co";
$PasswordSms="jesus00";
$DeviceId="48188";
$options = ['expires_at' => strtotime('+1 hour')];
$smsGateway = new SmsGateway($UsuarioSms, $PasswordSms);
$result2 = $smsGateway->getDevice($DeviceId);
 
/*****************************************************************************
 * PHP Multi-Client TCP Socket Example                                       *
 *****************************************************************************
 * Will listen on a given socket for TCP connections, echoing whatever data  *
 *  is sent to that socket.                                                  *
 *                                                                           *
 * Original script by KnoB in a comment in the PHP documentation:            *
 *  http://www.php.net/manual/en/ref.sockets.php#43155                       *
 *                                                                           *
 * Heavily modified and commented by Andrew Gillard                          *
 *****************************************************************************/
 
//What address are we listening on? This will have to be the same as in
// the client. You probably just want '127.0.0.1' for both.
$address = '0.0.0.0';
 
//What port to use? Again, the client will need to know this, too
$port = 7775;
 
?>
 
<?php
 
//Disable PHP's script execution time limit
set_time_limit(0);
 
//Ensure that every time we call "echo", the data is sent to the browser
// IMMEDIATELY, rather than when PHP feels like it
ob_implicit_flush();
 
//Normally when the user clicks the "Stop" button in their browser, the
// script is terminated. This line stops that happening, so that we can
// detect the Stop button ourselves and properly close our sockets (to
// prevent the listening socket remaining open and stealing the port)
ignore_user_abort(true);
 
//Define a function that we can call when any of our socket function calls
// fail. This allows us to consolidate our error message XHTML and avoid
// code repetition. If $die is set to true, the script will terminate
function socketError($errorFunction, $die=false) {
	$errMsg = socket_strerror(socket_last_error());
 
 	//This odd construct (known as a heredoc) just echos all of the text
 	// between "<<<EOHTML" and "EOHTML;". It's just a neater and easier to read
 	// format than using standard quoted strings. If you want to use one
 	// yourself, bear in mind that the structure is VERY strict: the opening
 	// line must be just "<<<" followed by the ending identifier, and the last
 	// line must contain NOTHING except the identifier ("EOHTML" in this case).
 	// The semi-colon after the closing identifier is optional, but it is
 	// important to realise that there cannot even be whitespace (tabs or
 	// spaces) before the EOHTML; at the end!!
	
	if ($die) {
		//Close the BODY and HTML tags as well as terminating script 
		//execution because the die() call prevents us ever getting to the last
		// lines of this script
		die('</body></html>');
	}
}
 
//Attempt to create our socket. The "@" hides PHP's standard error reporting,
// so that we can output our own error message if it fails
if (!($server = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP))) {
	socketError('socket_create', true);
}
 
//Set the "Reuse Address" socket option to enabled
socket_set_option($server, SOL_SOCKET, SO_REUSEADDR, 1);
 
//Attempt to bind our socket to the address and port that we're listening on.
// Again, we suppress PHP's error reporting in favour of our own
if (!@socket_bind($server, $address, $port)) {
	socketError('socket_bind', true);
}
 
//Start listening on the address and port that we bound our socket to above,
// using our own error reporting code as before
if (!@socket_listen($server)) {
	socketError('socket_listen', true);
}
 
//Create an array to store our sockets in. We use this so that we can
// determine which socket has new incoming data with the "socket_select()"
// function, and to properly close each socket when the script finishes
$allSockets = array($server);
 
//Start looping indefinitely. On each iteration we will make sure the browser's
// "Stop" button hasn't been pressed and, if not, see if we have any incoming
// client connection requests or any incoming data on existing clients
while (true) {
	//We have to echo something to the browser or PHP won't know if the Stop
	// button has been pressed
    echo ' ';
    if (connection_aborted()) {
    	//The Stop button has been pressed, so close all our sockets and exit
    	foreach ($allSockets as $socket) {
    		socket_close($socket);
		}
 
		//Now break out of this while() loop!
		break;
	}
 
	//socket_select() is slightly strange. You have to make a copy of the array
	// of sockets you pass to it, because it changes that array when it returns
	// and the resulting array will only contain sockets with waiting data on
	// them. $write and $except are set to NULL because we aren't interested in
	// them. The last parameter indicates that socket_select will return after
	// that many seconds if no data is receiveed in that time; this prevents the
	// script hanging forever at this point (remember, we might want to accept a
	// new connection or even exit entirely) and also pauses the script briefly
	// to prevent this tight while() loop using a lot of processor time
	$changedSockets = $allSockets;
	socket_select($changedSockets, $write = NULL, $except = NULL, 5);
 
	//Now we loop over each of the sockets that socket_select() says have new
	// data on them
	foreach($changedSockets as $socket) {
	    if ($socket == $server) {
	    	//socket_select() will include our server socket in the
	    	// $changedSockets array if there is an incoming connection attempt
	    	// on it. This will only accept one incoming connection per while()
	    	// loop iteration, but that shouldn't be a problem given the
	    	// frequency that we're iterating
	        if (!($client = @socket_accept($server))) {
	        	//socket_accept() failed for some reason (again, we hid PHP's
	        	// standard error message), so let's say what happened...
	        	socketError('socket_accept', false);
	        } else {
	        	//We've accepted the incoming connection, so add the new client
	        	// socket to our array of sockets
	        	$allSockets[] = $client;
	        }
	    } else {
			//Attempt to read data from this socket
	        $data = socket_read($socket, 2048);
	        if ($data === false || $data === '') {
            	//socket_read() returned FALSE, meaning that the client has
            	// closed the connection. Therefore we need to remove this
            	// socket from our client sockets array and close the socket
            	//A potential bug in PHP means that socket_read() will return
            	// an empty string instead of FALSE when the connection has
            	// been closed, contrary to what the documentation states. As
            	// such, we look for FALSE or an empty string (an empty string
            	// for the current, buggy, behaviour, and FALSE in case it ends
            	// up getting fixed at some point)
	            unset($allSockets[array_search($socket, $allSockets)]);
	            socket_close($socket);
	        } else {
            	//We got useful data from socket_read(), so let's echo it.
            	// "$socket" will be output as "Resource id #n", where n is
            	// the internal ID of the socket, e.g. "Resource id #3"
            	//Note also that $data can be an empty string, so we check
            	// for that in our "elseif ($data)" line
				echo "\r\n<p><strong>&middot;</strong> $socket wrote: $data</p>";
				
				if(strpos($data,"xPython")!==false){
				$s="PHP\r\n";
					foreach($allSockets as $m){
						socket_write($m, $s, strlen($s));
					}
				}
				else if(strpos($data,"WID=")!==false){
					$Wellid=str_replace("WID=","",$data);
				}
				else if(strpos($data,"LFR=")!==false){
					$LFR=str_replace("LFR=","",$data);
				}
				else if(strpos($data,"WFR=")!==false){
					$WFR=str_replace("WFR=","",$data);
				}
				else if(strpos($data,"OFR=")!==false){
					$OFR=str_replace("OFR=","",$data);
				}
				else if(strpos($data,"GFR=")!==false){
					$GFR=str_replace("GFR=","",$data);
				}
				else if(strpos($data,"GVF=")!==false){
					$GVF=str_replace("GVF=","",$data);
				}
				else if(strpos($data,"TMP=")!==false){
					$TMP=str_replace("TMP=","",$data);
				}
				else if(strpos($data,"PRE=")!==false){
					$PRE=str_replace("PRE=","",$data);
				}
				else if(strpos($data,"WCUT=")!==false){
					$WCUT=str_replace("WCUT=","",$data);
					$Date = date ('Y-m-d');
					$Hour = date ('h:i:s');
					//Query para ver el estado de la variable id
					$query_id=mysqli_query($link,"SELECT id FROM minutedata order by id DESC");
					$row_id=mysqli_fetch_array($query_id);
					$id=($row_id['id']+1);
					mysqli_query($link,"INSERT INTO minutedata(id,Datex,hour,wellid,LFR,WFR,OFR,GFR,GVF,TMP,PRE,WCUT) VALUES('$id','$Date','$Hour','$Wellid','$LFR', '$WFR', '$OFR', '$GFR','$GVF', '$TMP', '$PRE', '$WCUT')");
					//Echo menssage
					$s="PHP\r\n";
					foreach($allSockets as $m){
						socket_write($m, $s, strlen($s));
					}
				}
				#Accumulate RX Data
				else if(strpos($data,"ALxFR=")!==false){
					$ALFR=str_replace("ALxFR=","",$data);
				}
				else if(strpos($data,"AWxFR=")!==false){
					$AWFR=str_replace("AWxFR=","",$data);
				}
				else if(strpos($data,"AOxFR=")!==false){
					$AOFR=str_replace("AOxFR=","",$data);
				}
				else if(strpos($data,"AGxFR=")!==false){
					$AGFR=str_replace("AGxFR=","",$data);
					$Date = date ('Y-m-d');
					$Hour = date ('h:i:s');
					//Query para ver el estado de la variable id
					$query_id=mysqli_query($link,"SELECT id FROM accdata order by id DESC");
					$row_id=mysqli_fetch_array($query_id);
					$id=($row_id['id']+1);
					mysqli_query($link,"INSERT INTO accdata(id,date,hour,wellid,ALFR,AWFR,AOFR,AGFR) VALUES('$id','$Date','$Hour','$Wellid','$ALFR', '$AWFR', '$AOFR', '$AGFR')");
					//Echo menssage
					$s="PHP\r\n";
					foreach($allSockets as $m){
						socket_write($m, $s, strlen($s));
					}
				}

				#Average RX data
				else if(strpos($data,"AALvFR=")!==false){
					$AALFR=str_replace("AALvFR=","",$data);
				}
				else if(strpos($data,"AAWvFR=")!==false){
					$AAWFR=str_replace("AAWvFR=","",$data);
				}
				else if(strpos($data,"AAOvFR=")!==false){
					$AAOFR=str_replace("AAOvFR=","",$data);
				}
				else if(strpos($data,"AAGvFR=")!==false){
					$AAGFR=str_replace("AAGvFR=","",$data);
				}
				else if(strpos($data,"AAWvCT=")!==false){
					$AAWCT=str_replace("AAWvCT=","",$data);
				}
				else if(strpos($data,"AAGvVF=")!==false){
					$AAGVF=str_replace("AAGvVF=","",$data);
				}
				else if(strpos($data,"AATvMP=")!==false){
					$AATMP=str_replace("AATvMP=","",$data);
				}
				else if(strpos($data,"AAPvRES=")!==false){
					$AAPRES=str_replace("AAPvRES=","",$data);
				}
				else if(strpos($data,"AAIvPRES=")!==false){
					$AAIPRES=str_replace("AAIvPRES=","",$data);
				}
				else if(strpos($data,"AAOPRvES=")!==false){
					$AAOPRES=str_replace("AAOPRvES=","",$data);
				}
				else if(strpos($data,"AAvGOR=")!==false){
					$AAGOR=str_replace("AAvGOR=","",$data);
					$Date = date ('Y-m-d');
					$Hour = date ('h:i:s');
					//Query para ver el estado de la variable id
					$query_id=mysqli_query($link,"SELECT id FROM avedata order by id DESC");
					$row_id=mysqli_fetch_array($query_id);
					$id=($row_id['id']+1);
					mysqli_query($link,"INSERT INTO avedata(id,date,hour,wellid,AALFR,AAWFR,AAOFR,AAGFR,AAWCT,AAGVF,AATMP,AAPRES,AAIPRES,AAOPRES,AAGOR) VALUES('$id','$Date','$Hour','$Wellid','$AALFR', '$AAWFR', '$AAOFR', '$AAGFR', '$AAWCT', '$AAGVF', '$AATMP', '$AAPRES', '$AAIPRES', '$AAOPRES', '$AAGOR')");
					//Echo menssage
					$s="PHP\r\n";
					foreach($allSockets as $m){
						socket_write($m, $s, strlen($s));
					}
				}
				else if(strpos($data,"C=1")!==false){
					$s="Command1\r\n";
					foreach($allSockets as $m){
						socket_send($m, $s, strlen($s),0);
					}
				}
				else if(strpos($data,"C=2")!==false){
					$s="Command2\r\n";
					foreach($allSockets as $m){
						socket_send($m, $s, strlen($s),0);
					}
				}
				else if(strpos($data,"time=")!==false){
					$s=$data;
					foreach($allSockets as $m){
						socket_send($m, $s, strlen($s),0);
					}
				}
				else if(strpos($data,"cron=")!==false){
					$s=$data;
					foreach($allSockets as $m){
						socket_send($m, $s, strlen($s),0);
					}
				}
			}
		}
	}
}
 
//Once we get here, the sockets have been closed, so just echo our XHTML footer
 
?>
 
</body>
</html>