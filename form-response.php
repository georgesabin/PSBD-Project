<?php

    $inputs = (object)$_POST;

    $errors = [];

    if ($inputs->rezervare_tip_camera === '') { 
        $errors['rezervare_tip_camera'] = 'Alege un tip de camera!';
    }
    if ($inputs->rezervare_data_sosire === '') { 
        $errors['rezervare_data_sosire'] = 'Selecteaza o data de sosire!';
    }
    if ($inputs->rezervare_data_plecare === '') { 
        $errors['rezervare_data_plecare'] = 'Selecteaza o data de plecare!';
    }
    if ($inputs->rezervare_nume === '') { 
        $errors['rezervare_nume'] = 'Numele nu este completat!';
    }
    if ($inputs->rezervare_cnp === '') {
        $errors['rezervare_cnp'] = 'CNP-ul nu este completat!';
    } else {
        $sex = substr($inputs->rezervare_cnp, 0, 1);
        $an = substr($inputs->rezervare_cnp, 1, 2);
        $luna = substr($inputs->rezervare_cnp, 3, 2);
        $zi = substr($inputs->rezervare_cnp, 5, 2);
        $dataNastere = date('d-m-Y', strtotime($zi . '-' . $luna . '-' . $an));
        // if (!($dataNastere >= '01-01-1900' && $dataNastere <= '31-12-1999' && ($sex == 1 || $sex == 2))) {
        //     $errors['rezervare_cnp'] = 'CNP invalid!';
        // }
        //var_dump(gettype(date('Y', strtotime($an)))); exit;
        if ($zi > 31 || $luna > 12 || date('Y', strtotime($an)) < '1900') {
            $errors['rezervare_cnp'] = 'CNP invalid!';
        }
    }

    if ($inputs->rezervare_telefon === '') { 
        $errors['rezervare_telefon'] = 'Numarul de telefon nu este completat!';
    } else if (strlen($inputs->rezervare_telefon) > 10 || strlen($inputs->rezervare_telefon) < 10) {
        $errors['rezervare_telefon'] = 'Numarul de telefon este incorect!';
    }

    if (!empty($errors)) { echo json_encode(['errors' => $errors]); exit; }

    $dbInfo = file_get_contents('login.txt');
	$dbInfo = json_decode($dbInfo);
	
	$conn = oci_connect($dbInfo->user, $dbInfo->pass, $dbInfo->ip);
	
    if (!$conn) {
        $e = oci_error();
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }

    $camere = json_decode($_POST['camere_rezervate']);

    $verificareClient = oci_parse($conn, 'BEGIN :id_client := verificare_client(:cnp, :nume, :nr_telefon); END;');
    oci_bind_by_name($verificareClient, ':cnp', $inputs->rezervare_cnp);
    oci_bind_by_name($verificareClient, ':nume', $inputs->rezervare_nume);
    oci_bind_by_name($verificareClient, ':nr_telefon', $inputs->rezervare_telefon);
    oci_bind_by_name($verificareClient, ':id_client', $id_client);
    oci_execute($verificareClient);

    // Fac un foreach prin toate camere pentru rezervare si adaug datele in tabela rezervare_ocupare
    foreach ($camere as $camera) {
        $output = json_decode(str_replace('\'', '"', $camera));
        $insert_rezervare = oci_parse($conn, 'BEGIN insert_rezervare_ocupare(:id_client, :id_camera, :data_start, :data_finish); END;');
        oci_bind_by_name($insert_rezervare, ':id_client', $id_client);
        oci_bind_by_name($insert_rezervare, ':id_camera', $output->id);
        $dataStart = date('d-M-Y', strtotime($inputs->rezervare_data_sosire));
        $dataFinish = date('d-M-Y', strtotime($inputs->rezervare_data_plecare));
        oci_bind_by_name($insert_rezervare, ':data_start', $dataStart);
        oci_bind_by_name($insert_rezervare, ':data_finish', $dataFinish);
        // Returnez eroarea din pl/sql daca exista
        $r = oci_execute($insert_rezervare);
        if (!$r) {
            $error = oci_error($insert_rezervare);
            $e = explode("\n", $error['message']);
            echo json_encode(['errors' => htmlentities(explode(': ', $e[0])[1])]);

        }
        oci_free_statement($insert_rezervare);    
    }

    oci_free_statement($verificareClient);
	oci_close($conn);