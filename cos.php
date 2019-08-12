<?php session_start();
include 'DatabaseConn.php';
include 'menuBar.php';
?>
<html>
	<?php
		$_SESSION['save_option'] = 2;
		$_SESSION['buy_option'] = 2;
		
		$sql_saved_orders = 'BEGIN select_item_from_order(:cust_id, :it_cursor); END;';
		$sql_saved_orders_stmt = oci_parse($conn, $sql_saved_orders);
		$cust_id =$_SESSION['cust_id'];
		$it_cursor = oci_new_cursor($conn);
		
		oci_bind_by_name($sql_saved_orders_stmt, ':cust_id', $cust_id, 100);
		oci_bind_by_name($sql_saved_orders_stmt, ':it_cursor', $it_cursor, -1, OCI_B_CURSOR);				
		oci_execute($sql_saved_orders_stmt);
		oci_execute($it_cursor);
		
		$nrows = oci_fetch_all($it_cursor, $itemid_arr);
		
	?>
	<body>
		<section>
		<?php
		if (!isset($_SESSION['user']))
		{	
			echo "<form id='FormaLogare' action = 'login.php' method = 'post'>
			<center><h2>Logare:</h2></center><br/>
			Nume: <input type = 'text' name='user'/><br/><br/>
			Parola: <input type = 'password' name = 'password'/><br/><br/>
			<input class='MenuElement' id='FormaLogareButon' type = 'submit' value = 'Logare'/>
			</form>";
			if (isset($_SESSION['eroare']))
			{
				echo"<center><h2>".$_SESSION['eroare']."</h2></center>";				
			}
		}
		else
		{
			echo "<div style='width:15%; float:left; display:block'><h2 id='MesajBunVenit'>Bun Venit, ".$_SESSION['user']."</h2>";
			echo "<form  action='logout.php'>
            <input type = 'hidden' name='csrf' value='".$_SESSION['token']."'/>
			<input class='MenuElement' type = 'submit' value='Delogare'/>
			</form><br/>
			</div>";
			echo "<br/><br/><center><h1 style='margin-right:300px'>Cosul meu</h1></center>"; 	
		}
	
		echo "<div class='Specificatii' style='margin-top:100px;'><br/>";	
		
		for ($x = 0; $x < $nrows; $x++)
		{
			$select_name_price = 'BEGIN select_name_price(:item_id, :item_name, :v_price); END;';
			$select_name_price_stmt = oci_parse($conn, $select_name_price);
			$item_id = $itemid_arr['ITEMID'][$x];
			
			oci_bind_by_name($select_name_price_stmt, ':item_id', $item_id,100 );
			oci_bind_by_name($select_name_price_stmt, ':item_name', $item_name, 1000);
			oci_bind_by_name($select_name_price_stmt, ':v_price', $v_price, 1000);
			
			oci_execute($select_name_price_stmt);
			
			if ($v_price < 0)
			{
				echo "<script>alert('".$item_name."');</script>";
				break;
			}
			else
			{
				echo "<center>".$item_name."</center>";
				echo "<center>".$v_price."</center>";
				echo "<center><form action='Portofel.php' method='get'>";
				echo "<input type = 'hidden' name='id' value = '".$itemid_arr['ITEMID'][$x]."'>";
				echo "<input class='MenuElement' type = 'submit' value='Procura produsul' style='width:280px'/>";
				echo "</form></center>";
				
				echo "<center><form action='modifica.php' method='get'>";
				echo "<input type = 'hidden' name='id' value = '".$itemid_arr['ITEMID'][$x]."'>";
				echo "<input class='MenuElement' type = 'submit' value='Sterge din cos' style='width:280px'/>";
				echo "</form></center>";
			}
		}
		
		echo "</div>";
		
		?>
		</section>
	
	</body>

</html>	
		