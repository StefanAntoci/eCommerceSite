<?php session_start();
include 'DatabaseConn.php'; 
include 'menuBar.php';
?>

<?php
	if ( strpos($_SERVER['HTTP_REFERER'], 'cos.php') )
	{
		$_SESSION['item_id'] = $_GET['id'];
	}
	
	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		if ($_POST['nr_items'] != '' && $_POST['ship_addr'] != '' && $_POST['nr_items'] > 0)
		{
			$sql_check_stock = 'BEGIN CHECK_AV_STOCK(:item_id, :av_stock, :valid); END;';
			$sql_check_stock_stmt = oci_parse($conn, $sql_check_stock);
			oci_bind_by_name($sql_check_stock_stmt, ':av_stock', $_POST['nr_items'], 1000);
			oci_bind_by_name($sql_check_stock_stmt, ':item_id', $_SESSION['item_id'], 1000);
			oci_bind_by_name($sql_check_stock_stmt, ':valid', $valid, 10);
			

			oci_execute($sql_check_stock_stmt);
			if ($valid == -1)
			{
				echo "<script>alert('Eroare. Nu exista asa produs in stoc');</script>";
			}
			else if ($valid == 0)
			{
				echo "<script>alert('Nu avem suficiente produse in stoc');</script>";
			}
			else
			{			
				if ($_SESSION['buy_option'] == 1)
				{
					//if ($_POST['nr_items'] != '' && $_POST['ship_addr'] != '')
					//{
						$c_orderid = oci_new_cursor($conn);
						$sql_select_max = 'BEGIN SELECT_MAX_ORDERID(:c_orderid); END;';
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
//							if ($_POST['ship_addr'] != '' && $_POST['nr_items'] != '' && $_POST['nr_items'] > 0)
//							{
								$insert_new_order = 'BEGIN :valid := NEW_ORDER(:order_id, :items_nr, :saved_item, :ship_addr, :cust_id, :item_id); END;';
								$insert_new_order_stmt = oci_parse($conn, $insert_new_order);
								
								$saved_item = 0;
								oci_bind_by_name($insert_new_order_stmt, ":order_id", $order_id, 100);
								oci_bind_by_name($insert_new_order_stmt, ":items_nr", $_POST['nr_items'], 100);
								oci_bind_by_name($insert_new_order_stmt, ":saved_item", $saved_item, 10);
								oci_bind_by_name($insert_new_order_stmt, ":ship_addr", $_POST['ship_addr'], 100);
								oci_bind_by_name($insert_new_order_stmt, ":cust_id", $_SESSION['cust_id'], 100);
								oci_bind_by_name($insert_new_order_stmt, ":item_id", $_SESSION['item_id'], 100);
								
								oci_bind_by_name($insert_new_order_stmt, ":valid", $valid, 100);
								
								oci_execute($insert_new_order_stmt);
								if ($valid == 1)
								{
									echo "<script>alert('Inserare reusita');</script>";
								}
								else
								{
									echo "<script>alert('Inserarea nu a reusit');</script>";
								}
//							}
						}
					//}
				}
				else if ($_SESSION['buy_option'] == 2)
				{
					if ($_POST['nr_items'] != '' && $_POST['ship_addr'] != '')
					{
						$update_saved_order = 'BEGIN update_order(:nr_items, :ship_addr, :cust_id, :item_id, :valid, :message); END;';
						$update_saved_order_stmt = oci_parse($conn, $update_saved_order);
						
						oci_bind_by_name($update_saved_order_stmt, ':nr_items', $_POST['nr_items'], 1000);
						oci_bind_by_name($update_saved_order_stmt, ':ship_addr', $_POST['ship_addr'], 1000);
						oci_bind_by_name($update_saved_order_stmt, ':cust_id', $_SESSION['cust_id'], 1000);
						oci_bind_by_name($update_saved_order_stmt, ':item_id', $_SESSION['item_id'], 1000);
						oci_bind_by_name($update_saved_order_stmt, ':valid', $valid, 1000);
						oci_bind_by_name($update_saved_order_stmt, ':message', $message, 1000);
							
						oci_execute($update_saved_order_stmt);	
						echo "<script>alert('".$message."');</script>";
					}
					
				}
			}
		}
		else
		{
			echo "<script>alert('Completati toate campurile corect');</script>";
		}	
	}
?>
<html>

		<center>
			<form class = "Inregistrare" action="<?php $_SERVER['PHP_SELF'];?>" method = "post">
				Adresa de livrare : <input  class = "Inregistrare" type = "text" name = "ship_addr"><br/><br/>
				Numar produse : <input id="NrItems" class = "Inregistrare" type = "number" name = "nr_items"><br/><br/>
				<input class = "Inregistrare" type = "submit" value = "Inserare comanda noua"/>
			</form>
		</center>
		
		
	</body>		
</html>