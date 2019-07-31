<?php
	session_start();
	include 'DatabaseConn.php';

	if (isset($_SESSION['save_option']) && $_SESSION['save_option'] == 1)
	{
		$c_orderid = oci_new_cursor($conn);
		$sql_select_max = 'BEGIN select_max_orderid(:c_orderid); END;';
		$select_max_stmt = oci_parse($conn, $sql_select_max);
		oci_bind_by_name($select_max_stmt, ':c_orderid', $c_orderid, -1, OCI_B_CURSOR); 	
		oci_execute($select_max_stmt);
		oci_execute($c_orderid);
		
		$n_orders = oci_fetch_all($c_orderid, $orders);
		
		if ($n_orders == 0)
		{
			$order_id = -1;
		}
		else
		{
			$order_id = max($orders['ORDERID']);			
		}
		
		if ($order_id == -1)
		{
			echo "<script>alert('Eroare la baza de date');</script>";		
		}
		else
		{
			$order_id++;
			$saved_item = 1;
			$sql_save_order = 'BEGIN :valid := save_order(:order_id, :saved, :cust_id, :item_id); END;';
			$sql_save_order_stmt = oci_parse($conn, $sql_save_order);
			oci_bind_by_name($sql_save_order_stmt, ':order_id', $order_id, 10);
			oci_bind_by_name($sql_save_order_stmt, ':saved', $saved_item,10);
			oci_bind_by_name($sql_save_order_stmt, ':cust_id', $_SESSION['cust_id'], 100);
			oci_bind_by_name($sql_save_order_stmt, ':item_id', $_SESSION['item_id'], 100);
			
			oci_bind_by_name($sql_save_order_stmt, ':valid', $valid, 10);
			
			oci_execute($sql_save_order_stmt);
			
			
			if ($valid == 1)
			{
				echo "<script>alert('Itemul a fost salvat');</script>";
				echo "<script>window.location='".$_SERVER['HTTP_REFERER']."'</script>";
				//header("Location: ".$_SERVER["HTTP_REFERER"]);
				//echo "Itemul a fost salvat";
			}
			else
			{
				echo "<script>alert('Itemul nu a fost salvat');</script>";
				echo "<script>window.location='".$_SERVER['HTTP_REFERER']."'</script>";
				//header("Location: ".$_SERVER["HTTP_REFERER"]);
				//echo "Itemul nu a fost salvat";
			}
		}
	}
	
	if (isset($_SESSION['save_option']) && $_SESSION['save_option'] == 2)
	{
		$_SESSION['item_id'] = $_GET['id'];
		$sql_delete_savedItm = 'BEGIN delete_saved_item(:item_id, :cust_id, :valid); END;';
		$delete_savedItm_stmt = oci_parse($conn, $sql_delete_savedItm);
		oci_bind_by_name($delete_savedItm_stmt, ':item_id', $_SESSION['item_id'], 1000);
		oci_bind_by_name($delete_savedItm_stmt, ':cust_id', $_SESSION['cust_id'], 1000);
		oci_bind_by_name($delete_savedItm_stmt, ':valid', $valid,100);
		
		oci_execute($delete_savedItm_stmt);
		
		
		if ($valid== -1)
		{
			echo "<script>alert('Eroare la baza de date');</script>";
			echo "<script>window.location='".$_SERVER['HTTP_REFERER']."'</script>";			
		}
		else if ($valid == 0)
		{
			echo "<script>alert('Nu exista asa item salvat in cos');</script>";
			echo "<script>window.location='".$_SERVER['HTTP_REFERER']."'</script>";
		}
		else
		{
			echo "<script>alert('Itemul a fost sters din cosul tau');</script>";
			echo "<script>window.location='".$_SERVER['HTTP_REFERER']."'</script>";
		}
	}
	
	//header("Location: ".$_SERVER["HTTP_REFERER"]);	
?>