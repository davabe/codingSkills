<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


    require_once '../../config/sys_application.php';

    require_once CLASS_DIR . 'Utils.class.php';
    require_once CLASS_DIR . 'Logger.class.php';
    require_once CLASS_DIR . 'Check.class.php';
    require_once CLASS_DIR . 'AjaxFunction.class.php';
    
    //echo session_save_path();

    if (empty($_SESSION['dati'])) {

       header("Location: " . URL_FILE_BACKOFFICE . "index.php");
       exit();
    }

    $control = new Check();
    
    $oUtils = new Utils();

    $fileDate = $oUtils->checkValue($_GET['id']);
    
    $file = LOG_PATH."WS_error_".$fileDate.".log";
    
    
    $myfile = fopen($file, "r") or die("Impossibile aprire il file ".$file);

    $testoFile = "";
    
    while(!feof($myfile)) {
        
        $testoFile .= fgets($myfile)."<br />";
        
    }

    fclose($myfile);
    
    
    $ajaxFunction = new AjaxFunction();
    $measurationType = $ajaxFunction->getMeasurationType();
    


    $tpl = new Smarty;
    require_once NOTIFY;
    require_once MESSAGES;

    $anno = substr($fileDate, 0, 4);
    $mese = substr($fileDate, 4, 2);
    $giorno = substr($fileDate, 6, 2);
    
    $tpl->assign("filedate", $giorno."/".$mese."/".$anno);
    
    $tpl->assign("testoFile", $testoFile);
    $tpl->assign("measurationType", $measurationType);
    
    //echo "<pre>";var_dump($allmedicaldevice);exit;

    $tpl->compile_check = COMPILE_CHECK;
    $tpl->debugging = FALSE;


    $tpl->display("viewLog.tpl");
