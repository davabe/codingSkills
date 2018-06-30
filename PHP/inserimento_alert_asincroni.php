<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once '../../config/sys_application.php';
require_once CLASS_DIR . 'Check.class.php';
require_once CLASS_DIR . 'LevelAlerts.class.php';
require_once CLASS_DIR . 'Utils.class.php';
require_once CLASS_DIR . 'Users.class.php';



//echo json_encode(var_dump($_POST));
//echo json_encode(var_dump($_SESSION));
//exit;

//echo json_encode($_POST);exit;
$levelalert=new LevelAlerts();
$user=new Users();
$oUtils=new Utils();
$result=array();
$error = "";



// inizio settagio misurazioni


if ( !empty($_POST['idLevelAlert'])) {
    
        //NB è IMPORTANTE CONTROLLARE IL QUESTO IDLEVELALERT SI MODIFICABILE DAL DOTTORE CHE è IN SESSIONE IN CASO DI MANOMISSIONE DEL LINK MODIFICA LATO CLIENT
        $levelalert->idLevelAlert=$_POST['idLevelAlert'];

}
 else {
    //$levelalert->idLevelAlert='';
}



if (isset($_POST['glicemia']) && $_POST['glicemia'] == "si") {
                    $segno_min_glicemia=null;
                    $segno_max_glicemia=null;

                if ( $_POST['segno_min_glicemia']=="Segno" && $_POST['segno_max_glicemia']=="Segno" )
                    $error .= "E' obbligatorio inserire uno dei due segni in glicemia<br />";
               
                if ( $_POST['segno_min_glicemia']!="Segno") 
                    $segno_min_glicemia=$oUtils->checkValue($_POST['segno_min_glicemia']);
                 if ( $_POST['segno_max_glicemia']!="Segno")   
                    $segno_max_glicemia=$oUtils->checkValue($_POST['segno_max_glicemia']);
                


                 if ( ($_POST['segno_min_glicemia']!="Segno" && $_POST['min_glicemia']!="" && is_numeric($_POST['min_glicemia'])) || ($_POST['segno_max_glicemia']!="Segno" && $_POST['max_glicemia']!="" && is_numeric($_POST['max_glicemia'])) ){
                    $min_glicemia=$oUtils->checkValue($_POST['min_glicemia']);
                    if($segno_min_glicemia!=null )
                        $levelalert->minGlycemiaMensuration=$segno_min_glicemia.SEPARATORE_ALERT.$min_glicemia;
                    $max_glicemia=$oUtils->checkValue($_POST['max_glicemia']);
                    if($segno_max_glicemia!=null)
                        $levelalert->maxGlycemiaMensuration=$segno_max_glicemia.SEPARATORE_ALERT.$max_glicemia;
                        
                        if ( (($_POST['segno_min_glicemia']!="Segno" && $_POST['min_glicemia']=="") || ($_POST['min_glicemia']!="" && !is_numeric($_POST['min_glicemia'])) ) || (($_POST['segno_max_glicemia']!="Segno" && $_POST['max_glicemia']=="") || ($_POST['max_glicemia']!="" && !is_numeric($_POST['max_glicemia'])) ) )
                            $error .= "E' obbligatorio inserire il valore a glicemia<br />";
                        if(($_POST['segno_min_glicemia']=="Segno" && $_POST['min_glicemia']!="") || ($_POST['segno_max_glicemia']=="Segno" && $_POST['max_glicemia']!=""))
                                $error .= "E' obbligatorio inserire il segno a glicemia<br />";
                 }
                else {

                    $error .= "E' obbligatorio inserire il valore a glicemia<br />";
                }







                /*if ( $_POST['segno_max_glicemia']=="Segno" )
                    $error .= "E' obbligatorio inserire il segno<br />";
                
                    $segno_max_glicemia=$oUtils->checkValue($_POST['segno_max_glicemia']);

                


                 if ( $_POST['max_glicemia']=="" )
                    $error .= "E' obbligatorio inserire il massimo glicemia<br />";
                else {

                    $max_glicemia=$oUtils->checkValue($_POST['max_glicemia']);

                   $levelalert->maxGlycemiaMensuration=$segno_max_glicemia.SEPARATORE_ALERT.$max_glicemia;



                }*/



}
//else
   // $error .= "E' obbligatorio inserire la regola a glicemia<br />";
//ricordarsi di eliminare questo else nel caso si vanno a inserire altri alert per le diverse misurazioni e decommentare il  controllo scritto in giù e ricontrollare il tutto






if (isset($_POST['emo']) && $_POST['emo'] == "si") {

                        $segno_min_emo=null;
                        $segno_max_emo=null;

                        if ( $_POST['segno_min_emo']=="Segno" && $_POST['segno_max_emo']=="Segno")
                            $error .= "E' obbligatorio inserire uno dei due segni in emoglobina<br />";
                        
                        if ( $_POST['segno_min_emo']!="Segno")
                            $segno_min_emo=$oUtils->checkValue($_POST['segno_min_emo']);
                        if ( $_POST['segno_max_emo']!="Segno")
                            $segno_max_emo=$oUtils->checkValue($_POST['segno_max_emo']);


                         if ( ($_POST['segno_min_emo']!="Segno" && $_POST['min_emo']!="") || ($_POST['segno_max_emo']!="Segno" && $_POST['max_emo']!="") ){
                             $min_emo=$oUtils->checkValue($_POST['min_emo']);
                             if($segno_min_emo!=null)
                             $levelalert->minPulseoximetryMensuration=$segno_min_emo.SEPARATORE_ALERT.$min_emo;
                             $max_emo=$oUtils->checkValue($_POST['max_emo']);
                             if($segno_max_emo!=null)
                             $levelalert->maxPulseoximetryMensuration=$segno_max_emo.SEPARATORE_ALERT.$max_emo;
                             if ( ($_POST['segno_min_emo']!="Segno" && $_POST['min_emo']=="" || ($_POST['min_emo']!="" && !is_numeric($_POST['min_emo'])) ) || ($_POST['segno_max_emo']!="Segno" && $_POST['max_emo']=="" || ($_POST['max_emo']!="" && !is_numeric($_POST['max_emo'])) ) )
                                $error .= "E' obbligatorio inserire il valore a emoglobina<br />";
                            if(($_POST['segno_min_emo']=="Segno" && $_POST['min_emo']!="") || ($_POST['segno_max_emo']=="Segno" && $_POST['max_emo']!=""))
                                $error .= "E' obbligatorio inserire il segno a emoglobina<br />";
                             
                         }  
                        else {

                            $error .= "E' obbligatorio inserire il valore a emoglobina<br />";
                        }
                            








                        /*if ( $_POST['segno_max_emo']=="Segno" )
                            $error .= "E' obbligatorio inserire il segno<br />";
                        

                            $segno_max_emo=$oUtils->checkValue($_POST['segno_max_emo']);

                        


                         if ( $_POST['max_emo']=="" )
                            $error .= "E' obbligatorio inserire il massimo emoglobbina<br />";
                        else {

                            $max_emo=$oUtils->checkValue($_POST['max_emo']);

                           $levelalert->maxPulseoximetryMensuration=$segno_max_emo.SEPARATORE_ALERT.$max_emo;



                        }*/

}





















if (isset($_POST['premin']) && $_POST['premin'] == "si") {
                    $segno_min_premin=null;
                    $segno_max_premin=null;
                if ( $_POST['segno_min_premin']=="Segno" && $_POST['segno_max_premin']=="Segno" )
                    $error .= "E' obbligatorio inserire uno dei due segni in pressione minima<br />";
                
                 if ( $_POST['segno_min_premin']!="Segno")
                    $segno_min_premin=$oUtils->checkValue($_POST['segno_min_premin']);
                 if ( $_POST['segno_max_premin']!="Segno")
                    $segno_max_premin=$oUtils->checkValue($_POST['segno_max_premin']);


                 if ( ($_POST['segno_min_premin']!="Segno" && $_POST['min_premin']!="") || ($_POST['segno_max_premin']!="Segno" && $_POST['max_premin']!="") ){
                        $min_premin=$oUtils->checkValue($_POST['min_premin']);
                        if($segno_min_premin!=null)
                        $levelalert->minPressureMensurationsMinimum=$segno_min_premin.SEPARATORE_ALERT.$min_premin;
                        $max_premin=$oUtils->checkValue($_POST['max_premin']);
                        if($segno_max_premin!=null)
                       $levelalert->maxPressureMensurationsMinimum=$segno_max_premin.SEPARATORE_ALERT.$max_premin;
                        if ( ($_POST['segno_min_premin']!="Segno" && $_POST['min_premin']=="" || ($_POST['min_premin']!="" && !is_numeric($_POST['min_premin'])) ) || ($_POST['segno_max_premin']!="Segno" && $_POST['max_premin']=="" || ($_POST['max_premin']!="" && !is_numeric($_POST['max_premin'])) ) )
                            $error .= "E' obbligatorio inserire il valore a pressione minima<br />";
                        if(($_POST['segno_min_premin']=="Segno" && $_POST['min_premin']!="") || ($_POST['segno_max_premin']=="Segno" && $_POST['max_premin']!=""))
                            $error .= "E' obbligatorio inserire il segno a pressione minima<br />";

                     
                 }
                    
                else {
                        $error .= "E' obbligatorio inserire il valore pressione minima<br />";
                   


                }







                /*if ( $_POST['segno_max_premin']=="Segno" )
                    $error .= "E' obbligatorio inserire il segno<br />";
                

                    $segno_max_premin=$oUtils->checkValue($_POST['segno_max_premin']);

                


                 if ( $_POST['max_premin']=="" )
                    $error .= "E' obbligatorio inserire il massimo della pressione minima<br />";
                else {

                    $max_premin=$oUtils->checkValue($_POST['max_premin']);

                   $levelalert->maxPressureMensurationsMinimum=$segno_max_premin.SEPARATORE_ALERT.$max_premin;



                }*/


}











if (isset($_POST['premax']) && $_POST['premax'] == "si") {
            $segno_min_premax=null;
            $segno_max_premax=null;     
                 if ( $_POST['segno_min_premax']=="Segno" && $_POST['segno_max_premax']=="Segno" )
                    $error .= "E' obbligatorio inserire uno dei due segni in pressione massima<br />";
                
                 if ( $_POST['segno_min_premax']!="Segno")
                    $segno_min_premax=$oUtils->checkValue($_POST['segno_min_premax']);
                 if ( $_POST['segno_max_premax']!="Segno")
                    $segno_max_premax=$oUtils->checkValue($_POST['segno_max_premax']);


                if ( ($_POST['segno_min_premax']!="Segno" && $_POST['min_premax']!="") || ($_POST['segno_max_premax']!="Segno" && $_POST['max_premax']!="") ){
                    $min_premax=$oUtils->checkValue($_POST['min_premax']);
                    if($segno_min_premax!=null)
                    $levelalert->minPressureMensurationsMaximum=$segno_min_premax.SEPARATORE_ALERT.$min_premax;
                    $max_premax=$oUtils->checkValue($_POST['max_premax']);
                    if($segno_max_premax!=null)
                    $levelalert->maxPressureMensurationsMaximum=$segno_max_premax.SEPARATORE_ALERT.$max_premax;
                     if ( ($_POST['segno_min_premax']!="Segno" && $_POST['min_premax']=="" || ($_POST['min_premax']!="" && !is_numeric($_POST['min_premax'])) ) || ($_POST['segno_max_premax']!="Segno" && $_POST['max_premax']=="" || ($_POST['max_premax']!="" && !is_numeric($_POST['max_premax'])) ) )
                            $error .= "E' obbligatorio inserire il valore a pressione massima<br />";
                     if(($_POST['segno_min_premax']=="Segno" && $_POST['min_premax']!="") || ($_POST['segno_max_premax']=="Segno" && $_POST['max_premax']!=""))
                            $error .= "E' obbligatorio inserire il segno a pressione massima<br />";
                 }
                    
                else {

                    
                    $error .= "E' obbligatorio inserire il valore pressione massima<br />";

                }







               /* if ( $_POST['segno_max_premax']=="Segno" )
                    $error .= "E' obbligatorio inserire il segno<br />";
                

                    $segno_max_premax=$oUtils->checkValue($_POST['segno_max_premax']);

                


                 if ( $_POST['max_premax']=="" )
                    $error .= "E' obbligatorio inserire il massimo della pressione massima<br />";
                else {

                    $max_premax=$oUtils->checkValue($_POST['max_premax']);

                   $levelalert->maxPressureMensurationsMaximum=$segno_max_premax.SEPARATORE_ALERT.$max_premax;



                }*/

}












// inizio respirazione 

if (isset($_POST['pef']) && $_POST['pef'] == "si") {
                    $segno_min_pef=null;
                    $segno_max_pef=null;
                if ( $_POST['segno_min_pef']=="Segno" && $_POST['segno_max_pef']=="Segno" )
                    $error .= "E' obbligatorio inserire uno dei due segni in pef<br />";
                
                 if ( $_POST['segno_min_pef']!="Segno")
                    $segno_min_pef=$oUtils->checkValue($_POST['segno_min_pef']);
                 if ( $_POST['segno_max_pef']!="Segno")
                    $segno_max_pef=$oUtils->checkValue($_POST['segno_max_pef']);


                 if ( ($_POST['segno_min_pef']!="Segno" && $_POST['min_pef']!="") || ($_POST['segno_max_pef']!="Segno" && $_POST['max_pef']!="") ){
                        $min_pef=$oUtils->checkValue($_POST['min_pef']);
                        if($segno_min_pef!=null)
                        $levelalert->minPefMensuration=$segno_min_pef.SEPARATORE_ALERT.$min_pef;
                        $max_pef=$oUtils->checkValue($_POST['max_pef']);
                        if($segno_max_pef!=null)
                       $levelalert->maxPefMensuration=$segno_max_pef.SEPARATORE_ALERT.$max_pef;
                        if ( ($_POST['segno_min_pef']!="Segno" && $_POST['min_pef']=="" || ($_POST['min_pef']!="" && !is_numeric($_POST['min_pef'])) ) || ($_POST['segno_max_pef']!="Segno" && $_POST['max_pef']=="" || ($_POST['max_pef']!="" &&  !is_numeric($_POST['max_pef'])) ) )
                            $error .= "E' obbligatorio inserire il valore a Pef minima<br />";
                        if(($_POST['segno_min_pef']=="Segno" && $_POST['min_pef']!="") || ($_POST['segno_max_pef']=="Segno" && $_POST['max_pef']!=""))
                            $error .= "E' obbligatorio inserire il segno a Pef minima<br />";

                     
                 }
                    
                else {
                        $error .= "E' obbligatorio inserire il valore Pef<br />";
                   


                }



}



if (isset($_POST['fev1']) && $_POST['fev1'] == "si") {
            $segno_min_fev1=null;
            $segno_max_fev1=null;     
                 if ( $_POST['segno_min_fev1']=="Segno" && $_POST['segno_max_fev1']=="Segno" )
                    $error .= "E' obbligatorio inserire uno dei due segni in Fev1<br />";
                
                 if ( $_POST['segno_min_fev1']!="Segno")
                    $segno_min_fev1=$oUtils->checkValue($_POST['segno_min_fev1']);
                 if ( $_POST['segno_max_fev1']!="Segno")
                    $segno_max_fev1=$oUtils->checkValue($_POST['segno_max_fev1']);


                if ( ($_POST['segno_min_fev1']!="Segno" && $_POST['min_fev1']!="") || ($_POST['segno_max_fev1']!="Segno" && $_POST['max_fev1']!="") ){
                    $min_fev1=$oUtils->checkValue($_POST['min_fev1']);
                    if($segno_min_fev1!=null)
                    $levelalert->minFev1Mensuration=$segno_min_fev1.SEPARATORE_ALERT.$min_fev1;
                    $max_fev1=$oUtils->checkValue($_POST['max_fev1']);
                    if($segno_max_fev1!=null)
                    $levelalert->maxFev1Mensuration=$segno_max_fev1.SEPARATORE_ALERT.$max_fev1;
                     if ( ($_POST['segno_min_fev1']!="Segno" && $_POST['min_fev1']=="" || ($_POST['min_fev1']!="" && !is_numeric($_POST['min_fev1'])) ) || ($_POST['segno_max_fev1']!="Segno" && $_POST['max_fev1']=="" || ($_POST['max_fev1']!="" && !is_numeric($_POST['max_fev1'])) ) )
                            $error .= "E' obbligatorio inserire il valore a Fev1 massima<br />";
                     if(($_POST['segno_min_fev1']=="Segno" && $_POST['min_fev1']!="") || ($_POST['segno_max_fev1']=="Segno" && $_POST['max_fev1']!=""))
                            $error .= "E' obbligatorio inserire il segno a Fev1 massima<br />";
                 }
                    
                else {

                    
                    $error .= "E' obbligatorio inserire il valore Fev1 massima<br />";

                }





}



// fine respirazione 







if (isset($_POST['peso']) && $_POST['peso'] == "si") {

                $segno_min_peso=null;
                $segno_max_peso=null;
                if ( $_POST['segno_min_peso']=="Segno" &&  $_POST['segno_max_peso']=="Segno")
                    $error .= "E' obbligatorio inserire uno dei due segni al peso<br />";
               
                    if ( $_POST['segno_min_peso']!="Segno")
                        $segno_min_peso=$oUtils->checkValue($_POST['segno_min_peso']);
                    if ( $_POST['segno_max_peso']!="Segno")
                        $segno_max_peso=$oUtils->checkValue($_POST['segno_max_peso']);


                if ( ($_POST['segno_min_peso']!="Segno" && $_POST['min_peso']!="") || ($_POST['segno_max_peso']!="Segno" && $_POST['max_peso']!="") ){
                    $min_peso=$oUtils->checkValue($_POST['min_peso']);
                    if($segno_min_peso!=null)
                    $levelalert->minWeightMensuration=$segno_min_peso.SEPARATORE_ALERT.$min_peso;
                    $max_peso=$oUtils->checkValue($_POST['max_peso']);
                    if($segno_max_peso!=null)
                    $levelalert->maxWeightMensuration=$segno_max_peso.SEPARATORE_ALERT.$max_peso;
                    if ( ($_POST['segno_min_peso']!="Segno" && $_POST['min_peso']=="" || ($_POST['min_peso']!="" && !is_numeric($_POST['min_peso'])) ) || ($_POST['segno_max_peso']!="Segno" && $_POST['max_peso']=="" || ($_POST['max_peso']!="" && !is_numeric($_POST['max_peso'])) ) )
                            $error .= "E' obbligatorio inserire il valore a peso<br />";
                    if(($_POST['segno_min_peso']=="Segno" && $_POST['min_peso']!="") || ($_POST['segno_max_peso']=="Segno" && $_POST['max_peso']!=""))
                            $error .= "E' obbligatorio inserire il segno a peso<br />";
                     
                 }
                else {
                        $error .= "E' obbligatorio inserire il valore peso<br />";
                    

                }







                /*if ( $_POST['segno_max_peso']=="Segno" )
                    $error .= "E' obbligatorio inserire il segno<br />";
                

                    $segno_max_peso=$oUtils->checkValue($_POST['segno_max_peso']);

                


                 if ( $_POST['max_peso']=="" )
                    $error .= "E' obbligatorio inserire il massimo del peso<br />";
                else {

                    $max_peso=$oUtils->checkValue($_POST['max_peso']);

                   $levelalert->maxWeightMensuration=$segno_max_peso.SEPARATORE_ALERT.$max_peso;



                }*/

}// fine settagio misurazioni


if( !isset($_POST['peso']) && !isset($_POST['fev1']) && !isset($_POST['pef']) && !isset($_POST['premax']) && !isset($_POST['premin']) && !isset($_POST['emo']) && !isset($_POST['glicemia']) ) 
                            $error .= "E' obbligatorio inserire una regola<br />";




//settagio id paziente

if (isset($_POST['idpaziente'])) {

      $idpaziente=$oUtils->checkValue($_POST['idpaziente']);
      $levelalert->fkUser= $idpaziente;

}
else{
    
    
    header("Location: " . URL_FILE_BACKOFFICE . "index.php");
    exit();
    
    
}






// settaggio id dottore

if (isset($_POST['iddottore'])) {
    if($_POST['iddottore'] != "--seleziona--"){
      $iddottore=$oUtils->checkValue($_POST['iddottore']);
      $levelalert->fkDoctor= $iddottore;
    }else
        $error .= "E' obbligatorio inserire il dottore<br />";
}
else{
    
    if($_SESSION['dati']['idRole']==DOCTORS){
        $iddottore=$_SESSION['dati']['idUser'];
        $levelalert->fkDoctor= $iddottore; 
    }
    else {
        header("Location: " . URL_FILE_BACKOFFICE . "index.php");
        exit();
    }
        
    
}
    



if (isset($_POST['contatto']) && $_POST['contatto'] != "--seleziona--") {
    if($_POST['contatto']==TIPO_CONTATTO_EMAIL || $_POST['contatto']==TIPO_CONTATTO_SMS || $_POST['contatto']==TIPO_CONTATTO_SMS_EMAIL)
    $levelalert->typeContact=$_POST['contatto'];
    
}else {
         
     $error .= "E' obbligatorio inserire il tipo di contatto <br />";
      
}
    
     






if (isset($_POST['nummisurazioni'])&& $_POST['nummisurazioni'] != ''){
    if(is_numeric($_POST['nummisurazioni']))
        $levelalert->period= $oUtils->checkValue($_POST['nummisurazioni']);
    else 
        $error .= "Numero di misurazioni non è numero <br />";

    
}else {

     $error .= "E' obbligatorio inserire il numero di misurazioni <br />";
      
}

     





if (isset($_POST['alert']) && $_POST['alert'] != "--seleziona--" && ($_POST['alert']==ALERT_ROSSO || $_POST['alert']==ALERT_GIALLO )) {
        $levelalert->typeAlert=$_POST['alert'];
        if($_POST['alert']==ALERT_ROSSO)
             $alert='ROSSO';
         if($_POST['alert']==ALERT_GIALLO)
             $alert='GIALLO';
}else {
 
     $error .= "E' obbligatorio inserire il tipo di alert <br />";
      
}
     




if (isset($_POST['stato'])) {
    
    if($_POST['stato']==ALERT_STATO_ATTIVO || $_POST['stato']==ALERT_STATO_DISATTIVO ){
         $levelalert->state=$_POST['stato'];
         if($_POST['stato']==ALERT_STATO_ATTIVO )
            $stato='ATTIVO';
         if($_POST['stato']==ALERT_STATO_DISATTIVO  )
            $stato='DISATTIVO';
    }else {
            header("Location: " . URL_FILE_BACKOFFICE . "index.php");
            exit();
         }  
    
    
}
 


if (isset($_POST['notifica_paziente']) && $_POST['notifica_paziente'] == "si") {
    $patient='SI';
    $levelalert->receiverMessagePatient=1;
}else{
    $levelalert->receiverMessagePatient=0;
    $patient='NO';
}   
    

if (isset($_POST['notifica_parente']) && $_POST['notifica_parente'] == "si") {
    $relative='SI';
    $levelalert->receiverMessageRelative=1;
} else {
    $levelalert->receiverMessageRelative=0;
    $relative='NO';
} 
    
 if ( $error!=""  ){
                $error = "Si sono verificati i seguenti errori:<br />".$error;
                $result['errore']=$error;
                print json_encode($result);
                exit();
 }else{
     $dt = new DateTime();
     $levelalert->date=$dt->format('Y-m-d H:i:s');
     $levelalert->insert();
     $idLevelAlert=$levelalert->getidLevelAlert();
     
     $result['errore']='0';
     $result['riga'][0]=$dt->format('d-m-Y H:i:s');
     $result['riga'][1]=$stato;
     $result['riga'][2]=$alert;
     $user->idUser=$iddottore;
     $dottore=$user->getUserbyId();
     $result['riga'][3]=$dottore[0]['name']." ".$dottore[0]['surname'];
     $result['riga'][4]=$_POST['contatto'];
     $user->idUser=$idpaziente;
     $paziente=$user->getUserbyId();
     $result['riga'][5]="<a href=grafici.php?id=$idpaziente&".$idLevelAlert[0]['idLevelAlert'].">Visualizza Misurazioni</a>";
     
     
     $result['riga'][6]='<a onclick="visualizza(this.parentNode)">Dettaglio</a>';
     $result['riga'][7]='<a onclick="modifica(this.parentNode)">Modifica</a>';
     
     $result['riga'][8]=$_POST['nummisurazioni'];
     
     if(empty($levelalert->minGlycemiaMensuration))
       $result['riga'][9]="";  
     else    
        $result['riga'][9]=str_replace(';'," ",$levelalert->minGlycemiaMensuration);
     
      if(empty($levelalert->maxGlycemiaMensuration))
       $result['riga'][10]="";  
     else    
        $result['riga'][10]=str_replace(';'," ",$levelalert->maxGlycemiaMensuration);
     
     
     
     
     if(empty($levelalert->minPressureMensurationsMinimum))
       $result['riga'][11]="";  
     else    
        $result['riga'][11]=str_replace(';'," ",$levelalert->minPressureMensurationsMinimum);
     if(empty($levelalert->maxPressureMensurationsMinimum))
       $result['riga'][12]="";  
     else    
        $result['riga'][12]=str_replace(';'," ",$levelalert->maxPressureMensurationsMinimum);
     
     
     
     if(empty($levelalert->minPressureMensurationsMaximum))
       $result['riga'][13]="";  
     else    
        $result['riga'][13]=str_replace(';'," ",$levelalert->minPressureMensurationsMaximum);
     if(empty($levelalert->maxPressureMensurationsMaximum))
       $result['riga'][14]="";  
     else    
        $result['riga'][14]=str_replace(';'," ",$levelalert->maxPressureMensurationsMaximum);
     
     
     if(empty($levelalert->minWeightMensuration))
       $result['riga'][15]="";  
     else    
        $result['riga'][15]=str_replace(';'," ",$levelalert->minWeightMensuration);
     if(empty($levelalert->maxWeightMensuration))
       $result['riga'][16]="";  
     else    
        $result['riga'][16]=str_replace(';'," ",$levelalert->maxWeightMensuration);
     
     
      if(empty($levelalert->minPulseoximetryMensuration))
       $result['riga'][17]="";  
     else    
        $result['riga'][17]=str_replace(';'," ",$levelalert->minPulseoximetryMensuration);
      if(empty($levelalert->maxPulseoximetryMensuration))
       $result['riga'][18]="";  
     else    
        $result['riga'][18]=str_replace(';'," ",$levelalert->maxPulseoximetryMensuration);
     
    
     if(empty($levelalert->minPefMensuration))
       $result['riga'][19]="";  
     else    
        $result['riga'][19]=str_replace(';'," ",$levelalert->minPefMensuration);
      if(empty($levelalert->maxPefMensuration))
       $result['riga'][20]="";  
     else    
        $result['riga'][20]=str_replace(';'," ",$levelalert->maxPefMensuration);
     
     if(empty($levelalert->minFev1Mensuration))
       $result['riga'][21]="";  
     else    
        $result['riga'][21]=str_replace(';'," ",$levelalert->minFev1Mensuration);
      if(empty($levelalert->maxFev1Mensuration))
       $result['riga'][22]="";  
     else    
        $result['riga'][22]=str_replace(';'," ",$levelalert->maxFev1Mensuration);
     
     
     
     $result['riga'][23]=$patient;
     $result['riga'][24]=$relative;
     $result['riga'][25]=$idpaziente;
     $result['riga'][26]=$iddottore;
     $result['riga'][27]=$_POST['stato'];
     $result['riga'][28]=$_POST['alert'];
     $result['riga'][29]=$idLevelAlert[0]['idLevelAlert'] ;
     print json_encode($result);
     exit();
     
 }
 











?>