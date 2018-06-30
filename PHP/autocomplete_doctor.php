<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once '../../config/sys_application.php';
require_once CLASS_DIR.'DB.class.php';

 


$role=DOCTORS;
$state=UTENTE_ATTIVO;





if (isset($_GET) && isset($_GET['term']) && !empty($_GET['term'])) {
        $term = $_GET['term'];
                $app=$term;
                $counttot=  strlen($app);
                //$term1=$term;
                //$term2=$term;       
                if($term2=strstr($app,' ')){
                 //die('siamo dentro');
                 $term2bis=trim($term2);
                 $count2=  strlen($term2bis); 
                 $count1=$counttot-($count2+1);
                 $term1= substr($term,0,$count1);
                // echo "$term1'a'$term2bis";
                 
                 
                 $select_userbyrole = "SELECT  users.idUser, users.name, users.surname ";  
                $select_userbyrole .= " FROM login ";
                $select_userbyrole .= " INNER JOIN userStates ON login.fkUserState = userStates.idUserstate ";
                $select_userbyrole .= " INNER JOIN bridgeUsersRoles ON login.fkBridgeUserRole = bridgeUsersRoles.idBridgeUserRole ";
                $select_userbyrole .= " INNER JOIN users ON bridgeUsersRoles.fkUser = users.idUser ";
                $select_userbyrole .= " INNER JOIN roles ON bridgeUsersRoles.fkRole = roles.idRole ";
                $select_userbyrole .= " WHERE roles.idRole = ".$role;
                $select_userbyrole .= " AND userStates.idUserState = ".$state;
                $select_userbyrole .= " AND users.surname LIKE '%{$term1}%' AND users.name LIKE '%{$term2bis}%' ";
                $select_userbyrole .= " ORDER BY  users.surname, users.name";
                 
                 
                 
                 
                 
                 
                    
                }
 else{
                $select_userbyrole = "SELECT  users.idUser, users.name, users.surname ";  
                $select_userbyrole .= " FROM login ";
                $select_userbyrole .= " INNER JOIN userStates ON login.fkUserState = userStates.idUserstate ";
                $select_userbyrole .= " INNER JOIN bridgeUsersRoles ON login.fkBridgeUserRole = bridgeUsersRoles.idBridgeUserRole ";
                $select_userbyrole .= " INNER JOIN users ON bridgeUsersRoles.fkUser = users.idUser ";
                $select_userbyrole .= " INNER JOIN roles ON bridgeUsersRoles.fkRole = roles.idRole ";
                $select_userbyrole .= " WHERE roles.idRole = ".$role;
                $select_userbyrole .= " AND userStates.idUserState = ".$state;
                $select_userbyrole .= " AND users.surname LIKE '%{$term}%' OR users.name LIKE '%{$term}%' ";
                $select_userbyrole .= " ORDER BY  users.surname, users.name";
 }
                $db=new autocomplete_doctor();
                $db->setSql($select_userbyrole);
                $result_userbyrole = $db->query();
                
                $arrUserbyrole = NULL;
                
                $a_json = array();
                while ( $row_userbyrole =  $db->fetchas($result_userbyrole) ) {
                    
                    $arrUserbyrole['value'] = $row_userbyrole['idUser'];
                    $arrUserbyrole['label'] = $row_userbyrole['surname'];
                    $arrUserbyrole['label'] .= ' ';
                    $arrUserbyrole['label'] .= $row_userbyrole['name'];
                    array_push($a_json,$arrUserbyrole);
                    
                    
                }
 
 
}
 
 
 
 $json = json_encode($a_json);
 print $json;
 







?>
