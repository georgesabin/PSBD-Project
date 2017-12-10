<?php
    $inputs = (object)$_POST;

    $dbInfo = file_get_contents('../login.txt');
    $dbInfo = json_decode($dbInfo);
    
    $conn = oci_connect($dbInfo->user, $dbInfo->pass, $dbInfo->ip);
    
    if (!$conn) {
        $e = oci_error();
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }
    
    if (isset($inputs->actionType) && $inputs->actionType == 'rezervari') {
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
        
        $dataSosire = '';
        $dataPlecare = '';
        $idRezervare = '';
        $idCamera = '';
        $rezervare = json_decode($_POST['editRezervare']);
        foreach ($rezervare as $data) {
            $dataSosire = date('d-M-Y', strtotime($data->rezervare_data_sosire));
            $dataPlecare = date('d-M-Y', strtotime($data->rezervare_data_plecare));
            $idRezervare = $data->id_rezervare;
            $idCamera = $data->id_camera;
        }
        $updateRezervare = oci_parse($conn, 'BEGIN update_rezervare(:id_rezervare, :id_camera, :data_sosire, :data_plecare); END;');
        oci_bind_by_name($updateRezervare, ':id_rezervare', $idRezervare);
        oci_bind_by_name($updateRezervare, ':id_camera', $idCamera);
        oci_bind_by_name($updateRezervare, ':data_sosire', $dataSosire);
        oci_bind_by_name($updateRezervare, ':data_plecare', $dataPlecare);
        $r = oci_execute($updateRezervare);
        if (!$r) {
            $error = oci_error($updateRezervare);
            $e = explode("\n", $error['message']);
            echo json_encode(['error' => htmlentities(explode(': ', $e[0])[1])]);

        }
        oci_free_statement($updateRezervare);

    }