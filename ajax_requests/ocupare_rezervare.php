<?php
    $inputs = (object)$_POST;

    $dbInfo = file_get_contents('../login.txt');
    $dbInfo = json_decode($dbInfo);
    
    $conn = oci_connect($dbInfo->user, $dbInfo->pass, $dbInfo->ip);
    
    if (!$conn) {
        $e = oci_error();
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }

    if ($_POST['actionType'] == 'ocupare') {
        $errors = [];
        
        if ($inputs->cnp === '') {
            $errors['cnp'] = 'CNP-ul nu este completat!';
        } else {
            $sex = substr($inputs->cnp, 0, 1);
            $an = substr($inputs->cnp, 1, 2);
            $luna = substr($inputs->cnp, 3, 2);
            $zi = substr($inputs->cnp, 5, 2);
            if ($zi > 31 || $luna > 12 || date('Y', strtotime($an)) < '1900') {
                $errors['cnp'] = 'CNP invalid!';
            }
        }

        if (!empty($errors)) { echo json_encode(['errors' => $errors]); exit; }

        $rezervari = [];
        
        $idClient = oci_parse($conn, 'SELECT id FROM clienti WHERE cnp=:cnp');
        oci_bind_by_name($idClient, ':cnp', $inputs->cnp);
        oci_execute($idClient);
        $client_id = oci_fetch_object($idClient)->ID;
        oci_free_statement($idClient);

        $retObject = oci_parse($conn, 'SELECT * FROM rezervare_ocupare WHERE id_client=:id_client AND status_camera=1');
        oci_bind_by_name($retObject, ':id_client', $client_id);
        oci_execute($retObject);

        while (($row = oci_fetch_object($retObject)) != false) {
            $rezervari[] = [
                'id'            => $row->ID,
                'id_camera'     => $row->ID_CAMERA,
                'data_start'    => $row->DATA_START,
                'data_sfarsit'  => $row->DATA_SFARSIT
            ];
        }

        oci_free_statement($retObject);

        echo json_encode($rezervari);

    } else {

        
        $dataResult = json_decode($_POST['ocupareRezervare']);

        $ocupare = oci_parse($conn, 'UPDATE rezervare_ocupare SET status_camera=2 WHERE id=:id_rezervare');
        oci_bind_by_name($ocupare, ':id_rezervare', $dataResult[0]->id_rezervare);
        oci_execute($ocupare);
        oci_free_statement($ocupare);      
        
        echo json_encode(1);

    }