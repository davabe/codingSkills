<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
//echo '<pre>';
//var_dump($_POST);exit();

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

    $idRuolo =$oUtils->checkValue($_POST['ruolo']); 
    
    $idStato = UTENTE_ATTIVO;
    
    
    

    if ($_POST['optionsRadios'] == "maschio") {
        $sesso = 'm';
        $pregnancy = 0;
    }

    if ($_POST['optionsRadios'] == "femmina") {
        $sesso = 'f';
        $pregnancy = 0;
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
    $oUser->pregnancy = $pregnancy;
    
    $oLogin->setFiled2($username, $password, null, null);
    //var_dump($oLogin);
    $error = "";
    if($idRuolo==ADMINISTRATOR || $idRuolo==DOCTORS){
        $oBridgeUsersRoles->fkRole = $idRuolo;
        if(isset($_POST['doctortype']) && $_POST['doctortype']!='--Specializzazione--' && $idRuolo==DOCTORS)
            $oUser->fkDoctorType=$_POST['doctortype'];
         elseif ($idRuolo==DOCTORS && ( (isset($_POST['doctortype']) && $_POST['doctortype']=='--Specializzazione--') || !isset($_POST['doctortype']) )) 
              $error="Obbligatorio inserire tipo medico <br>";
    }    
    else 
        $error= "Obbligatorio inserire Ruolo <br>";
    
    $user_pass=$oLogin->getUsernamePass();// questo perchè è user e password sono private
    
    
    
    if(!empty($_POST['idutente']) && !empty($_POST['idlogin']) && !empty($_POST['idbridge'])){
        $oUser->idUser=$_POST['idutente'];
        $oBridgeUsersRoles->idBridgeUserRole=$_POST['idbridge'];
        $oLogin->idLogin=$_POST['idlogin'];
        $utente_pre_update=$oUser->getUserbyId();
        $login_pre_update=$oLogin->getLoginByid();
        
        
        if($utente_pre_update[0]['email']!=$oUser->email && $oUser->checkUniqueEmail()>0)
            $error .= "Lindirizzo email inserito è già utilizzato<br>";
        if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",  $oUser->email)) {
                 $error .= "Email non valida<br>";
             }
        if($utente_pre_update[0]['codeIdentifier']!=$oUser->codeIdentifier && $oUser->checkUniqueCodFiscal()>0)
            $error .= "Il Codice Fiscale inserito è già utilizzato, ricontrollare o contattare l'amministratore<br>";
        
            //echo  $login_pre_update[0]['username'];echo "<br>";var_dump($user_pass);
            
        if($login_pre_update[0]['username']!=$user_pass['username'])
            $error .= "Modifica username inappropriata<br>";
        
        
        
    }
        
        
    else{
    $error.= $oUser->isError();
    
    $error.=$oLogin->isError();
    }
        
        
    
    

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
        if(empty($oUser->idUser))
         $idUser = $oUser->getUserbyCd();
        //$_SESSION['dati']['idPatient'] = $idUser[0]['idUser'];
        if(empty($oBridgeUsersRoles->idBridgeUserRole))
            $oBridgeUsersRoles->fkUser = $idUser[0]['idUser'];
        else
            $oBridgeUsersRoles->fkUser =$oUser->idUser;
        
        $oBridgeUsersRoles->insertBridgeUsersRoles();
        
        
        if(empty($oBridgeUsersRoles->idBridgeUserRole)){
            $idBridgeUsersRoles = $oBridgeUsersRoles->getIdBridgeUsersRoles();
            $oLogin->setFiled($username, $password, $idStato, $idBridgeUsersRoles[0]['idBridgeUserRole']);
        }
        // gestire errore per username già esistente 
        else
            $oLogin->setFiled($username, $password, $idStato, $oBridgeUsersRoles->idBridgeUserRole);
            
        if($user_pass['password']!="")
            $oLogin->insertuserpass();
        
        
        //$idLogin=$oLogin->getIdLogin();
        //$_SESSION['dati']['idLogin']=$idLogin[0]['idLogin'];
        

        $var = array("redirect" => URL_FILE_BACKOFFICE . $_SESSION['dati']['home']);
         echo json_encode($var);
         exit();
    }


   

?>
