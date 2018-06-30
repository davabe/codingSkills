<?php

require_once '../../config/sys_application.php';
require_once CLASS_DIR . 'Check.class.php';
require_once CLASS_DIR . 'Users.class.php';
require_once CLASS_DIR . 'Login.class.php';
require_once CLASS_DIR . 'Utils.class.php';
require_once CLASS_DIR . 'BridgeUsersRoles.class.php';
require_once CLASS_DIR . 'DoctorTypes.class.php';

$tpl = new Smarty;

if ( !empty($_GET['idutente']) ){
 $idutente = trim($_GET['idutente']);
 $oBridgeUsersRoles = new BridgeUsersRoles();
 $oLogin = new Login();
 $oUser = new Users();
 $oUser->idUser=$idutente;
 $utente=$oUser->getUserbyId();
 
 $oBridgeUsersRoles->fkUser=$idutente;
 //considerando che un ammistratore non può essere un dottore
 $idbridge=$oBridgeUsersRoles->getIdBridgeUsersRoles(1);
 
 $oLogin->_fkBridgeUserRole=$idbridge[0]['idBridgeUserRole'];
 $LoginByfkBridge=$oLogin->getIdLoginByfkBridge();
 $tpl->assign("utente",$utente[0]);
 $tpl->assign("LoginByfkBridge",$LoginByfkBridge[0]);
 $tpl->assign("idbridge",$idbridge[0]['idBridgeUserRole']);
 $tpl->assign("fkrole",$idbridge[0]['fkRole']);
}
 


$doctortypes=new DoctorTypes();
$alldoctortypes=$doctortypes->getAlldoctortype();


$tpl->assign("alldoctortypes",$alldoctortypes);
//$tpl->assign("allrisk",$allrisk);
//$tpl->assign("allmedicaldevice1", $allmedicaldevice1);
//$tpl->assign("alldiabetes", $alldiabetes);
//$tpl->assign("resultuserbyrole", $resultuserbyrole);
//$tpl->assign("familiare", $familiare);

require_once NOTIFY;
require_once MESSAGES;
$tpl->compile_check = COMPILE_CHECK;
$tpl->debugging = FALSE;




//var_dump( $_SESSION['dati']);    
//echo  $_SESSION['dati']['name'];
$tpl->display("inserimento_utente.tpl");
?>
