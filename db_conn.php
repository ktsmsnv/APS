<?php  

$sname = "localhost";
$uname = "root";
$password = "";

$db_name = "IndustrialData";

$conn = mysqli_connect($sname, $uname, $password, $db_name);

if (!$conn) {
	echo "Сбой подключения!";
	exit();
}