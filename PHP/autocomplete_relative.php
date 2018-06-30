<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once '../../config/sys_application.php';
require_once CLASS_DIR.'autocomplete_relative.class.php';

if (isset($_GET) && isset($_GET['term']) && !empty($_GET['term'])) {
                $autocomplete=new autocomplete_relative();
                $term = $_GET['term'];
                $app=$term;
                $counttot=strlen($app);
                //$term1=$term;
                //$term2=$term;       
                if($term2=strstr($app,' ')){
                 //die('siamo dentro');
                 $term2bis=trim($term2);
                 $count2=  strlen($term2bis); 
                 $count1=$counttot-($count2+1);
                 $term1= substr($term,0,$count1);
                // echo "$term1'a'$term2bis";
                 $autocomplete->getdoctorsjson2($term1, $term2bis);

                }else{
                    
                    
                    $autocomplete->getdoctorsjson1($term);
                    
                
                }



}




?>
