<?php

include "connectDB.php";

$table = $_GET["table"];

if($table == "location"){
	$idlocation = $_GET["idlocation"];

	$sql = "DELETE FROM $table WHERE (idlocation = '$idlocation');";
	$result = mysqli_query($DB_Link, $sql);
	
}
else if($table == "station"){
	$idstation = $_GET["idstation"];

	$sql = "DELETE FROM $table WHERE (idstation = '$idstation');";
	$result = mysqli_query($DB_Link, $sql);
}
else if($table == "datapoint"){
	$iddatapoint = $_GET["iddatapoint"];

	$sql = "DELETE FROM $table WHERE (iddatapoint = '$iddatapoint');";
	$result = mysqli_query($DB_Link, $sql);
}
else if($table == "criteria"){
	$idcriteria= $_GET["idcriteria"];

	$sql = "DELETE FROM $table WHERE (idcriteria = '$idcriteria');";
	$result = mysqli_query($DB_Link, $sql);
}

if(!$result){
		print(mysqli_error($DB_Link));
	}

	mysqli_close($DB_Link);
?>
<script type="text/javascript">
location.href="adminPage.php";
</script>