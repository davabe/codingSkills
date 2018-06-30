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
//require_once CLASS_DIR . 'Risks.class.php';
//require_once CLASS_DIR . 'Consequences.class.php';
require_once CLASS_DIR . 'Login.class.php';
require_once CLASS_DIR . 'Pregnancy.class.php';
require_once CLASS_DIR . 'Utils.class.php';
require_once CLASS_DIR . 'BridgeUsersRoles.class.php';
require_once CLASS_DIR . 'BridgePatientsRelatives.class.php';
require_once CLASS_DIR . 'BridgePatientsDoctors.class.php';
require_once CLASS_DIR . 'Devices.class.php';
require_once CLASS_DIR . 'BridgeUserMedicalDevices.class.php';
require_once CLASS_DIR . 'NumberMeasurementsHours.class.php';
require_once CLASS_DIR . 'BridgeDeviceSerials.class.php';


if (empty($_SESSION)) {

    header("Location: " . URL_FILE_BACKOFFICE . "index.php");
    exit();
}


$control = new Check();


if (!$control->checkpermit($_SESSION['dati']['idRole'], basename($_SERVER['PHP_SELF']))) {
    session_destroy();
    header("Location: " . URL_FILE_BACKOFFICE . "index.php");
    exit();
}

//var_dump($_POST);exit;
if (isset($_POST['idtab']) && $_POST['idtab'] == "1") {
    $datastart = NULL;
    $datafinish = NULL;
    $oUtils = new Utils();

    $nome = $oUtils->checkValue($_POST['nome']);
    $cognome = $oUtils->checkValue($_POST['cognome']);
    $username = $oUtils->checkValue($_POST['username']);
    $password = $oUtils->checkValue($_POST['password']);
    $email = $oUtils->checkValue($_POST['email']);
    $date = $oUtils->checkValue($_POST['datanascita']);
    $datetime=new DateTime($date);
    $date =$datetime->format('Y-m-d H:i:s');
    $domicilio = $oUtils->checkValue($_POST['domicilio']);
    $residenza = $oUtils->checkValue($_POST['residenza']);
    $cd = $oUtils->checkValue($_POST['cd']);
    $tel = $oUtils->checkValue(str_replace('+39','',$_POST['tel']));

    $idRuolo = PATIENT;
    $idStato = UTENTE_PROFILO_INCOMPLETO;

    if ($_POST['optionsRadios'] == "maschio") {
        $sesso = 'm';
        $pregnancy = 0;
    }

    if ($_POST['optionsRadios'] == "femmina") {
        $sesso = 'f';
        if ($_POST['optionsRadios2'] == "si") {
            $datastart0 =new DateTime($oUtils->checkValue($_POST['datainizio']));
            $datafinish0 =new DateTime($oUtils->checkValue($_POST['datafine']));
            $datastart = $datastart0->format('Y-m-d H:i:s');
            $datafinish = $datafinish0->format('Y-m-d H:i:s');
            $pregnancy = 1;
        } else {

            $pregnancy = 0;
        }
    }

    
    
    //provo a mettere  $_SESSION['dati']['idDoctor'] a null
   /* if (isset($_POST['iddoctor']) && isset($_POST['typedoctor'])) {
        $idDoctor = $oUtils->checkValue($_POST['iddoctor']);
        $typeDoctor = $oUtils->checkValue($_POST['typedoctor']);
    } else {

        $idDoctor = $_SESSION['dati']['idUser'];
        $typeDoctor = $_SESSION['dati']['fkDoctorType'];
    }*/
    $idDoctor=$_SESSION['dati']['idUser'];
    $_SESSION['dati']['idDoctor'] =  $idDoctor;


    $oPregnancy = new Pregnancy();
    $oBridgeUsersRoles = new BridgeUsersRoles();
    $oLogin = new Login();
    $oUser = new Users();
    //$oBridgePatientsDoctors = new BridgePatientsDoctors;

    $oUser->name = $nome;
    $oUser->surname = $cognome;
    $oUser->email = $email;
    $oUser->tel = $tel;
    $oUser->domicile = $domicilio;
    $oUser->gender = $sesso;
    $oUser->codeIdentifier = $cd;
    $oUser->residency = $residenza;
    $oUser->fkDoctor = $idDoctor;
    $oUser->date = $date;
    $oUser->age = $oUser->age($date);
    $oUser->pregnancy = $pregnancy;
    $oLogin->setFiled($username, $password, null, null);
    
    
        //exit();
    //settaggio dati per la modifica manca pregnancy
        if( !empty($_SESSION['dati']['idLogin']) && !empty($_SESSION['dati']['idPatient']) && !empty($_SESSION['dati']['idBridgeUsersRoles']) && !empty($_SESSION['dati']['idBridgePatientDoctor'])){
            $oUser->idUser=$_SESSION['dati']['idPatient'];
            $oBridgeUsersRoles->idBridgeUserRole=$_SESSION['dati']['idBridgeUsersRoles'];       
            //$oBridgePatientsDoctors->idBridgePatientDoctor=$_SESSION['dati']['idBridgePatientDoctor'];
            $oLogin->idLogin=$_SESSION['dati']['idLogin'];
            if(!empty($_SESSION['dati']['idPregnancy']))
                $oPregnancy->idPregnancy=$_SESSION['dati']['idPregnancy'];
        }
    
    
    

    $error = "";
   
    if (isset($datastart) && isset($datafinish)) {
        $oPregnancy->datestart = $datastart;
        $oPregnancy->datefinish = $datafinish;
        $error.=$oPregnancy->isError();
    }
    
    if( !empty($_SESSION['dati']['idLogin']) && !empty($_SESSION['dati']['idPatient']) && !empty($_SESSION['dati']['idBridgeUsersRoles']) && !empty($_SESSION['dati']['idBridgePatientDoctor'])){
    
        /*if ( $oLogin->checkUniqueUsername()>0  ) 
            $error .= " L'username inserito è già utilizzato";*/
        $pas=$oLogin->getUsernamePass();
        $utente_pre_update=$oUser->getUserbyId();
        $login_pre_update=$oLogin->getLoginByid();
        if($utente_pre_update[0]['email']!=$oUser->email && $oUser->checkUniqueEmail()>0)
            $error .= "Lindirizzo email inserito è già utilizzato<br>";
        if($utente_pre_update[0]['codeIdentifier']!=$oUser->codeIdentifier && $oUser->checkUniqueCodFiscal()>0)
            $error .= "Il Codice Fiscale inserito è già utilizzato, ricontrollare o contattare l'amministratore<br>";
        if($login_pre_update[0]['username']!=$pas['username'])
            $error .= "Modifica username inappropriata<br>";
             
            
            //var_dump($pas);
            
             if (!empty($pas['password']) && strlen($pas['password'])<8 )
                $error .= "La password deve essere composta da almeno 8 caratteri<br>";
             if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",  $oUser->email)) 
                 $error .= "Email non valida <br>";
    }
             
        
    else {
            $error = $oUser->isError();
            $error.=$oLogin->isError();
        
    }
    


    /* inserire poi per il login if (  $oLogin->checkUniqueUsername()>0  ) {

      if ( empty($error) )
      $error = "Si è verificato il seguente errore: <br />";

      $error .= "L'username inserita è già utilizzata";

      } */


    if (!empty($error)) {

        echo json_encode($error);
        exit();
    } else {
        
        
        

        $oUser->insert();
        $idUser = $oUser->getUserbyCd();
        $_SESSION['dati']['idPatient'] = $idUser[0]['idUser'];
        if (isset($datastart) && isset($datafinish)) {
            $oPregnancy->setPregnancy($datastart, $datafinish, $idUser[0]['idUser']);
            $oPregnancy->insertPregnancydate();
            $idPregnancy=$oPregnancy->getIdpregnancy();
            $_SESSION['dati']['idPregnancy']=$idPregnancy[0]['idPregnancy'];
        }
        if(empty($_SESSION['dati']['idBridgeUsersRoles'])){
                    $oBridgeUsersRoles->fkUser = $idUser[0]['idUser'];
                    $oBridgeUsersRoles->fkRole = $idRuolo;
                    $oBridgeUsersRoles->insertBridgeUsersRoles();
                    $idBridgeUsersRoles = $oBridgeUsersRoles->getIdBridgeUsersRoles();
                    $_SESSION['dati']['idBridgeUsersRoles']=$idBridgeUsersRoles[0]['idBridgeUserRole'];
        }
        
        
        $oLogin->setFiled($username, $password, $idStato, $_SESSION['dati']['idBridgeUsersRoles']);
        // gestire errore per username già esistente 
        $pas=$oLogin->getUsernamePass();
        if($pas['password']!="")
            $oLogin->insertuserpass();
        
        if(empty($_SESSION['dati']['idLogin'])){
            $idLogin=$oLogin->getIdLogin();
            $_SESSION['dati']['idLogin']=$idLogin[0]['idLogin'];
        }
        
        /*if(empty($_SESSION['dati']['idBridgePatientDoctor'])){
                
                $oBridgePatientsDoctors->fkDoctor = $idDoctor;
                $oBridgePatientsDoctors->fkPatients = $idUser[0]['idUser'];
                $oBridgePatientsDoctors->fkDoctorType = $typeDoctor;
                $oBridgePatientsDoctors->insertBridgePatientsDoctors();
                $idBridgePatientsDoctors=$oBridgePatientsDoctors->getIdBridgePatientsDoctors();
                $_SESSION['dati']['idBridgePatientDoctor']=$idBridgePatientsDoctors[0]['idBridgePatientDoctor'];
        }*/
        
        
        $error = '0';
        echo json_encode($error);
        exit();
    }


   
}





if (isset($_POST['idtab']) && $_POST['idtab'] == "4") {
    
    
    
    $oBridgePatientsDoctors=new BridgePatientsDoctors();
    
    
    $error = "";
    
    if(empty($_POST['medici']))
        $error = "inserire almeno un medico";
   
    if (!empty($error)) {

        echo json_encode($error);
        exit();
    } else {
        
     if( !empty($_SESSION['dati']['idBridgePatientDoctor'])){
    
       $oBridgePatientsDoctors->idBridgePatientDoctor=$_SESSION ['dati']['idBridgePatientDoctor'];
       $oBridgePatientsDoctors->delete();  
         
    }
         
         $oBridgePatientsDoctors->fkPatients=$_SESSION['dati']['idPatient'];
         $oBridgePatientsDoctors->fkDoctor=$_POST['medici'];
        
         $oBridgePatientsDoctors->insertBridgePatientsDoctors2();
         
              $_SESSION['dati']['idBridgePatientDoctor']=$oBridgePatientsDoctors->getIdBridgePatientsDoctors2();
              
          
         
        
        $error = '0';
        echo json_encode($error);
        exit();
    }


   
}























if (isset($_POST['idtab']) && $_POST['idtab'] == "2") {
    //var_dump($_POST);exit();
    $oUtils = new Utils();


    if (!empty($_POST['tag2']) && !empty($_POST['tagid2'])) {


        $oUser = new Users();
        if ($_POST['sesso'] == "maschio") {
            $sesso = 'm';
        }

        if ($_POST['sesso'] == "femmina") {
            $sesso = 'f';
        }

        $oUser->name = $_POST['nome1'];
        $oUser->surname = $_POST['cognome1'];
        $oUser->email = $_POST['email1'];
        $oUser->tel = $_POST['tel1'];
        $oUser->domicile = $_POST['domicilio1'];
        $oUser->gender = $sesso;
        $oUser->codeIdentifier = $_POST['cd1'];
        $oUser->residency = $_POST['residenza1'];
        $oUser->date = $_POST['datanascita1'];
        $idUser = $oUser->getUserbyPost();
        if ($idUser[0]['idUser'] == $_POST['tagid2']) {
            $oBridgePatientsRelatives = new BridgePatientsRelatives();
            $oBridgePatientsRelatives->fkPatients = $_SESSION['dati']['idPatient'];
            $oBridgePatientsRelatives->fkRelative = $_POST['tagid2'];
            $oBridgePatientsRelatives->fkRelativeType = $_POST['parentela'];
            
            $oBridgeUsersRoles = new BridgeUsersRoles();
            $oLogin = new Login();
            $error = "";
            $error = $oBridgePatientsRelatives->isError();
            if (!empty($error)) {

                echo json_encode($error);
                exit();
            }

            //inserimento parente con profilo gia esistente
            
            //Se fkDoctor è null significa che questo utente nn è parente con nessuno quindi nn estiste un login come paziente quindi nn c'è un idbridgeuserrole come parente
            if(!empty($idUser[0]['doctor'])){
                //ha profilo parente 
                    $oBridgePatientsRelatives->fkDoctor=$idUser[0]['doctor'];
                
                    /*$oBridgeUsersRoles->fkUser = $idUser[0]['idUser'];
                    $oBridgeUsersRoles->fkRole = RELATIVE;
                    $oBridgeUsersRoles->insertBridgeUsersRoles();
                    $idBridgeUsersRoles = $oBridgeUsersRoles->getIdBridgeUsersRoles();
                    $setta=0;
                    foreach ($idUser as $key => $valore){
                        if($valore['fkRole']==RELATIVE){
                            $oLogin->setFiled($idUser[0]['username'],$idUser[0]['password'], UTENTE_ATTIVO,$valore['idBridgeUserRole']);
                            $setta=1;
                        }   
                    }
                        
                    if($setta==1)
                        $oLogin->insertuserpass();
                    
                        */
            }
            else{
                    //nn ha profilo parente controllo se è dottore o amministratore 
                if(!empty($idUser[0]['fkDoctor']))
                    $oBridgePatientsRelatives->fkDoctor=$idUser[0]['fkDoctor'];
                 else
                     $oBridgePatientsRelatives->fkDoctor=$_SESSION['dati']['idDoctor'];
                 //questo significa che è dottore
                 
                 
                 
                    $oBridgeUsersRoles->fkUser = $idUser[0]['idUser'];
                    $oBridgeUsersRoles->fkRole = RELATIVE;
                    $oBridgeUsersRoles->insertBridgeUsersRoles();
                    $idBridgeUsersRoles = $oBridgeUsersRoles->getIdBridgeUsersRoles();

                    $oLogin->setFiled($idUser[0]['username'],$idUser[0]['password'], UTENTE_ATTIVO, $idBridgeUsersRoles[0]['idBridgeUserRole']);

                    $oLogin->insertuserpass();
            }
                
            
            $oBridgePatientsRelatives->insertBridgePatientsRelatives();
            $error = '0';
            echo json_encode($error);
            exit();
        } else {

            session_destroy();
            $var = array("redirect" => URL_FILE_BACKOFFICE . "index.php");
            echo json_encode($var);
            //header("Location: " . URL_FILE_BACKOFFICE . "index.php");
            exit();
        }
    } else {

       // var_dump($_POST);
        //exit();
        $oUtils = new Utils();
        $nome = $oUtils->checkValue($_POST['nome1']);
        $cognome = $oUtils->checkValue($_POST['cognome1']);
        $username = $oUtils->checkValue($_POST['username1']);
        $password = md5($oUtils->checkValue($_POST['password1']));
        $email = $oUtils->checkValue($_POST['email1']);
        $date = $oUtils->checkValue($_POST['datanascita1']);
        $domicilio = $oUtils->checkValue($_POST['domicilio1']);
        $residenza = $oUtils->checkValue($_POST['residenza1']);
        $cd = $oUtils->checkValue($_POST['cd1']);
        $tel = $oUtils->checkValue(str_replace('+39','',$_POST['tel1']));
        $parentela = $oUtils->checkValue($_POST['parentela']);
        $idRuolo = RELATIVE;
        $idStato = UTENTE_ATTIVO;
        $pregnancy = 0;
        $sesso='';

        if ( array_key_exists('sesso',$_POST ) && $_POST['sesso'] == "maschio") {
            $sesso = 'm';
        }

        if (array_key_exists('sesso',$_POST) && $_POST['sesso'] == "femmina") {
            $sesso = 'f';
        }


        $oBridgeUsersRoles = new BridgeUsersRoles();
        $oLogin = new Login();
        $oUser = new Users();
        $oBridgePatientsRelatives = new BridgePatientsRelatives();
        $error = "";
        if(!empty($_POST['idrelative'])){
        $oUser->idUser=$_POST['idrelative'];   
        $oUser->email = $email;
        $oUser->tel = $tel;
        $oBridgePatientsRelatives->fkRelativeType = $parentela;
        if ($oUser->email!="" && $oUser->checkUniqueEmail()>0  ) 
                    $error .= "Lindirizzo email inserito è già utilizzato<br>";
         if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",  $oUser->email)) 
                 $error .= "Email non valida<br>";
                 
                  
        }else{
        $oUser->name = $nome;
        $oUser->surname = $cognome;
        $oUser->email = $email;
        $oUser->tel = $tel;
        $oUser->domicile = $domicilio;
        $oUser->gender = $sesso;
        $oUser->codeIdentifier = $cd;
        $oUser->residency = $residenza;
        $oUser->fkDoctor=$_SESSION['dati']['idDoctor'];
        $oUser->date = $date;
        $oUser->age = $oUser->age($date);
        $oUser->pregnancy = $pregnancy;
        //settare fkdoctor in base se c'è la modifica o l'inserimento
        $oLogin->setFiled($username, $password, null, null);
        $oBridgePatientsRelatives->fkRelativeType = $parentela;

        
        $error = $oUser->isError2();
        $error.=$oLogin->isError();
        $error.=$oBridgePatientsRelatives->isError();
        }



        if(!empty($_POST['idrelative']) && $_SESSION['dati']['idRole']==DOCTORS){
            
            $modifica=$oUser->getUserbyId();
            if($modifica[0]['fkDoctor']!=$_SESSION['dati']['idUser'])
               $error .= "l'utente non è modificabile <br />"; 
            
            }
            
            
            
            if(!empty($_POST['idrelative']) && $_SESSION['dati']['idRole']==ADMINISTRATOR){
            
            $modifica=$oUser->getUserbyId();
            
            
            }
            
            
        if (!empty($error)) {
            echo json_encode($error);
            exit();
        } else {
            //il seguente codice è per la modifica
            if(!empty($_POST['idrelative']) && !empty($_POST['doctor'])){
                
                //$oUser->idUser=$_POST['idrelative'];
                $oBridgePatientsRelatives->fkDoctor=$_POST['doctor'];
                
            }
            else
                $oBridgePatientsRelatives->fkDoctor=$_SESSION['dati']['idDoctor'];
            
            
            if(!empty($_POST['idrelative'])){//&& $_SESSION['dati']['idRole']==DOCTORS
                $oUser->updateEmailTel();
                $oBridgePatientsRelatives->fkRelative =$modifica[0]['idUser'];
                
            }else  {  
                $oUser->insert();
                $idUser = $oUser->getUserbyCd();
                $oBridgePatientsRelatives->fkRelative = $idUser[0]['idUser'];
            }   


            if(empty($_POST['idrelative'])){
                    $oBridgeUsersRoles->fkUser = $idUser[0]['idUser'];
                    $oBridgeUsersRoles->fkRole = $idRuolo;
                    $oBridgeUsersRoles->insertBridgeUsersRoles();
                    $idBridgeUsersRoles = $oBridgeUsersRoles->getIdBridgeUsersRoles();


                    $oLogin->setFiled($username, $password, $idStato, $idBridgeUsersRoles[0]['idBridgeUserRole']);
                    // gestire errore per username già esistente 
                    $oLogin->insertuserpass();
            }

            //questo viene garantito dalla univocità di fkPatients fkRelative
            $oBridgePatientsRelatives->fkPatients = $_SESSION['dati']['idPatient'];
            
            
            
            $oBridgePatientsRelatives->insertBridgePatientsRelatives();
            $error = '0';
            echo json_encode($error);
            exit();
        }
    }
}








if (!empty($_POST['idtab']) && $_POST['idtab'] == "3") {
    
    //var_dump($_POST);exit();
    
    $error = "";
    $oUser = new Users();
    $oDevice = new Devices();
    $oMedicalDevice=new MedicalDevices();
    if(!empty($_SESSION['dati']['idDevice']))
        $oDevice->idDevice=$_SESSION['dati']['idDevice'];
    $oBridgeUserMedicalDevice = new BridgeUserMedicalDevices();
    $oNumberMeasurementsHours = new NumberMeasurementsHours();
    $oBridgeDeviceSerials=new BridgeDeviceSerials();
    $oDevice->serial=trim($_POST['seriale']);
    
    $error =$oDevice->isError();
    if(!array_key_exists('capacitarespiratoria',$_POST ) && !array_key_exists('emoglobina',$_POST ) && !array_key_exists('pressione',$_POST ) && !array_key_exists('peso',$_POST) && !array_key_exists('glicemia',$_POST )  )
            $error.="Obbligatorio inserire uno dei dispositivi medici <br>";
    //var_dump($error);exit();
     if (!empty($error)) {
           // $var = array("redirect" =>'0',"error" =>$error);
            print json_encode($error);
            exit();
        } else {
     
     
     
     
                $oDevice->insert();
                $fkDevices=$oDevice->getIdDevice();
                $oUser->idUser=$_SESSION['dati']['idPatient'];
                $oUser->fkDevice=$fkDevices[0]['idDevice'];
                $oUser->updatefkDevice();
    
                        if ( array_key_exists('capacitarespiratoria',$_POST ) && $_POST['capacitarespiratoria'] == "si") {
                            
                            //echo json_encode($fkDevices[0]['idDevice']);exit();
                            if(!empty($_POST['serialerespirazione']))
                                $oBridgeDeviceSerials->serial=trim($_POST['serialerespirazione']);
                            
                            $oNumberMeasurementsHours->numMesurations=$_POST['spirometronum'];
                            $oNumberMeasurementsHours->fkMedicalDevice=$_POST['spirometro'];
                            $time='time';
                            
                            for($i=1;$i<=$_POST['spirometronum'];$i++){
                                $time=$time.$i;    
                                $oNumberMeasurementsHours->$time=$_POST["oraspirometronum$i"].":00";
                                $time='time';
                            }
                            
                            $error.=$oNumberMeasurementsHours->isError('allo spirometro');
                            //$error.=$oBridgeDeviceSerials->isError('allo spirometro');
                            if (!empty($error)) {
          
                                //print json_encode($error);
                                //exit();
                            } else {
                            
                                        
                                        if(!empty($_SESSION['dati']['idBridgeUserMedicalDevicePolmonare']) && !empty($_SESSION['dati']['idBridgeDeviceSerialsPolmonare']) && !empty($_SESSION['dati']['idNumberMeasurementsHoursPolmonare'])){
                                            
                                           // if($oBridgeUserMedicalDevice->checkUniquemedicalSerial($_SESSION['dati']['idBridgeDeviceSerialsPolmonare'])==1)
                                                $oBridgeDeviceSerials->idBridgeDeviceSerials=$_SESSION['dati']['idBridgeDeviceSerialsPolmonare'];
                                            
                                            $oBridgeUserMedicalDevice->idBridgeUserMedicalDevice=$_SESSION['dati']['idBridgeUserMedicalDevicePolmonare'];
                                            $oNumberMeasurementsHours->idNumberMeasurementsHours=$_SESSION['dati']['idNumberMeasurementsHoursPolmonare'];
                                        }
                                        
                                        $oBridgeDeviceSerials->fkMedicalDevice=$_POST['spirometro'];
                                        $oBridgeDeviceSerials->fkUser=$_SESSION['dati']['idPatient'];
                                        $oBridgeDeviceSerials->insert();
                                        $idBridgeDeviceSerials=$oBridgeDeviceSerials->getIdBridgeDeviceSerials();
                                        
                                        $oBridgeUserMedicalDevice->fkUser=$_SESSION['dati']['idPatient'];
                                        $oBridgeUserMedicalDevice->fkBridgeDeviceSerials=$idBridgeDeviceSerials[0]['idBridgeDeviceSerials'];
                                        $oBridgeUserMedicalDevice->insert();

                                        $oNumberMeasurementsHours->fkMedicalDevice=$_POST['spirometro'];
                                        $oNumberMeasurementsHours->fkDoctor=$_SESSION['dati']['idDoctor'];
                                        $oNumberMeasurementsHours->fkUser=$_SESSION['dati']['idPatient'];

                                        $oNumberMeasurementsHours->controller();
                                        $oNumberMeasurementsHours->insert();
                                        
                                        //$var = array("redirect" => URL_FILE_BACKOFFICE . "inserimento_paziente.php");
                                        //echo json_encode($var);
                                        //exit();
                                }

                    } else {
                        
                        $oBridgeUserMedicalDevice->deleteAssociationDeviceUser( $_SESSION['dati']['idPatient'], VOLUME_POLMONARE );
                        
                    }
                    /* fine if spirometro*/
       
                    
                    if ( array_key_exists('emoglobina',$_POST ) && $_POST['emoglobina'] == "si") {
                            
                        
                            //echo json_encode($fkDevices[0]['idDevice']);exit();
                            if(!empty($_POST['serialeemoglobina']))
                                $oBridgeDeviceSerials->serial=trim($_POST['serialeemoglobina']);
                            
                            $oNumberMeasurementsHours->numMesurations=$_POST['pulsiossimetronum'];
                            $oNumberMeasurementsHours->fkMedicalDevice=$_POST['pulsiossimetro'];
                            $time='time';
                            
                            for($i=1;$i<=$_POST['pulsiossimetronum'];$i++){
                                $time=$time.$i;    
                                $oNumberMeasurementsHours->$time=$_POST["orapulsiossimetronum$i"].":00";
                                $time='time';
                            }
                            
                            $error.=$oNumberMeasurementsHours->isError('pulsiossimetro');
                            if (!empty($error)) {
          
                                //print json_encode($error);
                                //exit();
                            } else {
                            
                                        //$oUser->idUser=$_SESSION['dati']['idPatient'];
                                        //$oUser->fkDevice=$fkDevices[0]['idDevice'];
                                        //$oBridgeUserMedicalDevice->fkUser=$_SESSION['dati']['idPatient'];
                                
                                
                                            if(!empty($_SESSION['dati']['idBridgeUserMedicalDeviceSangue']) && !empty($_SESSION['dati']['idBridgeDeviceSerialsSangue']) && !empty($_SESSION['dati']['idNumberMeasurementsHoursSangue'])){
                                                       
                                                //if($oBridgeUserMedicalDevice->checkUniquemedicalSerial($_SESSION['dati']['idBridgeDeviceSerialsSangue'])==1)
                                                        $oBridgeDeviceSerials->idBridgeDeviceSerials=$_SESSION['dati']['idBridgeDeviceSerialsSangue'];
                                                
                                                        $oBridgeUserMedicalDevice->idBridgeUserMedicalDevice=$_SESSION['dati']['idBridgeUserMedicalDeviceSangue'];
                                                        $oNumberMeasurementsHours->idNumberMeasurementsHours=$_SESSION['dati']['idNumberMeasurementsHoursSangue'];
                                         }
                                        
                                        
                                        $oBridgeDeviceSerials->fkMedicalDevice=$_POST['pulsiossimetro'];
                                        $oBridgeDeviceSerials->fkUser=$_SESSION['dati']['idPatient'];
                                        $medical=$oMedicalDevice->getMedicaldeviceById($_POST['pulsiossimetro']);
                                        foreach ($array_device_no_serial['pulsiossimetro'] as $key => $value) {
                                            if($value['marca']==$medical['brand'] && $value['modello']==$medical['model'] )
                                                 $oBridgeDeviceSerials->serial=$value['serial'];
                                        }
                                        $oBridgeDeviceSerials->insert();
                                        $idBridgeDeviceSerials=$oBridgeDeviceSerials->getIdBridgeDeviceSerials();
                                        
                                        $oBridgeUserMedicalDevice->fkUser=$_SESSION['dati']['idPatient'];
                                        $oBridgeUserMedicalDevice->fkBridgeDeviceSerials=$idBridgeDeviceSerials[0]['idBridgeDeviceSerials'];
                                        $oBridgeUserMedicalDevice->insert();
                                        
                                        
                                        
                                        $oNumberMeasurementsHours->fkMedicalDevice=$_POST['pulsiossimetro'];
                                        $oNumberMeasurementsHours->fkDoctor=$_SESSION['dati']['idDoctor'];
                                        $oNumberMeasurementsHours->fkUser=$_SESSION['dati']['idPatient'];

                                        $oNumberMeasurementsHours->controller();
                                        $oNumberMeasurementsHours->insert();
                                        //$var = array("redirect" => URL_FILE_BACKOFFICE . "inserimento_paziente.php");
                                        //echo json_encode($var);
                                        //exit();
                                }

                    } else {
                        
                        $oBridgeUserMedicalDevice->deleteAssociationDeviceUser( $_SESSION['dati']['idPatient'], SATURAZIONE_SANGUA );
                        
                    }
                    /* fine if pulsiossimetro*/
                    
                    
                    if ( array_key_exists('pressione',$_POST ) && $_POST['pressione'] == "si") {
                            
                            //echo json_encode($fkDevices[0]['idDevice']);exit();
                            if(!empty($_POST['serialepressione']))
                              $oBridgeDeviceSerials->serial=trim($_POST['serialepressione']);
                            
                            $oNumberMeasurementsHours->numMesurations=$_POST['sfigmomanometronum'];
                            $oNumberMeasurementsHours->fkMedicalDevice=$_POST['sfigmomanometro'];
                            $time='time';
                            
                            for($i=1;$i<=$_POST['sfigmomanometronum'];$i++){
                                $time=$time.$i;    
                                $oNumberMeasurementsHours->$time=$_POST["orasfigmomanometronum$i"].":00";
                                $time='time';
                            }
                            
                            $error.=$oNumberMeasurementsHours->isError('allo sfigmomanometro');
                            if (!empty($error)) {
          
                               // print json_encode($error);
                               // exit();
                            } else {
                            
                                        //$oUser->idUser=$_SESSION['dati']['idPatient'];
                                        //$oUser->fkDevice=$fkDevices[0]['idDevice'];
                                        //$oBridgeUserMedicalDevice->fkUser=$_SESSION['dati']['idPatient'];
                                
                                        if(!empty($_SESSION['dati']['idBridgeUserMedicalDevicePressione']) && !empty($_SESSION['dati']['idBridgeDeviceSerialsPressione']) && !empty($_SESSION['dati']['idNumberMeasurementsHoursPressione'])){
                                                               
                                                            //if($oBridgeUserMedicalDevice->checkUniquemedicalSerial($_SESSION['dati']['idBridgeDeviceSerialsPressione'])==1)
                                                                $oBridgeDeviceSerials->idBridgeDeviceSerials=$_SESSION['dati']['idBridgeDeviceSerialsPressione'];
                                            
                                                                $oBridgeUserMedicalDevice->idBridgeUserMedicalDevice=$_SESSION['dati']['idBridgeUserMedicalDevicePressione'];
                                                                $oNumberMeasurementsHours->idNumberMeasurementsHours=$_SESSION['dati']['idNumberMeasurementsHoursPressione'];
                                         }

                                        
                                        $oBridgeDeviceSerials->fkMedicalDevice=$_POST['sfigmomanometro'];
                                        $oBridgeDeviceSerials->fkUser=$_SESSION['dati']['idPatient'];
                                        $medical=$oMedicalDevice->getMedicaldeviceById($_POST['sfigmomanometro']);
                                        foreach ($array_device_no_serial['pressione'] as $key => $value) {
                                            if($value['marca']==$medical['brand'] && $value['modello']==$medical['model'] )
                                                 $oBridgeDeviceSerials->serial=$value['serial'];
                                        }
                                        
                                        $oBridgeDeviceSerials->insert();
                                        $idBridgeDeviceSerials=$oBridgeDeviceSerials->getIdBridgeDeviceSerials();
                                        
                                        $oBridgeUserMedicalDevice->fkUser=$_SESSION['dati']['idPatient'];
                                        $oBridgeUserMedicalDevice->fkBridgeDeviceSerials=$idBridgeDeviceSerials[0]['idBridgeDeviceSerials'];
                                        $oBridgeUserMedicalDevice->insert();
                                        
                                        
                                        
                                        $oNumberMeasurementsHours->fkMedicalDevice=$_POST['sfigmomanometro'];
                                        $oNumberMeasurementsHours->fkDoctor=$_SESSION['dati']['idDoctor'];
                                        $oNumberMeasurementsHours->fkUser=$_SESSION['dati']['idPatient'];

                                        $oNumberMeasurementsHours->controller();
                                        $oNumberMeasurementsHours->insert();
                                        //$var = array("redirect" => URL_FILE_BACKOFFICE . "inserimento_paziente.php");
                                        //echo json_encode($var);
                                        //exit();
                                }

                    } else {
                        
                        $oBridgeUserMedicalDevice->deleteAssociationDeviceUser( $_SESSION['dati']['idPatient'], PRESSIONE );
                        
                    }
                    /* fine if sfigmomanometro*/
                    
                    
                    if ( array_key_exists('peso',$_POST ) && $_POST['peso'] == "si") {
                            
                            //echo json_encode($fkDevices[0]['idDevice']);exit();
                            if(!empty($_POST['serialepeso']))
                                    $oBridgeDeviceSerials->serial=trim($_POST['serialepeso']);
                            
                            $oNumberMeasurementsHours->numMesurations=$_POST['bilancianum'];
                            $oNumberMeasurementsHours->fkMedicalDevice=$_POST['bilancia'];
                            $time='time';
                            
                            for($i=1;$i<=$_POST['bilancianum'];$i++){
                                $time=$time.$i;    
                                $oNumberMeasurementsHours->$time=$_POST["orabilancianum$i"].":00";
                                $time='time';
                            }
                            
                            $error.=$oNumberMeasurementsHours->isError('alla bilancia');
                            if (!empty($error)) {
          
                               // print json_encode($error);
                               // exit();
                            } else {
                            
                                       // $oUser->idUser=$_SESSION['dati']['idPatient'];
                                        //$oUser->fkDevice=$fkDevices[0]['idDevice'];
                                        //$oBridgeUserMedicalDevice->fkUser=$_SESSION['dati']['idPatient'];
                                        
                                        if(!empty($_SESSION['dati']['idBridgeUserMedicalDevicePeso']) && !empty($_SESSION['dati']['idBridgeDeviceSerialsPeso']) && !empty($_SESSION['dati']['idNumberMeasurementsHoursPeso'])){
                                                                
                                                               //if($oBridgeUserMedicalDevice->checkUniquemedicalSerial($_SESSION['dati']['idBridgeDeviceSerialsPeso'])==1)
                                                               $oBridgeDeviceSerials->idBridgeDeviceSerials=$_SESSION['dati']['idBridgeDeviceSerialsPeso'];
                                                               
                                                                $oBridgeUserMedicalDevice->idBridgeUserMedicalDevice=$_SESSION['dati']['idBridgeUserMedicalDevicePeso'];
                                                                $oNumberMeasurementsHours->idNumberMeasurementsHours=$_SESSION['dati']['idNumberMeasurementsHoursPeso'];
                                         }

                                        $oBridgeDeviceSerials->fkMedicalDevice=$_POST['bilancia'];
                                        $oBridgeDeviceSerials->fkUser=$_SESSION['dati']['idPatient'];
                                        $medical=$oMedicalDevice->getMedicaldeviceById($_POST['bilancia']);
                                        foreach ($array_device_no_serial['bilancia'] as $key => $value) {
                                            if($value['marca']==$medical['brand'] && $value['modello']==$medical['model'] )
                                                 $oBridgeDeviceSerials->serial=$value['serial'];
                                        }
                                        $oBridgeDeviceSerials->insert();
                                        $idBridgeDeviceSerials=$oBridgeDeviceSerials->getIdBridgeDeviceSerials();
                                        
                                        $oBridgeUserMedicalDevice->fkUser=$_SESSION['dati']['idPatient'];
                                        $oBridgeUserMedicalDevice->fkBridgeDeviceSerials=$idBridgeDeviceSerials[0]['idBridgeDeviceSerials'];
                                        $oBridgeUserMedicalDevice->insert();
                                        
                                        
                                        
                                        $oNumberMeasurementsHours->fkMedicalDevice=$_POST['bilancia'];
                                        $oNumberMeasurementsHours->fkDoctor=$_SESSION['dati']['idDoctor'];
                                        $oNumberMeasurementsHours->fkUser=$_SESSION['dati']['idPatient'];

                                        $oNumberMeasurementsHours->controller();
                                        $oNumberMeasurementsHours->insert();
                                        //$var = array("redirect" => URL_FILE_BACKOFFICE . "inserimento_paziente.php");
                                        //echo json_encode($var);
                                        //exit();
                                }

                    } else {
                        
                        $oBridgeUserMedicalDevice->deleteAssociationDeviceUser( $_SESSION['dati']['idPatient'], PESO );
                        
                    }
                    /* fine if bilancia*/
                    
                    
                    
                    if ( array_key_exists('glicemia',$_POST ) && $_POST['glicemia'] == "si") {
                            
                            //echo json_encode($fkDevices[0]['idDevice']);exit();
                            if(!empty($_POST['serialeglicemia']))
                                $oBridgeDeviceSerials->serial=trim($_POST['serialeglicemia']);
                            
                            $oNumberMeasurementsHours->numMesurations=$_POST['glucometronum'];
                            $oNumberMeasurementsHours->fkMedicalDevice=$_POST['glucometro'];
                            $time='time';
                            
                            for($i=1;$i<=$_POST['glucometronum'];$i++){
                                $time=$time.$i;    
                                $oNumberMeasurementsHours->$time=$_POST["oraglucometronum$i"].":00";
                                $time='time';
                            }
                            
                            $error.=$oNumberMeasurementsHours->isError('al glucometro');
                            if (!empty($error)) {
          
                                print json_encode($error);
                                exit();
                            } else {
                            
                                       // $oUser->idUser=$_SESSION['dati']['idPatient'];
                                        //$oUser->fkDevice=$fkDevices[0]['idDevice'];
                                        //$oBridgeUserMedicalDevice->fkUser=$_SESSION['dati']['idPatient'];
                                        if(!empty($_SESSION['dati']['idBridgeUserMedicalDeviceGlicemia']) && !empty($_SESSION['dati']['idBridgeDeviceSerialsGlicemia']) && !empty($_SESSION['dati']['idNumberMeasurementsHoursGlicemia'])){
                                                               
                                                        //if($oBridgeUserMedicalDevice->checkUniquemedicalSerial($_SESSION['dati']['idBridgeDeviceSerialsGlicemia'])==1)
                                                              $oBridgeDeviceSerials->idBridgeDeviceSerials=$_SESSION['dati']['idBridgeDeviceSerialsGlicemia'];
                                                       
                                                               $oBridgeUserMedicalDevice->idBridgeUserMedicalDevice=$_SESSION['dati']['idBridgeUserMedicalDeviceGlicemia'];
                                                               $oNumberMeasurementsHours->idNumberMeasurementsHours=$_SESSION['dati']['idNumberMeasurementsHoursGlicemia'];
                                         }
                                        
                                        $oBridgeDeviceSerials->fkMedicalDevice=$_POST['glucometro'];
                                        $oBridgeDeviceSerials->fkUser=$_SESSION['dati']['idPatient'];
                                        
                                        $medical=$oMedicalDevice->getMedicaldeviceById($_POST['glucometro']);
                                        foreach ($array_device_no_serial['glucometro'] as $key => $value) {
                                            if($value['marca']==$medical['brand'] && $value['modello']==$medical['model'] )
                                                 $oBridgeDeviceSerials->serial=$value['serial'];
                                        }
                                        $oBridgeDeviceSerials->insert();
                                        $idBridgeDeviceSerials=$oBridgeDeviceSerials->getIdBridgeDeviceSerials();
                                        
                                        $oBridgeUserMedicalDevice->fkUser=$_SESSION['dati']['idPatient'];
                                        $oBridgeUserMedicalDevice->fkBridgeDeviceSerials=$idBridgeDeviceSerials[0]['idBridgeDeviceSerials'];
                                        $oBridgeUserMedicalDevice->insert();
                                        
                                        
                                        
                                        
                                        $oNumberMeasurementsHours->fkMedicalDevice=$_POST['glucometro'];
                                        $oNumberMeasurementsHours->fkDoctor=$_SESSION['dati']['idDoctor'];
                                        $oNumberMeasurementsHours->fkUser=$_SESSION['dati']['idPatient'];
                                        
                                        $oNumberMeasurementsHours->controller();
                                        $oNumberMeasurementsHours->insert();

                                        
                                }
                             } else {
                        
                                $oBridgeUserMedicalDevice->deleteAssociationDeviceUser( $_SESSION['dati']['idPatient'], GLICEMIA );
                        
                             }
                             /* fine glucometro*/
                                    
                                    /*attivo l'utente*/ 
                                        $oLogin = new Login();
                                        $oLogin->idLogin=$_SESSION['dati']['idLogin'];
                                        $oLogin->fkUserState=UTENTE_ATTIVO;
                                        $oLogin->updatefkUserState();
                                        
                                        
                                       $var = array("redirect" => URL_FILE_BACKOFFICE .$_SESSION['dati']['home']);
                                        echo json_encode($var);
                                        exit();
                    
                            } /*fine else*/        
        
}/*fine if tab 3*/  
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
        
        
        
        

    
    
    
    
    
    
    
    
    
    























