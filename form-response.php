<?php

    $inputs = (object)$_POST;

    $errors = [
        'rezervare_tip_camera'    => '',
        'rezervare_data_sosire'    => '',
        'rezervare_data_plecare'    => '',
        'rezervare_nume'    => '',
        'rezervare_cnp'     => '',
        'rezervare_telefon' => ''
    ];

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

    $conn = oci_connect('system', 'sabingeorge95', 'localhost/XE');
    if (!$conn) {
        $e = oci_error();
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }

    $query = oci_parse($conn, 'INSERT INTO clienti (id, nume, cnp, nr_telefon) VALUES(1, :nume, :cnp, :nr_telefon)');
    oci_bind_by_name($query, ':nume', $inputs->rezervare_nume);
    oci_bind_by_name($query, ':cnp', $inputs->rezervare_cnp);
    oci_bind_by_name($query, ':nr_telefon', $inputs->rezervare_telefon);
    oci_execute($query);


    oci_free_statement($query);
	oci_close($conn);