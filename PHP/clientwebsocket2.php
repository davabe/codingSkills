<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
    require_once '../site/config/sys_application_local_diraimo.php';
    require_once 'class.websocket_client.php';
  
  
  $websocketclient=new WebsocketClient();
  
  $host=IP_WEBSOCKET;
  $port=PORTA_WEBSOCKET;
  $path=""; 
  $origin=ORIGIN_WEBSOCKET;
  
  
  
  $websocketclient->connect($host, $port, $path, $origin);
  //la variabile data è costituita da iddottore;data;idnotificationalert;idstato alert giallo o rosso usare define;id paziente
  $data= "2;11-12-86 12:65:00;3;1;3";

  $websocketclient->sendData($data,'text');
    
  
/*$websocketclient->disconnect();
$websocketclient->reconnect();
 $websocketclient->sendData($data,'text');
$websocketclient->disconnect();*/

?>
