<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


require_once '../../config/sys_application.php';
require_once CLASS_DIR . 'Check.class.php';
require_once CLASS_DIR . 'Messages.class.php';
require_once CLASS_DIR . 'Users.class.php';


//controlli dell asessione
//echo basename($_SERVER["HTTP_REFERER"]) ;exit();



if ( !empty($_GET['id']) )
   $idpaziente = trim($_GET['id']); 
$oUsers=new Users();
$oUtils=new Utils();
$message=new Messages();

//var_dump($_POST);exit();

if(isset($_POST['textMessage'])){
    $message->fkReceive=$idpaziente;
    $message->fkSender=$_SESSION['dati']['idUser'];
    $message->textMessage=$oUtils->checkValue($_POST['textMessage']);
    $dt = new DateTime();
    $message->date=$dt->format('Y-m-d H:i:s');
    $message->type='manuale';
    $message->insertMessage();
    //echo 'pippo';
}

    
$oUsers->idUser=$idpaziente;
$name=$oUsers->getUserbyId();
$recive=$name[0]['name']." ".$name[0]['surname'];
$message->fkReceive=$_SESSION['dati']['idUser'];
$message->fkSender=$idpaziente;
$messages=$message->getMessages();
//var_dump($_SERVER['HTTP_REFERER']);exit();
if(isset($_SERVER['HTTP_REFERER']) && basename($_SERVER["HTTP_REFERER"])==("alert.php?id=".$idpaziente) && !isset($_GET['mes'])){
    header("Location: " . URL_FILE_BACKOFFICE .basename($_SERVER["HTTP_REFERER"]));
    exit();
    
}


//per fare update
$message->fkSender=$idpaziente;
$message->fkReceive=$_SESSION['dati']['idUser'];
$message->updateStateMessage();


$tpl = new Smarty;
require_once NOTIFY;
require_once MESSAGES;
$tpl->assign("messages",$messages);
$tpl->assign("recive",$recive);
$tpl->assign("id",$idpaziente);
$tpl->compile_check = COMPILE_CHECK;
$tpl->debugging = FALSE;
$tpl->display("message.tpl");
    


?>






        





