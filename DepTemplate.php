<?php session_start(); 
include 'DatabaseConn.php';
include 'menuBar.php';?>
<html>
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
			echo "<div id ='FormaLogare'><h2 id='MesajBunVenit'>Bun Venit, ".$_SESSION['user']."</h2>";
			echo "<form  action='logout.php'>
			<input class='MenuElement' type = 'submit' value='Delogare'/>
			</form><br/>";
			echo "</div>";
			echo "<form action = 'cos.php'>
			<input  class= 'Cos' type = 'submit' value = 'Cosul meu'/>
			</form>";
		}
		
		$item_cursor = oci_new_cursor($conn);
		$print_item = oci_parse($conn, 'begin select_items_dept(:v_dept, :item_cursor); end;');
		
		oci_bind_by_name($print_item, ":item_cursor", $item_cursor, -1, OCI_B_CURSOR);
		oci_bind_by_name($print_item, ":v_dept", $_GET['dept']);
		
		oci_execute ($print_item);
		oci_execute ($item_cursor);
		
		
		while ( ($row = oci_fetch_array($item_cursor, OCI_ASSOC)) != false  )
		{
			
			$concat_name = '';
			$words_of_name = explode(" ",$row['ITEMNAME']);
			foreach ($words_of_name as $word)
			{
				$concat_name .= $word;
			}
			//echo "ItemTemplate.php?data=".$row['ITEMNAME']."&id=".$row['ITEMID'];
			echo "<div id = 'Column'>
					<ul id = 'Content'>
					<li><a href='ItemTemplate.php?data=".$row['ITEMNAME']."&id=".$row['ITEMID']."'> <img src='./imagini/".$concat_name.".jpg' style='width:70%'>
						<p class='DenumiriPaginaPrincipala'>".$row['ITEMNAME']."</p>
						</a> </li> 
					</ul>
				 </div>";					
		}
		oci_free_statement($item_cursor);
		oci_free_statement($print_item);
		?>
		
</html>