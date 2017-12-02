<?php

    $conn = oci_connect('system', 'sabingeorge95', 'localhost/XE');
    if (!$conn) {
        $e = oci_error();
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }

    $dateClient = [];

    $query = 'SELECT nume, nr_telefon
    FROM clienti
    WHERE cnp=:cnp';
    $output = oci_parse($conn, $query);
    oci_bind_by_name($output, ':cnp', $_POST['cnp']);
    oci_execute($output);

    while (($row = oci_fetch_object($output)) != false) {
        $dateClient = [
            'nume'       => $row->NUME,
            'nr_telefon' => $row->NR_TELEFON
        ];
    }

    oci_free_statement($output);
    oci_close($conn);

    echo json_encode($dateClient);