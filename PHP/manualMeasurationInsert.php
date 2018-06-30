<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


    require_once '../../config/sys_application.php';
    require_once CLASS_DIR . 'Utils.class.php';
   
    

    if ( empty($_SESSION['dati']) || $_SESSION['dati']['idRole']!=ADMINISTRATOR ) {

       header("Location: " . URL_FILE_BACKOFFICE . "index.php");
       exit();
    }
    
    
    $error = "";
    
    switch ($_POST['categoria']) {
        case GLICEMIA:
            $type = "glucometro;";
            $mea = $_POST['glicemia'];
            if ( empty($_POST['glicemia']) )
                $error .= "Misurazione non inserita<br />";
            else
                $mea = $_POST['glicemia'];
            
            if ( empty($_POST['um']) )
                $error .= "Unita' di misura non inserita<br />";
            else
                $um = $_POST['um'];
            
            break;
        case PESO:
            $type = "bilancia;";

            if ( empty($_POST['peso']) )
                $error .= "Misurazione non inserita<br />";
            else
                $mea = $_POST['peso'];
            
            if ( empty($_POST['um']) )
                $error .= "Unita' di misura non inserita<br />";
            else
                $um = $_POST['um'];
            break;
        case PRESSIONE:
            $type = "misuratore pressione;";
            
            if ( empty($_POST['press_min']) || empty($_POST['press_max']) || empty($_POST['puls']) )
                $error .= "Misurazione non inserita<br />";
            else
                $mea = $_POST['press_min'].";".$_POST['press_max'].";".$_POST['puls'];
            
            if ( empty($_POST['um_press']) || empty($_POST['um_puls']) )
                $error .= "Unita' di misura non inserita<br />";
            else
                $um = $_POST['um_press'].";".$_POST['um_press'].";".$_POST['um_puls'];
            
            break;
        case VOLUME_POLMONARE:
            $type = "spirometro;";
            
            if ( empty($_POST['pef']) || empty($_POST['fev1']) )
                $error .= "Misurazione non inserita<br />";
            else
                $mea = $_POST['pef'].";".$_POST['fev1'];
            
            if ( empty($_POST['um_pef']) || empty($_POST['um_fev1']) )
                $error .= "Unita' di misura non inserita<br />";
            else
                $um = $_POST['um_pef'].";".$_POST['um_fev1'];
            

            break;
        case SATURAZIONE_SANGUA:
            $type = "pulssossimetro;";
            
            if ( empty($_POST['emoglobina']) )
                $error .= "Misurazione non inserita<br />";
            else
                $mea = $_POST['emoglobina'];
            
            if ( empty($_POST['um']) )
                $error .= "Unita' di misura non inserita<br />";
            else
                $um = $_POST['um'];
            

            break;
        
        default:
            $error .= "Tipo di invio dati non trovato<br />";
        break;
    }
    
    
    
    $serial = $_POST['serialeCoxnico'];
    $medicalSerial = $_POST['serialeMedical'];
    
    $typeDevice = $_POST['device'];
    
    if (!empty($typeDevice)) {
    
        require_once CLASS_DIR.'MedicalDevices.class.php';
        $oMedicalDevice = new MedicalDevices();
        $arrayDevice = $oMedicalDevice->getMedicaldeviceById($typeDevice);

        //echo "".var_dump($arrayDevice);exit;

        if ( !empty($arrayDevice) ) {
            $type .= $arrayDevice['brand'].";".$arrayDevice['model'];
        } else {
            $error .= "Dispositivo non trovato<br />";
        }
    } else {
        $error .= "Dispositivo non inserito<br />";
    }
    
    $date = $_POST['date'];
    
    if ( !is_numeric($_POST['ora']) || (intval($_POST['ora'])<0 || intval($_POST['ora'])>24) )
        $error .= "L'ora non e' corretta<br />";
    
    if ( !is_numeric($_POST['minuti']) || (intval($_POST['minuti'])<0 || intval($_POST['minuti'])>59) )
        $error .= "I minuti non sono corretti<br />";
    
    $ora = $_POST['ora'].$_POST['minuti']."00";
    
    $anno = substr($date, 6, 4);
    $mese = substr($date, 3, 2);
    $giorno = substr($date,0, 2);
    
    if ( checkdate($mese, $giorno, $anno)==FALSE )
            $error .= "La data non e' corretta<br />";
    
    $newDate = $anno.$mese.$giorno."_".$ora;

    $data = array(
        'type'=>$type,
        'serial' => $medicalSerial,
        'medicalSerial' => $serial,
        'um' => $um,
        'mea' => $mea,
        'datetime' => $newDate
    );

    if ( !empty($error) ) {
        echo "ATTENZIONE! Si sono riscontrati i seguenti errori:<br />".$error;
    
    } else {
     
        //echo "".var_dump ($data);exit;
        $bodyData = array (
            'json' => json_encode($data)
          );


          $bodyStr = http_build_query($bodyData);

          $url = URL_WS.'receive.php';

          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Content-Type: application/x-www-form-urlencoded', 'Content-Length: '.strlen($bodyStr) ) );
          curl_setopt($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $bodyStr);

          $result = curl_exec($ch);

          if ( $result=="DONE" ) {
              echo $result;
          } else {
              echo "I dati non sono stati inseriti. Controllare il LOG di errore";
          }
        
    }