<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
	<title>데이터 수정</title>
</head>

<?php
	include "connectDB.php";
	if(isset($_POST['submit']) && isset($_POST['location'])){
		$oldID = $_POST["oldID"];
		$newID = $_POST["idlocation"];
		$location1 = $_POST["location1"];
		$location2 = $_POST["location2"];
		$location3 = $_POST["location3"];
		$location4 = $_POST["location4"];

		$sql = "UPDATE location set idlocation='$newID', location1='$location1', location2='$location2', location3='$location3', location4='$location4' WHERE (idlocation = '$oldID');";
		$result = mysqli_query($DB_Link, $sql);

		if(!$result){
			print(mysqli_error($DB_Link));
		}

		?>
		<script type="text/javascript">
			location.href="adminPage.php";
		</script>
		
<?php
	}
	else if(isset($_POST['submit']) && isset($_POST['station'])){
		$oldID = $_POST["oldID"];
		$newID = $_POST["idstation"];
		$descr = $_POST["descr"];
		$idlocation = $_POST["idlocation"];

		$sql = "UPDATE station set idstation='$newID', `desc`='$descr', idlocation='$idlocation' WHERE (idstation = '$oldID');";
		$result = mysqli_query($DB_Link, $sql);

		if(!$result){
			print(mysqli_error($DB_Link));
		}
		?>
		<script type="text/javascript">
			location.href="adminPage.php";
		</script>

<?php
	}
	else if(isset($_POST['submit']) && isset($_POST['datapoint'])){
		$oldID = $_POST["oldID"];
		$iddatapoint = $_POST["iddatapoint"];
		$timestamp = date('ymd-H:i');;
		$value = $_POST["value"];
		$flag = $_POST["flag"];
		$idstation = $_POST["idstation"];
		$idcriteria = $_POST["idcriteria"];

		$sql = "UPDATE datapoint set iddatapoint='$iddatapoint', timestamp='$timestamp', value='$value', flag='$flag', idstation='$idstation', idcriteria='$idcriteria' WHERE (iddatapoint = '$oldID');";
		$result = mysqli_query($DB_Link, $sql);

		if(!$result){
			print(mysqli_error($DB_Link));
		}
		?>
		<script type="text/javascript">
			location.href="adminPage.php";
		</script>

<?php
	}
	else if(isset($_POST['submit']) && isset($_POST['criteria'])){


		if($_POST["value2"] == ''){
			$_POST["value2"] = "null";
		}

		$oldID = $_POST["oldID"];
		$newID = $_POST["idcriteria"];
		$value1 = $_POST["value1"];
		$value2 = $_POST["value2"];
		$descr = $_POST["descr"];
		$unit = $_POST["unit"];

		$sql = "UPDATE criteria set idcriteria='$newID', value1='$value1', value2=$value2, `desc`='$descr', unit='$unit' WHERE (idcriteria = '$oldID');";
		$result = mysqli_query($DB_Link, $sql);

		if(!$result){
			print(mysqli_error($DB_Link));
		}
	?>
		<script type="text/javascript">
			location.href="adminPage.php";
		</script>

<?php
	}


	mysqli_close($DB_Link);

?>	

<body>



<?php
	include "connectDB.php";

	if($_GET['table'] == "location"){
?>
	<section>
		<form method="post" action="modifyPage.php" onsubmit="return checkInsertedLocation()">
			<table border="1" width="450" cellspacing="0" cellpadding="3">
				<tr>
					<td colspan="2" align="center"><font size="5">Location 데이터 수정</td>
				</tr>
				<tr>
					<td><font color="red">*</font>idlocation</td>
					<td><input type="text" name="idlocation" id="idlocation" value="<?=$_GET["idlocation"];?>"></td>
				</tr>
				<tr>
					<td><font color="red">*</font>location1</td>
					<td><input type="text" name="location1" id="location1" value="<?=$_GET["location1"];?>"></td>
				</tr>
				<tr>    
					<td>location2</td>
					<td><input type="text" name="location2" id="location2" value="<?=$_GET["location2"];?>"></td>
				</tr>
				<tr>
					<td>location3</td>
					<td><input type="text" name="location3" id="location3" value="<?=$_GET["location3"];?>"></td>
				</tr>
				<tr>
					<td>location4</td>
					<td><input type="text" name="location4" id="location4" value="<?=$_GET["location4"];?>"></td>
				</tr>
				<tr>
					<td colspan="2" align="center">
					<input type="submit" name="submit" value="확인" class="btn btn-info">
					<input type="hidden" name="location">
					<input type="hidden" name="oldID" value="<?=$_GET["idlocation"];?>">
					<a type="button" class="btn btn-danger" href="./adminPage.php" style="text-decoration:none; color: white;">취소</a>
				</tr>
			</table>
		</form>
	</section>

<?php
	}
	else if($_GET['table'] == "station"){
?>
	<section>
		<form method="post" action="modifyPage.php" onsubmit="return checkInsertedStation()">
			<table border="1" width="450" cellspacing="0" cellpadding="3">
				<tr>
					<td colspan="2" align="center"><font size="5">Station 데이터 수정</td>
				</tr>
				<tr>
					<td><font color="red">*</font>idstation</td>
					<td><input type="text" name="idstation" id="idstation" value="<?=$_GET["idstation"];?>"></td>
				</tr>
				<tr>
					<td>descr</td>
					<td><input type="text" name="descr" id="descr" value="<?=$_GET["descr"];?>"></td>
				</tr>
				<tr>    
					<td>idlocation</td>
					<td><input type="text" name="idlocation" id="idlocation" value="<?=$_GET["idlocation"];?>"></td>
				</tr>
				<tr>
					<td colspan="2" align="center">
					<input type="submit" name="submit" value="확인" class="btn btn-info">
					<input type="hidden" name="station">
					<input type="hidden" name="oldID" value="<?=$_GET["idstation"];?>">
					<a type="button" class="btn btn-danger" href="./adminPage.php" style="text-decoration:none; color: white;">취소</a>
				</tr>
			</table>
		</form>
		
	</section>
	

<?php	
	}
	else if($_GET['table'] == "datapoint"){	
?>
	<section>
		<form method="post" action="modifyPage.php" onsubmit="return checkInsertedDatapoint()">
			<table border="1" width="450" cellspacing="0" cellpadding="3">
				<tr>
					<td colspan="2" align="center"><font size="5">Datapoint 데이터 수정</td>
				</tr>
				<tr>
					<td><font color="red">*</font>iddatapoint</td>
					<td><input type="text" name="iddatapoint" id="iddatapoint" value="<?=$_GET["iddatapoint"];?>"></td>
				</tr>
				<tr>    
					<td><font color="red">*</font>value</td>
					<td><input type="text" name="value" id="value" value="<?=$_GET["value"];?>"></td>
				</tr>
				<tr>    
					<td><font color="red">*</font>flag</td>
					<td><input type="text" name="flag" id="flag" value="<?=$_GET["flag"];?>"></td>
				</tr>
				<tr>    
					<td><font color="red">*</font>idstation</td>
					<td><input type="text" name="idstation" id="idstation" value="<?=$_GET["idstation"];?>"></td>
				</tr>
				<tr>    
					<td><font color="red">*</font>idcriteria</td>
					<td><input type="text" name="idcriteria" id="idcriteria" value="<?=$_GET["idcriteria"];?>"></td>
				</tr>
				<tr>
					<td colspan="2" align="center">
					<input type="submit" name="submit" value="확인" class="btn btn-info">
					<input type="hidden" name="datapoint">
					<input type="hidden" name="oldID" value="<?=$_GET["iddatapoint"];?>">
					<a type="button" class="btn btn-danger" href="./adminPage.php" style="text-decoration:none; color: white;">취소</a>
				</tr>
			</table>
		</form>
		
	</section>

<?php
	}
	else if($_GET['table'] == "criteria"){
?>
	<section>
		<form method="post" action="modifyPage.php" onsubmit="return checkInsertedCriteria()">
			<table border="1" width="450" cellspacing="0" cellpadding="3">
				<tr>
					<td colspan="2" align="center"><font size="5">Criteria 데이터 수정</td>
				</tr>
				<tr>
					<td><font color="red">*</font>idcriteria</td>
					<td><input type="text" name="idcriteria" id="idcriteria" value="<?=$_GET["idcriteria"];?>"></td>
				</tr>
				<tr>
					<td><font color="red">*</font>value1</td>
					<td><input type="text" name="value1" id="value1" value="<?=$_GET["value1"];?>"></td>
				</tr>
				<tr>    
					<td>value2</td>
					<td><input type="text" name="value2" id="value2" value="<?=$_GET["value2"];?>"></td>
				</tr>
				<tr>    
					<td>descr</td>
					<td><input type="text" name="descr" id="descr" value="<?=$_GET["descr"];?>"></td>
				</tr>
				<tr>    
					<td><font color="red">*</font>unit</td>
					<td><input type="text" name="unit" id="unit" value="<?=$_GET["unit"];?>"></td>
				</tr>
				<tr>
					<td colspan="2" align="center">
					<input type="submit" name="submit" value="확인" class="btn btn-info">
					<input type="hidden" name="criteria">
					<input type="hidden" name="oldID" value="<?=$_GET["idcriteria"];?>">
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

		if(document.getElementById('timestamp').value == ""){
			alert("Please Insert timestamp");
			document.getElementById('timestamp').focus();
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