<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once '../../config/sys_application.php';
require_once CLASS_DIR . 'Check.class.php';
require_once CLASS_DIR . 'MedicalDevices.class.php';
require_once CLASS_DIR . 'Diabetes.class.php';
require_once CLASS_DIR . 'Users.class.php';
require_once CLASS_DIR . 'Risks.class.php';
require_once CLASS_DIR . 'Consequences.class.php';
require_once CLASS_DIR . 'Login.class.php';
require_once CLASS_DIR . 'Utils.class.php';
require_once CLASS_DIR . 'BridgeUsersRoles.class.php';
require_once CLASS_DIR . 'NumberMeasurementsHours.class.php';
require_once CLASS_DIR . 'BridgeUserMedicalDevices.class.php';
require_once CLASS_DIR . 'BridgePatientsDoctors.class.php';

//unset($_SESSION['permit']);
//var_dump($_SESSION['permit']);
//exit();

if (empty($_SESSION)) {

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


/*if ($_POST != NULL) {

    //$_POST['rischi'][];
    var_dump($_POST);
    echo '<br>';
    var_dump($_POST['pippo']);
    //var_dump($familiare);
    exit();
}*/


$medicaldevice = new MedicalDevices();
$allmedicaldevice = $medicaldevice->getAllmedicaldevice();
//var_dump($allmedicaldevice);echo '<br>';
$allmedicaldevice1 = array();
$i = 0;
foreach ($allmedicaldevice as $key => $value) {
    //var_dump($key);echo '<br>';var_dump($value);echo '<br>';

    foreach ($value as $key1 => $value1) {
        //if(is_array($allmedicaldevice1[$value1])){
        //$allmedicaldevice1[$value1]=array();
        //}
        if ($key1 == 'name') {
            //$allmedicaldevice1[$value1]=array();  
            // $allmedicaldevice1[$value1]=array_merge($allmedicaldevice1[$value1],$value);
            //if($prec==$key1){
            $allmedicaldevice1[$value1][$i] = $value;
            //$prec=$key1;
            // }
        }
    }

    $i++;
}

//var_dump($allmedicaldevice1);exit;
//$risk=new Risks();
//$allrisk=$risk->getAllRisks();
//var_dump($allrisk);exit();
//$consequence=new Consequences();
//$allconsequences=$consequence->getAllConsequences();
//var_dump($allconsequences);exit();
//$diabetes = new Diabetes();
//$alldiabetes = $diabetes->getAlldiabates();
//var_dump($alldiabetes);exit();
//$userbyrole = new Users();
//$resultuserbyrole = $userbyrole->getUserbyrole(DOCTORS, UTENTE_ATTIVO);
//var_dump($resultuserbyrole);exit();
$tpl = new Smarty;

if ( !empty($_GET['idpaziente']) ){
    
unset($_SESSION['dati']['idPatient'], $_SESSION['dati']['idBridgeUsersRoles'], $_SESSION['dati']['idBridgePatientDoctor'], $_SESSION['dati']['idDoctor'], $_SESSION['dati']['idLogin'],$_SESSION['dati']['idDevice'],$_SESSION['dati']['idPregnancy']);

unset($_SESSION['dati']['idBridgeUserMedicalDeviceGlicemia'], $_SESSION['dati']['idBridgeDeviceSerialsGlicemia'], $_SESSION['dati']['idNumberMeasurementsHoursGlicemia']);

unset($_SESSION['dati']['idBridgeUserMedicalDevicePeso'],$_SESSION['dati']['idBridgeDeviceSerialsPeso'],$_SESSION['dati']['idNumberMeasurementsHoursPeso']);

unset($_SESSION['dati']['idBridgeUserMedicalDevicePressione'],$_SESSION['dati']['idBridgeDeviceSerialsPressione'],$_SESSION['dati']['idNumberMeasurementsHoursPressione']);

unset($_SESSION['dati']['idBridgeUserMedicalDevicePolmonare'],$_SESSION['dati']['idBridgeDeviceSerialsPolmonare'],$_SESSION['dati']['idNumberMeasurementsHoursPolmonare']);

unset($_SESSION['dati']['idBridgeUserMedicalDeviceSangue'],$_SESSION['dati']['idBridgeDeviceSerialsSangue'],$_SESSION['dati']['idNumberMeasurementsHoursSangue']);
    
    
    
    
    
    
    
    
 $idpaziente = trim($_GET['idpaziente']);
 $oBridgeUsersRoles = new BridgeUsersRoles();
 $oLogin = new Login();
 $oUser = new Users();
 $oNumberMeasurementsHours= new NumberMeasurementsHours();
 $oBridgeUserMedicalDevices=new BridgeUserMedicalDevices();
 $oBridgePatientsDoctors=new BridgePatientsDoctors();
 
 
 $oUser->idUser=$idpaziente;
 $paziente=$oUser->getUserbyId();
 
 /*if($_SESSION['dati']['idRole']==DOCTORS && $_SESSION['dati']['idUser']!=$paziente[0]['fkDoctor']){
     session_destroy();
     exit();
 }*/
  $oBridgePatientsDoctors->fkPatients=$idpaziente;
  $oBridgePatientsDoctors->fkDoctor=$paziente[0]['fkDoctor'];
  $idBridgePatientsDoctor=$oBridgePatientsDoctors->getIdBridgePatientsDoctors2();
  $medici=$oBridgePatientsDoctors->getMedici();
  //var_dump($idBridgePatientsDoctor);exit();
    
 $oBridgeUsersRoles->fkUser=$idpaziente;
 $oBridgeUsersRoles->fkRole=PATIENT;
 $idbridge=$oBridgeUsersRoles->getIdBridgeUsersRoles();
 
             if(empty($idbridge)){
                session_destroy();
                header("Location: " . URL_FILE_BACKOFFICE . "index.php");
                exit();

             }
  
 $oLogin->_fkBridgeUserRole=$idbridge[0]['idBridgeUserRole'];
 $LoginByfkBridge=$oLogin->getIdLoginByfkBridge();
 
 $_SESSION['dati']['idPatient']=$idpaziente;
 $_SESSION['dati']['idBridgeUsersRoles']=$idbridge[0]['idBridgeUserRole'];
 $_SESSION['dati']['idBridgePatientDoctor']=$idBridgePatientsDoctor;
 $_SESSION['dati']['idLogin']=$LoginByfkBridge[0]['idLogin'];
 //$_SESSION['dati']['idDevice']=$paziente[0]['fkDevice'];
 $_SESSION['dati']['idDoctor']=$paziente[0]['fkDoctor'];
 if($paziente[0]['pregnancy']==1)
     $_SESSION['dati']['idPregnancy']=$paziente[0]['idPregnancy'];
 
 
 
 $relativesNonModificabili=array();
 if($_SESSION['dati']['idRole']==ADMINISTRATOR)
    $relativesModificabili=$oUser->getRelativesFromUser($idpaziente,1);
 else{
     
     $relativesModificabili=$oUser->getRelativesFromUser($idpaziente,1);
     //questo vuol dire che un administatore e un dottore per diventare paziente nn dovrà toccare la fkdoctor della tabella user ma solo nei bridge 
     if(!empty($relativesModificabili)){
              foreach ($relativesModificabili as $key => $value) {
                 if(empty($value['fkDoctor'])|| $value['fkDoctor']!=$_SESSION['dati']['idUser'] ){
                     $relativesNonModificabili[]=$value;
                     unset($relativesModificabili[$key]);

                 }
             }
        }
 }
 /*
 echo'modificabili <br>';
 var_dump($relativesModificabili);
 echo'non modificabili <br>';
 var_dump($relativesNonModificabili);
 exit();
 */
 
 //$oNumberMeasurementsHours->fkDoctor=$_SESSION['dati']['idUser'];
 $oNumberMeasurementsHours->fkDoctor=$paziente[0]['fkDoctor'];
 $oNumberMeasurementsHours->fkUser=$idpaziente;
 $numberMeasurementsHours=$oNumberMeasurementsHours->getNumberMeasurementsHours();
 
 //var_dump($numberMeasurementsHours);
 $oBridgeUserMedicalDevices->fkUser=$idpaziente;
 $bridgeUserMedicalDevices=$oBridgeUserMedicalDevices->getBridgeUserMedicalDevices();
 $_SESSION['dati']['idDevice']=$paziente[0]['fkDevice'];
 if(!empty($numberMeasurementsHours) && !empty($bridgeUserMedicalDevices)){
 
             if( array_key_exists(GLICEMIA, $bridgeUserMedicalDevices) &&  array_key_exists(GLICEMIA, $numberMeasurementsHours)){

                    $_SESSION['dati']['idBridgeUserMedicalDeviceGlicemia']=$bridgeUserMedicalDevices[GLICEMIA]['idBridgeUserMedicalDevice'];
                    $_SESSION['dati']['idBridgeDeviceSerialsGlicemia']=$bridgeUserMedicalDevices[GLICEMIA]['idBridgeDeviceSerials'];
                    $_SESSION['dati']['idNumberMeasurementsHoursGlicemia']=$numberMeasurementsHours[GLICEMIA]['idNumberMeasurementsHours'];
             }



             if( array_key_exists(PESO, $bridgeUserMedicalDevices) &&  array_key_exists(PESO, $numberMeasurementsHours)){

                    $_SESSION['dati']['idBridgeUserMedicalDevicePeso']=$bridgeUserMedicalDevices[PESO]['idBridgeUserMedicalDevice'];
                    $_SESSION['dati']['idBridgeDeviceSerialsPeso']=$bridgeUserMedicalDevices[PESO]['idBridgeDeviceSerials'];
                    $_SESSION['dati']['idNumberMeasurementsHoursPeso']=$numberMeasurementsHours[PESO]['idNumberMeasurementsHours'];
             }



             if( array_key_exists(PRESSIONE, $bridgeUserMedicalDevices) &&  array_key_exists(PRESSIONE, $numberMeasurementsHours)){

                    $_SESSION['dati']['idBridgeUserMedicalDevicePressione']=$bridgeUserMedicalDevices[PRESSIONE]['idBridgeUserMedicalDevice'];
                    $_SESSION['dati']['idBridgeDeviceSerialsPressione']=$bridgeUserMedicalDevices[PRESSIONE]['idBridgeDeviceSerials'];
                    $_SESSION['dati']['idNumberMeasurementsHoursPressione']=$numberMeasurementsHours[PRESSIONE]['idNumberMeasurementsHours'];
             }




             if( array_key_exists(VOLUME_POLMONARE, $bridgeUserMedicalDevices) &&  array_key_exists(VOLUME_POLMONARE, $numberMeasurementsHours)){

                    $_SESSION['dati']['idBridgeUserMedicalDevicePolmonare']=$bridgeUserMedicalDevices[VOLUME_POLMONARE]['idBridgeUserMedicalDevice'];
                    $_SESSION['dati']['idBridgeDeviceSerialsPolmonare']=$bridgeUserMedicalDevices[VOLUME_POLMONARE]['idBridgeDeviceSerials'];
                    $_SESSION['dati']['idNumberMeasurementsHoursPolmonare']=$numberMeasurementsHours[VOLUME_POLMONARE]['idNumberMeasurementsHours'];
             }


             if( array_key_exists(SATURAZIONE_SANGUA, $bridgeUserMedicalDevices) &&  array_key_exists(SATURAZIONE_SANGUA, $numberMeasurementsHours)){

                    $_SESSION['dati']['idBridgeUserMedicalDeviceSangue']=$bridgeUserMedicalDevices[SATURAZIONE_SANGUA]['idBridgeUserMedicalDevice'];
                    $_SESSION['dati']['idBridgeDeviceSerialsSangue']=$bridgeUserMedicalDevices[SATURAZIONE_SANGUA]['idBridgeDeviceSerials'];
                    $_SESSION['dati']['idNumberMeasurementsHoursSangue']=$numberMeasurementsHours[SATURAZIONE_SANGUA]['idNumberMeasurementsHours'];
             }
}

if(empty($idBridgePatientsDoctor))
     $tab=4;
elseif(empty($relativesModificabili) && empty($relativesNonModificabili))
     $tab=2;
 elseif(empty ($paziente[0]['fkDevice'])) 
     $tab=3;
   else
       $tab=1;
 
 
 //echo '<br>';
 //var_dump($bridgeUserMedicalDevices);
 
  //exit();
 
 $tpl->assign("paziente",$paziente[0]);
 $tpl->assign("LoginByfkBridge",$LoginByfkBridge[0]);
 $tpl->assign("idbridge",$idbridge[0]['idBridgeUserRole']);
 $tpl->assign("relativesNonModificabili",$relativesNonModificabili);
 $tpl->assign("relativesModificabili",$relativesModificabili);
 $tpl->assign("numberMeasurementsHours",$numberMeasurementsHours);
 $tpl->assign("bridgeUserMedicalDevices",$bridgeUserMedicalDevices);
 $tpl->assign("tab", $tab);
 $tpl->assign("medici", $medici);
 
 //echo '<pre>'.var_dump($_SESSION).'</pre>';
 
}else{//fine modifica

unset($_SESSION['dati']['idPatient'], $_SESSION['dati']['idBridgeUsersRoles'], $_SESSION['dati']['idBridgePatientDoctor'], $_SESSION['dati']['idDoctor'], $_SESSION['dati']['idLogin'],$_SESSION['dati']['idDevice'],$_SESSION['dati']['idPregnancy']);

unset($_SESSION['dati']['idBridgeUserMedicalDeviceGlicemia'], $_SESSION['dati']['idBridgeDeviceSerialsGlicemia'], $_SESSION['dati']['idNumberMeasurementsHoursGlicemia']);

unset($_SESSION['dati']['idBridgeUserMedicalDevicePeso'],$_SESSION['dati']['idBridgeDeviceSerialsPeso'],$_SESSION['dati']['idNumberMeasurementsHoursPeso']);

unset($_SESSION['dati']['idBridgeUserMedicalDevicePressione'],$_SESSION['dati']['idBridgeDeviceSerialsPressione'],$_SESSION['dati']['idNumberMeasurementsHoursPressione']);

unset($_SESSION['dati']['idBridgeUserMedicalDevicePolmonare'],$_SESSION['dati']['idBridgeDeviceSerialsPolmonare'],$_SESSION['dati']['idNumberMeasurementsHoursPolmonare']);

unset($_SESSION['dati']['idBridgeUserMedicalDeviceSangue'],$_SESSION['dati']['idBridgeDeviceSerialsSangue'],$_SESSION['dati']['idNumberMeasurementsHoursSangue']);
$tab=NULL;
$oUser=new Users();
//var_dump($_SESSION);
}
//$tpl->assign("allconsequences",$allconsequences);
//$tpl->assign("allrisk",$allrisk);
$alldoctor=$oUser->getAllDoctor();
//echo'<pre>';var_dump($alldoctor);exit();

$tpl->assign("alldoctor", $alldoctor);
$tpl->assign("allmedicaldevice1", $allmedicaldevice1);
$tpl->assign("tab", $tab);

//$tpl->assign("alldiabetes", $alldiabetes);
//$tpl->assign("resultuserbyrole", $resultuserbyrole);booooooooooooooooooooooooooooo nn ricordo forse nn centra niente
$tpl->assign("familiare", $familiare);

require_once NOTIFY;
require_once MESSAGES;
$tpl->compile_check = COMPILE_CHECK;
$tpl->debugging = FALSE;




//var_dump( $_SESSION['dati']);    
//echo  $_SESSION['dati']['name'];
$tpl->display("inserimento_paziente.tpl");
?>
