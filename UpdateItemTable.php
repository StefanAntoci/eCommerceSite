<?php session_start();
include 'DatabaseConn.php';
include 'menuBar.php'; 
?>
<html>
	<head>
			<script type="text/javascript" src = "js/script.js"></script>
	</head>
	<body>
		<div style='margin-top:50px;'>
		<center>
			<form action = 'UpdateItemForm.php'>
				<input class='MenuElement' type = 'submit' style='width:150px;' value = 'Update item'/>
			</form>
			
			<form action = 'InsertItemDB.php'>
				<input class='MenuElement' type = 'submit' style='width:150px;' value = 'Insert item'/>	
			</form>
		</center>
		</div>
	</body>
</html>