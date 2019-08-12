<html>
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=8;FF=3;OtherUA=4" />
		<meta charset="utf-8">		
		<link rel = "stylesheet" href="/css/style.css">
		<script type="text/javascript" src = "js/script.js">javascript</script>
	</head>
	
	<body>
		<header id = "header">
			<h1 ><a id="NumeSite" href = "index.php">Magazin online</a></h1>
		</header> 	
		<div id = "navigation">
			<nav id="Menu">
				<a class="MenuElement" onclick="loadDoc('MainPage.php');">PaginaPrincipala </a>
				<?php
				$dept_cursor = oci_new_cursor($conn);
				$get_dept = 'BEGIN select_dept(:dept_cursor); END;';
				$get_dept_stmt = oci_parse($conn, $get_dept);
				
				oci_bind_by_name($get_dept_stmt, ':dept_cursor', $dept_cursor, -1, OCI_B_CURSOR);
				
				oci_execute($get_dept_stmt);
				oci_execute($dept_cursor);
				
				$nrows = oci_fetch_all($dept_cursor, $depts);
				
				for ($x = 0; $x < $nrows; $x++)
				{
                    $param = "\"DepTemplate.php?dept=".$depts['DEPARTMENT'][$x]."\"";
					echo "<a class='MenuElement' onclick='loadDoc(".$param.");'>".$depts['DEPARTMENT'][$x]." </a>";
				}
				?>

				<div id = "ButtonInregistrare">						
					<?php					
						if (isset($_SESSION['user']) && $_SESSION['user'] == 'admin')
						{
							echo "<a class='MenuElement' href = 'UpdateItemTable.php'>Gestiune produse</a>";
							echo "<a class='MenuElement' href = 'modifyOrders.php'>Modifica o comanda</a>";							
						}
					?>
					<a class="MenuElement" href = "Inregistrare.php">Inregistrare </a>
				</div>
                

			</nav>
		</div>
	</body>
</html>	