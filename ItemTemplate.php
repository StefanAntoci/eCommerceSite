<?php
session_start();
include 'DatabaseConn.php';
include 'menuBar.php';
echo "<body onload='showSlides(1)'>";
$id = 1;
$_SESSION['item_id'] = $_GET['id'];
$_SESSION['item_name'] = $_GET['data'];

$_SESSION['buy_option'] = 1;
$_SESSION['save_option'] = 1;

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
	echo "<div style='width:20%; float:left'><h2 id='MesajBunVenit'>Bun Venit, ".$_SESSION['user']."</h2>";
	echo "<form  action='logout.php'>
	<input class='MenuElement' type = 'submit' value='Delogare'/>
	</form>
	<form action='modifica.php' method='get'>
	<input type = 'hidden' name='id' value = '".$_GET['id']."'>
	<input class='MenuElement' type = 'submit' value='Adauga in/Scoate din Cos' style='width:280px'/>
	</form>
	<form action='Portofel.php' method='get'>
	<input type = 'hidden' name='id' value = '".$_GET['id']."'>
	<input class='MenuElement' type = 'submit' value='Procura produsul' style='width:280px'/>
	</form>
	</div>";
	echo "<form action = 'cos.php'>
	<input  class= 'Cos' type = 'submit' value = 'Cosul meu'/>
	</form>";
}

echo "<center><h1 class='NumeProduse'>".$_GET['data']."</h1></center><br/><br/>";
echo "<div class='Container'>";
	$pictures_folder = "./imagini/".$_GET['data'];
	$pictures = scandir($pictures_folder);
	for ($x = 2; $x < count($pictures);$x++)
	{
		echo "<div class='Poze'>
				<img class='BigPhotos' src='".$pictures_folder."/".$pictures[$x]."' style='width:50%'>
			  </div>";	
	}

	echo "<a class='prev' onclick='plusSlides(-1);'>&#10094;</a>";
	echo "<a class='next' onclick='plusSlides(1);'>&#10095;</a>";

	echo "<div class='caption-container'>
			<p id='caption'></p>
		  </div>";

	echo "<div class='column'>";
		for ($x = 2; $x < count($pictures); $x++) 
		{
			echo "<img class='demo cursor' src='".$pictures_folder."/".$pictures[$x]."' style='width:30%' onclick='currentSlide(".($x - 1).");'>";
		}
	echo "</div>";
echo "</div>";


echo "<div class='Specificatii'>";
		echo "<center><h1 id='Specificatii'>Specificatii:</center>";
$sql_get_item_desc = 'BEGIN ITEM_INFO(:item_name, :item_id, :item_desc, :item_price); END;';
$get_item_desc = oci_parse($conn, $sql_get_item_desc);
oci_bind_by_name($get_item_desc, ':item_name',$_GET['data'],300);
oci_bind_by_name($get_item_desc, ':item_id', $item_id,10);
oci_bind_by_name($get_item_desc, ':item_desc', $item_desc, 300);
oci_bind_by_name($get_item_desc, ':item_price', $item_price,100);
oci_execute($get_item_desc);

if ($item_id == -1)
{
	echo $item_desc;
}
else
{
	$specs = explode(",",$item_desc);
	for ($x = 0; $x < count($specs);$x++)
	{
		echo "<center>".$specs[$x]."</center><br/><br/>";
	}
	echo "<center> Pret: ".$item_price."</center><br/><br/>";
}
//print_r($specs);
echo "</body>";
?>