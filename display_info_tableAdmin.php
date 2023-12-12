<script>
    $(document).ready(function() {
        var $items = $(".item");
        var $select = $("#zkSelect");

        $select.on("change", function() {
            var selectedValue = $(this).val();

            if (selectedValue == "all") {
                $items.show();
            } else {
                $items.hide();
                $items.filter("." + selectedValue).show();
            }
        });
    });
</script>
<?php
echo "<div class='box'>";
echo "<p>Фильтр по №ЗК</p>";
echo "<select id='zkSelect' class='filter-list'>";
echo "<option class='active btn' value='all'>Все</option>";

$sql = "SELECT DISTINCT zkNumber FROM informationBoard";
$result_type = $conn->query($sql);
if ($result_type === false) {
    echo "Ошибка запроса: " . $conn->error;
}
while ($row = $result_type->fetch_assoc()) {
    $zkNumber = $row['zkNumber'];
    echo "<option class='btn' value='$zkNumber'>$zkNumber</option>";
}

echo "</select>";
echo "</div>";
$sqli = "SELECT informationBoard.id,
               informationBoard.prodOrderNum,
               informationBoard.userFio, 
               informationBoard.productName, 
               informationBoard.factoryNumber,
               informationBoard.kd_code,
               prodType.name as prodType_name,
               informationBoard.ProdType,
               informationBoard.zkNumber, 
               informationBoard.buildStart, 
               informationBoard.buildEnd, 
               informationBoard.director, 
               informationBoard.constructor, 
               informationBoard.prodType_id,
               status.name as status_name
        FROM informationBoard
        INNER JOIN status ON informationBoard.status_id = status.id
        INNER JOIN prodType ON informationBoard.prodType_id = prodType.id ORDER BY id DESC";


if ($result = mysqli_query($conn, $sqli)) {

    $rowsCount = mysqli_num_rows($result); // количество полученных строк
    // echo "<p>Получено объектов: $rowsCount</p>";

    echo "<div class='table'>
    <div class='table-header'>

    <div class='header__item' style='max-width:40px;'> 
        <input type='checkbox' name='file' class='chk-all styled-checkbox'/>
     </div>

    <div class='header__item'><a id='zk' class='filter__link--number' href='#'>ЗК №</a></div>
    <div class='header__item'><a id='OrderNum' class='filter__link' href='#'>№ ЗПр.</a></div>
    <div class='header__item'><a id='product' class='filter__link' href='#'>Наименование</a></div>
    <div class='header__item'><a id='Fio' class='filter__link' href='#'>ФИО</a></div>
    <div class='header__item'><a id='Start' class='filter__link--date' href='#'>Начало сборки</a></div>
    <div class='header__item'><a id='End' class='filter__link--date' href='#'>Конец сборки</a></div>
    <div class='header__item'><a id='status_n' class='filter__link' href='#'>Статус</a></div>
    <div class='header__item' style='max-width:50px;'><a id='h' class='filter__link'></a></div>
       <div class='header__item'  style='max-width:50px;'><a id='a' class='filter__link'></a></div>
    <div class='header__item' style='max-width:50px;'><a id='a' class='filter__link'></a></div>
    </div>
    <div class='table-content'>";

    foreach ($result as $row) {

        echo "<div class='table-rowMain item " . $row["zkNumber"] . "'  style='display: flex;'>";
        echo "<div class='table-row' id='row_" . out_inform_Panel_data_protect($row["id"]) . "'>";

        //скачивание чекбоксы
        echo "<div class='table-data' style='max-width:40px;'><input type='checkbox' name='file' class='styled-checkbox' data-id='" . $row["id"] . "' data-zkNumber='" . $row["zkNumber"] .
            "' data-kd_code='" . $row["kd_code"] . "' data-director='" . urlencode($row["director"]) . "' data-constructor='" . urlencode($row["constructor"]) . "' data-productName='" . urlencode($row["productName"]) . "' 
data-factoryNumber='" . $row["factoryNumber"] . "' data-buildStart='"
            . $row["buildStart"] . "' data-userFio='" . urlencode($row["userFio"]) . "' data-prodType_id='" . urlencode($row["prodType_id"]) . "'/></div>";

        echo "<div class='table-data'> " . out_inform_Panel_data_protect($row["zkNumber"]) . "</div>";
        echo "<div class='table-data'> " . out_inform_Panel_data_protect($row["prodOrderNum"]) . "</div>";
        echo "<div class='table-data'> " . out_inform_Panel_data_protect($row["productName"]) . "</div>";
        echo "<div class='table-data'> " . out_inform_Panel_data_protect($row["userFio"]) . "</div>";
        echo "<div class='table-data editable date-field' id='buildStart' data-id='{$row["id"]}'> " . date('d.m.Y', strtotime(out_inform_Panel_data_protect($row["buildStart"]))) . "</div>";
        echo "<div class='table-data editable date-field' id='buildEnd' data-id='{$row["id"]}'> " . date('d.m.Y', strtotime(out_inform_Panel_data_protect($row["buildEnd"]))) . "</div>";
        echo "<div class= 'table-data " . out_inform_Panel_data_protect($row["status_name"]) . " editable' id='status_name' data-id='{$row["id"]}' ><p>" . out_inform_Panel_data_protect($row["status_name"]) . "</p></div>";

        echo "<div class='table-data' style='max-width:50px;'><a href='#' class='downloadButton-" . $row["id"] . "'> <img src='images/download-solid.svg'> </a></div>";

        echo "<div class='table-data' style='max-width:50px;'><a id='sendDeleteSvg' href='#' class='sendDeleteSvg' data-id='" . $row["prodOrderNum"] . "'><img src='images/delete.svg'></a></div>";

        $prodType_id = urlencode($row["prodType_id"]);
        $urlParameters = "id={$row["id"]}&zkNumber={$row["zkNumber"]}&kd_code=" . urlencode($row["kd_code"]) . "&constructor=" . urlencode($row["constructor"]) . '&director=' . urlencode($row["director"]) . "&productName=" . urlencode($row["productName"]) . "&factoryNumber={$row["factoryNumber"]}&buildStart={$row["buildStart"]}&userFio=" . urlencode($row["userFio"]) . "&prodType_id=" . urlencode($row["prodType_id"]);

        echo "<script>
console.log('$prodType_id');
console.log('$urlParameters');
document.querySelector('.downloadButton-" . $row["id"] . "').addEventListener('click', function(event) {
    event.preventDefault();
    var urlParameters = '" . $urlParameters . "';
    // Открываем ссылку для загрузки файлов 
    window.open('php/downloadFiles.php?' + urlParameters);
});
</script>";

        echo "<div class='table-data' style='max-width:50px;'> ", "<a class='downloadHistory' target='_blank' href='/history.php?prodOrderNum=" . out_inform_Panel_data_protect($row["prodOrderNum"]) . "'><img src='images/history.svg' style='width:25px;'></a> </div>";

        echo "</div>";
        echo "</div>";
    }
    echo "</div>";

    //скачивание чекбоксы скачать все downloadChecked() и фидбэк
    echo "<div class='d-flex justify-content-between align-items-end'>";

    echo "<div class='d-flex align-items-center AllBtns'>";
    echo "<p>С отмеченными: </p>";
    echo "<a class='checkDownload' type='button' value='Скачать выбранное' onClick='downloadChecked();'><img src='images/download-solid.svg'> </a>";
    echo "<a id='checkDelete' class='checkDelete' type='button' value='Удалить выбранное'><img src='images/delete.svg'> </a>";
    echo "</div>";
    echo "<div class='d-flex align-items-center'>";
    echo "<button id='addEntry'>Добавить запись <img src='images/row-plus.svg'> </button>";
    echo "<button id='saveEntry'>Сохранить запись</button>";
    echo "</div>";

    echo " <button id='feedbackButton' class='btn btn-primary'>Обратная связь</button>";
    echo "</div>";
    echo " <div id='notification' class='notification'></div>";
    echo "</div>";
    mysqli_free_result($result);
} //else {
//     echo "Ошибка: " . mysqli_error($conn);
// }
// mysqli_close($conn);

?>

<!-- Скрипт открытия модального окна удаления и удаления записи -->
<script>
    // открытие модального окна
    function openModal() {
        $('.DeleteModal').show();
    }
    // закрытие модального окна
    function closeModalDelete() {
        $('.DeleteModal').hide();
    }
    // обработчик события для кнопки
    $('body').on('click', '.sendDeleteSvg', function() {
        // data-id из элемента
        var data_id = $(this).data('id');
        // data-id в кнопку "Да"
        $('#confirmDeleteButton').data('id', data_id);
        // функция для открытия модального окна
        openModal();
    });
    // закрытие модального окна при клике на кнопку "Нет"
    $('.close-button, #cancelDeleteButton').on('click', function() {
        // функция для закрытия модального окна
        closeModalDelete();
    });
    // закрытие модалки если клик вне его
    $(window).on('click', function(event) {
        if (event.target == $('.DeleteModal')[0]) {
            // функция для закрытия модального окна
            closeModalDelete();
        }
    });
    // клик на кнопку "Да"
    $('#confirmDeleteButton').on('click', function() {
        var v_prodOrderNumDelete = $(this).data('id');
        $.ajax({
                method: "POST",
                url: "php/deleteData.php",
                data: {
                    prodOrderNumDelete: v_prodOrderNumDelete
                }
            })
            .done(function() {
                var popup = $('<div class="popup">Запись была удалена</div>');
                $('body').append(popup);
                popup.fadeIn();
                setTimeout(function() {
                    popup.fadeOut(function() {
                        $(this).remove();
                    });
                }, 1200);
                setTimeout(function() {
                    window.location.reload();
                }, 1800);
            });
        // функция для закрытия модального окна после выполнения AJAX-запроса
        closeModalDelete();
    });
</script>
<!-- обратная связь -->
<script>
    // Получение элементов модального окна и кнопки
    const feedbackModal = document.getElementById("feedbackModal");
    const feedbackButton = document.getElementById("feedbackButton");
    const closeModal = document.getElementById("closeModal");

    // Получение элементов ползунка и элемента для отображения значения
    const ratingInput = document.getElementById("rating");
    const ratingValue = document.getElementById("ratingValue");

    // Обработчик события input для обновления значения в <output>
    ratingInput.addEventListener("input", () => {
        ratingValue.textContent = ratingInput.value;
    });

    // Обработчик клика на кнопку
    feedbackButton.addEventListener("click", () => {
        feedbackModal.style.display = "flex";
    });

    // Обработчик клика на крестик для закрытия модального окна
    closeModal.addEventListener("click", () => {
        feedbackModal.style.display = "none";
    });

    // Обработчик клика на фон модального окна для закрытия модального окна
    feedbackModal.addEventListener("click", (event) => {
        if (event.target === feedbackModal) {
            feedbackModal.style.display = "none";
        }
    });

    // Обработчик отправки формы
    const feedbackForm = document.getElementById("feedbackForm");
    feedbackForm.addEventListener("submit", (event) => {
        event.preventDefault();
        let formData = new FormData(feedbackForm);
        $.ajax({
            url: "php/send_feedback.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Создаем и стилизуем popup элемент
                var popup = $('<div class="popup">Успешно отправлено!</div>');
                $('body').append(popup);
                popup.fadeIn();
                // Закрываем popup 
                setTimeout(function() {
                    // Плавно скрываем и удаляем popup
                    popup.fadeOut(function() {
                        $(this).remove();
                    });
                }, 1800);

                $("#feedbackForm")[0].reset();
                $("#ratingValue").text("5");
                $("#feedbackModal").removeClass("open");
            },
            error: function(xhr) {
                // Создаем и стилизуем popup элемент
                var popup = $('<div class="popup">Ощибка при отправке. Напишите на почту aps@kst-energo.ru</div>');
                $('body').append(popup);
                popup.fadeIn();
                // Закрываем popup 
                setTimeout(function() {
                    // Плавно скрываем и удаляем popup
                    popup.fadeOut(function() {
                        $(this).remove();
                    });
                }, 1200);
            }
        });
        feedbackModal.style.display = "none";
    });
</script>
<!-- сортировка -->
<!-- <script>
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
    //сортировка столбцов таблицы
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
</script> -->
<!-- скачивание по чекбоксам -->
<script src="https://cdn.jsdelivr.net/npm/jszip@3.5.0/dist/jszip.min.js"></script>
<script>
    //выбор всех чекбоксов
    $(document).on('change', 'input[type=checkbox]', function() {
        var $this = $(this),
            $chks = $(document.getElementsByName(this.name)),
            $all = $chks.filter(".chk-all");

        if ($this.hasClass('chk-all')) {
            $chks.prop('checked', $this.prop('checked'));
        } else switch ($chks.filter(":checked").length) {
            case +$all.prop('checked'):
                $all.prop('checked', false).prop('indeterminate', false);
                break;
            case $chks.length - !!$this.prop('checked'):
                $all.prop('checked', true).prop('indeterminate', false);
                break;
            default:
                $all.prop('indeterminate', true);
        }
    });
    //скачивание по выбору checkbox
    async function downloadChecked() {
        const checkboxes = document.querySelectorAll('input[name="file"]:checked:not(.chk-all)');
        // Проверка на выбранные чекбоксы
        if (checkboxes.length === 0) {
            const notification = document.getElementById("notification");
            notification.innerText = "Выберите записи";
            notification.style.display = "block"; // Показать уведомление
            setTimeout(function() {
                notification.style.display = "none";
            }, 3000);
            return;
        }
        const zip = new JSZip();
        const fetchFileAndAddToZip = async (url, fileName) => {
            const response = await fetch(url);
            const data = await response.blob();
            zip.file(fileName, data, {
                binary: true
            });
        };

        const allPromises = Array.from(checkboxes).map(async function(checkbox) {
            var id = checkbox.getAttribute('data-id');
            var zkNumber = checkbox.getAttribute('data-zkNumber');
            var director = checkbox.getAttribute('data-director');
            var constructor = checkbox.getAttribute('data-constructor');
            var kd_code = checkbox.getAttribute('data-kd_code');
            var productName = checkbox.getAttribute('data-productName');
            var factoryNumber = checkbox.getAttribute('data-factoryNumber');
            var buildStart = checkbox.getAttribute('data-buildStart');
            var userFio = checkbox.getAttribute('data-userFio');
            var prodType_id = checkbox.getAttribute('data-prodType_id');
            console.log(prodType_id + '\n');
            console.log(zkNumber + '\n');

            //тех.паспорт
            var url = 'php/passport.php?id=' + id + '&zkNumber=' + zkNumber + '&kd_code=' + encodeURIComponent(kd_code) +
                '&productName=' + encodeURIComponent(productName) + '&factoryNumber=' + factoryNumber + '&buildStart=' +
                buildStart + '&constructor=' + encodeURIComponent(constructor) + '&director=' + encodeURIComponent(director) +
                '&userFio=' + encodeURIComponent(userFio);
            var url2 = 'php/OperCardGeneral.php?id=' + id + '&kd_code=' + encodeURIComponent(kd_code) + '&factoryNumber=' + factoryNumber +
                '&productName=' + encodeURIComponent(productName);
            var url3 = 'php/OperCardExplosion.php?id=' + id + '&kd_code=' + encodeURIComponent(kd_code) + '&factoryNumber=' + factoryNumber +
                '&productName=' + encodeURIComponent(productName);

            await fetchFileAndAddToZip(url, `passport_${factoryNumber}.docx`);
            if (prodType_id === '1') {
                await fetchFileAndAddToZip(url2, `operCard_Ob_${factoryNumber}.docx`);
            } else if (prodType_id === '2') {
                await fetchFileAndAddToZip(url3, `operCard_Vz_${factoryNumber}.docx`);
            }
            console.log(url + '\n');
        });
        await Promise.all(allPromises);
        // Создаем архив и начинаем его загрузку
        const blob = await zip.generateAsync({
            type: 'blob',
            mimeType: 'application/zip',
            encoding: 'UTF-8'
        });
        const link = document.createElement('a');
        // link.href = URL.createObjectURL(blob);
        link.href = URL.createObjectURL(new File([blob], 'документы.zip', {
            type: 'application/zip;charset=utf-8'
        }));
        link.download = 'документы.zip';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        // Сбрасываем состояние checked для всех checkbox
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = false;
        });
    }
</script>

<!-- плагин календарь -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!-- редактирование записи в таблице-->
<script>
    // функция для заполнения статусов <select> в таблице для редактирования
    function populateStatusSelect(id, selectedStatus) {
        $.ajax({
            type: 'POST',
            url: 'php/edit_status.php',
            data: {
                id: id,
                selectedStatus: selectedStatus
            },
            success: function(response) {
                var data;

                if (typeof response === "string") {
                    try {
                        data = JSON.parse(response);
                    } catch (e) {
                        console.error("Invalid JSON received:", response);
                        alert("Ошибка ответа сервера");
                        return;
                    }
                } else {
                    data = response;
                }
                var options = '';
                data.allStatuses.forEach(function(status) {
                    var isSelected = status.name === data.selectedStatus ? 'selected' : '';
                    options += `<option value="${status.name}" ${isSelected}>${status.name}</option>`;
                });
                $('#status-' + id).empty().append(options);
            },
            error: function() {
                alert('Ошибка подключения к серверу');
            }
        });
    }

    // создание календаря
    function createDatepicker(elem) {
        var val = elem.text(); //получаем значение ячейки
        var code = '<input type="text" class="edit-input edit-datepicker" value="' + val + '" />';
        elem.empty().append(code);
        $(".edit-datepicker").datepicker({
            dateFormat: "yy-mm-dd",
            onClose: function() {
                var input = $(this);
                processInput(input); // Вызовите processInput при закрытии datepicker
            }
        }).focus();
    }

    // обработчик события при клике на строку
    $(function() {
        $('.editable').on('click', function(e) {
            //ловим элемент, по которому кликнули
            var t = e.target || e.srcElement;
            //получаем название тега
            var elm_name = t.tagName.toLowerCase();
            //если это инпут или селект - ничего не делаем
            if (elm_name === 'input' || elm_name === 'select') {
                return false;
            }

            var id = $(this).data('id');
            var field = this.id;

            // Если кликнутый столбец 'status_name'
            if (field === "status_name") {
                var selectedStatus = $(this).html(); //получаем значение имеющегося статуса
                //формируем код селект поля
                var code = '<select class="edit-select" id="status-' + id + '"></select>';
                //удаляем содержимое ячейки, вставляем в нее сформированное поле
                $(this).empty().append(code);
                populateStatusSelect(id, selectedStatus);
                // если другое поле, т.е. дата
            } else if ($(this).hasClass('date-field')) { // Добавленное условие для полей даты
                createDatepicker($(this));
            }

        });
    });

    // при нажатии на клавиши сохранить если Enter
    $(document).on('keydown', '.edit-input', function(event) {
        if (!$(this).hasClass('edit-datepicker')) {
            if (event.keyCode === 13) { // Если это Enter
                processInput($(this)); // Выполняется функция для инпута
            }
        }
    });

    $(document).on('keyup', '.edit-datepicker', function(event) {
        if (event.keyCode === 13) { // Если это Enter
            processInput($(this)); // Выполняется функция для инпута
        }
    });

    $(document).on('blur', '.edit-input', function() {
        if (!$(this).hasClass('edit-datepicker')) {
            processInput($(this)); // Выполняется функция для инпут
        }
    });

    function processInput(input) {
        var newVal = input.val();
        var id = input.closest('.table-data').data('id');
        var field = input.parent().attr('id');
        inputValueToServer(id, field, newVal); //передавать в функцию для сохранения на сервер
    }

    // при изменении пунктов селектора
    $(document).on('change', '.edit-select', function() {
        processSelect($(this)); // выполняется функция для селектора
    });

    function processSelect(select) {
        var newVal = select.val();
        // var id = select.data('id');
        var id = select.closest('.table-data').data('id'); // Исправлено
        var field = select.parent().attr('id');
        selectValueToServer(id, field, newVal); //передавать в функцию для сохранения на сервер
    }

    // сохранение на сервер введенных данных в инпут
    function inputValueToServer(id, field, newVal) {
        console.log("inputValueToServer called:", id, field, newVal);
        $.ajax({
            type: 'POST',
            url: 'php/update_record.php',
            data: {
                id: id,
                field: field,
                newVal: newVal
            },
            success: function(response) {
                if (response === 'Успешно') {
                    $('.table-data[data-id="' + id + '"][id="' + field + '"]').html(newVal);
                    // Создаем и стилизуем popup элемент
                    var popup = $('<div class="popup">Изменения успешно внесены.</div>');
                    $('body').append(popup);
                    popup.fadeIn();
                    // Закрываем popup 
                    setTimeout(function() {
                        // Плавно скрываем и удаляем popup
                        popup.fadeOut(function() {
                            $(this).remove();
                        });
                    }, 1200);

                    setTimeout(function() {
                        window.location.reload();
                    }, 1800);
                } else {
                    // alert('Ошибка сохранения изменений');
                    // Создаем и стилизуем popup элемент
                    var popup = $('<div class="popup">Ошибка сохранения изменений</div>');
                    $('body').append(popup);
                    popup.fadeIn();
                    // Закрываем popup 
                    setTimeout(function() {
                        // Плавно скрываем и удаляем popup
                        popup.fadeOut(function() {
                            $(this).remove();
                        });
                    }, 1200);

                    setTimeout(function() {
                        window.location.reload();
                    }, 1800);
                }
            },
            error: function() {
                // alert('Ошибка подключения к серверу');
                // Создаем и стилизуем popup элемент
                var popup = $('<div class="popup">Ошибка подключения к серверу</div>');
                $('body').append(popup);
                popup.fadeIn();
                // Закрываем popup 
                setTimeout(function() {
                    // Плавно скрываем и удаляем popup
                    popup.fadeOut(function() {
                        $(this).remove();
                    });
                }, 1200);

                setTimeout(function() {
                    window.location.reload();
                }, 1800);
            }
        });
    }

    // сохранение на сервер введенных данных в селект
    function selectValueToServer(id, field, newVal) {
        console.log("selectValueToServer called:", id, field, newVal);
        $.ajax({
            type: 'POST',
            url: 'php/update_status.php',
            data: {
                id: id,
                field: field,
                newVal: newVal
            },
            success: function(response) {
                console.log("selectValueToServer response:", response);
                // Здесь изменено сравнение с 'успешно' на 'успешно изменен статус'
                if (response.trim() === 'успешно') {
                    $('.table-data[data-id="' + id + '"][id="' + field + '"]').html(newVal);
                    $('.table-data[data-id="' + id + '"][id="status"]').html(response.trim());
                    // alert('Статус успешно изменен!');
                    // Создаем и стилизуем popup элемент
                    var popup = $('<div class="popup">Изменения успешно внесены.</div>');
                    $('body').append(popup);
                    popup.fadeIn();
                    // Закрываем popup 
                    setTimeout(function() {
                        // Плавно скрываем и удаляем popup
                        popup.fadeOut(function() {
                            $(this).remove();
                        });
                    }, 1200);

                    setTimeout(function() {
                        window.location.reload();
                    }, 1800);
                } else {
                    console.error("Unexpected response:", response);
                    // alert('Ошибка сохранения изменений');
                    var popup = $('<div class="popup">Ошибка сохранения изменений</div>');
                    $('body').append(popup);
                    popup.fadeIn();
                    // Закрываем popup 
                    setTimeout(function() {
                        // Плавно скрываем и удаляем popup
                        popup.fadeOut(function() {
                            $(this).remove();
                        });
                    }, 1200);

                    setTimeout(function() {
                        window.location.reload();
                    }, 1800);
                }
            },
            error: function(error) {
                console.error("selectValueToServer error:", error);
                // alert('Ошибка подключения к серверу');
                var popup = $('<div class="popup">Ошибка подключения к серверу</div>');
                $('body').append(popup);
                popup.fadeIn();
                // Закрываем popup 
                setTimeout(function() {
                    // Плавно скрываем и удаляем popup
                    popup.fadeOut(function() {
                        $(this).remove();
                    });
                }, 1200);

                setTimeout(function() {
                    window.location.reload();
                }, 1800);
            }
        });
    }
</script>

<!-- добавление строки -->
<script>
    // подтягивание данных из таблицы изделий на основе выбранного изделия
    function updateFields() {
        var selectedProductName = document.getElementById("productName_new").value;
        $.ajax({
            type: 'POST',
            url: 'php/get_zk_curator.php',
            data: {
                productName: selectedProductName
            },
            success: function(response) {
                var data = JSON.parse(response);
                $('input.zkNumber').val(data.zkNumber);
                $('input.curatorFio').val(data.curatorFio);
                $('input.prodOrderNum').val(data.prodOrderNum);
                $('input.factoryNumber').val(data.factoryNumber);
                $('input.kd_code').val(data.kd_code);
                $('input.prodType_id').val(data.prodType_id);
                $('input.constructor').val(data.constructor);
                $('input.director').val(data.director);
            },
            error: function(xhr, error) {
                alert('Error: ' + error);
            }
        });
    }
    document.getElementById("addEntry").addEventListener("click", function() {
        // Добавить новую строку в таблицу
        let newRowHtml = `
    <div class='d-flex table-rowMain newEntry'>
    <div class='table-row'>
    <div class="table-data" style="min-width:40px;max-width: 40px;">&nbsp;</div>
        <div class='d-flex table-cell table-data justify-content-center'>
                <input type="text" name="zkNumber" class="zkNumber" placeholder="" value="">
        </div>
        <div class='d-flex table-cell table-data justify-content-center'>
                <input type="text" name="prodOrderNum" class="prodOrderNum" placeholder="" value="">
        </div>
         <div class='d-flex table-cell table-data  justify-content-center'>
                <label class="selectField-label d-none" for="productName_new"> Выберите изделие</label>
                <select class="selectField-multiselect newEntry" id="productName_new" name="productName" required onchange="updateFields()">
                </select>
        </div>
        <div class='d-flex table-cell table-data  justify-content-center'>
                <label class="selectField-label d-none" for="userFio2_new"> Выберите ФИО</label>
                <select class="selectField-multiselect newEntry" id="userFio2_new" name="userFio2" required>
                </select>
        </div>
        <div class='d-flex table-cell table-data justify-content-center'>
                <label class="field-label d-none"> Выберите дату начала сборки </label>
                <input type="date" class="buildStart newEntry" required>
        </div>
        <div class='d-flex table-cell table-data justify-content-center'>
                <label class="field-label d-none"> Выберите дату окончания сборки </label>
                <input type="date" class="buildEnd newEntry" required>
        </div>
        <div class='d-flex table-cell table-data justify-content-center'>
                <label class="selectField-label d-none" for="userStatus_new"> Выберите статус</label>
                <select class="selectField-multiselect newEntry" id="userStatus_new" name="userStatus" required>
                </select>
        </div>
        <div class='table-data' style='max-width:50px;'> </div>
        <div class='table-data' style='max-width:40px;'> </div>
        <div class='table-data' style='max-width:40px;'> </div>
       
        <input type="text" name="curatorFio" class="curatorFio d-none" placeholder="" value="">
        <input type="text" name="factoryNumber" class="factoryNumber d-none" placeholder="" value="">
        <input type="text" name="kd_code" class="kd_code d-none" placeholder="" value="">
        <input type="text" name="prodType_id" class="prodType_id d-none" placeholder="" value="">
        <input type="text" name="constructor" class="constructor d-none" placeholder="" value="">
        <input type="text" name="director" class="director d-none" placeholder="" value="">
        </div>
</div>        
</div>`;
        // вставка строки в конце таблицы
        document.querySelector(".table-content").insertAdjacentHTML("beforeend", newRowHtml);

        // скопировать опции выбора из селектов
        document.getElementById("productName_new").innerHTML = document.getElementById("productName").innerHTML;
        document.getElementById("userFio2_new").innerHTML = document.getElementById("userFio2").innerHTML;
        document.getElementById("userStatus_new").innerHTML = document.getElementById("userStatus").innerHTML;

        // скрыть кнопку "добавить запись" и показать кнопку "сохранить запись"
        document.getElementById("addEntry").style.display = "none";
        document.getElementById("saveEntry").style.display = "block";
    });

    document.getElementById("saveEntry").addEventListener("click", function() {
        // получить все значения полей из новой строки, добавленной в таблицу
        let productName = document.querySelector(".newEntry #productName_new").value;
        let userFio2 = document.querySelector(".newEntry #userFio2_new").value;
        let buildStart = document.querySelector(".newEntry .buildStart").value;
        let buildEnd = document.querySelector(".newEntry .buildEnd").value;
        let userStatus = document.querySelector(".newEntry #userStatus_new").value;

        var v_prodOrderNum = $('input.prodOrderNum').val();
        var v_kdcode = $('input.kd_code').val();
        var v_factoryNumber = $('input.factoryNumber').val();
        var v_prodType_id = $('input.prodType_id').val();
        var v_zkNumber = $('input.zkNumber').val();
        var v_constructor = $('input.constructor').val();
        var v_director = $('input.director').val();
        var v_curator = $('input.curatorFio').val();

        // данные на сервер и сохранение в БД
        $.ajax({
            method: "POST",
            url: "php/uploadData.php",
            data: {
                productName: productName,
                userFio2: userFio2,
                buildStart: buildStart,
                buildEnd: buildEnd,
                userStatus: userStatus,
                prodOrderNum: v_prodOrderNum,
                kd_code: v_kdcode,
                factoryNumber: v_factoryNumber,
                prodType_id: v_prodType_id,
                zkNumber: v_zkNumber,
                constructor: v_constructor,
                director: v_director,
                curatorFio: v_curator
            },
            success: function(response) {
                if (response === "success") {
                    popup = $('<div class="popup">Данные успешно добавлены, уведомление отправлено монтажнику.</div>');
                } else if (response === "duplicate") {
                    popup = $('<div class="popup">Данное изделие уже существует!</div>');
                } else {
                    popup = $('<div class="popup">Произошла ошибка</div>');
                }
                $('body').append(popup);
                popup.fadeIn();
                // Закрываем popup 
                setTimeout(function() {
                    // Плавно скрываем и удаляем popup
                    popup.fadeOut(function() {
                        $(this).remove();
                    });
                }, 2000);
                // setTimeout(function() {
                //     window.location.reload();
                // }, 2100);
            },
            error: function(xhr, error) {
                console.error(error);
            },
        });
        // возвращаем кнопку добавить
        document.getElementById("addEntry").style.display = "block";
        document.getElementById("saveEntry").style.display = "none";
    });
</script>

<!-- удаляем по выбору chekbox -->
<script>
    //выбор всех чекбоксов
    $(document).on('change', 'input[type=checkbox]', function() {
        var $this = $(this),
            $chks = $(document.getElementsByName(this.name)),
            $all = $chks.filter(".chk-all");

        if ($this.hasClass('chk-all')) {
            $chks.prop('checked', $this.prop('checked'));
        } else switch ($chks.filter(":checked").length) {
            case +$all.prop('checked'):
                $all.prop('checked', false).prop('indeterminate', false);
                break;
            case $chks.length - !!$this.prop('checked'):
                $all.prop('checked', true).prop('indeterminate', false);
                break;
            default:
                $all.prop('indeterminate', true);
        }
    });

    // открытие модального окна
    function openMultiModalDelete() {
        $('.MultiDeleteModal').show();
    }
    // закрытие модального окна
    function closeMultiModalDelete() {
        $('.MultiDeleteModal').hide();
    }

    // обработчик события для кнопки
    $('body').on('click', '.checkDelete', function() {
        const checkboxes = document.querySelectorAll('input[name="file"]:checked:not(.chk-all)');
        // Проверка на выбранные чекбоксы
        if (checkboxes.length === 0) {
            const notification = document.getElementById("notification");
            notification.innerText = "Выберите записи для удаления";
            notification.style.display = "block"; // Показать уведомление
            setTimeout(function() {
                notification.style.display = "none";
            }, 3000);
            return;
        }

        // data-id из элемента
        var data_id = $(this).data('id');
        // data-id в кнопку "Да"
        $('#confirmMultiDeleteButton').data('id', data_id);
        // функция для открытия модального окна
        openMultiModalDelete();
    });
    // закрытие модального окна при клике на кнопку "Нет"
    $('.multiClose-button, #cancelMultiDeleteButton').on('click', function() {
        // функция для закрытия модального окна
        closeMultiModalDelete();
    });
    // закрытие модалки если клик вне его
    $(window).on('click', function(event) {
        if (event.target == $('.MultiDeleteModal')[0]) {
            // функция для закрытия модального окна
            closeMultiModalDelete();
        }
    });

    // клик на кнопку "Да"
    $('#confirmMultiDeleteButton').on('click', function() {
        var v_idDelete = $(this).data('id');
        const checkboxes = document.querySelectorAll('input[name="file"]:checked:not(.chk-all)');
        // async function deleteChecked() {
        const allPromises = Array.from(checkboxes).map(async function(checkbox) {
            var v_idDelete = checkbox.getAttribute('data-id');
            console.log(v_idDelete + '\n');

            $.ajax({
                    method: "POST",
                    url: "php/deleteDataMulti.php",
                    data: {
                        id: v_idDelete
                    }
                })
                .done(function() {
                    var popup = $('<div class="popup">Записи были удалены</div>');
                    $('body').append(popup);
                    popup.fadeIn();
                    setTimeout(function() {
                        popup.fadeOut(function() {
                            $(this).remove();
                        });
                    }, 1200);
                    setTimeout(function() {
                        window.location.reload();
                    }, 1800);
                });
            // функция для закрытия модального окна после выполнения AJAX-запроса
            closeMultiModalDelete();
        });
    });
</script>
