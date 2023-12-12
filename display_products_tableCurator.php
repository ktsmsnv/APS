<?php
error_reporting(E_ALL); ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set('display_errors', 1);
$sql = "SELECT products.id,
products.prod_name, 
products.zkNum,
products.prodOrder,
products.factoryNum,
products.constructorName AS product_constructor_name,
products.directorName,
products.kd_prodCode,
products.quantity,
products.prod_type AS product_type,
prodType.name AS product_type_name
FROM products
INNER JOIN prodType ON prodType.id = products.prod_type
INNER JOIN `curators` ON products.curatorName = `curators`.curatorFio
WHERE `curators`.curatorFio = '$curatorRole'
ORDER BY id";

if ($result = mysqli_query($conn, $sql)) {

    $rowsCount = mysqli_num_rows($result); // количество полученных строк
    // echo "<p>Получено объектов: $rowsCount</p>";

    echo "<div class='table CuratorTable' id='products-table'>
    <div class='table-header'>  
    
    <div class='header__item' style='max-width:40px;'> 
        <input type='checkbox' name='file' class='chk-all styled-checkbox'/>
     </div>
 
        <div class='header__item'  style='max-width:40px;'><a id='id' class='filter__link--number' href='#'>ID</a></div>
        <div class='header__item'  style='max-width:90px;'><a id='zk' class='filter__link--number' href='#'>ЗК №</a></div>
        <div class='header__item'><a id='const' class='filter__link' href='#'>Конструктор</a></div>
        <div class='header__item' style='max-width:230px;'><a id='product' class='filter__link' href='#'>Наименование</a></div>
        <div class='header__item' style='max-width:80px;'><a id='quantity' class='filter__link' href='#'>Кол-во</a></div>
        <div class='header__item' style='max-width:280px;'><a id='kd' class='filter__link' href='#'>КД шифр</a></div>
        <div class='header__item' style='max-width:90px;'><a id='OrderNum' class='filter__link' href='#'>№ ЗПр.</a></div>
        <div class='header__item' style='max-width:120px;'><a id='factoryNum' class='filter__link' href='#'>Зав. №.</a></div>
        <div class='header__item' style='max-width:280px;'><a id='prodtype' class='filter__link' href='#'>Тип изделия</a></div>
        <div class='header__item'><a id='dir' class='filter__link' href='#'>Мастер участка</a></div>
        <div class='header__item'  style='max-width:40px;'><a id='h'></a></div>   
        <div class='header__item' style='max-width: 45px;'><a id='h' class='filter__link'></a></div>
    </div>
    <div class='table-content curatorTable'>";
    foreach ($result as $row) {
        echo "<div class='d-flex table-rowMain'>";
        echo "<div class='table-row' id='row_" . out_inform_Panel_data_protect($row["id"]) . "'  data-id='{$row["id"]}'>";

        echo "<div class='table-data' style='max-width:40px;'><input type='checkbox' name='file' class='styled-checkbox' data-id='" . $row["id"] . "'/></div>";

        echo "<div class='table-data'  style='max-width:40px;' id='info_id' data-id='{$row["id"]}'> " . out_inform_Panel_data_protect($row["id"]) . "</div>";
        echo "<div class='table-data editable' style='max-width:90px;' id='zkNum' data-id='{$row["id"]}'> " . out_inform_Panel_data_protect($row["zkNum"]) . "</div>";
        echo "<div class='table-data editable select-field selectConstructor' id='constructorName' data-id='{$row["id"]}'> " . out_inform_Panel_data_protect($row["product_constructor_name"]) . "</div>";
        echo "<div class='table-data editable' id='prod_name' data-id='{$row["id"]}' style='max-width:230px;'> " . out_inform_Panel_data_protect($row["prod_name"]) . "</div>";
        echo "<div class='table-data editable number-field' style='max-width:80px;' id='quantity' data-id='{$row["id"]}'> " . out_inform_Panel_data_protect($row["quantity"]) . "</div>";
        echo "<div class='table-data editable' style='max-width:280px;' id='kd_prodCode' data-id='{$row["id"]}'> " . out_inform_Panel_data_protect($row["kd_prodCode"]) . "</div>";
        echo "<div class='table-data editable' style='max-width:90px;' id='prodOrder' data-id='{$row["id"]}'> " . out_inform_Panel_data_protect($row["prodOrder"]) . "</div>";
        echo "<div class='table-data editable data-list' style='max-width:120px;' id='factoryNum' data-id='{$row["id"]}'> " . out_inform_Panel_data_protect($row["factoryNum"]) . "</div>";
        echo "<div class='table-data editable select-field selectProdType' style='max-width:280px;' id='prod_type' data-id='{$row["id"]}'> " . out_inform_Panel_data_protect($row["product_type_name"]) . "</div>";
        echo "<div class='table-data editable select-field selectDirector' id='directorName' data-id='{$row["id"]}'> " . out_inform_Panel_data_protect($row["directorName"]) . "</div>";
        echo "<div class='table-data' style='max-width:40px;'><a href='#' class='edit-button' data-id='" . $row["id"] . "'><img src='images/pencil.svg'></a></div>";
        echo "<div class='table-data' style='max-width:45px;'><a href='#' class='sendDeleteCurator' data-id=" . $row["id"] . " ><img src='images/delete.svg'></a></div>";
        echo "</div>";
        echo "</div>";
    }
    echo "</div>";

    echo "<div class='d-flex justify-content-between align-items-end'>";

    echo "<div class='d-flex align-items-center AllBtns'>";
    echo "<p>С отмеченными: </p>";
    echo "<a id='checkDeleteProducts' class='checkDeleteProducts' type='button' value='Удалить выбранное'><img src='images/delete.svg'> </a>";
    echo "</div>";
    echo "<div class='d-flex flex-column align-items-center justify-content-center'>";
    echo "<button id='addEntry'>Добавить запись <img src='images/row-plus.svg'> </button>";
    echo "<button id='saveEntry'>Сохранить запись</button>";
    echo "</div>";
    echo " <button id='feedbackButton2' class='btn btn-primary'>Обратная связь</button>";
    echo "</div>";
    echo " <div id='notification2' class='notification2'></div>";
    echo "</div>";
    mysqli_free_result($result);
}
?>

<!-- обратная связь -->
<script>
    // Получение элементов модального окна и кнопки
    const feedbackModal2 = document.getElementById("feedbackModal");
    const feedbackButton2 = document.getElementById("feedbackButton2");
    const closeModal2 = document.getElementById("closeModal");

    // Получение элементов ползунка и элемента для отображения значения
    const ratingInput2 = document.getElementById("rating");
    const ratingValue2 = document.getElementById("ratingValue");

    // Обработчик события input для обновления значения в <output>
    ratingInput2.addEventListener("input", () => {
        ratingValue2.textContent = ratingInput2.value;
    });

    // Обработчик клика на кнопку
    feedbackButton2.addEventListener("click", () => {
        feedbackModal2.style.display = "flex";
    });

    // Обработчик клика на крестик для закрытия модального окна
    closeModal2.addEventListener("click", () => {
        feedbackModal2.style.display = "none";
    });

    // Обработчик клика на фон модального окна для закрытия модального окна
    feedbackModal2.addEventListener("click", (event) => {
        if (event.target === feedbackModal) {
            feedbackModal2.style.display = "none";
        }
    });

    // Обработчик отправки формы
    const feedbackForm2 = document.getElementById("feedbackForm");
    feedbackForm2.addEventListener("submit", (event) => {
        event.preventDefault();
        let formData = new FormData(feedbackForm2);
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
                var popup = $('<div class="popup">Ошибка при отправке. Напишите на почту aps@kst-energo.ru</div>');
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
        feedbackModal2.style.display = "none";
    });
</script>

<!--удаление  -->
<!-- Скрипт открытия модального окна удаления и удаления записи -->
<script>
    // открытие модального окна
    function openModal_Products() {
        $('.DeleteModal_Products').show();
    }
    // закрытие модального окна
    function closeModalDelete_Products() {
        $('.DeleteModal_Products').hide();
    }
    // обработчик события для кнопки
    $('body').on('click', '.sendDeleteCurator', function() {
        // data-id из элемента
        var prodIdDelete = $(this).data('id');
        // data-id в кнопку "Да"
        $('#confirmDeleteButton_Products').data('id', prodIdDelete);
        // функция для открытия модального окна
        openModal_Products();
    });
    // закрытие модального окна при клике на кнопку "Нет"
    $('.close_Products-button, #cancelDeleteButton_Products').on('click', function() {
        // функция для закрытия модального окна
        closeModalDelete_Products();
    });
    // закрытие модалки если клик вне его
    $(window).on('click', function(event) {
        if (event.target == $('.DeleteModal_Products')[0]) {
            // функция для закрытия модального окна
            closeModalDelete_Products();
        }
    });
    // клик на кнопку "Да"
    $('#confirmDeleteButton_Products').on('click', function() {
        var v_prodIdDelete = $(this).data('id');
        $.ajax({
                method: "POST",
                url: "php/deleteDataCurator.php",
                data: {
                    prodIdDelete: v_prodIdDelete
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
        closeModalDelete_Products();
    });
</script>

<!-- редактирование в строке -->
<script>
    function getProductSelect(id) {
        $.ajax({
            type: 'POST',
            url: 'php/edit_prodtype.php',
            data: {
                id: id,
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
                data.allTypes.forEach(function(type) {
                    var isSelected = type.id === data.selectedType ? 'selected' : '';
                    options += `<option value="${type.id}" ${isSelected}>${type.name}</option>`;
                });

                $('#product_select-' + id).empty().append(options);
            },
            error: function() {
                alert('Ошибка подключения к серверу');
            }
        });
    }

    function getConstructors(id) {
        $.ajax({
            type: "POST",
            url: "php/edit_constructors.php",
            data: {
                id: id
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
                var options = "";
                data.allConstructors.forEach(function(constructor) {
                    var isSelected = constructor.fio === data.selectedConstructor ? "selected" : "";
                    options += `<option value="${constructor.fio}" ${isSelected}>${constructor.fio}</option>`;
                });

                $("#constructor_select-" + id).empty().append(options);
            },
            error: function() {
                alert("Ошибка подключения к серверу");
            }
        });
    }

    function getMasters(id) {
        $.ajax({
            type: "POST",
            url: "php/edit_masters.php",
            data: {
                id: id
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
                var options = "";
                data.allMasters.forEach(function(master) {
                    var isSelected = master.fio === data.selectedMaster ? "selected" : "";
                    options += `<option value="${master.fio}" ${isSelected}>${master.fio}</option>`;
                });

                $("#master_select-" + id).empty().append(options);
            },
            error: function() {
                alert("Ошибка подключения к серверу");
            }
        });
    }

    // обработчик события по клику на редактируемые строки
    $(function() {
        $('.curatorTable').on('click', '.edit-button', function(e) {
        // $('.edit-button').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var rowElement = $("#row_" + id);
            var editableElements = rowElement.find('.editable');

            editableElements.each(function() {
                var element = $(this);

                if (!element.find('input,select').length) {
                    var field = element.attr('id');
                    if (element.hasClass('selectProdType')) {
                        var selectedValue = element.html();
                        var code = '<select class="edit-select" id="product_select-' + id + '"></select>';
                        element.empty().append(code);
                        getProductSelect(id, selectedValue);
                    } else if (element.hasClass('selectConstructor')) {
                        var selectedValue = element.html();
                        var code = '<select class="edit-select" id="constructor_select-' + id + '"></select>';
                        element.empty().append(code);
                        getConstructors(id, selectedValue);
                    } else if (element.hasClass('selectDirector')) {
                        var selectedValue = element.html();
                        var code = '<select class="edit-select" id="master_select-' + id + '"></select>';
                        element.empty().append(code);
                        getMasters(id, selectedValue);
                    } else {
                        var val = element.html();
                        var code = '<input type="text" class="edit-input" value="' + val + '" />';
                        element.empty().append(code).find('.edit-input').focus();
                    }
                }
            });
        });
    });

    // при нажатии на клавиши сохранить если Enter
    $(document).on('keydown', '.edit-input', function(event) {
        if (event.keyCode === 13) { // Если это Enter
            saveRow($(this).closest('.table-row'));
        }
    });

    function saveRow(rowElement) {
        var id = rowElement.data('id');
        var data = {
            id: id,
            fields: []
        };

        rowElement.find('.editable').each(function() {
            var fieldElement = $(this);
            var field = fieldElement.attr('id');
            var fieldValue;

            if (fieldElement.find('.edit-input').length) {
                fieldValue = fieldElement.find('.edit-input').val().trim();
            } else if (fieldElement.find('.edit-select').length) {
                fieldValue = fieldElement.find('.edit-select').val();
            } else {
                return;
            }

            data.fields.push({
                field: field,
                value: fieldValue
            });
        });
        saveDataToServer(data);
        console.log(data);
    }
    // Функция для сохранения данных на сервере
    function saveDataToServer(data) {
        console.log("saveDataToServer called:", data);

        $.ajax({
            type: 'POST',
            url: 'php/save_row_data.php',
            data: data,
            success: function(response) {
                var popup = $('<div class="popup">Изменения успешно внесены.</div>');
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
            },
            error: function() {
                var popup = $('<div class="popup">Ошибка подключения к серверу</div>');
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
            }
        });
    }
</script>


<!-- ДОБАВЛЕНИЕ В ТАБЛИЦЕ -->
<script>
    // Добавляем счетчик строк
    let rowCounter = 0;
    var curatorInputFio = "<?php echo htmlspecialchars($_SESSION['fio']); ?>";
    document.getElementById("addEntry").addEventListener("click", function() {
        // Добавить новую строку в таблицу
        let newRowHtml = `
    <div class='d-flex table-rowMain newEntry'>
    <div class='table-row'>
    <div class="table-data" style="min-width:40px;max-width: 40px;">&nbsp;</div>
    <div class="table-data" style="min-width:40px;max-width: 40px;">&nbsp;</div>
        <div class='d-flex table-cell table-data justify-content-center' style="max-width:90px">
                <input type="text" name="zkNum" class="zkNum" required placeholder="ЗК№" value="" style="max-width:90px">
        </div>
        <div class='d-flex table-cell table-data justify-content-center'>
                <label class="selectField-label d-none" for="constructorName"> Выберите конструктора</label>
                <select class="selectField-multiselect newEntry constructorName_${rowCounter}" id="constructorName_new_${rowCounter}" name="constructorName" required>
                </select>
        </div>
        <div class='d-flex table-cell table-data  justify-content-center' style="max-width:230px;">
                <input type="text" name="prod_name" class="prod_name" required placeholder="наименование" value="" style="max-width:230px;">
        </div>
        <div class='d-flex table-cell table-data  justify-content-center' style="max-width:80px">
                <input type="text" name="quantity" class="quantity" required placeholder="кол-во" value="" style="max-width:100px">
        </div>
        <div class='d-flex table-cell table-data justify-content-center'  style="max-width:280px">
                <input type="text" name="kd_prodCode" class="kd_prodCode" required placeholder="кд шифр" value="" style="max-width:280px">
        </div>
        <div class='d-flex table-cell table-data justify-content-center' style="max-width:90px"> 
                <input type="text" name="prodOrder" class="prodOrder" required placeholder="№ЗПр" value="" style="max-width:90px"> 
        </div>
         <div class='d-flex table-cell table-data  justify-content-center' style="max-width:120px">
                <input list="factoryNumbs" name="factoryNum" class="factoryNum" required placeholder="Зав.№" value="" style="max-width:120px" />
                    <datalist id="factoryNumbs">
                        <option value="ARM">
                        <option value="KPP">
                        <option value="KIT">
                        <option value="KPT">
                        <option value="KAT">
          </datalist>
        </div>
        <div class='d-flex table-cell table-data justify-content-center'  style="max-width:280px">
                <label class="selectField-label d-none" for="prod_type"> Выберите тип изделия</label>
                <select class="selectField-multiselect newEntry  prod_type_${rowCounter}" id="prod_type_new_${rowCounter}"  name="prod_type" required style="max-width:280px">
                </select>
        </div>
        <div class='d-flex table-cell table-data justify-content-center'>
                <label class="selectField-label d-none" for="directorName"> Выберите мастера участка</label>
                <select class="selectField-multiselect newEntry directorName_${rowCounter}" id="directorName_new_${rowCounter}" name="directorName" required>
                </select>
        </div>
 <input type="text" name="curatorName" class="curatorName d-none" value="${curatorInputFio}" required>
        <div class="table-data" style="min-width:40px;max-width: 40px;">&nbsp;</div>
        <div class="table-data" style="min-width:40px;max-width: 45px;">&nbsp;</div>
        </div>        
        </div>`;
        rowCounter++;
        newRowHtml = newRowHtml.replace('${curatorInputFio}', curatorInputFio);
        // вставка строки в конце таблицы
        document.querySelector(".curatorTable").insertAdjacentHTML("beforeend", newRowHtml);
        populateProductTypes(`prod_type_new_${rowCounter - 1}`);
        populateConstructors(`constructorName_new_${rowCounter - 1}`);
        populateMasters(`directorName_new_${rowCounter - 1}`);

        // скрыть кнопку "добавить запись" и показать кнопку "сохранить запись"
        // document.getElementById("addEntry").style.display = "none";
        document.getElementById("saveEntry").style.display = "block";

        // типы изделий
        function populateProductTypes(selectId) {
            const selectElement = document.getElementById(selectId);
            selectElement.innerHTML = "";

            // Создание элемента "defaultOption"
            const defaultOption = document.createElement("option");
            defaultOption.value = "";
            defaultOption.text = "тип изделия";

            // Добавление элемента "defaultOption" в начало списка
            selectElement.add(defaultOption, selectElement[0]);

            // Получение и добавление списка из базы данных
            $.ajax({
                method: "GET",
                url: "php/get_prodTypesAll.php",
                success: function(response) {
                    console.log('Сервер ответил:', response);
                    const productTypes = JSON.parse(response);
                    console.log('Список изделий:', productTypes);
                    // Добавление элементов <option> списка в <select>
                    for (let i = 0; i < productTypes.length; i++) {
                        const option = document.createElement("option");
                        option.value = productTypes[i].name;
                        option.text = productTypes[i].name;
                        selectElement.add(option);
                    }
                },
                error: function(xhr, error) {
                    console.error(error);
                }
            });
        }

        function populateConstructors(selectId) {
            const selectElement = document.getElementById(selectId);
            selectElement.innerHTML = "";

            // Создание элемента "defaultOption"
            const defaultOption = document.createElement("option");
            defaultOption.value = "";
            defaultOption.text = "конструктор:";

            // Добавление элемента "defaultOption" в начало списка
            selectElement.add(defaultOption, selectElement[0]);

            // Получение и добавление списка из базы данных
            $.ajax({
                method: "GET",
                url: "php/get_constructors.php",
                success: function(response) {
                    console.log('Сервер ответил:', response);
                    const constructors = JSON.parse(response);
                    console.log('Список кураторов:', constructors);
                    // Добавление элементов <option> списка в <select>
                    for (let i = 0; i < constructors.length; i++) {
                        const option = document.createElement("option");
                        option.value = constructors[i].fio;
                        option.text = constructors[i].fio;
                        selectElement.add(option);
                    }
                },
                error: function(xhr, error) {
                    console.error(error);
                }
            });
        }

        // мастера участка
        function populateMasters(selectId) {
            const selectElement = document.getElementById(selectId);
            selectElement.innerHTML = "";

            // Создание элемента "defaultOption"
            const defaultOption = document.createElement("option");
            defaultOption.value = "";
            defaultOption.text = "мастер участка";

            // Добавление элемента "defaultOption" в начало списка
            selectElement.add(defaultOption, selectElement[0]);

            // Получение и добавление списка из базы данных
            $.ajax({
                method: "GET",
                url: "php/get_masters.php",
                success: function(response) {
                    const masters = JSON.parse(response);
                    // Добавление элементов <option> списка в <select>
                    for (let i = 0; i < masters.length; i++) {
                        const option = document.createElement("option");
                        option.value = masters[i].fio;
                        option.text = masters[i].fio;
                        selectElement.add(option);
                    }
                },
                error: function(xhr, error) {
                    console.error(error);
                }
            });
        }
    });

    $("#saveEntry").on("click", function() {
        const newEntries = $(".table-rowMain.newEntry");
        const dataArray = [];
        newEntries.each(function(rowCounter, entry) {
            const prod_type = ($(`.prod_type_${rowCounter}`, entry).val());
            const directorName = ($(`.directorName_${rowCounter}`, entry).val());
            const constructorName = ($(`.constructorName_${rowCounter}`, entry).val());
            const prod_name = $(".prod_name", entry).val();
            const quantity = $(".quantity", entry).val();
            const zkNum = $(".zkNum", entry).val();
            const curatorName = $(".curatorName", entry).val();
            const prodOrder = $(".prodOrder", entry).val();
            const factoryNum = $(".factoryNum", entry).val();
            const kd_prodCode = $(".kd_prodCode", entry).val();

            // объект, содержащий данные для одной строки
            const entryData = {
                prod_name,
                quantity,
                zkNum,
                curatorName,
                prodOrder,
                factoryNum,
                prod_type,
                kd_prodCode,
                constructorName,
                directorName
            };

            // объект в массив данных
            dataArray.push(entryData);

            console.log(entryData);
        });

        // Отправить данные на сервер
        $.ajax({
            method: "POST",
            url: "php/uploadDataCurator.php",
            data: {
                data: JSON.stringify(dataArray)
            },
            success: function(response) {
                // if (response === "success") {
                var popup = $('<div class="popup">Данные успешно добавлены, уведомление отправлено всем мастерам.</div>');
                // } else if (response === "duplicate") {
                //     popup = $('<div class="popup">Данное изделие уже существует!</div>');
                // } else {
                //     popup = $('<div class="popup">Произошла ошибка</div>');
                // }
                $('body').append(popup);
                popup.fadeIn();
                // Закрываем popup
                setTimeout(function() {
                    // Плавно скрываем и удаляем popup
                    popup.fadeOut(function() {
                        $(this).remove();
                    });
                }, 2000);
                setTimeout(function() {
                    window.location.reload();
                }, 2100);
            },
            error: function(xhr, error) {
                console.error(error);
            },
        });

        // Скрыть кнопку "сохранить запись"
        document.getElementById("saveEntry").style.display = "none";
    });
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
    $('body').on('click', '.checkDeleteProducts', function() {
        const checkboxes = document.querySelectorAll('input[name="file"]:checked:not(.chk-all)');
        // Проверка на выбранные чекбоксы
        if (checkboxes.length === 0) {
            const notification = document.getElementById("notification2");
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
                    url: "php/deleteDataMulti_products.php",
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