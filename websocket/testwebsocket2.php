<?php
//session_start();

switch ($argv[1]) {
                case  "ws.vitale.easimed.loc": 
                        $conf_require = 'sys_application_local_vitale.php';
			break; 
                case "vitale.easimed.loc": 
                        $conf_require = 'sys_application_local_vitale.php';
			break; 
                case "administrator.vitale.easimed.loc":
			$conf_require = 'sys_application_local_vitale.php';
			break;
                case  "ws.coxnico.com": 
                        $conf_require = 'sys_application_remote.php';
			break;
                case "www.coxnico.com": 
                        $conf_require = 'sys_application_remote.php';
			break;
                case "administrator.easimed.it" :
			$conf_require = 'sys_application_remote.php';
			break;
                case "diraimo.easimed.loc":
			$conf_require = 'sys_application_local_diraimo.php';
			break; 
                case "192.168.30.128":
			$conf_require = 'sys_application_local_debian.php';
			break; 
                default:
                        $conf_require = NULL;
                        break;
	}  
require_once '../config/sys_application.php';
require_once('websockets.php');
require_once CLASS_DIR . 'Users.class.php';

class echoServer extends WebSocketServer {
  //protected $maxBufferSize = 1048576; //1MB... overkill for an echo server, but potentially plausible for other applications.
  
  protected function process ($user, $message) {
     // per stare qui si sideve mandare un messaggio dopo un handshake
      if($user->headers['origin']=='Server'){
          $paziente=new Users;
          $testo=  explode(";", $message);
          $paziente->idUser=$testo[4];
          $nominativo=$paziente->getUserbyId();
          $testo[4]=$nominativo[0]['name'];
          $testo[5]=$nominativo[0]['surname'];
         // echo "sono le propietà della classe users $user->name $user->surname";
          //echo "questo è l'ogetto user/n";
          //var_dump($this->array_user_id[($testo[0])]);
          //echo "messaggio con explode";
          //print_r($testo);
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
               
           //session_id ($cookie[1]);
           //session_id ('pipipipipipipi21323445');
           //echo "ecco la stampa del session_id $a super pippo";
                               $file_sessione=session_save_path().PATH_FILE_SEPARATOR.'sess_'.$cookie[1];
                               //echo "questo è il file di sessione $file_sessione";
                      if(file_exists ($file_sessione)){         
                               $data=file_get_contents($file_sessione);
                               $sess_orig = $_SESSION;
                               $_SESSION = array();
                               
                                // decode session data to $_SESSION
                                session_decode($data);

                                // restore original session
                                $sess = $_SESSION;
                                $_SESSION = $sess_orig; 
                                //echo "la sessione attuale è";
                                //var_dump($_SESSION);
                                    
                                if(isset($sess['dati']) && $sess['dati']['idRole']== DOCTORS){
                                         $user->idUser=$sess['dati']['idUser'];
                                         $user->name=$sess['dati']['name'];
                                         $user->surname=$sess['dati']['surname'];
                                         //echo "sono le propietà della classe users $user->name $user->surname";
                                         //session_write_close();
                                         //echo "questo è l'ogetto user chiamata client ";
                                         //print_r($user);
                                         //forse un finish session vedere documentazione
                                         $this->array_user_id[$user->idUser]=$user;//occhio potrebbe essere il riferimento........teoricamente se gli passa qui come copia dovrebbe funzionare!
                                         //echo "user dall'array array_user_id";         
                                         //print_r($this->array_user_id[$user->idUser]);
                                         $this->send($user,'ok');
                                    }
                                 else{
                                             //unset($_SESSION);
                                             //session_destroy();
                                             //session_write_close();
                                             unlink($file_sessione);
                                             $this->disconnect($user->socket);
                                          }    
             
          
                      }else   {
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
    //var_dump($user->headers);
    //echo "/n";
  //$welcome_message = "Hello. Welcome to the Websocket server. Type help to see what commands are available.";
  //$this->send($user, $welcome_message);
      
  }
  
  protected function closed ($user) {
    // Do nothing: This is where cleanup would go, in case the user had any sort of
    // open files or other objects associated with them.  This runs after the socket 
    // has been closed, so there is no need to clean up the socket itself here.
      echo "User closed connectionn\n";
  }
}

$echo = new echoServer(IP_WEBSOCKET,PORTA_WEBSOCKET);

try {
  $echo->run();
  
}
catch (Exception $e) {
  $echo->stdout($e->getMessage());
}
