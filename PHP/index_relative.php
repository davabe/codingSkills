<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../../config/sys_application.php';
require_once CLASS_DIR . 'BridgePatientsRelatives.class.php';
require_once CLASS_DIR . 'Check.class.php';
//echo session_save_path();
if (empty($_SESSION['dati'])) {

    header("Location: " . URL_FILE_BACKOFFICE . "index.php");
    exit();
}

$control = new Check();

if (!$control->checkpermit($_SESSION['dati']['idRole'], basename($_SERVER['PHP_SELF']))) {
    session_destroy();
    header("Location: " . URL_FILE_BACKOFFICE . "index.php");
    exit();
}

$oBridgePatientsRelatives=new BridgePatientsRelatives();
$oBridgePatientsRelatives->fkRelative=$_SESSION['dati']['idUser'];
$allpatients=$oBridgePatientsRelatives->getAllPatientByRelative();


 $tpl = new Smarty;
require_once NOTIFY;
require_once MESSAGES;
        $tpl->assign("allpatients", $allpatients);
        $tpl->compile_check = COMPILE_CHECK;
        $tpl->debugging = FALSE;
        
       
       
        
        
 $tpl->display("index_relative.tpl");






?>
