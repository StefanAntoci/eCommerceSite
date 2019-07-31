<?php session_start(); 
include 'DatabaseConn.php';
include 'menuBar.php';
?>

<?php
	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{	
		if ($_POST['chosen_item'] != '' && $_POST['incoming_date'] != '' && $_POST['incoming_quantity'] != '' )
		{	
			if (ctype_digit($_POST['incoming_quantity']) && $_POST['incoming_quantity'] > 0)
			{
				$to_date = "BEGIN :value := to_date(:inc_date, 'yyyy-mm-dd'); END;";
				$date = oci_parse($conn, $to_date);
				oci_bind_by_name($date, ":inc_date", $_POST['incoming_date'], 100);
				oci_bind_by_name($date, ":value", $value, 100);		
				oci_execute($date);
				
				$add_incoming_stock_stmt = "BEGIN :validation := incoming_stock(:item_name, :inc_date, :items_nr); END;";
				$add_incoming_stock = oci_parse($conn, $add_incoming_stock_stmt);
				$validation = -1;
				oci_bind_by_name($add_incoming_stock, ":validation", $validation);
				oci_bind_by_name($add_incoming_stock, ":item_name", $_POST['chosen_item']);
				oci_bind_by_name($add_incoming_stock, ":inc_date", $value);
				oci_bind_by_name($add_incoming_stock, ":items_nr", $_POST['incoming_quantity']);
				
				oci_execute($add_incoming_stock);
				if ($validation == 1)
				{
					echo '<script>alert("Inserarea a reusit");</script>';
				}
				else
				{
					echo "<script>alert('Inserarea nu a reusit');</script>";
				}
			}
			else
			{
				echo "<script>alert('Introduceti un numar valid')</script>";
			}
				
		}
		else
		{
			echo '<script>alert ("Completati toate campurile")</script>';
		}
		
	}
?>
<html>
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=8;FF=3;OtherUA=4" />
		<meta charset="utf-8">		
		<link rel = "stylesheet" href="/css/style.css">
		<script type="text/javascript" src = "js/script.js"></script>
	</head>
	<body>
		<center>			
			<?php
				$items_cursor = oci_new_cursor($conn);
				$select_items_stmt = oci_parse($conn, 'begin select_items(:item_cursor); end;');
				oci_bind_by_name($select_items_stmt, ":item_cursor", $items_cursor, -1, OCI_B_CURSOR);
				oci_execute($select_items_stmt);
				oci_execute($items_cursor);
				
				$nrows = oci_fetch_all($items_cursor, $out);
				$options = '';
				echo "<form class = 'Inregistrare' action = '".$_SERVER["PHP_SELF"]."' method='post'>";
				echo "<select class = 'Inregistrare' name='chosen_item'>";				
				foreach ( $out['ITEMNAME'] as $name )
				{
					echo '<option value="'.$name.'">'.$name.'</option>';
				}
			?>
			</select><br/>
			
			<input class = "Inregistrare" type='date' name='incoming_date' ><br/>
			<input class = "Inregistrare" type='text' name='incoming_quantity'><br/>
			<input class = "Inregistrare" type='submit' value='Update Database'>
		</form>
		</center>
	</body>
</html>