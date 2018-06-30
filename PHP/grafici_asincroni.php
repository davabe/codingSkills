<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once '../../config/sys_application.php';
require_once CLASS_DIR . 'Check.class.php';
require_once CLASS_DIR . 'Mesuration.class.php';

if (empty($_SESSION['dati'])) {

    header("Location: " . URL_FILE_BACKOFFICE . "index.php");
    exit();
}


/*$control = new Check();


if (!$control->checkpermit($_SESSION['dati']['idRole'], basename($_SERVER['PHP_SELF']))) {
    session_destroy();
    header("Location: " . URL_FILE_BACKOFFICE . "index.php");
    exit();
} 

if (isset($_POST['idtab']) && $_POST['idtab'] == "1") {


}*/

$mesuration=new Mesuration();

$mesuration->fkUser=$_POST['id'];

if(!empty($_POST['idnotification'])){
    
            if(isset($_POST['table'])){
                        if($_POST['table']==1){
                              $mesuration->table='glycemiaMensurations';
                              $array=$mesuration->getMesurationForGraphicAsinc($_POST['fromdate'],$_POST['todate'],null,null,$_POST['idnotification']);
                        }
                        if($_POST['table']==2){
                              $mesuration->table='weightMensurations';
                              $array=$mesuration->getMesurationForGraphicAsinc($_POST['fromdate'],$_POST['todate'],null,null,$_POST['idnotification']);
                        }
                        if($_POST['table']==3){
                              $mesuration->table='pressureMensurations';  
                              $array=$mesuration->getMesurationForGraphicAsincPressure($_POST['fromdate'],$_POST['todate'],null,null,$_POST['idnotification']);

                        }
                        if($_POST['table']==4){
                              $mesuration->table='spirometryMensurations';  
                              $array=$mesuration->getMesurationForGraphicAsincSpirometry($_POST['fromdate'],$_POST['todate'],null,null,$_POST['idnotification']);
                        }
                        if($_POST['table']==5){
                               $mesuration->table='pulseoximetryMensurations';
                               $array=$mesuration->getMesurationForGraphicAsinc($_POST['fromdate'],$_POST['todate'],null,null,$_POST['idnotification']);
                        }
                    }
    
    
    
}







else{
            if(isset($_POST['table'])){
                if($_POST['table']==1){
                      $mesuration->table='glycemiaMensurations';
                      $array=$mesuration->getMesurationForGraphicAsinc($_POST['fromdate'],$_POST['todate']);
                }
                if($_POST['table']==2){
                      $mesuration->table='weightMensurations';
                      $array=$mesuration->getMesurationForGraphicAsinc($_POST['fromdate'],$_POST['todate']);
                }
                if($_POST['table']==3){
                      $mesuration->table='pressureMensurations';  
                      $array=$mesuration->getMesurationForGraphicAsincPressure($_POST['fromdate'],$_POST['todate']);

                }
                if($_POST['table']==4){
                      $mesuration->table='spirometryMensurations';  
                      $array=$mesuration->getMesurationForGraphicAsincSpirometry($_POST['fromdate'],$_POST['todate']);
                }
                if($_POST['table']==5){
                       $mesuration->table='pulseoximetryMensurations';
                       $array=$mesuration->getMesurationForGraphicAsinc($_POST['fromdate'],$_POST['todate']);
                }
            }
 }   

//var_dump($array);
//exit();
echo json_encode($array);
exit();





?>
    
    
    

