<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


require_once '../../config/sys_application.php';
require_once CLASS_DIR . 'Check.class.php';
require_once CLASS_DIR .'NotificationsAlert.class.php';
require_once CLASS_DIR . 'Mesuration.class.php';
require_once CLASS_DIR . 'Users.class.php';


if($_SESSION['dati']['idRole']==ADMINISTRATOR || $_SESSION['dati']['idRole']==DOCTORS){
    
}
else{
    session_destroy();
     header("Location: " . URL_FILE_BACKOFFICE . "index.php");
     exit();
}

$tpl = new Smarty;

if ( !empty($_GET['idpaziente']) ){
    $oUsers=new Users();
    $oUsers->idUser=$_GET['idpaziente'];
    $user=$oUsers->getUserbyId();
            
    
    if($_SESSION['dati']['idRole']==DOCTORS){
        
        $oNotificationsAlert=new NotificationsAlert();
        $oNotificationsAlert->fkUser=$_GET['idpaziente'];
        $notifications=$oNotificationsAlert->getStoricNotificationAlert();
        
    }
    
    if($_SESSION['dati']['idRole']==ADMINISTRATOR){
        
        $oNotificationsAlert=new NotificationsAlert('no');
        $oNotificationsAlert->fkUser=$_GET['idpaziente'];
        $notifications=$oNotificationsAlert->getStoricNotificationAlert();
        
    }
    
    $tpl->assign("notifications",$notifications);
    $tpl->assign("user",$user);
    
    
}

 else {
        session_destroy();
        header("Location: " . URL_FILE_BACKOFFICE . "index.php");
         exit();
    
}


require_once NOTIFY;
require_once MESSAGES;
$tpl->compile_check = COMPILE_CHECK;
$tpl->debugging = FALSE;



$tpl->display("storico_alert.tpl");


?>
