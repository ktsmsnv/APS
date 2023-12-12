<?php  

$sname = "aps.kst-energo.ru";
$uname = "root";
$password = "x4#7!aKs8V5u";

$db_name = "IndustrialData";

$conn = mysqli_connect($sname, $uname, $password, $db_name);

if (!$conn) {
	echo "Сбой подключения!";
	exit();
}
