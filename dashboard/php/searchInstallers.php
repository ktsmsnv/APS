<?php
include "../db_conn.php";
$search = $_POST['search'];
$sql = "SELECT fio, mail FROM users WHERE role='installer' AND fio LIKE '%$search%'";
$result_fio = $conn->query($sql);
if ($result_fio === false) {
  echo "Query error: " . $conn->error;
} else {
  while ($row = $result_fio->fetch_assoc()) {
    $fio = $row["fio"];
    $mail = $row["mail"];
    echo "
    <tr>
    <td width='60px'>
        <div class='imgBx'><img src='images/person-sharp.svg'></div>
    </td>
    <td>
        <h4>{$fio}<br><a href='mailto:{$mail}'>{$mail}</a></h4>
    </td>
</tr>
        ";
  }
}
