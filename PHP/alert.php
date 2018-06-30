<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once '../../config/sys_application.php';
require_once CLASS_DIR . 'Check.class.php';
require_once CLASS_DIR . 'Users.class.php';
require_once CLASS_DIR . 'BridgePatientsRelatives.class.php';
require_once CLASS_DIR . 'BridgePatientsDoctors.class.php';
require_once CLASS_DIR . 'LevelAlerts.class.php';
require_once CLASS_DIR . 'Messages.class.php';
require_once CLASS_DIR . 'BridgePatientsDoctors.class.php';

//var_dump($_SESSION);exit();
// tutti i controlli da mettere ovvero controllo session , se si può vedere la pagina e se può vedere il paziente !!!!!!!
if (empty($_SESSION['dati'])) {
    session_destroy();
    header("Location: " . URL_FILE_BACKOFFICE . "index.php");
    exit();
}

 if ( !empty($_GET['id']) )
   $idpaziente = trim($_GET['id']); 
 
 else{
     session_destroy();
     header("Location: " . URL_FILE_BACKOFFICE . "index.php");
    exit();
 }
 
 
 
 if($_SESSION['dati']['idRole']==DOCTORS || $_SESSION['dati']['idRole']==ADMINISTRATOR){
 
     
 }
  else{
     session_destroy();
     header("Location: " . URL_FILE_BACKOFFICE . "index.php");
     exit();
     
 }
 
     
     
 
 
 if($_SESSION['dati']['idRole']==DOCTORS){
         $ceckdoctors=new BridgePatientsDoctors();
         $ceckdoctors->fkPatients=$idpaziente;
         $idDoctors=$ceckdoctors->ceckDoctorPatient();
         //var_dump($idDoctors);exit();
                  
         if(!in_array($_SESSION['dati']['idUser'], $idDoctors)){
                session_destroy();
                header("Location: " . URL_FILE_BACKOFFICE . "index.php");
                exit();
         }
     }

     
     
 $tpl = new Smarty;
require_once NOTIFY;
require_once MESSAGES;
$levelalert=new LevelAlerts();
$levelalert->fkUser=$idpaziente;
//var_dump($levelalert->getAllLevelAlertsbyIdPatient() );exit();
$riga= $levelalert->getAllLevelAlertsbyIdPatient();
  // var_dump($riga);exit();

 
 
$oUtils=new Utils();
$message=new Messages();

 
 
   $userbyid = new Users();
   //$BridgePatientsRelatives= new BridgePatientsRelatives (); 
   $userbyid->idUser=$idpaziente;
   //$BridgePatientsRelatives->fkPatients=$idpaziente;
   
   
   $username=$userbyid->getUserbyId();
   
if($_SESSION['dati']['idRole']==ADMINISTRATOR){
  $BridgePatientsDoctors=new BridgePatientsDoctors();
   $BridgePatientsDoctors->fkPatients=$idpaziente;
   $doctors=$BridgePatientsDoctors->getAllDoctorOfPatient();
   //var_dump($doctors);exit();
   $tpl->assign("doctors", $doctors);
}
      

   

$message->fkReceive=$_SESSION['dati']['idUser'];
$message->fkSender=$idpaziente;
$messages=$message->getMessages(3);




   
   //$relatives=$BridgePatientsRelatives->getAllRelativeByPatient();
   
   //var_dump($relatives);exit;
        $tpl->assign("riga",$riga);
        
        $tpl->assign("idpaziente", $idpaziente);
        $tpl->assign("username", $username[0]);
        $tpl->assign("messages", $messages);
        
        $tpl->compile_check = COMPILE_CHECK;
        $tpl->debugging = FALSE;
        $tpl->display("alert.tpl");
        
        

























?>
