<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


/*$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
//socket_set_option($sock, SOL_SOCKET, SO_REUSEADDR, 1);
socket_bind($sock, "192.168.120.55" , 8081);
    $msg = "Ping !";
    $len = strlen($msg);

 socket_write($sock, $msg, $len);
 socket_close($sock);*/
 
//$fp = stream_socket_client("tcp://192.168.120.55:80", $errno, $errstr, 30);
//fwrite($fp, "GET / HTTP/1.0\r\nHost: www.example.com\r\nAccept: */*\r\n\r\n");

error_reporting(E_ALL);

echo "<h2>TCP/IP Connection</h2>\n";

/* Get the port for the WWW service. */
//$service_port = getservbyname('www', 'tcp');

/* Get the IP address for the target host. */
//$address = gethostbyname('www.example.com');

/* Create a TCP/IP socket. */
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket === false) {
    echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
} else {
    echo "OK.\n";
}

echo "Attempting to connect to '$address' on port '$service_port'...";
$result = socket_connect($socket, "192.168.120.55", 8080);
if ($result === false) {
    echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
} else {
    echo "OK.\n";
}

//$in = "HEAD / HTTP/1.1\r\n";
//$in .= "Host: www.example.com\r\n";
//$in .= "Connection: Close\r\n\r\n";
$in ="GET / HTTP/1.1\r\n";
$in .="Host: 192.168.120.55:8080\r\n";
$in .="Cache-Control: no-cache\r\n";
$in .="Pragma: no-cache\r\n";
$in .="Sec-WebSocket-Version: 13\r\n";
$in .="Upgrade: websocket\r\n";
$in .="Sec-WebSocket-Key: 0BuaYv+XRPKszT+9thY2wA==\r\n";
$in .="User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:27.0) Gecko/20100101 Firefox/27.0\r\n";       
$in .="Connection: keep-alive, Upgrade\r\n\r\n";




$out = '';

echo "Sending HTTP HEAD request...";
socket_write($socket, $in, strlen($in));
echo "OK.\n";

echo "Reading response:\n\n";
while ($out = socket_read($socket, 2048)) {
    echo $out;
}


//echo "Closing socket...";
//socket_close($socket);
echo "OK.\n\n";
$session_id='llllg9qpk1jpor128kmtkeeeq4';
/*$fname = session_save_path() . "/sess_" . $session_id;
function unserializesession($data) {
   $vars=preg_split('/([a-zA-Z0-9]+)\|/',$data,-1,PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
   for($i=0; $vars[$i]; $i++) {
       $result[$vars[$i++]]=unserialize($vars[$i]);       
   }
   return $result;
}
*/


session_id ($session_id);

session_start ();

echo $_SESSION['dati']['name'];

   //$sessione=unserializesession(file_get_contents($fname));
   


//var_dump($sessione);
?>



?>
