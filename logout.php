<?php
//выход
session_start();
include "db_conn.php";

if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'director' || $_SESSION['role'] == 'curator' || $_SESSION['role'] == 'master' || $_SESSION['role'] == 'installer')
 { $user_id = $_SESSION['id']; $role = $_SESSION['role']; $username = $_SESSION['username'];  $fio = $_SESSION['fio']; $action = 'logout'; $ip = getenv('REMOTE_ADDR'); 

 // Вставляем запись о выходе пользователя в журнал
 $insert_query = "INSERT INTO sessionLog (user_id, role, username, fio, action, ip, host) VALUES ('$user_id', '$role', '$username', '$fio', '$action', '$ip')"; mysqli_query($conn, $insert_query); }
   // действия по выходу пользователя
   session_unset(); 
   session_destroy(); 
// Перенаправляем пользователя на страницу входа 
header("Location: index.php");