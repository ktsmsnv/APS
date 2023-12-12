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
informationBoard.curator,
status.name as status_name
FROM informationBoard  
INNER JOIN status ON informationBoard.status_id = status.id
INNER JOIN prodType ON informationBoard.prodType_id = prodType.id 
WHERE informationBoard.curator = '$roleFio'
ORDER BY id DESC";
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
       <div class='header__item' style='max-width:50px;'><a id='a' class='filter__link'></a></div>
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
        echo "<div class='table-data'> " . date('d.m.Y', strtotime(out_inform_Panel_data_protect($row["buildStart"]) )). "</div>";
        echo "<div class='table-data'> " . date('d.m.Y', strtotime(out_inform_Panel_data_protect($row["buildEnd"]) )). "</div>";
        echo "<div class= 'table-data " . out_inform_Panel_data_protect($row["status_name"]) . "'><p>" . out_inform_Panel_data_protect($row["status_name"]) . "</p></div>";
        echo "<div class='table-data' style='max-width:50px;'>
            <a href='#'
            data-url1='" . urlencode($file1) . "'
            data-url2='" . urlencode($secondFilePath) . "'
                class='downloadButton-" . $row["id"] . "'>
                <img src='images/download-solid.svg'>
            </a>
        </div>";
        echo "<div class='table-data' style='max-width:50px;'>
        <a href='#'
            class='sendDeleteSvg' data-id=" . $row["prodOrderNum"] . " >
            <img src='images/delete.svg'>
        </a>
        </div>";

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

        echo "<div class='table-data' style='max-width:50px;'> ",
            "<a class='downloadHistory' target='_blank' href='/history.php?prodOrderNum=" . out_inform_Panel_data_protect($row["prodOrderNum"]) . "'><img src='images/history.svg' style='width:25px;'></a>
        </div>";

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
    echo " <button id='feedbackButton' class='btn btn-primary'>Обратная связь</button>";
    echo "</div>";
    echo " <div id='notification' class='notification'></div>";
    echo "</div>";
    mysqli_free_result($result);
}

?>

<script src="https://cdn.jsdelivr.net/npm/jszip@3.5.0/dist/jszip.min.js"></script>
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
                success: function (response) {
                    // Создаем и стилизуем popup элемент
                    var popup = $('<div class="popup">Успешно отправлено!</div>');
                        $('body').append(popup);
                        popup.fadeIn();
                        // Закрываем popup 
                        setTimeout(function () {
                            // Плавно скрываем и удаляем popup
                            popup.fadeOut(function () {
                                $(this).remove();
                            });
                        }, 1800);

                    $("#feedbackForm")[0].reset();
                    $("#ratingValue").text("5");
                    $("#feedbackModal").removeClass("open");
                },
                error: function (xhr) {
                   // Создаем и стилизуем popup элемент
                        var popup = $('<div class="popup">Ошибка при отправке. Напишите на почту aps@kst-energo.ru</div>');
                        $('body').append(popup);
                        popup.fadeIn();
                        // Закрываем popup 
                        setTimeout(function () {
                            // Плавно скрываем и удаляем popup
                            popup.fadeOut(function () {
                                $(this).remove();
                            });
                        }, 1200);
                }
            });
            feedbackModal.style.display = "none";
        });
</script>
<!-- скачивание по чекбоксам -->
<script>
      //выбор всех чекбоксов
        $(document).on('change', 'input[type=checkbox]', function () {
            var $this = $(this), $chks = $(document.getElementsByName(this.name)), $all = $chks.filter(".chk-all");

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

            const allPromises = Array.from(checkboxes).map(async function (checkbox) {
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
                    '&productName=' + encodeURIComponent(productName) ;
                var url3 = 'php/OperCardExplosion.php?id=' + id + '&kd_code=' + encodeURIComponent(kd_code) + '&factoryNumber=' + factoryNumber+
                    '&productName=' + encodeURIComponent(productName) ;

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
                type: 'blob'
            });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'документы.zip';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            // Сбрасываем состояние checked для всех checkbox
            checkboxes.forEach(function (checkbox) {
                checkbox.checked = false;
            });
        }
</script>

<!-- удаляем по выбору chekbox -->
<script>
    //выбор всех чекбоксов
    $(document).on('change', 'input[type=checkbox]', function () {
        var $this = $(this), $chks = $(document.getElementsByName(this.name)), $all = $chks.filter(".chk-all");

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
            setTimeout(function () {
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
            const allPromises = Array.from(checkboxes).map(async function (checkbox) {
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