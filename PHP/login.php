<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

        require_once '../../config/sys_application.php';
        
        require_once CLASS_DIR.'Utils.class.php';
        require_once CLASS_DIR.'Login.class.php';
        
        $oUtils = new Utils();
        
        if(!empty($_SESSION['loginmultiplo']) && isset($_GET['role'])){
            //var_dump($_SESSION);echo '</br>';
            $role=$_GET['role'];
           /* if(!is_int($role) || !$role<count($_SESSION['loginmultiplo']) ){
                
                session_destroy();
                header("Location: " . URL_FILE_BACKOFFICE . "index.php");
                exit();
            }*/
                
                
                
                
            $array2=array('data'=>date ('H:i d/m/Y'));
            $_SESSION['loginmultiplo'][$role] =array_merge( $_SESSION['loginmultiplo'][$role], $array2);
            //var_dump($_SESSION);exit();
            $array2=array('data'=>date ('H:i d/m/Y'));
            $_SESSION['loginmultiplo'][$role] =array_merge( $_SESSION['loginmultiplo'][$role], $array2);
            $_SESSION['dati'] =$_SESSION['loginmultiplo'][$role];
           //var_dump($_SESSION['dati']);exit(); 
            switch ($_SESSION['dati']['idRole']) {
		case ADMINISTRATOR:
                        $_SESSION['dati']['home'] ='index_admin.php';
			header("Location: ".URL_FILE_BACKOFFICE."index_admin.php");
                        
			break;
		case DOCTORS:
                        $_SESSION['dati']['home'] ='index_doctor.php';
			header("Location: ".URL_FILE_BACKOFFICE."index_doctor.php");
                       
			break;
                case PATIENT:
                        $_SESSION['dati']['home'] ='index_patient.php';
			header("Location: ".URL_FILE_BACKOFFICE."index_patient.php");
                        
			break;
                    
               case RELATIVE:
                        $_SESSION['dati']['home'] ='index_relative.php';
			header("Location: ".URL_FILE_BACKOFFICE."index_relative.php");
                        
			break;     
           
            
                }
        exit();
        }
        
        
        $username = $oUtils->checkValue($_POST['username']);
        $password = $oUtils->checkValue($_POST['password']);
        
        $_SESSION['error_login'] = "";
        $_SESSION['loginmultiplo']="";
        $html="";
        if ( empty($username) ) {
            $_SESSION['error_login'] .= "USERNAME non inserita<br />";
        }
        if ( empty($password) ) {
            $_SESSION['error_login'] .= "PASSWORD non inserita<br />";
        }
        
        $tpl = new Smarty();
        

            $oLogin = new Login();

            $oLogin->setFiled($username, $password,null,null);

            $datiLogin = null;

            $datiLogin = $oLogin->doLogin();
            //var_dump(count($datiLogin));exit();
            
            //echo $datiLogin['idRuolo'];
            //var_dump($datiLogin); session_destroy();exit();
            if ( empty($datiLogin) ) {
                $_SESSION['error_login'] = "Errore: utente non trovato";
                
                   header("Location: ".URL_FILE_BACKOFFICE."index.php");
                    exit;
                
            } elseif(count($datiLogin)>1) {
                // vedere meglio perchè ancora non fatto il login $datiLogin['data']=date('H:i d/m/Y');
                $_SESSION['loginmultiplo']= $datiLogin;
                //var_dump($_SESSION);session_destroy();exit();
                
                $i=0;
                
                foreach($datiLogin as $chiave => $valore){
                    if($valore['idRole']==DOCTORS)
                        $html.="<a type=button href=login.php?role=$i class='btn btn-labeled btn-primary' style='color:#ffffff; outline:none'><span class=btn-label><i class='glyphicon glyphicon-user'></i></span>  MEDICO</a></br>"; 
        
                    if($valore['idRole']==ADMINISTRATOR)
                       $html.="<a type=button href=login.php?role=$i class='btn btn-labeled btn-primary' style='color:#ffffff; outline:none'><span class=btn-label><i class='glyphicon glyphicon-user'></i></span> AMMINISTRATORE</a></br>";
                    if($valore['idRole']==RELATIVE)
                       $html.="<a type=button href=login.php?role=$i class='btn btn-labeled btn-primary' style='color:#ffffff; outline:none'><span class=btn-label><i class='glyphicon glyphicon-user'></i></span>  PARENTE</a></br>";
                    if($valore['idRole']==PATIENT)
                        $html.="<a type=button href=login.php?role=$i class='btn btn-labeled btn-primary' style='color:#ffffff; outline:none'><span class=btn-label><i class='glyphicon glyphicon-user'></i></span>  PAZIENTE</a></br>";
                        
                      $i++;  
                }
                    
                 $tpl->assign("html",$html);
                 $tpl->display("index.tpl");
          
        
        
                
            }
                    
            else{   
                $datiLogin[0]['data']=date('H:i d/m/Y');
                $_SESSION['dati'] = $datiLogin[0];
                
                
                //var_dump($datiLogin);exit();
                
                switch ($datiLogin[0]['idRole']) {
		case ADMINISTRATOR:
                        $_SESSION['dati']['home'] ='index_admin.php';
			header("Location: ".URL_FILE_BACKOFFICE."index_admin.php");
                        
			break;
		case DOCTORS:
                        $_SESSION['dati']['home'] ='index_doctor.php';
			header("Location: ".URL_FILE_BACKOFFICE."index_doctor.php");
                       
			break;
                case PATIENT:
                        $_SESSION['dati']['home'] ='index_patient.php';
			header("Location: ".URL_FILE_BACKOFFICE."index_patient.php");
                        
			break;
                    
               case RELATIVE:
                        $_SESSION['dati']['home'] ='index_relative.php';
			header("Location: ".URL_FILE_BACKOFFICE."index_relative.php");
                        
			break;     
                
	}  
                
                
                
                
                
				
                
                
            }
                 
        
        //}























?>
