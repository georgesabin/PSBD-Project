<?php

    $inputs = (object)$_POST;

    $errors = [];

    if ($inputs->rezervare_nume === '') { $errors['rezervare_nume'] = 'Numele nu este completat!'; }
    if ($inputs->rezervare_cnp === '') { $errors['rezervare_cnp'] = 'CNP-ul nu este completat!'; }

    if (!empty($errors)) { echo json_encode(['errors' => $errors]); }