<?php session_start();

if (isset ($_GET['csrf']) && $_GET['csrf'] == $_SESSION['token'])
{
	$Users = file_get_contents("./res/cos.txt");
	$UsersArray = json_decode($Users, true);
	for ($x  = 0; $x < count($UsersArray); $x++)
	{
		if ($UsersArray[$x]['Nume'] ==  $_SESSION['user'])
		{
			$UsersArray[$x]['Cos'] = $_SESSION['cos'];
		}
	}	
	file_put_contents("./res/cos.txt", json_encode($UsersArray));
    $_SESSION = array();
    session_destroy();
}
header('Location: MainPage.php');	
?>