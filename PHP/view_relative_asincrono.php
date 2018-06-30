<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../../config/sys_application.php';
require_once CLASS_DIR . 'Check.class.php';
require_once CLASS_DIR . 'Users.class.php';





if (!isset($_SESSION['dati'])) {

   
    session_destroy();
    exit();
}

//echo 'pippo';exit();

if (!empty($_POST['idpaziente'])) 
    $idpaziente = trim($_POST['idpaziente']);
    else{
    session_destroy();
    exit();
}


if($_SESSION['dati']['idRole']==DOCTORS || $_SESSION['dati']['idRole']==ADMINISTRATOR){
     //mancano i controlli per dire se la notifica può essere vista con fk del dottore dentro notificatinalert forse è da spostare il posto di questo controllo
 }
 else{
     session_destroy();
     exit();
     
 }

$oUser = new Users();
$oUser->idUser=$idpaziente;
$allrelatives['tabella']=$oUser->getRelativesFromUserAsync();
 
 
 print json_encode($allrelatives);
 exit();
 
 
 
 




?>
