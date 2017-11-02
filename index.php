<?php
//error_reporting(E_ALL);

  $conn = oci_connect("system", "sabingeorge95", "localhost/XE"); //127.0.0.1/XE

  if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
  }

	$sql =
	"
select * from test1
	";
  $stid = oci_parse($conn, $sql);

  //oci_bind_by_name($stid, ":v_test", $test);
  oci_execute($stid);
  //echo 'here ' . $test;
   //exit;

//  while ($stid_c = oci_get_implicit_resultset($stid)) {
    while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
//      foreach ($row as $item) {
       echo $row[0] . ' ' . $row[1];
      }
//    }
 // }

oci_free_statement($stid);
  oci_close($conn);
?>
