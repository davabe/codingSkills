<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


 require_once '../../config/sys_application.php';
 
require_once CLASS_DIR . 'Users.class.php';
require_once CLASS_DIR . 'Check.class.php';
require_once CLASS_DIR . 'BridgePatientsDoctors.class.php';
//echo session_save_path();

if (empty($_SESSION['dati'])) {

    header("Location: " . URL_FILE_BACKOFFICE . "index.php");
    exit();
}

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
$oUser=new Users();
$alladmin=$oUser->getUserbyrole(ADMINISTRATOR,UTENTE_ATTIVO);
$alldoctor=$oUser->getUserbyrole(DOCTORS,UTENTE_ATTIVO);
$allpatient=$oBridgePatientsDoctors->getAllPatientOfDoctor();


require_once CLASS_DIR . 'Logger.class.php';
$oLogger = new Logger();

$elencoLogger = $oLogger->getElencoFileWsError();

 $tpl = new Smarty;
        require_once NOTIFY;
        require_once MESSAGES;
        $tpl->assign("alladmin", $alladmin);
        $tpl->assign("alldoctor", $alldoctor);
        $tpl->assign("allpatient", $allpatient);
        
        $tpl->assign("elencoLogger", $elencoLogger);
        
        $tpl->compile_check = COMPILE_CHECK;
        $tpl->debugging = FALSE;
        
       
        $tpl->display("index_admin.tpl");


?>
       