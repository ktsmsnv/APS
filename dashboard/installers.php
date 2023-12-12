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
        <!-- Навигационное меню -->
        <?php include 'components/navigation.php'; ?>
        <!-- main -->
        <div class="main">
            <!-- Верхнее меню -->
            <?php include 'components/topbar.php'; ?>
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
                <div class="Installers">
                    <div class="cardHeader d-flex justify-content-between align-items-center">
                        <h2>Монтажники</h2>
                        <!-- Поиск по таблице -->
                        <div class="search">
                            <label>
                                <input id="searchInstallers" type="text" placeholder="Поиск...">
                                <ion-icon name="search-outline"></ion-icon>
                            </label>
                        </div>
                    </div>
                    <table id="example" class="table" style="width:100%">
                        <thead class="d-none">
                            <tr>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT fio, mail FROM users WHERE role='installer' ORDER BY fio";
                            $result_fio = $conn->query($sql);
                            if ($result_fio === false) {
                                echo "Query error: " . $conn->error;
                            } else {
                                while ($row = $result_fio->fetch_assoc()) {
                                    $fio = $row["fio"];
                                    $mail = $row["mail"];
                                    echo "
                                    <tr data-bs-toggle='modal' data-bs-target='#installerModal' data-fio='{$fio}' id='dataUserFio' class='dataUserFio'>
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
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
<?php include 'components/footer.php'; ?>
<!-- модальное окно монтажника -->
<div class="modal fade" id="installerModal" tabindex="-1" aria-labelledby="installerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="installerModalLabel">Статистика монтажника <span></span></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex flex-column">
                <div class="d-flex flex-column">
                    <h4>Количество готовых изделий за период</h4>
                    <div class="d-flex flex-column">
                        <span>выберите период:</span>
                        <div class="datepicker d-flex">
                            <div class="form__group field">
                                <input type="text" class="form__field" placeholder="от" name="start" id='datepicker-start' required />
                                <label for="datepicker-start" class="form__label">от</label>
                            </div>
                            <div class="form__group field">
                                <input type="text" class="form__field" placeholder="до" name="end" id='datepicker-end' required />
                                <label for="datepicker-end" class="form__label">до</label>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn showBtnInstaller">показать</button>
                </div>
                <div class="d-flex flex-column">
                    <div class="amount d-flex">
                        <h4>ВСЕГО &nbsp;<span class="total"></span></h4>
                        <h4>ГОТОВО &nbsp;<span class="finished"></span></h4>
                    </div>
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuClickable" data-bs-toggle="dropdown" data-bs-auto-close="false" aria-expanded="false">
                        Список изделий
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuClickable">
                        <li><a class="dropdown-item" href="#"></a></li>
                        <li><a class="dropdown-item" href="#"></a></li>
                        <li><a class="dropdown-item" href="#"></a></li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <form action="../dashboard/php/exportModal.php" method="post">
                    <input type="hidden" name="fio" value="" id="fio-input">
                    <input type="hidden" name="dateFrom" value="" id="dateFrom-input">
                    <input type="hidden" name="dateTo" value="" id="dateTo-input">
                    <input type="hidden" name="total" value="" id="total-input">
                    <input type="hidden" name="finished" value="" id="finished-input">
                    <input type="hidden" name="products" value="" id="products-input">
                    <!-- <button type="submit" class="btn btn-primary" name="excel">Выгрузить в Excel</button> -->
                    <button type="submit" class="btn btn-primary" name="word">Выгрузить в Word</button>
                </form>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // данные из запроса sql в modal
        $('#installerModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            // ФИО
            var fio = button.data('fio');
            var modal = $(this);
            modal.find('.modal-title span').text(fio);
            // Ззапрос к бд по выбранным фио и датам
            $(".showBtnInstaller").off('click').on('click', function() {
                // ДАТЫ
                const dateFrom = $('#datepicker-start').val();
                const dateTo = $('#datepicker-end').val();
                // данные для WORD
                const dateFromInput = $('#dateFrom-input').val(dateFrom);
                const dateToInput = $('#dateTo-input').val(dateTo);
                const fioInput = $('#fio-input').val(fio);
                $.ajax({
                    url: 'php/installerPeriodModal.php',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        dateFrom: dateFrom,
                        dateTo: dateTo,
                        userFio: fio
                    },
                    success: function(data) {
                        // ВСЕГО
                        $(".total").first().text(data.totalAmount);
                        // ГОТОВО
                        $(".finished").last().text(data.finishedAmount);
                         // Запись данных в скрытые input поля формы
                         $('#total-input').val(data.totalAmount);
                         $('#finished-input').val(data.finishedAmount);
                        // список изделий
                        let products_html = '';
                        let products_arr = []; // массив для сохранения продуктов
                        for (let product of data.products) {
                            products_html += `<li><a class="dropdown-item" href="#">${product}</a></li>`;
                            products_arr.push(product); // сохраните продукт в массиве
                        }
                        // сохранить продукты как строку JSON в скрытом поле
                        $('#products-input').val(JSON.stringify(products_arr));
                        
                        $(".modal-body ul.dropdown-menu").html(products_html);
                    },

                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                    }
                });
            });
            
        });
        // очистка полей даты при закрытии модального окна
        $('#installerModal').on('hide.bs.modal', function(e) {
            $('#datepicker-start').val(''); // очистка поля даты начала
            $('#datepicker-end').val(''); // очистка поля даты окончания
            $(".total").first().text(''); // очистка поля ВСЕГО
            $(".finished").last().text(''); // очистка поля ГОТОВО
            $(".modal-body ul.dropdown-menu").html(''); // очистка списка изделий

        });

        // DataTable пагинация
        $('#example').DataTable({
            "searching": false,
            "columnDefs": [{
                "orderable": false
            }],
            language: {
                'paginate': {
                    'previous': '<span class="fa fa-chevron-left"></span>',
                    'next': '<span class="fa fa-chevron-right"></span>'
                },
                "lengthMenu": 'Показать записей <select class="form-control input-sm">' +
                    '<option value="10">10</option>' +
                    '<option value="20">20</option>' +
                    '<option value="30">30</option>' +
                    '<option value="40">40</option>' +
                    '<option value="50">50</option>' +
                    '<option value="-1">Все</option>' +
                    '</select>',
                "info": "Показаны записи с _START_ до _END_ из _TOTAL_",
                "infoEmpty": "Показаны записи 0 до 0 из 0",
                "loadingRecords": "Загрузка...",
                "processing": "Обработка...",
            }
        });
        var lengthDiv = $("#example_length").detach();
        lengthDiv.appendTo("#example_wrapper .dataTables_paginate");

        // выбор периода в InstallersModal
        $("#datepicker-start, #datepicker-end").datepicker({
            dateFormat: 'dd-mm-yy'
        });

        // поисковая строка в таблице монтажников
        $("#searchInstallers").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $.ajax({
                url: 'php/searchInstallers.php',
                type: 'POST',
                data: {
                    'search': value
                },
                success: function(data) {
                    $('.Installers table').html(data);
                }
            });
        });
    });
</script>

</html>