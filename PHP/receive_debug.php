<?php

        define ("PATH_FILE","json/");

        define ("DEBUG",FALSE);

        //echo "Data e ora ricezione file: ".date("d/m/Y_H:i:s")."\n\n";

        $nome_file = date("dmY_His")."_".rand(1000, 9999).".txt";

        if ( DEBUG==true ) {
            echo "Inizio scrittura debug...\n";
            define ("PATH_FILE_DEBUG","debug/");
            $write_file_debug = fopen(PATH_FILE_DEBUG.$nome_file,"w");
            fwrite($write_file_debug,  "Debug\n\n");

            $msgDebug = "";

            foreach ( $_POST as $key=>$value ) {
                $msgDebug .= "\$_POST[".$key."]=".$value."\n";
            }

            fwrite($write_file_debug,  $msgDebug);
            fclose($write_file_debug);
        }

        if ( empty($_POST['json']) ) {
                echo "NOTDONE";
                exit;
        }

        $json = trim($_POST['json']);

        $json_receive = json_decode($json);

        if ( empty($json_receive) ) {
                echo "NOTDONE";
                exit;
        }

        if ( DEBUG==true ) {
            $retMessage = "JSON Ricevuto con successo.\n";
        } else {
            $retMessage = "";
        }


        $write_file = fopen(PATH_FILE.$nome_file,"w");

        if ( empty($write_file) ) {
                echo $retMessage."NOTDONE";
                exit;
        }

        fwrite($write_file,"Contenuto del JSON ricevuto\n\n");

        foreach ( $json_receive as $key => $value ) {
                fwrite($write_file,$key." => ".$value."\n");
        }

        fclose($write_file);
        
         $checkFileExists = file_exists(PATH_FILE.$nome_file);

        if ( empty($checkFileExists) )
                echo $retMessage."NOTDONE";
        else
                echo $retMessage."DONE";