<?php session_start();
include 'DatabaseConn.php'; 
$flag = 0;

 $cust_cursor = oci_new_cursor($conn);
 $login_stmt = oci_parse($conn, 'begin check_login(:cust_cursor); end;');
 //echo "Login statement\n";
 oci_bind_by_name($login_stmt,":cust_cursor",$cust_cursor, -1, OCI_B_CURSOR);
 //oci_bind_by_name($login_stmt,":temp_usr", $temp_usr);
 //oci_bind_by_name($login_stmt,":temp_pass", $temp_pass);
 //echo "Variables were binded";
 
 oci_execute($login_stmt);
 oci_execute($cust_cursor);
 //echo "queries are executed\n";
 
 
 while( ($row = oci_fetch_array($cust_cursor, OCI_ASSOC)) != false)
 {
	if ( ($_POST['user'] == $row['USERNAME']) && ($_POST['password'] == $row['PASSWORD']) )
	{
		$_SESSION['user'] = $_POST['user'];
		$_SESSION['cust_id'] = $row['CUSTOMERID'];
		$flag = 1;
	}
 }

 
if ($flag == 0)
{		
	$_SESSION['eroare'] = "Eroare nume utilizator/parola invalide";	
}

/* if ($flag != 0)
{
	$Cos = file_get_contents("./res/cos.txt");
	$CosArray = json_decode($Cos, true);
	for ($x = 0; $x < count($CosArray); $x++)
	{
		if ($_SESSION['user'] == $CosArray[$x]['Nume'])
		{
			$_SESSION['cos'] = array();
			$_SESSION['cos'] = $CosArray[$x]['Cos'];
		}
	}
} */

header("Location: ".$_SERVER["HTTP_REFERER"]);	
?>
