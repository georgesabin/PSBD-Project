<?php

    $dbInfo = file_get_contents('../login.txt');
    $dbInfo = json_decode($dbInfo);

    $conn = oci_connect($dbInfo->user, $dbInfo->pass, $dbInfo->ip);

    if (!$conn) {
        $e = oci_error();
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }

    $scoatereRezervari = oci_parse($conn, 'BEGIN eliberare_camere(); END;');
    oci_execute($scoatereRezervari);
    oci_free_statement($scoatereRezervari);