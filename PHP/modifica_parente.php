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
require_once CLASS_DIR . 'DoctorTypes.class.php';

//var_dump($_SESSION);
if($_SESSION['dati']['idRole']==ADMINISTRATOR || $_SESSION['dati']['idRole']==DOCTORS){
    
}
else{
    session_destroy();
     header("Location: " . URL_FILE_BACKOFFICE . "index.php");
     exit();
}



$tpl = new Smarty;

if ( !empty($_GET['idparente']) ){
 $idparente = trim($_GET['idparente']);
 $oBridgeUsersRoles = new BridgeUsersRoles();
 $oLogin = new Login();
 $oUser = new Users();
 $oUser->idUser=$idparente;
 $utente=$oUser->getUserbyId();
 
 $oBridgeUsersRoles->fkUser=$idparente;
 $oBridgeUsersRoles->fkRole=RELATIVE;
 $idbridge=$oBridgeUsersRoles->getIdBridgeUsersRoles();
 
 $oLogin->_fkBridgeUserRole=$idbridge[0]['idBridgeUserRole'];
 $LoginByfkBridge=$oLogin->getIdLoginByfkBridge();
 $tpl->assign("utente",$utente[0]);
 $tpl->assign("LoginByfkBridge",$LoginByfkBridge[0]);
 $tpl->assign("idbridge",$idbridge[0]['idBridgeUserRole']);
 $tpl->assign("fkrole",$idbridge[0]['fkRole']);
}
 else {
        session_destroy();
        header("Location: " . URL_FILE_BACKOFFICE . "index.php");
         exit();
    
}

//$tpl->assign("allrisk",$allrisk);
//$tpl->assign("allmedicaldevice1", $allmedicaldevice1);
//$tpl->assign("alldiabetes", $alldiabetes);
//$tpl->assign("resultuserbyrole", $resultuserbyrole);SESS
//$tpl->assign("familiare", $familiare);

require_once NOTIFY;
require_once MESSAGES;
$tpl->compile_check = COMPILE_CHECK;
$tpl->debugging = FALSE;




//var_dump( $_SESSION['dati']);    
//echo  $_SESSION['dati']['name'];
$tpl->display("modifica_parente.tpl");






?>
