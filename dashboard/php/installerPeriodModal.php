<?php 
include "../db_conn.php";

$dateFrom = $_POST['dateFrom'];
$dateTo = $_POST['dateTo'];
$userFio = $_POST['userFio'];

$formattedDateFrom = date('Y-m-d', strtotime($dateFrom));
$formattedDateTo = date('Y-m-d', strtotime($dateTo));

$data = [];

// ВСЕГО
$query = "SELECT COUNT(*) as totalAmount 
          FROM DataTable
          WHERE userFio = '$userFio' AND buildStart BETWEEN '$formattedDateFrom' AND '$formattedDateTo' AND buildEnd BETWEEN '$formattedDateFrom' AND '$formattedDateTo'";
$result = mysqli_query($conn, $query);
if (!$result) {
    die('Ошибка запроса: ' . mysqli_error($conn));
}
$row = mysqli_fetch_array($result);
$data['totalAmount'] = $row['totalAmount'];

// ГОТОВО
$query = "SELECT COUNT(*) as finishedAmount 
          FROM DataTable
          WHERE status_id = 9 AND userFio = '$userFio' AND buildStart BETWEEN '$formattedDateFrom' AND '$formattedDateTo' AND buildEnd BETWEEN '$formattedDateFrom' AND '$formattedDateTo'";
$result = mysqli_query($conn, $query);
if (!$result) {
    die('Ошибка запроса: ' . mysqli_error($conn));
}
$row = mysqli_fetch_array($result);
$data['finishedAmount'] = $row['finishedAmount'];

// Список изделий
$query = "SELECT productName 
          FROM DataTable
          WHERE userFio = '$userFio' AND buildStart BETWEEN '$formattedDateFrom' AND '$formattedDateTo' AND buildEnd BETWEEN '$formattedDateFrom' AND '$formattedDateTo'";
$result = mysqli_query($conn, $query);
if (!$result) {
    die('Ошибка запроса: ' . mysqli_error($conn));
}
$data['products'] = [];
while ($row = mysqli_fetch_array($result)) {
    array_push($data['products'], $row['productName']);
}

echo json_encode($data);

