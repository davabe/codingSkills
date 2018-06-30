<?php

    require_once '../../config/sys_application.php';
    require_once CLASS_DIR . 'AjaxFunction.class.php';
    
    if ( empty($_POST['function']) )
        return NULL;
    
    switch ($_POST['function']) {
        case "medicalDevice":
                
                if ( $_SESSION['dati']['idRole']!=ADMINISTRATOR )
                    return NULL;
                
                if ( empty($_POST['id']) || !is_numeric($_POST['id']) )
                    return NULL;
                
                $ajaxFunction = new AjaxFunction();
                $arrayDevice = $ajaxFunction->getMedicalDeviceByType($_POST['id']);
                
                echo $arrayDevice;
                
                break;

        default:
            return NULL;
            break;
    }