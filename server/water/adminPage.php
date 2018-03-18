<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">


	<link rel="stylesheet" type="text/css" href="adminPageStyle.css">
	<title>관리자 페이지</title>
</head>


<body>
	<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <h2>관리자 페이지</h2>
        </div>
      </div>
	</nav>

	<div class="container-fluid">
      
      <div class="row row-offcanvas row-offcanvas-left">
        
         <div class="col-sm-3 col-md-2 sidebar-offcanvas" id="sidebar" role="navigation">
         	<div class="list-group">
  				<form method="post" action="adminPage.php">
           		<button class="list-group-item active" style="width: 180px" name="selectOverview" id="overview">Overview</button>
           		<button class="list-group-item" style="width: 180px" name="selectLocation">Location DB</button>
           		<button class="list-group-item" style="width: 180px" name="selectStation">Station DB</button>
           		<button class="list-group-item" style="width: 180px" name="selectDatapoint">Datapoint DB</button>
           		<button class="list-group-item" style="width: 180px" name="selectCriteria">Criteria DB</button>
           	</form>
			</div>
           
        </div><!--/span-->
        
        <div class="col-sm-9 col-md-10 main">
          
          <!--toggle sidebar button-->
          <p class="visible-xs">
            <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas"><i class="glyphicon glyphicon-chevron-left"></i></button>
          </p>
          
		 <!--  <h1 class="page-header">
            데이터 베이스
          </h1> -->
<?php
include "connectDB.php";

if(isset($_POST["locationSubmit"]) && $_POST["locationSearch"] != ''){
	$locationSearch = $_POST["locationSearch"];
	$stationSql = "SELECT * FROM station;";
	$locationSql = "SELECT * FROM location WHERE idlocation='$locationSearch' or location1='$locationSearch' or location2='$locationSearch' or location3='$locationSearch' or location4='$locationSearch';";
	$datapointSql = "SELECT * FROM datapoint";
	$criteriaSql = "SELECT * FROM criteria";


	$stationResult = mysqli_query($DB_Link, $stationSql);
	$locationResult = mysqli_query($DB_Link, $locationSql);
	$datapointResult = mysqli_query($DB_Link, $datapointSql);
	$criteriaResult = mysqli_query($DB_Link, $criteriaSql);

}
else if(isset($_POST["stationSubmit"]) && $_POST["stationSearch"] != ''){
	$stationSearch = $_POST["stationSearch"];
	$stationSql = "SELECT * FROM station WHERE idstation='$stationSearch' or `desc`='$stationSearch' or idlocation='$stationSearch';";
	$locationSql = "SELECT * FROM location;";
	$datapointSql = "SELECT * FROM datapoint";
	$criteriaSql = "SELECT * FROM criteria";


	$stationResult = mysqli_query($DB_Link, $stationSql);
	$locationResult = mysqli_query($DB_Link, $locationSql);
	$datapointResult = mysqli_query($DB_Link, $datapointSql);
	$criteriaResult = mysqli_query($DB_Link, $criteriaSql);
}
else if(isset($_POST["datapointSubmit"]) && $_POST["datapointSearch"] != ''){
	$datapointSearch = $_POST["datapointSearch"];

	$stationSql = "SELECT * FROM station;";
	$locationSql = "SELECT * FROM location;";
	$datapointSql = "SELECT * FROM datapoint WHERE iddatapoint='$datapointSearch' or timestamp='$datapointSearch' or value='$datapointSearch' or flag='$datapointSearch' or idstation='$datapointSearch' or idcriteria='$datapointSearch';";
	$criteriaSql = "SELECT * FROM criteria";


	$stationResult = mysqli_query($DB_Link, $stationSql);
	$locationResult = mysqli_query($DB_Link, $locationSql);
	$datapointResult = mysqli_query($DB_Link, $datapointSql);
	$criteriaResult = mysqli_query($DB_Link, $criteriaSql);
}
else if(isset($_POST["criteriaSubmit"]) && $_POST["criteriaSearch"] != ''){
	$criteriaSearch = $_POST["criteriaSearch"];

	$stationSql = "SELECT * FROM station;";
	$locationSql = "SELECT * FROM location;";
	$datapointSql = "SELECT * FROM datapoint";
	$criteriaSql = "SELECT * FROM criteria WHERE idcriteria='$criteriaSearch' or value1='$criteriaSearch' or value2='$criteriaSearch' or `desc`='$criteriaSearch' or unit='$criteriaSearch';";


	$stationResult = mysqli_query($DB_Link, $stationSql);
	$locationResult = mysqli_query($DB_Link, $locationSql);
	$datapointResult = mysqli_query($DB_Link, $datapointSql);
	$criteriaResult = mysqli_query($DB_Link, $criteriaSql);
}
else{
	$stationSql = "SELECT * FROM station;";
	$locationSql = "SELECT * FROM location;";
	$datapointSql = "SELECT * FROM datapoint";
	$criteriaSql = "SELECT * FROM criteria";


	$stationResult = mysqli_query($DB_Link, $stationSql);
	$locationResult = mysqli_query($DB_Link, $locationSql);
	$datapointResult = mysqli_query($DB_Link, $datapointSql);
	$criteriaResult = mysqli_query($DB_Link, $criteriaSql);

	if(!isset($_POST["selectLocation"]) && !isset($_POST["selectStation"]) && !isset($_POST["selectDatapoint"]) && !isset($_POST["selectCriteria"])){
		$_POST["selectOverview"] = 1;
	}
}


if(mysqli_num_rows($locationResult) && (isset($_POST["selectLocation"]) || isset($_POST["selectOverview"]) || isset($_POST["locationSubmit"]))){

?>
	<h2>Location DB</h2>
		<form method="post" action="insertPage.php">
		<input type="submit" name="locationInsert" value="추가" class="btn btn-success">
	</form>
	<div class="form-group mb-2">
	<form method="post" action="adminPage.php" class="form-inline">
		<input type="text" name="locationSearch" id="locationSearch" class="form-control">
		<div class="mx-2">
		<input type="submit" name="locationSubmit" value="검색" class="btn btn-primary">
		</div>
	</form>
	</div>

	<table width="810px" class="table table-bordered table-hover table-striped">
		<thead class="thead-dark">
		<tr align="center">
			<th width="230px">idlocation</th>
			<th width="230px">location1</th>
			<th width="230px">location2</th>
			<th width="230px">location3</th>
			<th width="230px">location4</th>
			<th width="50px">options</th>
		</tr>
		</thead>
	<?php
		while ($input = mysqli_fetch_row($locationResult)) {
	?>
		<tr>
			<td align="center"><?=$input[0];?></td>
			<td align="center"><?=$input[1];?></td>
			<td align="center"><?=$input[2];?></td>
			<td align="center"><?=$input[3];?></td>
			<td align="center"><?=$input[4];?></td>
			<td align="center">
				<a type="button" class="btn btn-info" href="./modifyPage.php?table=location&idlocation=<?=$input[0];?>&location1=<?=$input[1];?>&location2=<?=$input[2];?>&location3=<?=$input[3];?>&location4=<?=$input[4];?>" style="text-decoration:none; color: white;">수정</a>
				<a type="button" class="btn btn-danger" href="./deleteAction.php?table=location&idlocation=<?=$input[0];?>" style="text-decoration:none; color: white;">삭제</a>
			</td>
		</tr>
<?php
	}
}
?>
	</table>



<?php
	if(mysqli_num_rows($stationResult) && (isset($_POST["selectStation"]) || isset($_POST["selectOverview"]) || isset($_POST["stationSubmit"]))){
?>
<h2>Station DB</h2>
	<form method="post" action="insertPage.php">
		<input type="submit" name="stationInsert" value="추가" class="btn btn-success">
	</form>
	<div class="form-group mb-2">	
	<form method="post" action="adminPage.php" class="form-inline">
		<input type="text" name="stationSearch" id="stationSearch" class="form-control">
		<div class="mx-2">
		<input type="submit" name="stationSubmit" value="검색" class="btn btn-primary">
		</div>
	</form>

	<table width="810px" class="table table-bordered table-hover table-striped">
		<thead class="thead-dark">
		<tr align="center">
			<th width="250px">idstation</th>
			<th width="650px">desc</th>
			<th width="250px">idlocation</th>
			<th width="50px">options</th>
		</tr>
	</thead>

	<?php
		while ($input = mysqli_fetch_row($stationResult)) {
	?>
		<tr>
			<td align="center"><?=$input[0];?></td>
			<td align="center"><?=$input[1];?></td>
			<td align="center"><?=$input[2];?></td>
			<td align="center">
				<a type="button" class="btn btn-info" href="./modifyPage.php?table=station&idstation=<?=$input[0];?>&descr=<?=$input[1];?>&idlocation=<?=$input[2];?>" style="text-decoration:none; color: white;">수정</a>
				<a type="button" class="btn btn-danger" href="./deleteAction.php?table=station&idstation=<?=$input[0];?>" style="text-decoration:none; color: white;">삭제</a>
			</td>
		</tr>
<?php
	}
}
?>
	</table>

<?php
	if(mysqli_num_rows($datapointResult) && (isset($_POST["selectDatapoint"]) || isset($_POST["selectOverview"]) || isset($_POST["datapointSubmit"]))){
?>
	<h2>Datapoint DB</h2>
		<form method="post" action="insertPage.php">
			<input type="submit" name="datapointInsert" value="추가" class="btn btn-success">
		</form>
	<form method="post" action="adminPage.php" class="form-inline">
		<input type="text" name="datapointSearch" id="locationSearch" class="form-control">
		<div class="mx-2">
		<input type="submit" name="datapointSubmit" value="검색" class="btn btn-primary">
	</div>
	</form>
	<table width="810px" class="table table-bordered table-hover table-striped">
		<thead class="thead-dark">
		<tr align="center">
			<th width="200px">iddatapoint</th>
			<th width="200px">timestamp</th>
			<th width="200px">value</th>
			<th width="100px">flag</th>
			<th width="200px">idstation</th>
			<th width="200px">idcriteria</th>
			<th width="50px">options</th>
		</tr>
	</thead>

	<?php
		while ($input = mysqli_fetch_row($datapointResult)) {
	?>
		<tr>
			<td align="center"><?=$input[0];?></td>
			<td align="center"><?=$input[1];?></td>
			<td align="center"><?=$input[2];?></td>
			<td align="center"><?=$input[3];?></td>
			<td align="center"><?=$input[4];?></td>
			<td align="center"><?=$input[5];?></td>
			<td align="center">
				<a type="button" class="btn btn-info" href="./modifyPage.php?table=datapoint&iddatapoint=<?=$input[0];?>&timestamp=<?=$input[1];?>&value=<?=$input[2];?>&flag=<?=$input[3];?>&idstation=<?=$input[4];?>&idcriteria=<?=$input[5];?>"style="text-decoration:none; color: white;">수정</a>
				<a type="button" class="btn btn-danger" href="./deleteAction.php?table=datapoint&iddatapoint=<?=$input[0];?>" style="text-decoration:none; color: white;">삭제</a>
			</td>
		</tr>
<?php
	}
}
?>
	</table>


<?php
	if(mysqli_num_rows($criteriaResult) && (isset($_POST["selectCriteria"]) || isset($_POST["selectOverview"]) || isset($_POST["criteriaSubmit"]) )){
?>
	<h2>Criteria DB</h2>
		<form method="post" action="insertPage.php">
			<input type="submit" name="criteriaInsert" value="추가" class="btn btn-success">
		</form>
	<form method="post" action="adminPage.php" class="form-inline">
		<input type="text" name="criteriaSearch" id="locationSearch" class="form-control">
		<div class="mx-2">
		<input type="submit" name="criteriaSubmit" value="검색" class="btn btn-primary">
	</div>
	</form>
	<table width="810px" class="table table-bordered table-hover table-striped">
		<thead class="thead-dark">
		<tr align="center">
			<th width="250px">idcriteria</th>
			<th width="200px">value1</th>
			<th width="200px">value2</th>
			<th width="250px">desc</th>
			<th width="200px">unit</th>
			<th width="50px">options</th>
		</tr>
		</thead>

	<?php
		while ($input = mysqli_fetch_row($criteriaResult)) {
	?>
		<tr>
			<td align="center"><?=$input[0];?></td>
			<td align="center"><?=$input[1];?></td>
			<td align="center"><?=$input[2];?></td>
			<td align="center"><?=$input[3];?></td>
			<td align="center"><?=$input[4];?></td>
			<td align="center">
				<a type="button" class="btn btn-info" href="./modifyPage.php?table=criteria&idcriteria=<?=$input[0];?>&value1=<?=$input[1];?>&value2=<?=$input[2];?>&descr=<?=$input[3];?>&unit=<?=$input[4];?>" style="text-decoration:none; color: white;">수정</a>
				<a type="button" class="btn btn-danger" href="./deleteAction.php?table=criteria&idcriteria=<?=$input[0];?>" style="text-decoration:none; color: white;">삭제</a>
			</td>
		</tr>
<?php
	}
}
?>
	</table>
</div>
</div>
</div>
</div>
</body>
</html>


<script type="text/javascript">
	function search(){
		location.href="search.php";
	}
</script>