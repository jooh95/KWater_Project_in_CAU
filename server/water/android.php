<?php
include "connectDB.php";

header('Content-Type: application/json; charset=utf-8');

$result = array();

if (isset($_GET['start']) && isset($_GET['end'])) {
	if (!(isset($_GET['idlocation']) || isset($_GET['criteria']))) {
		badRequest();
	}
	$idlocation = $_GET['idlocation'];
	$criteria = $_GET['criteria'];
	$start_time = $_GET['start'];
	$end_time = $_GET['end'];
	
	$sql = "SELECT `timestamp`, `value` FROM datapoint WHERE idcriteria = (SELECT idcriteria FROM criteria WHERE `desc` = '$criteria') and idstation IN (SELECT idstation FROM station WHERE idlocation = '$idlocation') and `timestamp` BETWEEN '$start_time' AND '$end_time' ORDER BY `timestamp` ASC;";
	$mysql_result = mysqli_query($DB_Link, $sql);
	
	if ($mysql_result === false || mysqli_num_rows($mysql_result) <= 0) {
		notFound();
	}

	while ($input = mysqli_fetch_assoc($mysql_result)) {
		$result['data'][] = array('timestamp' => $input['timestamp'], 'value' => (double) $input['value']);
	}
}
else if (isset($_GET['criteria'])) {
	if (!(isset($_GET['idlocation']))) {
		badRequest();
	}
	$idlocation = $_GET['idlocation'];
	$criteria = $_GET['criteria'];
	
	$sql = "SELECT MAX(`timestamp`), MIN(`timestamp`) FROM (SELECT `timestamp` FROM datapoint WHERE idcriteria = (SELECT idcriteria FROM criteria WHERE `desc` = '$criteria') and idstation IN (SELECT idstation FROM station WHERE idlocation = '$idlocation')) as time;";
	$mysql_result = mysqli_query($DB_Link, $sql);
	
	if ($mysql_result === false || mysqli_num_rows($mysql_result) != 1) {
		notFound();
	}

	$input = mysqli_fetch_assoc($mysql_result);
	$result['data'] = array('first_date' => $input['MIN(`timestamp`)'], 'last_date' => $input['MAX(`timestamp`)']);
}
else if (isset($_GET['idlocation'])) {
	$idlocation = $_GET['idlocation'];

	$sql = "SELECT DISTINCT `desc` FROM criteria WHERE idcriteria IN (SELECT idcriteria FROM datapoint WHERE idstation IN (SELECT idstation FROM station WHERE idlocation = '$idlocation'));";
	$mysql_result = mysqli_query($DB_Link, $sql);
	
	if ($mysql_result === false || mysqli_num_rows($mysql_result) <= 0) {
		notFound();
	}

	while ($input = mysqli_fetch_assoc($mysql_result)) {
		$result['data'][] = $input['desc'];
	}
}
else if (isset($_GET['L4'])) {
	if (!(isset($_GET['L1']) || isset($_GET['L2']) || isset($_GET['L3']))) {
		badRequest();
	}
	$L1 = $_GET['L1'];
	$L2 = $_GET['L2'];
	$L3 = $_GET['L3'];
	$L4 = $_GET['L4'];

	$sql = "SELECT idlocation FROM location WHERE location1 = '$L1' and location2 = '$L2' and location3 = '$L3' and location4 = '$L4';";
	$mysql_result = mysqli_query($DB_Link, $sql);

	if ($mysql_result === false || mysqli_num_rows($mysql_result) != 1) {
		notFound();
	}

	$input = mysqli_fetch_assoc($mysql_result);
	$idlocation = $input['idlocation'];
	
	$sql = "SELECT `desc`, value1, value2, `value`, unit, flag FROM (SELECT `desc`, value1, value2, `value`, unit, flag, `timestamp` FROM ( SELECT `timestamp`, `value`, flag, idcriteria FROM datapoint WHERE idstation IN (SELECT idstation FROM station WHERE idlocation = '$idlocation') ) AS datapoint JOIN criteria ON datapoint.idcriteria = criteria.idcriteria ORDER BY `timestamp` DESC) AS datapoint GROUP BY `desc`;";
	$mysql_result = mysqli_query($DB_Link, $sql);
	
	if ($mysql_result === false || mysqli_num_rows($mysql_result) <= 0) {
		notFound();
	}
	
	$result['idlocation'] = $idlocation;
	
	while ($input = mysqli_fetch_assoc($mysql_result)) {
		$result['data'][] = array('desc' => $input['desc'], 'upperBound' => (double) $input['value1'], 'lowerBound' => (double) $input['value2'], 'value' => (double) $input['value'], 'unit' => $input['unit'], 'flag' => (boolean) $input['flag']);
	}
}
else if (isset($_GET['L3'])) {
	if (!(isset($_GET['L1']) || isset($_GET['L2']))) {
		badRequest();
	}
	$L1 = $_GET['L1'];
	$L2 = $_GET['L2'];
	$L3 = $_GET['L3'];

	$sql = "SELECT DISTINCT location4 FROM location WHERE location1 = '$L1' and location2 = '$L2' and location3 = '$L3';";
	$mysql_result = mysqli_query($DB_Link, $sql);

	if ($mysql_result === false || mysqli_num_rows($mysql_result) <= 0) {
		notFound();
	}

	while ($input = mysqli_fetch_assoc($mysql_result)) {
		$result['data'][] = $input['location4'];
	}
}
else if (isset($_GET['L2'])) {
	if (!(isset($_GET['L1']))) {
		badRequest();
	}
	$L1 = $_GET['L1'];
	$L2 = $_GET['L2'];

	$sql = "SELECT DISTINCT location3 FROM location WHERE location1 = '$L1' and location2 = '$L2';";
	$mysql_result = mysqli_query($DB_Link, $sql);

	if ($mysql_result === false || mysqli_num_rows($mysql_result) <= 0) {
		notFound();
	}

	while ($input = mysqli_fetch_assoc($mysql_result)) {
		$result['data'][] = $input['location3'];
	}
}
else if (isset($_GET['L1'])) {
	$L1 = $_GET['L1'];

	$sql = "SELECT DISTINCT location2 FROM location WHERE location1 = '$L1';";
	$mysql_result = mysqli_query($DB_Link, $sql);

	if ($mysql_result === false || mysqli_num_rows($mysql_result) <= 0) {
		notFound();
	}

	while ($input = mysqli_fetch_assoc($mysql_result)) {
		$result['data'][] = $input['location2'];
	}
}
else if (count($_GET) == 0) {
	$sql = "SELECT DISTINCT location1 FROM location;";
	$mysql_result = mysqli_query($DB_Link, $sql);

	if ($mysql_result === false || mysqli_num_rows($mysql_result) <= 0) {
		notFound();
	}

	while ($input = mysqli_fetch_assoc($mysql_result)) {
		$result['data'][] = $input['location1'];
	}
}
else {
	badRequest();
}
if (empty($result)) {
	notFound();
}

echo json_encode($result);
//echo json_encode($result, JSON_UNESCAPED_UNICODE);

function badRequest() {
	header("HTTP/1.1 400 Bad Request");
	exit;
}

function notFound() {
	header("HTTP/1.1 404 Not Found");
	exit;
}
?>