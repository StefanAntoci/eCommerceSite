<?php
session_start();
include 'DatabaseConn.php';
include 'menuBar.php';
?>

<?php
	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		if ($_POST['item_id'] == -1)
		{
			echo "<script>alert('Eroare la baza de date');</script>";
		}
		else
		{
			if ($_POST['item_name'] != '' && $_POST['item_price'] != '' && $_POST['stock_qty'] != '' && $_POST['item_dept'] != '' && $_POST['item_desc'] != '' && $_POST['inc_date'] != '')
			{
				if (is_numeric($_POST['item_price']) && is_numeric($_POST['stock_qty']) && $_POST['stock_qty'] > 0 && $_POST['item_price'] > 0 )
				{
					//echo $_POST['item_id'];
					$to_date = "BEGIN :value := to_date(:inc_date, 'yyyy-mm-dd'); END;";
					$date = oci_parse($conn, $to_date);
					oci_bind_by_name($date, ":inc_date", $_POST['inc_date'], 100);
					oci_bind_by_name($date, ":value", $value, 100);		
					oci_execute($date);
					
					$sql_insert_item = 'BEGIN :valid := NEW_ITEM(:item_id, :item_name, :item_stock, :item_price, :item_desc, :item_dept, :item_date); END;';
					$sql_insert_item_stmt = oci_parse($conn, $sql_insert_item);
					
					oci_bind_by_name($sql_insert_item_stmt, ':item_id', $_POST['item_id'], 100000000);
					oci_bind_by_name($sql_insert_item_stmt, ':item_name', $_POST['item_name'], 100000000);
					oci_bind_by_name($sql_insert_item_stmt, ':item_price', $_POST['item_price'], 10000000);
					oci_bind_by_name($sql_insert_item_stmt, ':item_stock', $_POST['stock_qty'], 100000000);
					oci_bind_by_name($sql_insert_item_stmt, ':item_dept', $_POST['item_dept'], 10000000);
					oci_bind_by_name($sql_insert_item_stmt, ':item_desc', $_POST['item_desc'], 20000000);
					oci_bind_by_name($sql_insert_item_stmt, ':item_date', $value, 2000);					
					
					oci_bind_by_name($sql_insert_item_stmt, ':valid', $valid, 100000);
					
					
					oci_execute($sql_insert_item_stmt);
					if($valid == 1)
					{
						echo "<script>alert('Inserare reusita');</script>";
					}
					else if ($valid == -1)
					{
						echo "<script>alert('Eroare la inserare, asa produs deja exista');</script>";
					}
					else
					{
						echo "<script>alert('Inserarea nu a reusit');</script>";
					}
				}
				else
				{
					echo "<script>alert('Introduceti numere in campurile corespunzatoare');</script>";
				}
			}
			else
			{
				echo "<script>alert('Completati toate campurile');</script>";
			}	
		}
		
	}
?>

<html>
	<head>
	</head>
	
	<body>
	<center>
		<?php
		$sql_select_max = 'BEGIN SELECT_MAX_ITEMID(:item_id); END;';
		$select_max_stmt = oci_parse($conn, $sql_select_max);
		oci_bind_by_name($select_max_stmt, ':item_id', $item_id);
		oci_execute($select_max_stmt);
		echo $item_id;
		$item_id++;
		echo "<form class = 'Inregistrare' action = '".$_SERVER["PHP_SELF"]."' method = 'post'>";
		echo "<input class= 'Inregistrare' type='hidden' name='item_id' value='".$item_id."'><br/>";
		?>
			Numele produsului: <input class = "Inregistrare" type = 'text' name = 'item_name'><br/>
			Pretul produsului: <input class = "Inregistrare" type = 'number' name = 'item_price'><br/>
			Numarul de produse: <input class = "Inregistrare" type = 'number' name = 'stock_qty'><br/>
			Departamentul: <input class = "Inregistrare" type = 'text' name = 'item_dept'><br/>
			Data de aparitie: <input class = "Inregistrare" type = 'date' name = 'inc_date'><br/>				
			Descrierea produsului: <textarea class = "Inregistrare" name = 'item_desc' rows = '12' cols = '38'></textarea><br/>
			<input class = "Inregistrare" type = 'submit' value = 'Insereaza un nou element'>
		</form>
	</center>	
	</body>
</html>