<?php
// СТРАНИЦА МАСТЕРА
session_start();
include "db_conn.php";
if (!isset($_SESSION['username']) && !isset($_SESSION['id'])) {
    header("Location: index.php");
    exit;
}
$allowedRoles = array(
    'admin' => 'mainPage.php',
    'director' => 'directorPage.php',
    'curator' => 'curatorPage.php',
    'master' => 'masterPage.php',
    'installer' => 'installerPage.php'
);

if (!array_key_exists($_SESSION['role'], $allowedRoles) || $allowedRoles[$_SESSION['role']] !== basename($_SERVER['PHP_SELF'])) {
    header("Location: unauthorized.php");
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <?php require('header.php'); ?>
</head>

<body>
    <main>
        <div class="background_wrapper">
            <?php if ($_SESSION['role'] == 'master') { ?>
                <div class="logo d-flex flex-column align-items-center">
                    <img src="images/logo.svg">
                </div>
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">
                        <?php $_SESSION['role'];
                        if ($_SESSION['role'] == 'admin') {
                            echo ('Администратор -');
                        } elseif ($_SESSION['role'] == 'director') {
                            echo ('Руководитель -');
                        } elseif ($_SESSION['role'] == 'curator') {
                            echo ('Куратор -');
                        } elseif ($_SESSION['role'] == 'master') {
                            echo ('Мастер -');
                        } elseif ($_SESSION['role'] == 'installer') {
                            echo ('Монтажник -');
                        }
                        ?>
                        <?= $_SESSION['fio'] ?>
                    </h5>
                    <a href="logout.php" class="btn btn-light bg-primary text-light">Выйти</a>
                </div>
                <!-- Вывод асинхронных данных -->
                <div class="screenRental">
                    <div class="wrapper">
                        <div id="content" class="tableData">

                        </div>
                    </div>
                </div>
                <!-- (a2) Скрипт вывода (асинхронно) данных из БД + вывод даты и времени-->
                <script>
                    function show() {
                        $.ajax({
                            // url: "php/outputTime_Array.php",
                            url: "php/outputMaster.php",
                            cache: false,
                            success: function (html) {
                                $("#content").html(html);
                            }
                        });
                    }

                    $(document).ready(function () {
                        show();
                        // setInterval('show()', 1000);
                    });
                </script>

                <!-- данные для селектов -->
                <select class="selectField-multiselect d-none" id="productName" name="productName" required
                    onchange="updateFields()">
                    <option value="" disabled selected>Выберите изделие:</option>
                    <?php
                    $sql2 = "SELECT p.prod_name
                                    FROM products p
                                    LEFT JOIN informationBoard ib ON p.prod_name = ib.productName
                                    WHERE ib.productName IS NULL";
                    $result_type = $conn->query($sql2);
                    var_dump($result_type);
                    if ($result_type === false) {
                        echo "Query error: " . $conn->error;
                    }
                    while ($row = $result_type->fetch_assoc()) {
                        $typeProdName = $row["prod_name"];
                        echo "<option value=\"$typeProdName\">$typeProdName</option>";
                    }
                    ?>
                </select>
                <select class="selectField-multiselect d-none" id="userFio2" name="userFio2" required>
                    <option value="" disabled selected>Выберите ФИО</option>
                    <?php
                    $roleFio = $_SESSION['fio'];
                    $roleGrQuery = "SELECT groupId FROM users WHERE fio='$roleFio'";
                    $resultGr = mysqli_query($conn, $roleGrQuery);
                    if ($resultGr) {
                        $row = mysqli_fetch_assoc($resultGr);
                        $groupId = $row['groupId'];
                    } else {
                        // echo "Error: " . mysqli_error($conn);
                    }

                    $sql = "SELECT fio FROM users WHERE role='installer' AND groupId='$groupId'";
                    $result_fio = $conn->query($sql);
                    var_dump($result_fio);
                    if ($result_fio === false) {
                        echo "Query error: " . $conn->error;
                    }
                    while ($row = $result_fio->fetch_assoc()) {
                        $fio = $row["fio"];
                        echo "<option value=\"$fio\">$fio</option>";
                    }
                    ?>
                </select>
                <select class="selectField-multiselect d-none" id="userStatus" name="userStatus" required>
                    <option value="" disabled selected>Выберите статус:</option>
                    <?php
                    $sql = "SELECT `name` FROM `status`";
                    $result_status = $conn->query($sql);
                    var_dump($result_status);
                    if ($result_status === false) {
                        echo "Query error: " . $conn->error;
                    }
                    while ($row = $result_status->fetch_assoc()) {
                        $status = $row["name"];
                        echo "<option value=\"$status\">$status</option>";
                    }
                    ?>
                </select>

            <?php } ?>
        </div>
    </main>
</body>
<?php require('footer.php'); ?>

</html>