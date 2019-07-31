<?php
	//session_start();
	$conn = oci_connect("c##stefan","1234","//localhost/orcl");
	if (!$conn)
	{
		$m = oci_error();
		echo $m['message'], "\n";
		exit;
	}
	
	$check_incoming_items = 'BEGIN update_items(:message); END;';
	$check_incoming_items_stmt = oci_parse($conn, $check_incoming_items);
	
	oci_bind_by_name($check_incoming_items_stmt, ':message', $message, 1000);
	
	oci_execute($check_incoming_items_stmt);
	
	//echo "<script>alert('".$message."');</script>";
  ?>