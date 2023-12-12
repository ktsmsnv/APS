<?php
session_start();
include "../db_conn.php";
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
    <div class="container">
        <?php include 'components/navigation.php'; ?>
        <!-- main -->
        <div class="main Data">
            <?php include 'components/topbar.php'; ?>
            <div class="graphBox d-flex flex-column">
                <!-- Граф 1 -->
                <div class="box d-flex flex-column-reverse align-items-center" id='FinishedProd'>
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <div class="selectBox" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Выбрать период">
                            <select id="selectPeriodFinishProd">
                                <option value="Year" class="Year">Год</option>
                                <option value="Month" class="Month">Месяц</option>
                                <option value="Week" class="Week">Неделя</option>
                            </select>
                        </div>
                        <div class="d-flex align-items-center btns">
                            <button class="btn btn-primary me-3" onclick="downloadExcel()">Выгрузить в Excel</button>
                            <button class="btn btn-primary" onclick="downloadPDF()">Выгрузить в PDF</button>
                        </div>
                    </div>
                    <?php include 'php/graphFinishedProd.php'; ?>
                    <h5>за выбранный период</h5>
                    <h3>Готовые изделия</h3>
                </div>
                <!-- Граф 2 -->
                <div class="box d-flex flex-column-reverse align-items-center" id='AllStatuses'>
                    <div class="d-flex justify-content-end align-items-center w-100">
                        <div class="d-flex align-items-center btns">
                            <button class="btn btn-primary me-3" onclick="downloadExcel2()">Выгрузить в Excel</button>
                            <button class="btn btn-primary" onclick="downloadPDF2()">Выгрузить в PDF</button>
                        </div>
                    </div>
                    <?php include 'php/graphAllStatuses.php'; ?>
                    <h5>на текущий момент</h5>
                    <h3>Статистика по статусам</h3>
                </div>
            </div>
        </div>
    </div>
</body>
<?php include 'components/footer.php'; ?>

</html>