<?php

        require_once '../../config/sys_application.php';

        //var_dump($_SESSION);

        //session_destroy();
        
            
        
        $tpl = new Smarty;

        $tpl->compile_check = COMPILE_CHECK;
        $tpl->debugging = FALSE;
        //$tpl->config_load('C:/xampp/htdocs/easimed/site/Smarty/ITA.conf', 'global');


        if ( !empty($_SESSION['error_login']) ){
            $tpl->assign("error",$_SESSION['error_login']);
            //session_destroy() ;
        }
       
        unset($_SESSION);
        session_destroy() ;
       
        
        $tpl->assign("section","index");
        
        $tpl->assign("tipo_utente","");


        $tpl->display("index.tpl");

        
        
       
        