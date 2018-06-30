<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once '../../config/sys_application.php';
require_once CLASS_DIR . 'Check.class.php';
require_once CLASS_DIR .'NotificationsAlert.class.php';
require_once CLASS_DIR . 'Mesuration.class.php';

if (empty($_SESSION['dati'])) {
    session_destroy();
    header("Location: " . URL_FILE_BACKOFFICE . "index.php");
    exit();
}

if ( !empty($_GET['id']) )
   $idnotificatinalert = trim($_GET['id']);
else{
     session_destroy();
     header("Location: " . URL_FILE_BACKOFFICE . "index.php");
    exit();
 }

 
 if($_SESSION['dati']['idRole']==DOCTORS || $_SESSION['dati']['idRole']==ADMINISTRATOR){
     //mancano i controlli per dire se la notifica può essere vista con fk del dottore dentro notificatinalert forse è da spostare il posto di questo controllo
 }
 else{
     session_destroy();
     header("Location: " . URL_FILE_BACKOFFICE . "index.php");
     exit();
     
 }
 
 
 
 
 
$notificationalert=new NotificationsAlert();
$notificationalert->idNotificationsAlert=$idnotificatinalert;
$alerts=$notificationalert->getNotificationAlert();
//echo'ciao';
//exit();
if($_SESSION['dati']['idRole']==DOCTORS && $alerts['fkDoctor']!=$_SESSION['dati']['idUser']){
    
    session_destroy();
    header("Location: " . URL_FILE_BACKOFFICE . "index.php");
    exit();
    
}






$notificationalert->updateStateAlert();
//var_dump($alerts);exit();

 $mesuration=new Mesuration();

 $mesuration->fkUser=$alerts['fkUser'];
 $tpl = new Smarty;
 require_once NOTIFY;
 require_once MESSAGES;
        $tpl->assign("alerts", $alerts);
        $tpl->assign("idnotificatinalert", $idnotificatinalert);

        if($alerts['dataglicemia']!=null){ 
        $mesuration->table='glycemiaMensurations';
        $result_glycemia=$mesuration->getMesurationForGraphicAsinc('',$alerts['dataglicemia'],null,$alerts['period']);
        $tpl->assign("tableGlycemia", $result_glycemia['tabella']);
        }
        if($alerts['datarespirazione']!=null){
        $mesuration->table='spirometryMensurations';
        $result_spirometry=$mesuration->getMesurationForGraphicAsincSpirometry('',$alerts['datarespirazione'],null,$alerts['period']);
        $tpl->assign("tableSpirometry", $result_spirometry['tabella']);
        }
        if($alerts['datapeso']!=null){
        $mesuration->table='weightMensurations';
        $result_weight=$mesuration->getMesurationForGraphicAsinc('',$alerts['datapeso'],null,$alerts['period']);
        $tpl->assign("tableWeight", $result_weight['tabella']);
        }
        if($alerts['dataemoglobbina']!=null){
        $mesuration->table='pulseoximetryMensurations';
        $result_pulseoximetry=$mesuration->getMesurationForGraphicAsinc('',$alerts['dataemoglobbina'],null,$alerts['period']);
        $tpl->assign("tablePulseoximetry", $result_pulseoximetry['tabella']);
        }
        if($alerts['datapressione']!=null){
        $mesuration->table='pressureMensurations';
        $result_pressure=$mesuration->getMesurationForGraphicAsincPressure('',$alerts['datapressione'],null,$alerts['period']);
        $tpl->assign("tablePressure", $result_pressure['tabella']);

        }


        /*echo "<pre>";var_dump($result_pressure);
        echo "<pre>";var_dump($result_weight);
        echo "<pre>";var_dump($result_glycemia);
        exit();
        */
        $tpl->compile_check = COMPILE_CHECK;
        $tpl->debugging = FALSE;
        $tpl->display("alert_view.tpl");
        





?>
