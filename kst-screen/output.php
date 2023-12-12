<?php
session_start();
date_default_timezone_set('Asia/Yekaterinburg');
?>

<?
include "db_conn.php";

$sql = "SELECT * FROM informationBoard";

function out_inform_Panel_data_protect($data)
{
    $data = trim($data); //Удаляет пробелы (или другие символы) из начала и конца строки
    $data = stripslashes($data); //Удаляет экранирование символов (\)
    $data = htmlspecialchars($data); //Преобразует специальные символы: $, '''', """, <, >
    return $data;
}


$sql = "SELECT informationBoard.id,
               informationBoard.prodOrderNum,
               informationBoard.userFio, 
               informationBoard.productName, 
               informationBoard.factoryNumber,
               prodType.name as prodType_name,
               informationBoard.ProdType,
               informationBoard.zkNumber, 
               informationBoard.buildStart, 
               informationBoard.buildEnd, 
               status.name as status_name
        FROM informationBoard
        INNER JOIN status ON informationBoard.status_id = status.id
        INNER JOIN prodType ON informationBoard.prodType_id = prodType.id ORDER BY id DESC";


if ($result = mysqli_query($conn, $sql)) {

    $rowsCount = mysqli_num_rows($result); // количество полученных строк
    echo "<div class='table' id='table'>
    <div class='table-header'>
    <div class='d-flex justify-content-between align-items-center'>
    <div class='header__item zk'><a id='zk' class='filter__link--number' href='#'>ЗК №</a></div>
  
    <div class='header__item zkp'><a id='OrderNum' class='filter__link' href='#'>№ ЗПр.</a></div>
    <div class='header__item prod'><a id='product' class='filter__link' href='#'>Наименование</a></div>
    <div class='header__item fio'><a id='Fio' class='filter__link' href='#'>Исполнитель</a></div>
    <div class='header__item start'><a id='Start' class='filter__link--date' href='#'>Начало сборки</a></div>
    <div class='header__item end'><a id='End' class='filter__link--date' href='#'>Конец сборки</a></div>
    <div class='header__item stat'><a id='status_n' class='filter__link' href='#'>Статус</a></div>
    </div>
    </div>
    <div class='table-content'>";

    foreach ($result as $row) {

        echo "<div class='d-flex table-rowMain'>";
        echo "<div class='table-row' id='row_" . out_inform_Panel_data_protect($row["id"]) . "'>";
        echo "<div class='table-data zk'> " . out_inform_Panel_data_protect($row["zkNumber"]) . "</div>";
        // echo "<div class='table-data'> " . out_inform_Panel_data_protect($row["id"]) . "</div>";
        echo "<div class='table-data zkp'> " . out_inform_Panel_data_protect($row["prodOrderNum"]) . "</div>";
        echo "<div class='table-data prod'> " . out_inform_Panel_data_protect($row["productName"]) . "</div>";
        echo "<div class='table-data fio'> " . out_inform_Panel_data_protect($row["userFio"]) . "</div>";
        echo "<div class='table-data start'> " . date('d.m.Y', strtotime(out_inform_Panel_data_protect($row["buildStart"]))) . "</div>";
        echo "<div class='table-data end'> " . date('d.m.Y', strtotime(out_inform_Panel_data_protect($row["buildEnd"]))) . "</div>";
        // echo "<div class='table-data'> " . out_inform_Panel_data_protect($row["status_name"]) . "</div>";

        echo "<div class= 'table-data ". out_inform_Panel_data_protect($row["status_name"]) . "'><p>". out_inform_Panel_data_protect($row["status_name"]) ."</p></div>";
        echo "</div>";
        echo "</div>";
    }
    echo "</div>";
    mysqli_free_result($result);
} else {
    echo "Ошибка: " . mysqli_error($conn);
}
mysqli_close($conn);

?>


<script>
    var properties = [
        'zk',
        'id',
        'OrderNum',
        'Fio',
        'product',
        'Start',
        'End',
        'status_n',
    ];


    $.each(properties, function(i, val) {

        var orderClass = '';

        $("#" + val).click(function(e) {
            e.preventDefault();
            $('.filter__link.filter__link--active').not(this).removeClass('filter__link--active');
            $(this).toggleClass('filter__link--active');
            $('.filter__link').removeClass('asc desc');

            if (orderClass == 'desc' || orderClass == '') {
                orderClass = 'asc';
            } else {
                orderClass = 'desc';
            }

            var parent = $(this).closest('.header__item');
            var index = $(".header__item").index(parent);
            var $table = $('.table-content');
            var rows = $table.find('.table-rowMain').get();
            var isSelected = $(this).hasClass('filter__link--active');
            var isNumber = $(this).hasClass('filter__link--number');
            var isDate = $(this).hasClass('filter__link--date');

            rows.sort(function(a, b) {

                var x = $(a).find('.table-data').eq(index).text();
                var y = $(b).find('.table-data').eq(index).text();

                if (isNumber == true) {

                    if (isSelected) {
                        return x - y;
                    } else {
                        return y - x;
                    }
                } else if (isDate) {
                    if (isSelected) {
                        return new Date(x) - new Date(y);
                    } else {
                        return new Date(y) - new Date(x);
                    }
                } else {

                    if (isSelected) {
                        if (x < y) return -1;
                        if (x > y) return 1;
                        return 0;
                    } else {
                        if (x > y) return -1;
                        if (x < y) return 1;
                        return 0;
                    }
                }
            });

            $.each(rows, function(index, row) {
                $table.append(row);
            });

            return false;
        });

    });
</script>