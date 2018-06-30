<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */



require_once '../../config/sys_application.php';
require_once CLASS_DIR . 'BridgePatientsDoctors.class.php';
require_once CLASS_DIR . 'Check.class.php';
//echo session_save_path();
if (empty($_SESSION['dati'])) {

    header("Location: " . URL_FILE_BACKOFFICE . "index.php");
    exit();
}

//controllo permessi
$control = new Check();


//echo 'pippo';
//exit();
//var_dump($_SESSION['permit']);
//echo "<br>";
//var_dump($_SESSION['dati']['idRole']);
//echo "<br>";
//echo basename($_SERVER['PHP_SELF']);
//exit;
if (!$control->checkpermit($_SESSION['dati']['idRole'], basename($_SERVER['PHP_SELF']))) {
    session_destroy();
    header("Location: " . URL_FILE_BACKOFFICE . "index.php");
    exit();
}









$oBridgePatientsDoctors=new BridgePatientsDoctors();
$oBridgePatientsDoctors->fkDoctor=$_SESSION['dati']['idUser'];
$allpatient=$oBridgePatientsDoctors->getAllPatientOfDoctor();
//exit();
 $tpl = new Smarty;
require_once NOTIFY;
require_once MESSAGES;
        $tpl->assign("allpatient", $allpatient);
        $tpl->compile_check = COMPILE_CHECK;
        $tpl->debugging = FALSE;
        
       
       
        
        
        $tpl->display("index_doctor.tpl");




?>
