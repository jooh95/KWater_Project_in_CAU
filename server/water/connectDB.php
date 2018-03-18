<?php
@header("Content-type:text/html;charset=utf-8");


$DB_Link = mysqli_connect("localhost", "root", "apmsetup", "kwater");
//mysqli_set_charset($DB_Link, 'utf8');

mysqli_query($DB_Link, "set session character_set_connection=utf8;");
mysqli_query($DB_Link, "set session character_set_results=utf8;");
mysqli_query($DB_Link, "set session character_set_client=utf8;");


if(mysqli_connect_errno()){
	print("Connecting to DB Server is unavailable");
	exit();
}
?>