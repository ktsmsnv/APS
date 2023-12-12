<?php
// СТРАНИЦА КУРАТОРА 
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
    <?php require('components/header.php'); ?>
</head>

<body>
    <main>
        <div class="background_wrapper">
            <?php if ($_SESSION['role'] == 'curator') { ?>
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
                            url: "php/outputCurator.php",
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


                <select class="selectField-multiselect d-none" id="prod_type" name="prod_type" required>
                    <option value="" disabled selected>Выберите тип изделия:</option>
                    <?php
                    $sql2 = "SELECT `name` FROM `prodType`";
                    $result_type = $conn->query($sql2);
                    var_dump($result_type);
                    if ($result_type === false) {
                        echo "Query error: " . $conn->error;
                    }
                    while ($row = $result_type->fetch_assoc()) {
                        $type = $row["name"];
                        echo "<option value=\"$type\">$type</option>";
                    }
                    ?>
                </select>

                <select class="selectField-multiselect d-none" id="directorName" name="directorName" required>
                    <option value="" disabled selected>Выберите мастера участка</option>
                    <?php
                    $sql2 = "SELECT `fio` FROM `users` WHERE role='master'";
                    $result_type = $conn->query($sql2);
                    var_dump($result_type);
                    if ($result_type === false) {
                        echo "Query error: " . $conn->error;
                    }
                    while ($row = $result_type->fetch_assoc()) {
                        $director_id = $row["id"];
                        $director = $row["fio"];
                        echo "<option value=\"$director_id\">$director</option>";
                    }

                    ?>
                </select>


            <?php } ?>
        </div>
    </main>
</body>
<?php require('footer.php'); ?>

</html>