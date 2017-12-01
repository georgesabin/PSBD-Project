<?php
exit;
	$conn = oci_connect('system', 'sabingeorge95', 'localhost/XE');

	// Check if exists errors when try to connect oracle server
	if (!$conn) {
		$e = oci_error();
		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}

	// Get the content from file
	$sqlContent = file_get_contents('queries.txt');
	// Parse JSON and set the queries
	$queries = json_decode($sqlContent)->queries;

	// Create the table
	// !!! AFTER RUN THIS CODE, YOU COMMENT THIS !!!
	foreach ($queries->create_tables as $query) {
		$stid = oci_parse($conn, $query);
		oci_execute($stid);
	}

	// Make the alter table
	// !!! AFTER RUN THIS CODE, YOU COMMENT THIS !!!
	foreach ($queries->alter_tables as $query) {
		$stid = oci_parse($conn, $query);
		oci_execute($stid);
	}

	// The code for errors, if exists
	//if (!$r) {
		//$e = oci_error($stid);
		//echo '<pre>' . print_r($e['message'], true) . '</pre>';
		//echo '<pre>' . print_r($e['sqltext'], true) . '</pre>';
		//echo '<pre>' . print_r($e['offset'], true) . '</pre>';
	//}

	oci_free_statement($stid);
	oci_close($conn);
