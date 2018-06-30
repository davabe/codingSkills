<?php
	
        $_SERVER["HTTP_HOST"] = "script";
        define ("THIS_DEBUG",FALSE);
	require_once '../config/sys_application.php';
        require_once CLASS_DIR.'SmsAlert.class.php';
        
        
        $oSms = new SmsAlert();
        
        $arrSms = $oSms->getSmsToSend();
        
        if ( THIS_DEBUG ) {
                    var_dump($arrSms);
                    echo "\n";
        }
        
        if ( !empty( $arrSms ) ) {
            foreach ($arrSms as $key => $value) {
                
                $oSmsInvio = new SmsAlert();
                
                $oSmsInvio->idSmsAlert = $value['idSmsAlert'];
                $oSmsInvio->fkUser = $value['fkUser'];
                $oSmsInvio->fkDoctor = $value['fkDoctor'];
                $oSmsInvio->fkLevelAlert = $value['fkLevelAlert'];
                $oSmsInvio->text = $value['text'];
                $oSmsInvio->toNumber=$value['tel'];
                
                if ( THIS_DEBUG ) {
                    var_dump($oSmsInvio);
                    echo "\n";
                }
                echo "Invio SMS a numero: ".$value['tel']." per Alert ".$value['fkLevelAlert'].". Risposta: ";
                $response = $oSmsInvio->sendSms();
                
                echo $response."\n";
                
                unset($oSmsInvio);
                
                $oSmsUpdate = new SmsAlert();
                $oSmsUpdate->updateSms($value['idSmsAlert'], $response);
                unset($oSmsUpdate);
                

            }
            
            echo "\n";
        }
