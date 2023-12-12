<?php
//страница истории тзиенений записи
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
session_start();
?>

<!DOCTYPe html>
<html>

<head>
    <title>История изменений</title>
    <?php require('header.php'); ?>
  </head>

<body>
    <main class="HistoryPage">
    <div class="background_wrapper" style="padding: 20px;">
        <?php 
        date_default_timezone_set('Asia/Yekaterinburg');
        $month_list = array(
            1  => 'января',
            2  => 'февраля',
            3  => 'марта',
            4  => 'апреля',
            5  => 'мая', 
            6  => 'июня',
            7  => 'июля',
            8  => 'августа',
            9  => 'сентября',
            10 => 'октября',
            11 => 'ноября',
            12 => 'декабря'
        );
         
        echo  "<div class='dataH'>". date('d') . ' ' . $month_list[date('n')] . ' ' . date('Y') . date(' - H:i:s').  "</div>";// 09 августа 2023
        
        
        include "db_conn.php";
        
        
        $prodOrderNum = $_GET['prodOrderNum'];
        
        $sql = "SELECT * FROM informationBoard_history WHERE prodOrderNum = '$prodOrderNum' ORDER BY modified_at DESC";
        
        $result = mysqli_query($conn, $sql);
        ?>
    <h2>История изменений для Заказа Производства №<?php echo $prodOrderNum; ?></h2>
    <button id="exportToExcelBtn">Экспорт в Excel</button>
    <br></br>
    <table border="1">
        <tr>
            <th>Действие</th>
            <th>ID</th>
            <th>№ Заказа пр-ва</th>
            <th>ФИО</th>
            <th>Наим-ие</th>
            <th>№ Зав</th>
            <!-- <th>ProdType</th> -->
            <th>№ ЗК</th>
            <th>Начало сборки</th>
            <th>Конец сборки</th>
            <!-- <th>Status</th> -->
            <th>Статус</th>
            <th>Тип изделия</th>
            <th>Время изменения</th>
        </tr>
        <?php
        if (isset($result)) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['Action'] . "</td>";
                echo "<td>" . $row['Id'] . "</td>";
                echo "<td>" . $row['prodOrderNum'] . "</td>";
                echo "<td>" . $row['userFio'] . "</td>";
                echo "<td>" . $row['productName'] . "</td>";
                echo "<td>" . $row['factoryNumber'] . "</td>";
                // echo "<td>" . $row['prodType'] . "</td>";
                echo "<td>" . $row['zkNumber'] . "</td>";
                echo "<td>" . $row['buildStart'] . "</td>";
                echo "<td>" . $row['buildEnd'] . "</td>";
                // echo "<td>" . $row['status'] . "</td>";
                // echo "<td>" . $row['status_id'] . "</td>";

                $statusId = $row['status_id'];
                $statusQuery = "SELECT name FROM status WHERE id = $statusId";
                $statusResult = mysqli_query($conn, $statusQuery);
                $statusRow = mysqli_fetch_assoc($statusResult);
                echo "<td>" . $statusRow['name'] . "</td>";

                // echo "<td>" . $row['prodType_id'] . "</td>";

                $prodTypeId = $row['prodType_id'];
                $prodTypeQuery = "SELECT name FROM prodType WHERE id = $prodTypeId";
                $prodTypeResult = mysqli_query($conn, $prodTypeQuery);
                $prodTypeRow = mysqli_fetch_assoc($prodTypeResult);
                echo "<td>" . $prodTypeRow['name'] . "</td>";

                echo "<td>" . $row['modified_at'] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='2'>Нет изменений</td></tr>";
        }
        ?>
    </table>
    </div>
    </main>
</body>

</html>

<script>
    // Функция для экспорта в Excel
    function exportToExcel() {
        // Создание HTML-таблицы, содержащую данные истории
        var tableHtml = document.querySelector('table').outerHTML;

        // Преобразовать таблицу в формат Excel
        var excelContent = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
        excelContent += '<head>';
        excelContent += '<!--[if gte mso 9]>';
        excelContent += '<xml>';
        excelContent += '<x:ExcelWorkbook>';
        excelContent += '<x:ExcelWorksheets>';
        excelContent += '<x:ExcelWorksheet>';
        excelContent += '<x:Name>Sheet1</x:Name>';
        excelContent += '<x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions>';
        excelContent += '</x:ExcelWorksheet>';
        excelContent += '</x:ExcelWorksheets>';
        excelContent += '</x:ExcelWorkbook>';
        excelContent += '</xml>';
        excelContent += '<![endif]-->';
        excelContent += '</head>';
        excelContent += '<body>';
        excelContent += tableHtml;
        excelContent += '</body>';
        excelContent += '</html>';

        var blob = new Blob([excelContent], {
            type: 'application/vnd.ms-excel'
        });
        var url = window.URL.createObjectURL(blob);
        var downloadLink = document.createElement('a');
        downloadLink.href = url;
        downloadLink.download = 'история ЗКП.xls';

        downloadLink.click();
        window.URL.revokeObjectURL(url);
    }

    document.getElementById('exportToExcelBtn').addEventListener('click', exportToExcel);
</script>