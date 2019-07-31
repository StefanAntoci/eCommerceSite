<?php session_start();
	include 'DatabaseConn.php';
	include 'menuBar.php';	
?>

<html>
	<head>
		<meta charset="utf-8">		
		<link rel = "stylesheet" href="/css/style.css">
		<script type="text/javascript" src = "js/script.js"></script>
	</head>
	
	<body>

		<center>
				<form class = "Inregistrare" action="" method = "post">
					Nume utilizator: <input class = "Inregistrare" type = "text" name="user"/><br/><br/>
					Introduceti o parola : <input class = "Inregistrare" type = "password" name = "pass" ><br/><br/>
					Confirmati parola : <input class = "Inregistrare" type = "password" name = "cpass"><br/><br/>
					<input class = "Inregistrare" type = "submit" value = "Inregistrare"/>
				</form>
		</center>
		<?php
		$flag  = 1;
		
		if (isset($_POST['user']) && isset($_POST['pass']) && isset($_POST['cpass']))
		{
			if ( ($_POST['user'] == ''))
			{
				echo "<center><h1>Nume de utilizator invalid</h1></center>";
			}	
			else if (($_POST['pass'] == '') )
			{
				echo "<center><h1>Parolele nu coincid</h1></center>";
			}
			 else if (isset ($_POST['user']) && isset($_POST['pass']) && isset($_POST['cpass']) && ($_POST['user'] != '') && ($_POST['pass'] != '') && ($_POST['cpass'] != ''))
			{
				{			
					if($_POST['pass'] === $_POST['cpass'])
					{
						$sql_select_max = 'BEGIN SELECT_MAX_CUSTID(:cust_id); END;';
						$select_max_statement = oci_parse($conn, $sql_select_max);
						oci_bind_by_name($select_max_statement, ':cust_id', $cust_id);
						oci_execute($select_max_statement);
						echo $cust_id;
						if ($cust_id == 0)
						{
							$cust_id = 1;
							$insert_comm = 'insert into customers  values ('.$cust_id.', \''.$_POST['user'].'\', \''.$_POST['pass'].'\' , \''.$_POST['addr'].'\')';
							$insert_id = oci_parse($conn,$insert_comm);
							oci_execute($insert_id);
							echo "<center><h2>Inregistrare finalizata. Shopping placut.</h2></center>";
						}
						else
						{
							$sql_check_usr = 'begin verify_username(:temp_usr, :ret_val); end;';
							$check_usr_stmt = oci_parse($conn, $sql_check_usr);
							oci_bind_by_name($check_usr_stmt, ':temp_usr', $_POST['user']);
							oci_bind_by_name($check_usr_stmt, ':ret_val', $return_val);
							oci_execute($check_usr_stmt);
							if ($return_val == 1)
							{
								$cust_id++;
								$insert_comm = 'insert into customers  values ('.$cust_id.', \''.$_POST['user'].'\', \''.$_POST['pass'].'\' , \''.$_POST['addr'].'\')';
								$insert_id = oci_parse($conn,$insert_comm);
								oci_execute($insert_id);
								echo "<center><h2>Inregistrare finalizata. Shopping placut.</h2></center>";								
							}
							else
							{
								echo "<center><h2>Eroare! Asa username deja exista.</h2></center>";
							}
						}
							
					}
					else
					{
						echo "<center><h2>Parolele nu coincid</h2></center>";
					}
				}
			}
			else
			{
				echo "<center><h2>Date invalide, va rugam reintroduceti datele</h2></center>";
			}
		}
		?>
	</body>
</html>