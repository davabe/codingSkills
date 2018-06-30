<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../../config/sys_application.php';
require_once CLASS_DIR . 'Check.class.php';
require_once CLASS_DIR . 'BridgePatientsDoctors.class.php';

if (!isset($_SESSION['dati'])) {

   
    session_destroy();
    exit();
}

if (!empty($_POST['idpaziente'])) {
    $idpaziente = trim($_POST['idpaziente']);
} else{
    session_destroy();
    exit();
}

$oBridgePatientsDoctors=new BridgePatientsDoctors();
$oBridgePatientsDoctors->fkPatients=$idpaziente;
$alldoctor['tabella']=$oBridgePatientsDoctors->getAllDoctorOfPatientAsync();
        

print json_encode($alldoctor);
 exit();


?>
