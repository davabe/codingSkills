<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
 require_once '../../config/sys_application.php';
 require_once CLASS_DIR.'DB.class.php';
 require_once CLASS_DIR.'Utils.class.php';
 require_once CLASS_DIR.'Users.class.php';
 require_once CLASS_DIR.'Mail.class.php';
 require_once CLASS_DIR.'BridgeUsersRoles.class.php';
 require_once CLASS_DIR.'Login.class.php';
 $error = "";
 
 if (!empty($_POST['email'])) {
 
 
 
 $oUser=new Users();
 $oBridgeUserRole=new BridgeUsersRoles();
 $oLogin=new Login();
 $oUser->email=$_POST['email'];
 $user=$oUser->getUserbyEmail();
 
 if(empty($user[0]['idUser'])){
     $error = "<p id='messaggio'>email inesistente</p>";

 }
 else{

         $psw=$oUser->generatePassword();
         $pswmd5=md5($psw);
         //var_dump($user);
         $oBridgeUserRole->fkUser=$user[0]['idUser'];
         $roles=$oBridgeUserRole->getIdBridgeUsersRoles('pippo');
        // var_dump($roles);
         foreach ($roles as $key => $value) {
           $oLogin->_fkBridgeUserRole=$value['idBridgeUserRole'];
           $oLogin->updatePassword($pswmd5);

         }
            
            $from = MAIL_AUTH_USERNAME;  
            $to =$user[0]['email'] ;  
            $mail_body = "Salve <strong>".$user[0]['surname']." ".$user[0]['name']."</strong>, <BR><br >E' stata modificata la sua password come richiesto.<br><br /> La nuova password &egrave;: <strong>$psw</strong><br /><BR>Si prega di rispettare le maiuscole e le minuscole"; 
            $subject = "RECUPERO PASSWORD piattaforma ".PLATFORM_NAME; 
            
            $mailToSend = new Mail();

            $mailToSend->inviaMail($to,$subject,$mail_body);
            
            $error = 0;


      }

     

 }

else{

   $error = "<p id='messaggio'>Inserire email</p>";


}

echo json_encode($error);
exit();
?>
