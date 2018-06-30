<?php
	
	require_once '../config/sys_application.php';
        require_once CLASS_DIR.'Utils.class.php';
        
        //  var_dump ($aMisurazioniStrumento);exit;
        if ( empty($_POST['json']) ) {
		echo "NOTDONE";
                exit;                
        }
        
        
	$json = trim($_POST['json']);

	$json_receive = json_decode($json);
        
        //var_dump($json_receive);exit;
        
	if ( empty($json_receive) ) {
		echo "NOTDONE";
                exit;
        }
        
        
        $oInsert = NULL;
        
        $oLogger = NULL;
        
        $errorMessage = "";
        
        $type = addslashes(trim(strtolower($json_receive->type)));
        
        
        $aType = split(JSON_SEPARATOR, $type);
        
        //var_dump ($aType);exit;
        $typeMensuration = strtolower($aType[0]);
        $marca = $aType[1];
        $modello = $aType[2];
        
        $serial = addslashes(trim($json_receive->medicalSerial));
        $medicalSerial = addslashes(trim($json_receive->serial));

        $um = addslashes(trim($json_receive->um));
        $mea = addslashes(trim($json_receive->mea));
        
        $oUtils = new Utils();
        
        if ( $typeMensuration == "bilancia" ) {
            $datetime = addslashes(trim($json_receive->date));
            $datetime .= " ".date("H")."0000";
        } else {
            $datetime = addslashes(trim($json_receive->datetime));
        }
        
        $datetime = $oUtils->getDateTimeFromString($datetime);
        
        
        
        
        switch ($typeMensuration) {
            
            case "glucometro":
                require_once CLASS_DIR.'GlycemiaMensuration.class.php';
                $oMensuration = new GlycemiaMensuration($mea,$um,$datetime,$medicalSerial,$serial);
            break;
        
            case "bilancia":
                require_once CLASS_DIR.'WeightMensuration.class.php';
                $oMensuration = new WeightMensuration($mea,$um,$datetime,$medicalSerial,$serial);
            break;
        
            case "misuratore pressione":
                require_once CLASS_DIR.'PressureMensuration.class.php';
                $oMensuration = new PressureMensuration($mea,$um,$datetime,$medicalSerial,$serial);
            break;
        
            case "spirometro":
                require_once CLASS_DIR.'SpirometryMensuration.class.php';
                $oMensuration = new SpirometryMensurations($mea,$um,$datetime,$medicalSerial,$serial);
            break;
            
            case "pulssossimetro":
                require_once CLASS_DIR.'PulseoximetryMensurations.class.php';
                $oMensuration = new PulseoximetryMensuration($mea,$um,$datetime,$medicalSerial,$serial);
            break;
        
            default:
                if ( empty($typeMensuration) )
                    $errorMessage .= "Tipo misurazione non trovata: Campo non valorizzato\n";
                else
                    $errorMessage .= "TYPE: Valore ricevuto non corretto - ".$typeMensuration." Marca: ".$marca." Modello: ".$modello."\n";
            break;
        }
        
        
        if ( empty($medicalSerial) ) {
            
            $medicalSerial = $oMensuration->setSerial($marca, $modello);
            
            if ( empty($medicalSerial) )
                $errorMessage .= "Seriale dispositivo medico non trovato nell'elenco dei dispositivi\n";
        }
        
        
        
        if ( !empty($errorMessage) ) {
            require_once CLASS_DIR.'Logger.class.php';
            $oLogger = new Logger();
            
            $errorMessage .= "marca: ".$marca."\nmodello: ".$modello."\nserial: $serial\nmedicalSerial:$medicalSerial\num:$um\nmea:$mea\ndatetime:$datetime\n---------------------\n\n";
            
            $oLogger->wsError($errorMessage);
            
            
            require_once CLASS_DIR.'Mail.class.php';
            
            $from = MAIL_AUTH_USERNAME; 
            $to = MAIL_ADMIN_CONTACT;
            $mail_body = $errorMessage; 
            $subject = "ALERT piattaforma ".PLATFORM_NAME . " - Ricezione Dati"; 
            
            $mailToSend = new Mail();

            $mailToSend->inviaMail($to,$subject,$mail_body);
            
            echo "NOTDONE";
            exit;
        } else {
		$oMensuration->insert();
	}
