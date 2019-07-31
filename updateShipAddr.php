<?php session_start();
	include 'DatabaseConn.php';
	include 'menuBar.php';
 ?>
 
	<?php
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			if ($_POST['new_date'] != '')
			{
				$to_date = "BEGIN :value := to_date(:inc_date, 'yyyy-mm-dd'); END;";				
				$date = oci_parse($conn, $to_date);
				
				oci_bind_by_name($date, ":inc_date", $_POST['new_date'], 100);
				oci_bind_by_name($date, ":value", $value, 100);		
				oci_execute($date);
				
				$update_date = 'BEGIN update_orderdate(:new_date, :order_id, :valid); END;';
				$update_date_stmt = oci_parse($conn, $update_date);
				
				oci_bind_by_name($update_date_stmt, ':new_date', $value, 1000);
				oci_bind_by_name($update_date_stmt, ':order_id', $_POST['ord_id'], 1000);
				oci_bind_by_name($update_date_stmt, ':valid', $valid, 1000);

				echo $_POST['ord_id'];
				echo $value;		
				oci_execute($update_date_stmt);
				
				if ($valid == -1)
				{
					echo '<script>alert ("Eroare la baza de date");</script>';
				}
				else if ($valid == 0)
				{
					echo '<script>alert ("Nu a fost gasita aceasta comanda in baza de date");</script>';
				}	
				else
				{
					echo '<script>alert ("Data de livrare a fost actualizata cu succes");</script>';
				}
			}
			else
			{
				echo '<script>alert ("Completati toate campurile");</script>';
			}
		}
	?>	
 		<center>
			<form class = "Inregistrare" action="<?php $_SERVER['PHP_SELF'];?>" method = "post">
				Data livrare noua: <input  class = "Inregistrare" type = "date" name = "new_date"><br/><br/>
				<input id="NrItems" class = "Inregistrare" type = "hidden" name = "ord_id" value = "<?php echo $_GET['id']; ?>"><br/><br/>
				<input class = "Inregistrare" type = "submit" value = "Actualizare data livrare"/>
			</form>
		</center>