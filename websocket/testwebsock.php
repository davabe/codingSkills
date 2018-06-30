<?php

session_start();
require_once '../site/config/sys_application_remote.php';
//require_once '/../site/config/sys_define.php';
require_once('websockets.php');


class echoServer extends WebSocketServer {
  //protected $maxBufferSize = 1048576; //1MB... overkill for an echo server, but potentially plausible for other applications.
  
  protected function process ($user, $message) {
     // per stare qui si sideve mandare un messaggio dopo un handshake
      if($user->headers['origin']=='Server'){
          
          $testo=  explode(";", $message);
          $testo[4]=$this->array_user_id[$testo[0]]->name;
          $testo[5]=$this->array_user_id[$testo[0]]->surname;
         // echo "sono le propietà della classe users $user->name $user->surname";
          echo "questo è l'ogetto user/n";
          var_dump($this->array_user_id[($testo[0])]);
          echo "messaggio con explode";
          print_r($testo);
          $text=  implode(";", $testo);
          $this->send($this->array_user_id[$testo[0]],$text);
          //$this->send($this->array_user_id[$message],$text);
          $this->send($user,'ok');
      }

                  
          
       if($user->headers['origin']=='http://'.DOMINIO_SITO){
         if(array_key_exists('cookie',$user->headers ) && isset($user->headers['cookie'])){
           $cookie=explode("=",$user->headers['cookie']);
          //echo "il cookie  de phpsesid è il sguente: ";
          //echo "$cookie[1]";
               
           session_id ($cookie[1]);
           //session_id ('pipipipipipipi21323445');
           //echo "ecco la stampa del session_id $a super pippo";
                        
                                session_start ();
                        
                                    
                                if(isset($_SESSION['dati']) && $_SESSION['dati']['idRole']== DOCTORS){
                                         $user->idUser=$_SESSION['dati']['idUser'];
                                         $user->name=$_SESSION['dati']['name'];
                                         $user->surname=$_SESSION['dati']['surname'];
                                         //echo "sono le propietà della classe users $user->name $user->surname";
                                         session_write_close();
                                         echo "questo è l'ogetto user chiamata client ";
                                         print_r($user);
                                         //forse un finish session vedere documentazione
                                         $this->array_user_id[$user->idUser]=$user;//occhio potrebbe essere il riferimento........teoricamente se gli passa qui come copia dovrebbe funzionare!
                                         echo "user dall'array array_user_id";         
                                         print_r($this->array_user_id[$user->idUser]);
                                         $this->send($user,'ok');
                                    }
                                 else{
                                             unset($_SESSION);
                                             session_destroy();
                                             session_write_close();
                                             $this->disconnect($user->socket);
                                          }    
             
          
                                          
          
          
          
             }  else {
              $this->disconnect($user->socket);    
          }                              
       }
          
          
           
           
           
           
           
           
      
      
    /* if($message == 'help')
        {
            $reply = 'Following commands are available - date, hi';
        }
        else if($message == 'date')
        {
            $reply = "Current date is " . date('Y-m-d H:i:s');
        }
        
         else if($message == 'sessione')
        {
            $reply = "la sessione è". $_SESSION['dati']['idUser'];
        }
        else if($message == 'hi')
        {
            $reply = "Hello user. This is a websocket server.";
        }
        else
        {
            $reply = "Thank you for the message : $message";
        }
         
        $this->send($user, $reply);
      echo "Requested resource : " . $user->requestedResource . "/n";*/
  
  }
  
  protected function connected ($user) {
    // Do nothing: This is just an echo server, there's no need to track the user.
    // However, if we did care about the users, we would probably have a cookie to
    // parse at this step, would be looking them up in permanent storage, etc.
    $this->stdout("questo è l'headers nell'handeshake");  
    var_dump($user->headers);
    echo "/n";
  //$welcome_message = "Hello. Welcome to the Websocket server. Type help to see what commands are available.";
  //$this->send($user, $welcome_message);
      
  }
  
  protected function closed ($user) {
    // Do nothing: This is where cleanup would go, in case the user had any sort of
    // open files or other objects associated with them.  This runs after the socket 
    // has been closed, so there is no need to clean up the socket itself here.
      echo "User closed connectionn";
  }
}

$echo = new echoServer(IP_WEBSOCKET,PORTA_WEBSOCKET);

try {
  $echo->run();
  
}
catch (Exception $e) {
  $echo->stdout($e->getMessage());
}
