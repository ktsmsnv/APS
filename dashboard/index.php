<?php
session_start();
include "../db_conn.php";
try {
    $pdo = new PDO("mysql:host=localhost;database=$db_name", $uname, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
}

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['username']) && !isset($_SESSION['id']) && !isset($_SESSION['role'])) {
    header("Location: index.php"); // Перенаправляем на страницу входа, если не авторизован
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<?php include 'components/header.php'; ?>

<body>
    <div class="container cd-main">
        <!-- Навигационное меню -->
        <?php include 'components/navigation.php'; ?>
        <!-- main -->
        <div class="main">
            <!-- Верхнее меню -->
            <?php include 'components/topbar.php'; ?>
            <!-- Карточки -->
            <div class="cardBox">
                <?php
                $sql = "SELECT COUNT(*) as count FROM informationBoard";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);

                $totalItems = $row['count'];

                $sql = "SELECT COUNT(*) as count FROM informationBoard WHERE status_id = 9";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);

                $finished = $row['count'];
                ?>
                <div class="card">
                    <div>
                        <div class="numbers"><?php echo $totalItems; ?></div>
                        <div class="cardName">Всего изделий в работе</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="eye-outline"></ion-icon>
                    </div>
                </div>
                <div class="card">
                    <div>
                        <div class="numbers"><?php echo $finished; ?></div>
                        <div class="cardName">Всего готовых изделий</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="checkmark-done-outline"></ion-icon>
                    </div>
                </div>
            </div>
            <!-- Графики -->
            <div class="graphBox d-flex flex-column">
                <div class="d-flex flex-column">
                    <div class="box">
                        <div class="swiper-container">
                            <div class="swiper-wrapper d-flex align-items-center">
                                <!-- Граф 1 -->
                                <div class="swiper-slide d-flex flex-column">
                                    <div class="d-flex openChart" class="toggle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Выбрать период">
                                        <div class="selectBox">
                                            <select id="selectPeriodFinishProd">
                                                <option value="Year" class="Year">Год</option>
                                                <option value="Month" class="Month">Месяц</option>
                                                <option value="Week" class="Week">Неделя</option>
                                            </select>
                                        </div>
                                        <a href="data.php#FinishedProd" target="_blank" type="button" class="btn btn-secondary graphFinishedProd" class="toggle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Открыть график в новой вкладке">Открыть</a>
                                    </div>
                                    <?php include 'php/graphFinishedProd.php'; ?>
                                </div>
                                <!-- Граф 2 -->
                                <div class="swiper-slide d-flex flex-column">
                                    <div class="d-flex openChart" class="toggle">
                                        <a href="data.php#AllStatuses" target="_blank" type="button" class="btn btn-secondary graphAllStatuses" class="toggle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Открыть график в новой вкладке">Открыть</a>
                                    </div>
                                    <?php include 'php/graphAllStatuses.php'; ?>
                                </div>
                            </div>
                            <div class="swiper-pagination"></div>
                            <div class="swiper-button-prev" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Предыдущий слайд"></div>
                            <div class="swiper-button-next" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Следующий слайд"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Таблицы -->
            <div class="details">
                <!-- <div class="recentOrders">
                    <div class="cardHeader">
                        <h2>Recent Orders</h2>
                        <a href="#" class="btn">View all</a>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <td>Name</td>
                                <td>Price</td>
                                <td>Payment</td>
                                <td>Status</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Star Refrigerator</td>
                                <td>$1200</td>
                                <td>Paid</td>
                                <td><span class="status delivered">delivered</span></td>
                            </tr>
                            <tr>
                                <td>Star Refrigerator</td>
                                <td>$1200</td>
                                <td>Paid</td>
                                <td><span class="status pending">pending</span></td>
                            </tr>
                            <tr>
                                <td>Star Refrigerator</td>
                                <td>$1200</td>
                                <td>Paid</td>
                                <td><span class="status return">return</span></td>
                            </tr>
                            <tr>
                                <td>Star Refrigerator</td>
                                <td>$1200</td>
                                <td>Paid</td>
                                <td><span class="status inprogress">in progress</span></td>
                            </tr>
                            <tr>
                                <td>Star Refrigerator</td>
                                <td>$1200</td>
                                <td>Paid</td>
                                <td><span class="status delivered">delivered</span></td>
                            </tr>
                            <tr>
                                <td>Star Refrigerator</td>
                                <td>$1200</td>
                                <td>Paid</td>
                                <td><span class="status pending">pending</span></td>
                            </tr>
                            <tr>
                                <td>Star Refrigerator</td>
                                <td>$1200</td>
                                <td>Paid</td>
                                <td><span class="status return">return</span></td>
                            </tr>
                            <tr>
                                <td>Star Refrigerator</td>
                                <td>$1200</td>
                                <td>Paid</td>
                                <td><span class="status delivered">delivered</span></td>
                            </tr>
                            <tr>
                                <td>Star Refrigerator</td>
                                <td>$1200</td>
                                <td>Paid</td>
                                <td><span class="status pending">pending</span></td>
                            </tr>
                            <tr>
                                <td>Star Refrigerator</td>
                                <td>$1200</td>
                                <td>Paid</td>
                                <td><span class="status return">return</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div> -->
                <!-- Монтажники таблица -->
                <div class="Installers" class="toggle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Статистика по монтажникам">
                    <div class="cardHeader d-flex justify-content-between align-items-center">
                        <h2>Монтажники</h2>
                        <a href="installers.php" class="btn">Смотреть всех</a>
                    </div>
                    <table>
                        <?php
                        $sql = "SELECT fio, mail FROM users WHERE role='installer' ORDER BY fio LIMIT 5";
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
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>

</body>
<?php include 'components/footer.php'; ?>

</html>