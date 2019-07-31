<?php session_start(); 
	include 'DatabaseConn.php';
	include 'menuBar.php';
	
	$select_orders = 'BEGIN SELECT_ORDERS(:order_cursor); END;';
	$select_orders_stmt = oci_parse($conn, $select_orders);
	$order_cursor = oci_new_cursor($conn);
	
	oci_bind_by_name($select_orders_stmt, ':order_cursor', $order_cursor, -1, OCI_B_CURSOR);
	oci_execute($select_orders_stmt);
	oci_execute($order_cursor);
	
	$nrows = oci_fetch_all($order_cursor, $all_orders);
	
	//var_dump($all_orders);
	
	echo "<table>";
	echo "<tr><th>Id comanda</th><th>Data livrare</th><th>Cumparator</th><th>Produs</th><th>Actualizare</th></tr>";
	
	for ($x = 0; $x < count($all_orders['ORDERID']); $x++)
	{
		$id = $all_orders['ORDERID'][$x];
		$date = $all_orders['ORDERDATE'][$x];
		
		$get_cust_name = 'BEGIN select_customername(:cust_id, :cust_name); END;';
		$get_cust_name_stmt = oci_parse($conn, $get_cust_name);
		oci_bind_by_name($get_cust_name_stmt, ':cust_name', $cust_name, 1000);
		oci_bind_by_name($get_cust_name_stmt, ':cust_id', $all_orders['CUSTOMERID'][$x], 1000);
		
		$get_item_name = 'BEGIN select_itemname(:item_id, :item_name); END;';
		$get_item_name_stmt = oci_parse($conn, $get_item_name);
		oci_bind_by_name($get_item_name_stmt, ':item_name', $item_name, 1000);
		oci_bind_by_name($get_item_name_stmt, ':item_id', $all_orders['ITEMID'][$x], 1000);
		
		oci_execute($get_cust_name_stmt);
		oci_execute($get_item_name_stmt);
		
		$link = "updateShipAddr.php?id=".$id."&date=".$date;
		echo "<tr><td>".$id."</td><td>".$date."</td><td>".$cust_name."</td><td>".$item_name."</td><td> <a href='".$link."'>Update ship date</a> </td></tr>";
	}
	
	echo "</table>";
?>