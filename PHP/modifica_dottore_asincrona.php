<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../../config/sys_application.php';
require_once CLASS_DIR . 'Check.class.php';
require_once CLASS_DIR . 'Users.class.php';
require_once CLASS_DIR . 'Login.class.php';
require_once CLASS_DIR . 'Utils.class.php';
require_once CLASS_DIR . 'BridgeUsersRoles.class.php';

if (!isset($_SESSION['dati'])) {

    header("Location: " . URL_FILE_BACKOFFICE . "index.php");
    session_destroy();
    exit();
}
//var_dump($_POST);
//exit();
   
    $oUtils = new Utils();

    $nome = $oUtils->checkValue($_POST['nome']);
    $cognome = $oUtils->checkValue($_POST['cognome']);
    $username = $oUtils->checkValue($_POST['username']);
    $password = $oUtils->checkValue($_POST['password']);
    $email = $oUtils->checkValue($_POST['email']);
    $date = $oUtils->checkValue($_POST['datanascita']);
    $datetime=new DateTime($date);
    $date =$datetime->format('Y-m-d H:i:s');
    $domicilio = $oUtils->checkValue($_POST['domicilio']);
    $residenza = $oUtils->checkValue($_POST['residenza']);
    $cd = $oUtils->checkValue($_POST['cd']);
    $tel = $oUtils->checkValue(str_replace('+39','',$_POST['tel']));

    $idStato = UTENTE_ATTIVO;
    
    
    if ($_POST['optionsRadios'] == "maschio") {
        $sesso = 'm';
        
    }

    if ($_POST['optionsRadios'] == "femmina") {
        $sesso = 'f';
       
    }
        

    
    $oBridgeUsersRoles = new BridgeUsersRoles();
    $oLogin = new Login();
    $oUser = new Users();

    $oUser->name = $nome;
    $oUser->surname = $cognome;
    $oUser->email = $email;
    $oUser->tel = $tel;
    $oUser->domicile = $domicilio;
    $oUser->gender = $sesso;
    $oUser->codeIdentifier = $cd;
    $oUser->residency = $residenza;
    $oUser->date = $date;
    $oUser->age = $oUser->age($date);
    
    
    
    
    
    
    
    $oLogin->setFiled2($username, $password, null, null);
    //var_dump($oLogin);
    $error = "";
    
    
    $user_pass=$oLogin->getUsernamePass();// questo perchè è user e password sono private
    
    
    
    if(!empty($_POST['idutente']) && !empty($_POST['idlogin']) && !empty($_POST['idbridge'])){
        $oUser->idUser=$_POST['idutente'];
        $parente=$oUser->getUserbyId();
        
        if(empty($parente[0]['fkDevice']))
          $oUser->fkDevice='NULL';  
        else 
          $oUser->fkDevice=$parente[0]['fkDevice'];
        
        if(empty($parente[0]['fkDiabetes']))
            $oUser->fkDiabetes='NULL';
        else
            $oUser->fkDiabetes=$parente[0]['fkDiabetes'];
        
        if(empty($parente[0]['fkDoctor']))
             $oUser->fkDoctor='NULL';
        else
            $oUser->fkDoctor=$parente[0]['fkDoctor'];
        
            
        if(empty($parente[0]['fkDoctorType']))
             $oUser->fkDoctorType='NULL';
        else
            $oUser->fkDoctorType=$parente[0]['fkDoctorType'];
        
        
        if(empty($parente[0]['pregnancy']))
             $oUser->pregnancy='NULL';
       else 
            $oUser->pregnancy=$parente[0]['pregnancy'];
        
        $oBridgeUsersRoles->idBridgeUserRole=$_POST['idbridge'];
        $oLogin->idLogin=$_POST['idlogin'];
        $utente_pre_update=$oUser->getUserbyId();
        $login_pre_update=$oLogin->getLoginByid();
        
        
        if($utente_pre_update[0]['email']!=$oUser->email && $oUser->checkUniqueEmail()>0)
            $error .= "Lindirizzo email inserito è già utilizzato<br>";
       
        if($utente_pre_update[0]['codeIdentifier']!=$oUser->codeIdentifier && $oUser->checkUniqueCodFiscal()>0)
            $error .= "Il Codice Fiscale inserito è già utilizzato, ricontrollare o contattare l'amministratore<br>";
        
            //echo  $login_pre_update[0]['username'];echo "<br>";var_dump($user_pass);
            
        if($login_pre_update[0]['username']!=$user_pass['username']){
            echo $login_pre_update[0]['username'];
            $error .= "Modifica username inappropriata<br>";
        
        }
        
    }
        
        
    else{
    
        session_destroy();
        exit();
        
    }
        
     $error.= $oUser->isError();
    
     $error.=$oLogin->isError();   
    
    

    /* inserire poi per il login if (  $oLogin->checkUniqueUsername()>0  ) {

      if ( empty($error) )
      $error = "Si è verificato il seguente errore: <br />";

      $error .= "L'username inserita è già utilizzata";

      } */


    if (!empty($error)) {

        echo json_encode($error);
        exit();
    } else {
        
        
        
        $oUser->insert(); 
       
        $oLogin->setFiled($username, $password, $idStato, null);
            
        if($user_pass['password']!="")
            $oLogin->insertuserpass();
        
        
        //$idLogin=$oLogin->getIdLogin();
        //$_SESSION['dati']['idLogin']=$idLogin[0]['idLogin'];
        

        $var = array("redirect" => URL_FILE_BACKOFFICE.$_SESSION['dati']['home']);
         echo json_encode($var);
         exit();
    }


?>
