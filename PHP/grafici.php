<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once '../../config/sys_application.php';
require_once CLASS_DIR . 'Mesuration.class.php';
require_once CLASS_DIR . 'BridgePatientsDoctors.class.php';
require_once CLASS_DIR . 'BridgePatientsRelatives.class.php';
require_once CLASS_DIR . 'NotificationsAlert.class.php';
require_once CLASS_DIR . 'Users.class.php';





//var_dump($_SESSION);exit;

if (empty($_SESSION['dati'])) {
    session_destroy();
    header("Location: " . URL_FILE_BACKOFFICE . "index.php");
    exit();
}

 if ( !empty($_GET['id']) )
   $id = trim($_GET['id']);
 else{
     session_destroy();
     header("Location: " . URL_FILE_BACKOFFICE . "index.php");
    exit();
 }
     
     if($_SESSION['dati']['idRole']==PATIENT && $id!=$_SESSION['dati']['idUser']){
            session_destroy();
            header("Location: " . URL_FILE_BACKOFFICE . "index.php");
            exit();
     }
     
     
     if($_SESSION['dati']['idRole']==DOCTORS){
         $ceckdoctors=new BridgePatientsDoctors();
         $ceckdoctors->fkPatients=$id;
         $idDoctors=$ceckdoctors->ceckDoctorPatient();
         //var_dump($idDoctors);exit();
                  
         if(!in_array($_SESSION['dati']['idUser'], $idDoctors)){
                session_destroy();
                header("Location: " . URL_FILE_BACKOFFICE . "index.php");
                exit();
         }
     }
         
     if($_SESSION['dati']['idRole']==RELATIVE){
         $ceckrelative=new BridgePatientsRelatives();
         $ceckrelative->fkPatients=$id;
         $idRelatives=$ceckrelative->ceckRelativePatient();
         if(!in_array($_SESSION['dati']['idUser'], $idRelatives)){
            session_destroy();
            header("Location: " . URL_FILE_BACKOFFICE . "index.php");
            exit();
         }
     }
         
        $oUser=new Users();
        $oUser->idUser=$id;
        $info_user=$oUser->getUserbyId();
        $mesuration=new Mesuration();
        $oNotificationsAlert=new NotificationsAlert();
        $mesuration->fkUser=$id ;
        
        
        
        $fromDateFiltri=date ("d-m-Y",mktime(0,0,0,date("m"),date("d"),date("Y")));
        $toDateFiltri=date("d-m-Y",mktime(0,0,0,date("m"),date("d")-6,date("Y")));
        
        $mesuration->table='glycemiaMensurations';
        $dateGlycemia = $mesuration->getDateLastMeasuration($id);
        $flagGlycemia = false;
        if ( !empty($dateGlycemia) ) {
            $flagGlycemia = true;
            $annoGlycemia = substr($dateGlycemia, 0, 4);
            $meseGlycemia = substr($dateGlycemia, 5, 2);
            $giornoGlycemia = substr($dateGlycemia, 8, 2);
            $fromDateFiltriGlycemia=date ("d-m-Y",mktime(0,0,0,$meseGlycemia,$giornoGlycemia,$annoGlycemia));
            $toDateFiltriGlycemia=date("d-m-Y",mktime(0,0,0,$meseGlycemia,$giornoGlycemia-6,$annoGlycemia));
            $toGlycemia = date ("Y-m-d H:i:s",mktime(23,59,59,$meseGlycemia,$giornoGlycemia,$annoGlycemia));
            $fromGlycemia = date ("Y-m-d H:i:s",mktime(0,0,0,$meseGlycemia,$giornoGlycemia-6,$annoGlycemia));
        } else {
            $fromDateFiltriGlycemia=$fromDateFiltri;
            $toDateFiltriGlycemia=$toDateFiltri;
            $toGlycemia = date ("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d"),date("Y")));
            $fromGlycemia = date ("Y-m-d H:i:s",mktime(0,0,0,date("m"),date("d")-6,date("Y")));
        }
        
        $mesuration->table='pressureMensurations';
        $datePressure = $mesuration->getDateLastMeasuration($id);
        $flagPressure = false;
        if ( !empty($datePressure) ) {
            $flagPressure = true;
            $annoPressure = substr($datePressure, 0, 4);
            $mesePressure = substr($datePressure, 5, 2);
            $giornoPressure = substr($datePressure, 8, 2);
            $fromDateFiltriPressure=date ("d-m-Y",mktime(0,0,0,$mesePressure,$giornoPressure,$annoPressure));
            $toDateFiltriPressure=date("d-m-Y",mktime(0,0,0,$mesePressure,$giornoPressure-6,$annoPressure));
            $toPressure = date ("Y-m-d H:i:s",mktime(23,59,59,$mesePressure,$giornoPressure,$annoPressure));
            $fromPressure = date ("Y-m-d H:i:s",mktime(0,0,0,$mesePressure,$giornoPressure-6,$annoPressure));
        } else {
            $fromDateFiltriPressure=$fromDateFiltri;
            $toDateFiltriPressure=$toDateFiltri;
            $toPressure = date ("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d"),date("Y")));
            $fromPressure = date ("Y-m-d H:i:s",mktime(0,0,0,date("m"),date("d")-6,date("Y")));
        }
        
        $mesuration->table='pulseoximetryMensurations';
        $datePulseoximetry = $mesuration->getDateLastMeasuration($id);
        $flagPulseoximetry = false;
        if ( !empty($datePulseoximetry) ) {
            $flagPulseoximetry = true;
            $annoPulseoximetry = substr($datePulseoximetry, 0, 4);
            $mesePulseoximetry = substr($datePulseoximetry, 5, 2);
            $giornoPulseoximetry = substr($datePulseoximetry, 8, 2);
            $fromDateFiltriPulseoximetry=date ("d-m-Y",mktime(0,0,0,$mesePulseoximetry,$giornoPulseoximetry,$annoPulseoximetry));
            $toDateFiltriPulseoximetry=date("d-m-Y",mktime(0,0,0,$mesePulseoximetry,$giornoPulseoximetry-6,$annoPulseoximetry));
            
            $toPulseoximetry = date ("Y-m-d H:i:s",mktime(23,59,59,$mesePulseoximetry,$giornoPulseoximetry,$annoPulseoximetry));
            $fromPulseoximetry = date ("Y-m-d H:i:s",mktime(0,0,0,$mesePulseoximetry,$giornoPulseoximetry-6,$annoPulseoximetry));
        } else {
            $fromDateFiltriPulseoximetry=$fromDateFiltri;
            $toDateFiltriPulseoximetry=$toDateFiltri;
            $toPulseoximetry = date ("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d"),date("Y")));
            $fromPulseoximetry = date ("Y-m-d H:i:s",mktime(0,0,0,date("m"),date("d")-6,date("Y")));
        }
        
        $mesuration->table='spirometryMensurations';
        $dateSpirometry = $mesuration->getDateLastMeasuration($id);
        $flagSpirometry = false;
        if ( !empty($dateSpirometry) ) {
            $flagSpirometry = true;
            $annoSpirometry = substr($dateSpirometry, 0, 4);
            $meseSpirometry = substr($dateSpirometry, 5, 2);
            $giornoSpirometry = substr($dateSpirometry, 8, 2);
            $fromDateFiltriSpirometry=date ("d-m-Y",mktime(0,0,0,$meseSpirometry,$giornoSpirometry,$annoSpirometry));
            $toDateFiltriSpirometry=date("d-m-Y",mktime(0,0,0,$meseSpirometry,$giornoSpirometry-6,$annoSpirometry));
            
            $toSpirometry = date ("Y-m-d H:i:s",mktime(23,59,59,$meseSpirometry,$giornoSpirometry,$annoSpirometry));
            $fromSpirometry = date ("Y-m-d H:i:s",mktime(0,0,0,$meseSpirometry,$giornoSpirometry-6,$annoSpirometry));
        } else {
            $fromDateFiltriSpirometry=$fromDateFiltri;
            $toDateFiltriSpirometry=$toDateFiltri;
            $toSpirometry = date ("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d"),date("Y")));
            $fromSpirometry = date ("Y-m-d H:i:s",mktime(0,0,0,date("m"),date("d")-6,date("Y")));
        }
        
        $mesuration->table='weightMensurations';
        $dateWeight = $mesuration->getDateLastMeasuration($id);
        $flagWeight = false;
        if ( !empty($dateWeight) ) {
            $flagWeight = true;
            $annoWeight = substr($dateWeight, 0, 4);
            $meseWeight = substr($dateWeight, 5, 2);
            $giornoWeight = substr($dateWeight, 8, 2);
            $fromDateFiltriWeight=date ("d-m-Y",mktime(0,0,0,$meseWeight,$giornoWeight,$annoWeight));
            $toDateFiltriWeight=date("d-m-Y",mktime(0,0,0,$meseWeight,$giornoWeight-6,$annoWeight));
            
            $toWeight = date ("Y-m-d H:i:s",mktime(23,59,59,$meseWeight,$giornoWeight,$annoWeight));
            $fromWeight= date ("Y-m-d H:i:s",mktime(0,0,0,$meseWeight,$giornoWeight-6,$annoWeight));
        } else {
            $fromDateFiltriWeight=$fromDateFiltri;
            $toDateFiltriWeight=$toDateFiltri;
            $toWeight = date ("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d"),date("Y")));
            $fromWeight = date ("Y-m-d H:i:s",mktime(0,0,0,date("m"),date("d")-6,date("Y")));
        }
        
        
        
        
        
        
        
        $tpl = new Smarty;
         if(!empty($_GET['idlevelalert'])){
                        $mesuration->table='glycemiaMensurations';
                        $result_glycemia=$mesuration->getMesurationForGraphicAsinc(null,null,7);
                        $mesuration->table='spirometryMensurations';
                        $result_spirometry=$mesuration->getMesurationForGraphicAsincSpirometry(null,null,7);
                        $mesuration->table='weightMensurations';
                        $result_weight=$mesuration->getMesurationForGraphicAsinc(null,null,7);
                        $mesuration->table='pulseoximetryMensurations';
                        $result_pulseoximetry=$mesuration->getMesurationForGraphicAsinc(null,null,7);
                        $mesuration->table='pressureMensurations';
                        $result_pressure=$mesuration->getMesurationForGraphicAsincPressure(null,null,7);
                        
                        
                        //$oNotificationsAlert->idNotificationsAlert=$_GET['idnotification'];
                        $limiti=$oNotificationsAlert->getLevelAlertsByidLevelAlert($_GET['idlevelalert']);
                        //var_dump($limiti);exit();
                        $tpl->assign("limiti", $limiti);
                        $tpl->assign("idlevelalert", $_GET['idlevelalert']);

                       
                        if(!empty($limiti['minGlycemiaMensuration'][0])){
                             if(!empty($result_glycemia['grafico'])){
                                    $date=date_create($result_glycemia['grafico'][0][0]);
                                    $date1=date_create($result_glycemia['grafico'][(count($result_glycemia['grafico'])-1)][0]);
                                    date_sub($date,new DateInterval("P1D"));
                                    date_add($date1,new DateInterval("P1D"));
                                    $minglicemia=[[date_format($date,"Y-m-d H:i:s"),(float)$limiti['minGlycemiaMensuration'][1]] ,[date_format($date1,"Y-m-d H:i:s"),(float)$limiti['minGlycemiaMensuration'][1]]];
                                    }else{
                                        $minglicemia=[['boo',(float)$limiti['minGlycemiaMensuration'][1]] ,['boo',(float)$limiti['minGlycemiaMensuration'][1]]];

                                    }
                               $tpl->assign("minglicemia", json_encode($minglicemia));
                        }

                       // var_dump($minglicemia);exit();
                        
                         if(!empty($limiti['maxGlycemiaMensuration'][0])){
                            if(!empty($result_glycemia['grafico'])){
                                $date=date_create($result_glycemia['grafico'][0][0]);
                                $date1=date_create($result_glycemia['grafico'][(count($result_glycemia['grafico'])-1)][0]);
                                date_sub($date,new DateInterval("P1D"));
                                date_add($date1,new DateInterval("P1D"));
                                $maxglicemia=[[date_format($date,"Y-m-d H:i:s"),(float)$limiti['maxGlycemiaMensuration'][1]] ,[date_format($date1,"Y-m-d H:i:s"),(float)$limiti['maxGlycemiaMensuration'][1]]];

                            }else{
                                $maxglicemia=[['boo',(float)$limiti['maxGlycemiaMensuration'][1]] ,['boo',(float)$limiti['maxGlycemiaMensuration'][1]]];

                            }   
                                $tpl->assign("maxglicemia", json_encode($maxglicemia));

                         }
                        /*$minglicemia[0][0]=$result_glycemia['grafico'][0][0];
                        $minglicemia[0][1]=(float)$limiti['minGlycemiaMensuration'][1];
                        $minglicemia[1][0]=$result_glycemia['grafico'][(count($result_glycemia['tabella'])-1)][0];
                        $minglicemia[1][1]=(float)$limiti['maxGlycemiaMensuration'][1];*/

                       /* var_dump( json_encode($minglicemia));echo'<br>';
                        var_dump( json_encode($result_glycemia['grafico']));
                                exit();*/
                             //var_dump($limiti); exit();
                         //Peso                      
                        if(!empty($limiti['minWeightMensuration'][0])){
                            if(!empty($result_weight['grafico'])){
                                    $date=date_create($result_weight['grafico'][0][0]);
                                    $date1=date_create($result_weight['grafico'][(count($result_weight['grafico'])-1)][0]);
                                    date_sub($date,new DateInterval("P1D"));
                                    date_add($date1,new DateInterval("P1D"));
                                    $minpeso=[[date_format($date,"Y-m-d H:i:s"),(float)$limiti['minWeightMensuration'][1]] ,[date_format($date1,"Y-m-d H:i:s"),(float)$limiti['minWeightMensuration'][1]]];  
                                   //echo"$minpeso";

                            }  else {
                                 $minpeso=[['boo',(float)$limiti['minWeightMensuration'][1]] ,['boo',(float)$limiti['minWeightMensuration'][1]]];  
                                 //echo"$minpeso";
                            }
                            $tpl->assign("minpeso", json_encode($minpeso));
                        }
                            
                            
                            
                        if(!empty($limiti['maxWeightMensuration'][0])){
                            if(!empty($result_weight['grafico'])){
                                    $date=date_create($result_weight['grafico'][0][0]);
                                    $date1=date_create($result_weight['grafico'][(count($result_weight['grafico'])-1)][0]);
                                    date_sub($date,new DateInterval("P1D"));
                                    date_add($date1,new DateInterval("P1D"));
                                    $maxpeso=[[date_format($date,"Y-m-d H:i:s"),(float)$limiti['maxWeightMensuration'][1]] ,[date_format($date1,"Y-m-d H:i:s"),(float)$limiti['maxWeightMensuration'][1]]];
                            }else{
                                 $maxpeso=[['boo',(float)$limiti['maxWeightMensuration'][1]] ,['boo',(float)$limiti['maxWeightMensuration'][1]]];

                            }
                            $tpl->assign("maxpeso", json_encode($maxpeso));
                            
                            
                        }
                        
                        //Pressione
                        if(!empty($limiti['minPressureMensurationsMaximum'][0])){
                            if(!empty($result_pressure['graficomin'])){
                                $date=date_create($result_pressure['graficomin'][0][0]);
                                $date1=date_create($result_pressure['graficomin'][(count($result_pressure['graficomin'])-1)][0]);
                                date_sub($date,new DateInterval("P1D"));
                                date_add($date1,new DateInterval("P1D"));
                                $minpremax=[[date_format($date,"Y-m-d H:i:s"),(float)$limiti['minPressureMensurationsMaximum'][1]] ,[date_format($date1,"Y-m-d H:i:s"),(float)$limiti['minPressureMensurationsMaximum'][1]]];
                            }else{
                                $minpremax=[['boo',(float)$limiti['minPressureMensurationsMaximum'][1]] ,['boo',(float)$limiti['minPressureMensurationsMaximum'][1]]];

                            }
                            $tpl->assign("minpremax", json_encode($minpremax));
                        }
                                
                        if(!empty($limiti['maxPressureMensurationsMaximum'][0])){
                         if(!empty($result_pressure['graficomin'])){   
                                $date=date_create($result_pressure['graficomin'][0][0]);
                                $date1=date_create($result_pressure['graficomin'][(count($result_pressure['graficomin'])-1)][0]);
                                date_sub($date,new DateInterval("P1D"));
                                date_add($date1,new DateInterval("P1D"));
                                $maxpremax=[[date_format($date,"Y-m-d H:i:s"),(float)$limiti['maxPressureMensurationsMaximum'][1]] ,[date_format($date1,"Y-m-d H:i:s"),(float)$limiti['maxPressureMensurationsMaximum'][1]]];
                            }
                            else{
                                   $maxpremax=[['boo',(float)$limiti['maxPressureMensurationsMaximum'][1]] ,['boo',(float)$limiti['maxPressureMensurationsMaximum'][1]]];

                            }
                            $tpl->assign("maxpremax", json_encode($maxpremax));
                        }
                        
                        
                        if(!empty($limiti['minPressureMensurationsMinimum'][0])){
                          if(!empty($result_pressure['graficomin'])){   
                            $date=date_create($result_pressure['graficomin'][0][0]);
                            $date1=date_create($result_pressure['graficomin'][(count($result_pressure['graficomin'])-1)][0]);
                            date_sub($date,new DateInterval("P1D"));
                            date_add($date1,new DateInterval("P1D"));
                            $minpremin=[[date_format($date,"Y-m-d H:i:s"),(float)$limiti['minPressureMensurationsMinimum'][1]] ,[date_format($date1,"Y-m-d H:i:s"),(float)$limiti['minPressureMensurationsMinimum'][1]]];
                            }else{
                                $minpremin=[['boo',(float)$limiti['minPressureMensurationsMinimum'][1]] ,['boo',(float)$limiti['minPressureMensurationsMinimum'][1]]];

                            }
                                $tpl->assign("minpremin", json_encode($minpremin));

                        }
                        
                        
                        
                        if(!empty($limiti['maxPressureMensurationsMinimum'][0])){
                           if(!empty($result_pressure['graficomin'])){
                        $date=date_create($result_pressure['graficomin'][0][0]);
                        $date1=date_create($result_pressure['graficomin'][(count($result_pressure['graficomin'])-1)][0]);
                        date_sub($date,new DateInterval("P1D"));
                        date_add($date1,new DateInterval("P1D"));
                        $maxpremin=[[date_format($date,"Y-m-d H:i:s"),(float)$limiti['maxPressureMensurationsMinimum'][1]] ,[date_format($date1,"Y-m-d H:i:s"),(float)$limiti['maxPressureMensurationsMinimum'][1]]];
                        }else{
                              $maxpremin=[['boo',(float)$limiti['maxPressureMensurationsMinimum'][1]] ,['boo',(float)$limiti['maxPressureMensurationsMinimum'][1]]];

                            }
                             $tpl->assign("maxpremin", json_encode($maxpremin));

                        }
                        
                        
                        
                        
                        
                        
                        
                        //Emoglobina
                        if(!empty($limiti['minPulseoximetryMensuration'][0])){
                           if(!empty($result_pulseoximetry['grafico'])){
                            $date=date_create($result_pulseoximetry['grafico'][0][0]);
                            $date1=date_create($result_pulseoximetry['grafico'][(count($result_pulseoximetry['grafico'])-1)][0]);
                            date_sub($date,new DateInterval("P1D"));
                            date_add($date1,new DateInterval("P1D"));
                            $minemo= [[date_format($date,"Y-m-d H:i:s"),(float)$limiti['minPulseoximetryMensuration'][1]] ,[date_format($date1,"Y-m-d H:i:s"),(float)$limiti['minPulseoximetryMensuration'][1]]];
                            }else{
                                $minemo= [['boo',(float)$limiti['minPulseoximetryMensuration'][1]] ,['boo',(float)$limiti['minPulseoximetryMensuration'][1]]];

                            }
                                  $tpl->assign("minemo", json_encode($minemo));

                        }
                                
                        
                        
                        if(!empty($limiti['maxPulseoximetryMensuration'][0])){
                             if(!empty($result_pulseoximetry['grafico'])){
                              $date=date_create($result_pulseoximetry['grafico'][0][0]);
                              $date1=date_create($result_pulseoximetry['grafico'][(count($result_pulseoximetry['grafico'])-1)][0]);
                              date_sub($date,new DateInterval("P1D"));
                              date_add($date1,new DateInterval("P1D"));
                              $maxemo= [[date_format($date,"Y-m-d H:i:s"),(float)$limiti['maxPulseoximetryMensuration'][1]] ,[date_format($date1,"Y-m-d H:i:s"),(float)$limiti['maxPulseoximetryMensuration'][1]]];
                            }else{
                               $maxemo= [['boo',(float)$limiti['maxPulseoximetryMensuration'][1]] ,['boo',(float)$limiti['maxPulseoximetryMensuration'][1]]];

                            }
                            $tpl->assign("maxemo", json_encode($maxemo));
                        }
                        
                        
                        //Capacità Respiratoria
                        
                        if(!empty($limiti['minPefMensuration'][0])){
                            if(!empty($result_spirometry['graficopef'])){
                                $date=date_create($result_spirometry['graficopef'][0][0]);
                                $date1=date_create($result_spirometry['graficopef'][(count($result_spirometry['graficopef'])-1)][0]);
                                date_sub($date,new DateInterval("P1D"));
                                date_add($date1,new DateInterval("P1D"));
                                $minpef=[[date_format($date,"Y-m-d H:i:s"),(float)$limiti['minPefMensuration'][1]] ,[date_format($date1,"Y-m-d H:i:s"),(float)$limiti['minPefMensuration'][1]]];
                            }else{
                               $minpef=[['boo',(float)$limiti['minPefMensuration'][1]] ,['boo',(float)$limiti['minPefMensuration'][1]]];

                            }
                            $tpl->assign("minpef", json_encode($minpef));
                        }   
                        
                        
                       if(!empty($limiti['maxPefMensuration'][0])){
                           if(!empty($result_spirometry['graficopef'])){
                                $date=date_create($result_spirometry['graficopef'][0][0]);
                                $date1=date_create($result_spirometry['graficopef'][(count($result_spirometry['graficopef'])-1)][0]);
                                date_sub($date,new DateInterval("P1D"));
                                date_add($date1,new DateInterval("P1D"));
                                $maxpef= [[date_format($date,"Y-m-d H:i:s"),(float)$limiti['maxPefMensuration'][1]] ,[date_format($date1,"Y-m-d H:i:s"),(float)$limiti['maxPefMensuration'][1]]];
                           }else{
                                $maxpef= [['boo',(float)$limiti['maxPefMensuration'][1]] ,['boo',(float)$limiti['maxPefMensuration'][1]]];

                           }
                                $tpl->assign("maxpef", json_encode($maxpef));

                        } 
                        
                        if(!empty($limiti['minFev1Mensuration'][0])){
                            if(!empty($result_spirometry['graficopef'])){
                            $date=date_create($result_spirometry['graficopef'][0][0]);
                            $date1=date_create($result_spirometry['graficopef'][(count($result_spirometry['graficopef'])-1)][0]);
                            date_sub($date,new DateInterval("P1D"));
                            date_add($date1,new DateInterval("P1D"));
                            $minfev1= [[date_format($date,"Y-m-d H:i:s"),(float)$limiti['minFev1Mensuration'][1]] ,[date_format($date1,"Y-m-d H:i:s"),(float)$limiti['minFev1Mensuration'][1]]];
                            }else{
                                $minfev1= [['boo',(float)$limiti['minFev1Mensuration'][1]] ,['boo',(float)$limiti['minFev1Mensuration'][1]]];

                            }
                               $tpl->assign("minfev1", json_encode($minfev1));

                        }
                        
                        
                        
                        
                        if(!empty($limiti['minFev1Mensuration'][0])){
                            if(!empty($result_spirometry['graficopef'])){
                                    $date=date_create($result_spirometry['graficopef'][0][0]);
                                    $date1=date_create($result_spirometry['graficopef'][(count($result_spirometry['graficopef'])-1)][0]);
                                    date_sub($date,new DateInterval("P1D"));
                                    date_add($date1,new DateInterval("P1D"));
                                    $maxfev1= [[date_format($date,"Y-m-d H:i:s"),(float)$limiti['maxFev1Mensuration'][1]] ,[date_format($date1,"Y-m-d H:i:s"),(float)$limiti['maxFev1Mensuration'][1]]];
                            }else{
                               $maxfev1= [['boo',(float)$limiti['maxFev1Mensuration'][1]] ,['boo',(float)$limiti['maxFev1Mensuration'][1]]];

                            }

                        }
        
        }
        
        elseif(!empty($_GET['idnotification'])){
                        $oNotificationsAlert->idNotificationsAlert=$_GET['idnotification'];
                        $limiti=$oNotificationsAlert->getLevelAlertsByidNotificationsAlert();
                        
                        $alerts=$oNotificationsAlert->getNotificationAlert();
                        if($alerts['dataglicemia']!=null){ 
                        $mesuration->table='glycemiaMensurations';
                        $result_glycemia=$mesuration->getMesurationForGraphicAsinc('',$alerts['dataglicemia'],null,$alerts['period'],$_GET['idnotification']);
                        $result_glycemia['grafico']=array_reverse($result_glycemia['grafico']);
       
                        }else{
                            $result_glycemia['grafico']=null;
                            $result_glycemia['tabella']=null;
                        }
                        
                        if($alerts['datarespirazione']!=null){
                        $mesuration->table='spirometryMensurations';
                        $result_spirometry=$mesuration->getMesurationForGraphicAsincSpirometry('',$alerts['datarespirazione'],null,$alerts['period'],$_GET['idnotification']);
                        $result_spirometry['graficopef']=array_reverse($result_spirometry['graficopef']);
                        $result_spirometry['graficofev1']=array_reverse($result_spirometry['graficofev1']);

                        }else{
                            $result_spirometry['graficopef']=null;
                            $result_spirometry['graficofev1']=null;
                            $result_spirometry['tabella']=null;
                        }
                        
                        if($alerts['datapeso']!=null){
                        $mesuration->table='weightMensurations';
                        $result_weight=$mesuration->getMesurationForGraphicAsinc('',$alerts['datapeso'],null,$alerts['period'],$_GET['idnotification']);
                        $result_weight['grafico']=array_reverse($result_weight['grafico']);

                                               
                        
                        }else{
                            $result_weight['grafico']=null;
                            $result_weight['tabella']=null;
                        }
                        
                        if($alerts['dataemoglobbina']!=null){
                        $mesuration->table='pulseoximetryMensurations';
                        $result_pulseoximetry=$mesuration->getMesurationForGraphicAsinc('',$alerts['dataemoglobbina'],null,$alerts['period'],$_GET['idnotification']);
                        $result_pulseoximetry['grafico']=array_reverse($result_pulseoximetry['grafico']);

                                   
                        }else{
                            $result_pulseoximetry['grafico']=null;
                            $result_pulseoximetry['tabella']=null;
                        }
                        
                        if($alerts['datapressione']!=null){
                        $mesuration->table='pressureMensurations';
                        $result_pressure=$mesuration->getMesurationForGraphicAsincPressure('',$alerts['datapressione'],null,$alerts['period'],$_GET['idnotification']);
                        $result_pressure['graficomax']=array_reverse($result_pressure['graficomax']);
                        $result_pressure['graficomin']=array_reverse($result_pressure['graficomin']);

                        }else{
                            $result_pressure['graficomax']=null;
                            $result_pressure['graficomin']=null;
                            $result_pressure['tabella']=null;
                        }
                        
                        $tpl->assign("limiti", $limiti);
                        $tpl->assign("idnotification", $_GET['idnotification']);

                        //var_dump($limiti['minWeightMensuration']);exit();
                        //echo $result_glycemia['tabella'][0][1];exit();
                           //$minglicemia=array();   
                        //$date=new DateTime($result_glycemia['grafico'][0][0]);
                        //$date1=$result_glycemia['grafico'][(count($result_glycemia['grafico'])-1)][0];

                        //echo $result_glycemia['grafico'][(count($result_glycemia['grafico'])-1)][0];
                        //$pippo=date('Y-m-d H:i:s',new DateTime($result_glycemia['grafico'][(count($result_glycemia['grafico'])-1)][0]));
                        //$date=date('Y-m-d H:i:s', strtotime('+1 day', $pippo=new DateTime($result_glycemia['grafico'][(count($result_glycemia['grafico'])-1)][0])));
                        //$date1->add(new DateInterval('P1D'));
                        //glicemia
                        if(!empty($limiti['minGlycemiaMensuration'][0])){
                             if(!empty($result_glycemia['grafico'])){
                                    $date=date_create($result_glycemia['grafico'][0][0]);
                                    $date1=date_create($result_glycemia['grafico'][(count($result_glycemia['grafico'])-1)][0]);
                                    date_sub($date,new DateInterval("P1D"));
                                    date_add($date1,new DateInterval("P1D"));
                                    $minglicemia=[[date_format($date,"Y-m-d H:i:s"),(float)$limiti['minGlycemiaMensuration'][1]] ,[date_format($date1,"Y-m-d H:i:s"),(float)$limiti['minGlycemiaMensuration'][1]]];
                                    //var_dump($minglicemia);exit();
                                                                        
                                    
                                    }else{
                                        $minglicemia=[['boo',(float)$limiti['minGlycemiaMensuration'][1]] ,['boo',(float)$limiti['minGlycemiaMensuration'][1]]];

                                    }
                               $tpl->assign("minglicemia", json_encode($minglicemia));
                        }

                        
                        
                         if(!empty($limiti['maxGlycemiaMensuration'][0])){
                            if(!empty($result_glycemia['grafico'])){
                                $date=date_create($result_glycemia['grafico'][0][0]);
                                $date1=date_create($result_glycemia['grafico'][(count($result_glycemia['grafico'])-1)][0]);
                                date_sub($date,new DateInterval("P1D"));
                                date_add($date1,new DateInterval("P1D"));
                                $maxglicemia=[[date_format($date,"Y-m-d H:i:s"),(float)$limiti['maxGlycemiaMensuration'][1]] ,[date_format($date1,"Y-m-d H:i:s"),(float)$limiti['maxGlycemiaMensuration'][1]]];

                            }else{
                                $maxglicemia=[['boo',(float)$limiti['maxGlycemiaMensuration'][1]] ,['boo',(float)$limiti['maxGlycemiaMensuration'][1]]];

                            }   
                                $tpl->assign("maxglicemia", json_encode($maxglicemia));

                         }
                        /*$minglicemia[0][0]=$result_glycemia['grafico'][0][0];
                        $minglicemia[0][1]=(float)$limiti['minGlycemiaMensuration'][1];
                        $minglicemia[1][0]=$result_glycemia['grafico'][(count($result_glycemia['tabella'])-1)][0];
                        $minglicemia[1][1]=(float)$limiti['maxGlycemiaMensuration'][1];*/

                       /* var_dump( json_encode($minglicemia));echo'<br>';
                        var_dump( json_encode($result_glycemia['grafico']));
                                exit();*/
                             //var_dump($limiti); exit();
                         //Peso                      
                        if(!empty($limiti['minWeightMensuration'][0])){
                            if(!empty($result_weight['grafico'])){
                                    $date=date_create($result_weight['grafico'][0][0]);
                                    $date1=date_create($result_weight['grafico'][(count($result_weight['grafico'])-1)][0]);
                                    date_sub($date,new DateInterval("P1D"));
                                    date_add($date1,new DateInterval("P1D"));
                                    $minpeso=[[date_format($date,"Y-m-d H:i:s"),(float)$limiti['minWeightMensuration'][1]] ,[date_format($date1,"Y-m-d H:i:s"),(float)$limiti['minWeightMensuration'][1]]];  
                                   //echo"$minpeso";

                            }  else {
                                 $minpeso=[['boo',(float)$limiti['minWeightMensuration'][1]] ,['boo',(float)$limiti['minWeightMensuration'][1]]];  
                                 //echo"$minpeso";
                            }
                            $tpl->assign("minpeso", json_encode($minpeso));
                        }
                            
                            
                            
                        if(!empty($limiti['maxWeightMensuration'][0])){
                            if(!empty($result_weight['grafico'])){
                                    $date=date_create($result_weight['grafico'][0][0]);
                                    $date1=date_create($result_weight['grafico'][(count($result_weight['grafico'])-1)][0]);
                                    date_sub($date,new DateInterval("P1D"));
                                    date_add($date1,new DateInterval("P1D"));
                                    $maxpeso=[[date_format($date,"Y-m-d H:i:s"),(float)$limiti['maxWeightMensuration'][1]] ,[date_format($date1,"Y-m-d H:i:s"),(float)$limiti['maxWeightMensuration'][1]]];
                            }else{
                                 $maxpeso=[['boo',(float)$limiti['maxWeightMensuration'][1]] ,['boo',(float)$limiti['maxWeightMensuration'][1]]];

                            }
                            $tpl->assign("maxpeso", json_encode($maxpeso));
                            
                            
                        }
                        
                        //Pressione
                        if(!empty($limiti['minPressureMensurationsMaximum'][0])){
                            if(!empty($result_pressure['graficomin'])){
                                $date=date_create($result_pressure['graficomin'][0][0]);
                                $date1=date_create($result_pressure['graficomin'][(count($result_pressure['graficomin'])-1)][0]);
                                date_sub($date,new DateInterval("P1D"));
                                date_add($date1,new DateInterval("P1D"));
                                $minpremax=[[date_format($date,"Y-m-d H:i:s"),(float)$limiti['minPressureMensurationsMaximum'][1]] ,[date_format($date1,"Y-m-d H:i:s"),(float)$limiti['minPressureMensurationsMaximum'][1]]];
                            }else{
                                $minpremax=[['boo',(float)$limiti['minPressureMensurationsMaximum'][1]] ,['boo',(float)$limiti['minPressureMensurationsMaximum'][1]]];

                            }
                            $tpl->assign("minpremax", json_encode($minpremax));
                        }
                                
                        if(!empty($limiti['maxPressureMensurationsMaximum'][0])){
                         if(!empty($result_pressure['graficomin'])){   
                                $date=date_create($result_pressure['graficomin'][0][0]);
                                $date1=date_create($result_pressure['graficomin'][(count($result_pressure['graficomin'])-1)][0]);
                                date_sub($date,new DateInterval("P1D"));
                                date_add($date1,new DateInterval("P1D"));
                                $maxpremax=[[date_format($date,"Y-m-d H:i:s"),(float)$limiti['maxPressureMensurationsMaximum'][1]] ,[date_format($date1,"Y-m-d H:i:s"),(float)$limiti['maxPressureMensurationsMaximum'][1]]];
                            }
                            else{
                                   $maxpremax=[['boo',(float)$limiti['maxPressureMensurationsMaximum'][1]] ,['boo',(float)$limiti['maxPressureMensurationsMaximum'][1]]];

                            }
                            $tpl->assign("maxpremax", json_encode($maxpremax));
                        }
                        
                        
                        if(!empty($limiti['minPressureMensurationsMinimum'][0])){
                          if(!empty($result_pressure['graficomin'])){   
                            $date=date_create($result_pressure['graficomin'][0][0]);
                            $date1=date_create($result_pressure['graficomin'][(count($result_pressure['graficomin'])-1)][0]);
                            date_sub($date,new DateInterval("P1D"));
                            date_add($date1,new DateInterval("P1D"));
                            $minpremin=[[date_format($date,"Y-m-d H:i:s"),(float)$limiti['minPressureMensurationsMinimum'][1]] ,[date_format($date1,"Y-m-d H:i:s"),(float)$limiti['minPressureMensurationsMinimum'][1]]];
                            }else{
                                $minpremin=[['boo',(float)$limiti['minPressureMensurationsMinimum'][1]] ,['boo',(float)$limiti['minPressureMensurationsMinimum'][1]]];

                            }
                                $tpl->assign("minpremin", json_encode($minpremin));

                        }
                        
                        
                        
                        if(!empty($limiti['maxPressureMensurationsMinimum'][0])){
                           if(!empty($result_pressure['graficomin'])){
                        $date=date_create($result_pressure['graficomin'][0][0]);
                        $date1=date_create($result_pressure['graficomin'][(count($result_pressure['graficomin'])-1)][0]);
                        date_sub($date,new DateInterval("P1D"));
                        date_add($date1,new DateInterval("P1D"));
                        $maxpremin=[[date_format($date,"Y-m-d H:i:s"),(float)$limiti['maxPressureMensurationsMinimum'][1]] ,[date_format($date1,"Y-m-d H:i:s"),(float)$limiti['maxPressureMensurationsMinimum'][1]]];
                        }else{
                              $maxpremin=[['boo',(float)$limiti['maxPressureMensurationsMinimum'][1]] ,['boo',(float)$limiti['maxPressureMensurationsMinimum'][1]]];

                            }
                             $tpl->assign("maxpremin", json_encode($maxpremin));

                        }
                        
                        
                        
                        
                        
                        
                        
                        //Emoglobina
                        if(!empty($limiti['minPulseoximetryMensuration'][0])){
                           if(!empty($result_pulseoximetry['grafico'])){
                            $date=date_create($result_pulseoximetry['grafico'][0][0]);
                            $date1=date_create($result_pulseoximetry['grafico'][(count($result_pulseoximetry['grafico'])-1)][0]);
                            date_sub($date,new DateInterval("P1D"));
                            date_add($date1,new DateInterval("P1D"));
                            $minemo= [[date_format($date,"Y-m-d H:i:s"),(float)$limiti['minPulseoximetryMensuration'][1]] ,[date_format($date1,"Y-m-d H:i:s"),(float)$limiti['minPulseoximetryMensuration'][1]]];
                            }else{
                                $minemo= [['boo',(float)$limiti['minPulseoximetryMensuration'][1]] ,['boo',(float)$limiti['minPulseoximetryMensuration'][1]]];

                            }
                                  $tpl->assign("minemo", json_encode($minemo));

                        }
                                
                        
                        
                        if(!empty($limiti['maxPulseoximetryMensuration'][0])){
                             if(!empty($result_pulseoximetry['grafico'])){
                              $date=date_create($result_pulseoximetry['grafico'][0][0]);
                              $date1=date_create($result_pulseoximetry['grafico'][(count($result_pulseoximetry['grafico'])-1)][0]);
                              date_sub($date,new DateInterval("P1D"));
                              date_add($date1,new DateInterval("P1D"));
                              $maxemo= [[date_format($date,"Y-m-d H:i:s"),(float)$limiti['maxPulseoximetryMensuration'][1]] ,[date_format($date1,"Y-m-d H:i:s"),(float)$limiti['maxPulseoximetryMensuration'][1]]];
                            }else{
                               $maxemo= [[date_format($date,"Y-m-d H:i:s"),(float)$limiti['maxPulseoximetryMensuration'][1]] ,[date_format($date1,"Y-m-d H:i:s"),(float)$limiti['maxPulseoximetryMensuration'][1]]];

                            }
                            $tpl->assign("maxemo", json_encode($maxemo));
                        }
                        
                        
                        //Capacità Respiratoria
                        
                        if(!empty($limiti['minPefMensuration'][0])){
                            if(!empty($result_spirometry['graficopef'])){
                                $date=date_create($result_spirometry['graficopef'][0][0]);
                                $date1=date_create($result_spirometry['graficopef'][(count($result_spirometry['graficopef'])-1)][0]);
                                date_sub($date,new DateInterval("P1D"));
                                date_add($date1,new DateInterval("P1D"));
                                $minpef=[[date_format($date,"Y-m-d H:i:s"),(float)$limiti['minPefMensuration'][1]] ,[date_format($date1,"Y-m-d H:i:s"),(float)$limiti['minPefMensuration'][1]]];
                            }else{
                               $minpef=[['boo',(float)$limiti['minPefMensuration'][1]] ,['boo',(float)$limiti['minPefMensuration'][1]]];

                            }
                            $tpl->assign("minpef", json_encode($minpef));
                        }   
                        
                        
                       if(!empty($limiti['maxPefMensuration'][0])){
                           if(!empty($result_spirometry['graficopef'])){
                                $date=date_create($result_spirometry['graficopef'][0][0]);
                                $date1=date_create($result_spirometry['graficopef'][(count($result_spirometry['graficopef'])-1)][0]);
                                date_sub($date,new DateInterval("P1D"));
                                date_add($date1,new DateInterval("P1D"));
                                $maxpef= [[date_format($date,"Y-m-d H:i:s"),(float)$limiti['maxPefMensuration'][1]] ,[date_format($date1,"Y-m-d H:i:s"),(float)$limiti['maxPefMensuration'][1]]];
                           }else{
                                $maxpef= [['boo',(float)$limiti['maxPefMensuration'][1]] ,['boo',(float)$limiti['maxPefMensuration'][1]]];

                           }
                                $tpl->assign("maxpef", json_encode($maxpef));

                        } 
                        
                        if(!empty($limiti['minFev1Mensuration'][0])){
                            if(!empty($result_spirometry['graficopef'])){
                            $date=date_create($result_spirometry['graficopef'][0][0]);
                            $date1=date_create($result_spirometry['graficopef'][(count($result_spirometry['graficopef'])-1)][0]);
                            date_sub($date,new DateInterval("P1D"));
                            date_add($date1,new DateInterval("P1D"));
                            $minfev1= [[date_format($date,"Y-m-d H:i:s"),(float)$limiti['minFev1Mensuration'][1]] ,[date_format($date1,"Y-m-d H:i:s"),(float)$limiti['minFev1Mensuration'][1]]];
                            }else{
                                $minfev1= [['boo',(float)$limiti['minFev1Mensuration'][1]] ,['boo',(float)$limiti['minFev1Mensuration'][1]]];

                            }
                               $tpl->assign("minfev1", json_encode($minfev1));

                        }
                        
                        
                        
                        
                        if(!empty($limiti['minFev1Mensuration'][0])){
                            if(!empty($result_spirometry['graficopef'])){
                                    $date=date_create($result_spirometry['graficopef'][0][0]);
                                    $date1=date_create($result_spirometry['graficopef'][(count($result_spirometry['graficopef'])-1)][0]);
                                    date_sub($date,new DateInterval("P1D"));
                                    date_add($date1,new DateInterval("P1D"));
                                    $maxfev1= [[date_format($date,"Y-m-d H:i:s"),(float)$limiti['maxFev1Mensuration'][1]] ,[date_format($date1,"Y-m-d H:i:s"),(float)$limiti['maxFev1Mensuration'][1]]];
                            }else{
                               $maxfev1= [['boo',(float)$limiti['maxFev1Mensuration'][1]] ,['boo',(float)$limiti['maxFev1Mensuration'][1]]];

                            }
                                $tpl->assign("maxfev1", json_encode($maxfev1));
                        }
        
        }
        else {

            $mesuration->table='glycemiaMensurations';
            $result_glycemia=$mesuration->getMesurationForGraphicAsinc($fromGlycemia,$toGlycemia);
            $mesuration->table='spirometryMensurations';
            $result_spirometry=$mesuration->getMesurationForGraphicAsincSpirometry($fromSpirometry,$toSpirometry);
            $mesuration->table='weightMensurations';
            $result_weight=$mesuration->getMesurationForGraphicAsinc($fromWeight,$toWeight);
            $mesuration->table='pulseoximetryMensurations';
            $result_pulseoximetry=$mesuration->getMesurationForGraphicAsinc($fromPulseoximetry,$toPulseoximetry);
            $mesuration->table='pressureMensurations';
            $result_pressure=$mesuration->getMesurationForGraphicAsincPressure($fromPressure,$toPressure);
        
            
        }
        // var_dump($result_weight);exit();
        
        

        
        
        
        
        $tpl->assign("fromDateFiltriGlycemia", str_replace("-", "/",$fromDateFiltriGlycemia));
        $tpl->assign("toDateFiltriGlycemia", str_replace("-", "/",$toDateFiltriGlycemia) );
        $tpl->assign("flagGlycemia",$flagGlycemia);
        
        $tpl->assign("fromDateFiltriPressure", str_replace("-", "/",$fromDateFiltriPressure) );
        $tpl->assign("toDateFiltriPressure", str_replace("-", "/",$toDateFiltriPressure) );
        $tpl->assign("flagPressure",$flagPressure);
        
        $tpl->assign("fromDateFiltriPulseoximetry", str_replace("-", "/",$fromDateFiltriPulseoximetry) );
        $tpl->assign("toDateFiltriPulseoximetry", str_replace("-", "/",$toDateFiltriPulseoximetry) );
        $tpl->assign("flagPulseoximetry",$flagPulseoximetry);
        
        $tpl->assign("fromDateFiltriSpirometry", str_replace("-", "/",$fromDateFiltriSpirometry) );
        $tpl->assign("toDateFiltriSpirometry", str_replace("-", "/",$toDateFiltriSpirometry) );
        $tpl->assign("flagSpirometry",$flagSpirometry);
        
        $tpl->assign("fromDateFiltriWeight", str_replace("-", "/",$fromDateFiltriWeight) );
        $tpl->assign("toDateFiltriWeight", str_replace("-", "/",$toDateFiltriWeight) );
        $tpl->assign("flagWeight",$flagWeight);
        //echo $fromDateFiltri;
        //echo $toDateFiltri;
        //exit();
        //var_dump($result);exit();
        
        /*foreach ($result['grafico'] as $key => $value) {
            $prova.="[";
            $prova.=  implode(',', $value);
            $prova.="]";
        }*/
        
        //$prova= json_encode($result['grafico']); 
        //var_dump(json_encode($result['maxGlicemia']));var_dump(json_encode($result['grafico']));exit();
        
        
        
        require_once NOTIFY;
        require_once MESSAGES;
        $tpl->assign("id", $id);
        
        
        
        
        $tpl->assign("fromDateFiltri", $fromDateFiltri);
        $tpl->assign("toDateFiltri", $toDateFiltri);
        
        $tpl->assign("tableGlycemia", $result_glycemia['tabella']);
        $tpl->assign("graficoGlycemia", json_encode($result_glycemia['grafico']));
        //$tpl->assign("maxGlicemia", json_encode($result['maxGlicemia']));
        //$tpl->assign("minGlicemia", json_encode($result['minGlicemia']));
        $tpl->assign("tableWeight", $result_weight['tabella']);
        $tpl->assign("graficoWeight", json_encode($result_weight['grafico']));
        
        $tpl->assign("tablePulseoximetry", $result_pulseoximetry['tabella']);
        $tpl->assign("graficoPulseoximetry", json_encode($result_pulseoximetry['grafico']));
        
        $tpl->assign("tableSpirometry", $result_spirometry['tabella']);
        $tpl->assign("graficopef", json_encode($result_spirometry['graficopef']));
        $tpl->assign("graficofev1", json_encode($result_spirometry['graficofev1']));
        
        $tpl->assign("tablePressure", $result_pressure['tabella']);
        $tpl->assign("graficoPressureMin", json_encode($result_pressure['graficomin']));
        $tpl->assign("graficoPressureMax", json_encode($result_pressure['graficomax']));
        $tpl->assign("info_user", $info_user);

        $tpl->compile_check = COMPILE_CHECK;
        $tpl->debugging = FALSE;
        $tpl->display("grafici.tpl");
        
       
       
        
        










?>
