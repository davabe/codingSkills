<? 

    $_SERVER["HTTP_HOST"] = "script";
    define ("THIS_DEBUG",FALSE);
    require_once '../config/sys_application.php';
    require_once CLASS_DIR.'Users.class.php';
    require_once CLASS_DIR.'Mesuration.class.php';
    
    
    $oUsers = new Users();
    $oMeasuration = new Mesuration();
    
    $msg = "";
    $msgDet = "";
    
    $arrayMeasuration = $oMeasuration->getMeasurationType();
    
    
    
    foreach ($arrayMeasuration as $key => $value) {
        
        $rowMeasurations = $oUsers->getDailyMeasuration($value['idMeasurationType']);

        if ( !empty($rowMeasurations) ) {
            
            
            $check = FALSE;
            
            foreach ($rowMeasurations as $key2 => $value2) {
                
                $numeroMisurazioniUtente = $oMeasuration->getNumMeasurationForUser($value['idMeasurationType'], $value2['idUser'])."<br />";
                if ( $numeroMisurazioniUtente < $value2['numMeasuration'] ){
                    $msgDet .= "<strong>".$value2['nameUser']." ".$value2['surnameUser']."</strong><br />";
                    $msgDet .= "Numero misurazioni previste: <strong>".$value2['numMeasuration']."</strong> - Numero misurazioni registrate: <strong>".$numeroMisurazioniUtente."</strong><br />";
                    $check = TRUE;
                }
            }
            
            if ( $check ) {
                $msg .= "<br />Mancate misurazioni per: <strong>".$value['name']."</strong><br /><br />Pazienti:<br />".$msgDet;
            }
            
        }
       
        
    }
    
    
    if ( !empty($msg) ) {
        
        require_once CLASS_DIR.'Mail.class.php';
            
        $from = MAIL_AUTH_USERNAME; 
        $to = MAIL_SERVIZIO_CLIENTI; 
        $subject = "ALERT mancate misurazioni degli utenti"; 

        $mailToSend = new Mail();

        $mailToSend->inviaMail($to,$subject,$msg);
        
	echo "Email inviata\n\n";        

    } else {
	
	echo "Non e' stato necessario inviare email. Non ci sono pazienti che non rispettano le misurazioni da inviare\n\n";
	
    }
    
    
    
    
    
    


