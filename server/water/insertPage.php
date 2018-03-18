<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
	<title>데이터 추가</title>
</head>

<body>



<?php
	include "connectDB.php";

	if(isset($_POST['submit']) && isset($_POST["location"])){
		$idlocation = $_POST["idlocation"];
		$location1 = $_POST["location1"];
		$location2 = $_POST["location2"];
		$location3 = $_POST["location3"];
		$location4 = $_POST["location4"];

		$sql = "INSERT INTO location VALUES('$idlocation', '$location1', '$location2', '$location3', '$location4');";

		mysqli_query($DB_Link, $sql);

		?>
		<script type="text/javascript">
			location.href="adminPage.php";
		</script>

<?php
}
	else if(isset($_POST['submit']) && isset($_POST["station"])){
		$idstation = $_POST["idstation"];
		$descr = $_POST["descr"];
		$idlocation = $_POST["idlocation"];

		$sql = "INSERT INTO station VALUES('$idstation', '$descr', '$idlocation');";

		mysqli_query($DB_Link, $sql);
		?>
		<script type="text/javascript">
			location.href="adminPage.php";
		</script>
<?php
	}
	else if(isset($_POST['submit']) && isset($_POST["datapoint"])){
		$iddatapoint = $_POST["iddatapoint"];
		$timestamp = date('ymd-H:i');
		$value = $_POST["value"];
		$flag = $_POST["flag"];
		$idstation = $_POST["idstation"];
		$idcriteria = $_POST["idcriteria"];

		$sql = "INSERT INTO datapoint VALUES('$iddatapoint', '$timestamp', '$value', '$flag', '$idstation', '$idcriteria');";

		mysqli_query($DB_Link, $sql);
		// echo $timestamp;
		?>

		<script type="text/javascript">
			location.href="adminPage.php";
		</script>
<?php
	}
	else if(isset($_POST['submit']) && isset($_POST["criteria"])){

		if($_POST["value2"] == ''){
			$_POST["value2"] = "null";
		}

		$idcriteria = $_POST["idcriteria"];
		$value1 = $_POST["value1"];
		$value2 = $_POST["value2"];
		$descr = $_POST["descr"];
		$unit = $_POST["unit"];

		$sql = "INSERT INTO criteria VALUES('$idcriteria', '$value1', $value2, '$descr', '$unit');";

		mysqli_query($DB_Link, $sql);
		?>


		<script type="text/javascript">
			location.href="adminPage.php";
		</script>
<?php
	}
	if(isset($_POST['locationInsert'])){
?>
	<section>
		<form method="post" action="insertPage.php" onsubmit="return checkInsertedLocation()">
			<table border="1" width="450" cellspacing="0" cellpadding="3">
				<tr>
					<td colspan="2" align="center"><font size="5">Location 데이터 추가</td>
				</tr>
				<tr>
					<td><font color="red">*</font>idlocation</td>
					<td><input type="text" name="idlocation" id="idlocation"></td>
				</tr>
				<tr>
					<td><font color="red">*</font>location1</td>
					<td><input type="text" name="location1" id="location1"></td>
				</tr>
				<tr>    
					<td>location2</td>
					<td><input type="text" name="location2" id="location2" "></td>
				</tr>
				<tr>
					<td>location3</td>
					<td><input type="text" name="location3" id="location3"></td>
				</tr>
				<tr>
					<td>location4</td>
					<td><input type="text" name="location4" id="location4"></td>
				</tr>
				<tr>
					<td colspan="2" align="center">
					<input class="btn btn-info" type="submit" name="submit" value="확인" >
					<input type="hidden" name="location">
					<a type="button" class="btn btn-danger" href="./adminPage.php" style="text-decoration:none; color: white;">취소</a>
				</tr>
			</table>
		</form>
	</section>

<?php
	}
	else if(isset($_POST['stationInsert'])){
?>
	<section>
		<form method="post" action="insertPage.php" onsubmit="return checkInsertedStation()">
			<table border="1" width="450" cellspacing="0" cellpadding="3">
				<tr>
					<td colspan="2" align="center"><font size="5">Station 데이터 추가</td>
				</tr>
				<tr>
					<td><font color="red">*</font>idstation</td>
					<td><input type="text" name="idstation" id="idstation"></td>
				</tr>
				<tr>
					<td>descr</td>
					<td><input type="text" name="descr" id="descr"></td>
				</tr>
				<tr>    
					<td>idlocation</td>
					<td><input type="text" name="idlocation" id="idlocation"></td>
				</tr>
				<tr>
					<td colspan="2" align="center">
					<input class="btn btn-info" type="submit" name="submit" value="확인">
					<input type="hidden" name="station">
					<a type="button" class="btn btn-danger" href="./adminPage.php" style="text-decoration:none; color: white;">취소</a>
				</tr>
			</table>
		</form>
		
	</section>
	

<?php	
	}
	else if(isset($_POST['datapointInsert'])){	
?>
	<section>
		<form method="post" action="insertPage.php" onsubmit="return checkInsertedDatapoint()">
			<table border="1" width="450" cellspacing="0" cellpadding="3">
				<tr>
					<td colspan="2" align="center"><font size="5">Datapoint 데이터 추가</td>
				</tr>
				<tr>
					<td><font color="red">*</font>iddatapoint</td>
					<td><input type="text" name="iddatapoint" id="iddatapoint"></td>
				</tr>
				<tr>    
					<td><font color="red">*</font>value</td>
					<td><input type="text" name="value" id="value"></td>
				</tr>
				<tr>    
					<td><font color="red">*</font>flag</td>
					<td><input type="text" name="flag" id="flag"></td>
				</tr>
				<tr>    
					<td><font color="red">*</font>idstation</td>
					<td><input type="text" name="idstation" id="idstation"></td>
				</tr>
				<tr>    
					<td><font color="red">*</font>idcriteria</td>
					<td><input type="text" name="idcriteria" id="idcriteria"></td>
				</tr>
				<tr>
					<td colspan="2" align="center">
					<input class="btn btn-info" type="submit" name="submit" value="확인">
					<input type="hidden" name="datapoint">
					<a type="button" class="btn btn-danger" href="./adminPage.php" style="text-decoration:none; color: white;">취소</a>
				</tr>
			</table>
		</form>
		
	</section>

<?php
	}
	else if(isset($_POST['criteriaInsert'])){
?>
	<section>
		<form method="post" action="insertPage.php" onsubmit="return checkInsertedCriteria()">
			<table border="1" width="450" cellspacing="0" cellpadding="3">
				<tr>
					<td colspan="2" align="center"><font size="5">Criteria 데이터 추가</td>
				</tr>
				<tr>
					<td><font color="red">*</font>idcriteria</td>
					<td><input type="text" name="idcriteria" id="idcriteria"></td>
				</tr>
				<tr>
					<td><font color="red">*</font>value1</td>
					<td><input type="text" name="value1" id="value1"></td>
				</tr>
				<tr>    
					<td>value2</td>
					<td><input type="text" name="value2" id="value2"></td>
				</tr>
				<tr>    
					<td>descr</td>
					<td><input type="text" name="descr" id="descr"></td>
				</tr>
				<tr>    
					<td><font color="red">*</font>unit</td>
					<td><input type="text" name="unit" id="unit"></td>
				</tr>
				<tr>
					<td colspan="2" align="center">
					<input class="btn btn-info" type="submit" name="submit" value="확인">
					<input type="hidden" name="criteria">
					<a type="button" class="btn btn-danger" href="./adminPage.php" style="text-decoration:none; color: white;">취소</a>
				</tr>
			</table>
		</form>
		
	</section>

<?php
	}
?>

</body>
</html>

<script type="text/javascript">
	function checkInsertedLocation(){
		if(document.getElementById('idlocation').value == ""){
			alert("Please Insert the idlocation");
			document.getElementById('idlocation').focus();
			return false;
		}

		if(document.getElementById('location1').value == ""){
			alert("Please Insert location 1");
			document.getElementById('location1').focus();
			return false;
		}
	}

	function checkInsertedStation(){
		if(document.getElementById('idstation').value == ""){
			alert("Please Insert the idstation");
			document.getElementById('idstation').focus();
			return false;
		}

		if(document.getElementById('idlocation').value == ""){
			alert("Please Insert idlocation");
			document.getElementById('idlocation').focus();
			return false;
		}
	}

	function checkInsertedDatapoint(){
		if(document.getElementById('iddatapoint').value == ""){
			alert("Please Insert the iddatapoint");
			document.getElementById('iddatapoint').focus();
			return false;
		}

		if(document.getElementById('value').value == ""){
			alert("Please Insert value");
			document.getElementById('value').focus();
			return false;
		}

		if(document.getElementById('flag').value == ""){
			alert("Please Insert flag");
			document.getElementById('flag').focus();
			return false;
		}

		if(document.getElementById('idstation').value == ""){
			alert("Please Insert idstation");
			document.getElementById('idstation').focus();
			return false;
		}

		if(document.getElementById('idcriteria').value == ""){
			alert("Please Insert idcriteria");
			document.getElementById('idcriteria').focus();
			return false;
		}
	}

	function checkInsertedCriteria(){
		if(document.getElementById('idcriteria').value == ""){
			alert("Please Insert the idcriteria");
			document.getElementById('idcriteria').focus();
			return false;
		}

		if(document.getElementById('value1').value == ""){
			alert("Please Insert value1");
			document.getElementById('value1').focus();
			return false;
		}

		if(document.getElementById('unit').value == ""){
			alert("Please Insert unit");
			document.getElementById('unit').focus();
			return false;
		}
	}


</script>