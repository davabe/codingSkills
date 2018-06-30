<?php

        require_once '../../config/sys_application.php';
        require_once CLASS_DIR . 'Mail.class.php';
        $aMail = new Mail();

        $aMail->SMTPDebug=1;

	echo "<pre>Classe Mail:<br />";
	var_dump ($aMail);
	echo "<br /><hr><br />";


        $return = $aMail->inviaMail ( "info@vincerealsuperenalotto.it", "Test", "Messaggio", $from = EMAIL_FROM_ADDRESS , $fromName = EMAIL_FROM_NAME , $isHtml = true );

        echo "<br /><hr /><br />Return value: ".$return;
