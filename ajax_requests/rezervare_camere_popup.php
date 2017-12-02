<?php

    $conn = oci_connect('system', 'sabingeorge95', 'localhost/XE');
    if (!$conn) {
        $e = oci_error();
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }

    $camere = [];

    // $query = oci_parse($conn, 'INSERT INTO tip VALUES(1, \'dubla\', 150.00)');
    // oci_execute($query);
    // $query = oci_parse($conn, 'INSERT INTO camere VALUES(2, 200, 2, 1)');
    // oci_execute($query);

    // Aduc din baza de date camere in functie de tip
    $query = 'SELECT c.id, c.numar, c.etaj, t.pret_per_zi FROM tip t
    INNER JOIN camere c ON t.id=c.id_tip
    WHERE t.tip_camera=:rezervare_tip';
    $output = oci_parse($conn, $query);
    oci_bind_by_name($output, ':rezervare_tip', $_POST['tip_camera']);
    oci_execute($output);

    // Parcurg camerele, si verific pe fiecare daca e ocupata sau nu
    while (($row = oci_fetch_object($output)) != false) {
        //echo 'test' . $row->ID;

        // Execut functia din PL/SQL
        $sql = "BEGIN :r := verificare_camera(:parameter, :data_sosire, :data_plecare); END;";
        $outputCheck = oci_parse($conn, $sql);

        $data_sosire = date('d-M-Y', strtotime($_POST['data_sosire']));
        $data_plecare = date('d-M-Y', strtotime($_POST['data_plecare']));

        oci_bind_by_name($outputCheck, ':parameter', $row->ID) ;
        oci_bind_by_name($outputCheck, ':data_sosire', $data_sosire) ;
        oci_bind_by_name($outputCheck, ':data_plecare', $data_plecare) ;
        
        // In $r retin ceea ce returneaza functia PL/SQL
        oci_bind_by_name($outputCheck, ':r', $r);

        oci_execute($outputCheck);

        oci_free_statement($outputCheck);

        // Camera nu e ocupata, atunci o adaug in array
        if ($r == 0) {
            $camere[$row->ID] = [
                'id'          => $row->ID,
                'numar'       => $row->NUMAR,
                'etaj'        => $row->ETAJ,
                'pret_per_zi' => $row->PRET_PER_ZI
            ];
        }
    }

    oci_free_statement($output);
    oci_close($conn);

    // Return the result
    echo json_encode($camere);