<footer>
    <!-- вывод асинхронного времени -->
    <script>
        var time = setInterval(function () {
            var date = new Date();
            document.getElementById("time").innerHTML = (date.getDate() + "." + (date.getMonth() + 1) + "." + date.getFullYear() + "<br>" + date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds());
        }, 1000);
    </script>
    <!-- Автоматический скрол вниз страницы при открытии кнопок "Добавить", "Изменить", "Удалить" -->
    <script>
        $(function () {
            $('#scroll_bottomAdd').click(function () {
                $('html, body').animate({ scrollTop: $(document).height() - $(window).height() }, 100);
                return false;
            });
        });

        $(function () {
            $('#scroll_bottomEdit').click(function () {
                $('html, body').animate({ scrollTop: $(document).height() - $(window).height() }, 100);
                return false;
            });
        });

        $(function () {
            $('#scroll_bottomDelete').click(function () {
                $('html, body').animate({ scrollTop: $(document).height() - $(window).height() }, 100);
                return false;
            });
        });
    </script>
    <!-- добавление -->
    <script>
        $(document).ready(function () {
            $('button.sendDataInDB').on('click', function () {
                var v_prodOrderNum = $('input.prodOrderNum').val();
                var v_kdcode = $('input.kd_code').val();
                var v_userFio = $('#userFio2').val();
                var v_productName = $('#productName').val();
                var v_factoryNumber = $('input.factoryNumber').val();
                var v_prodType_id = $('input.prodType_id').val();
                var v_zkNumber = $('input.zkNumber').val();
                var v_buildStart = $('input.buildStart').val();
                var v_buildEnd = $('input.buildEnd').val();
                var v_userStatus = $('#userStatus').val();
                var v_constructor = $('input.constructor').val();
                var v_director = $('input.director').val();
                var v_curator = $('input.curatorFio').val();

                $.ajax({
                    method: "POST",
                    url: "php/uploadData.php",
                    data: {
                        prodOrderNum: v_prodOrderNum,
                        kd_code: v_kdcode,
                        userFio2: v_userFio,
                        productName: v_productName,
                        factoryNumber: v_factoryNumber,
                        prodType_id: v_prodType_id,
                        zkNumber: v_zkNumber,
                        buildStart: v_buildStart,
                        buildEnd: v_buildEnd,
                        userStatus: v_userStatus,
                        constructor: v_constructor,
                        director: v_director,
                        curatorFio: v_curator
                    },
                    success: function (response) {
                        var popup;
                        if (response === "success") {
                            popup = $('<div class="popup">Данные успешно добавлены, уведомление отправлено монтажнику.</div>');
                        } else if (response === "duplicate") {
                            popup = $('<div class="popup">Данное изделие уже существует!</div>');
                        } else {
                            popup = $('<div class="popup">Произошла ошибка</div>');
                        }
                        console.log(response);
                        $('body').append(popup);
                        popup.fadeIn();
                        // Закрываем popup 
                        setTimeout(function () {
                            // Плавно скрываем и удаляем popup
                            popup.fadeOut(function () {
                                $(this).remove();
                            });
                        }, 2000);
                        setTimeout(function () {
                            window.location.reload();
                        }, 2100);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error(textStatus + ': ' + errorThrown);
                    }
                });

                $('input.prodOrderNum').val('');
                $('input.kd_code').val('');
                $('#userFio2').val('');
                $('#productName').val('');
                $('input.factoryNumber').val('');
                $('input.prodType_id').val('');
                $('input.zkNumber').val('');
                $('input.buildStart').val('');
                $('input.buildEnd').val('');
                $('#userStatus').val('');
                $('input.constructor').val('');
                $('input.director').val('');
                $('input.curatorFio').val('');
            })
        });
    </script>
    <!-- подсветка полей -->
    <script>
        const form = document.querySelector('.panel.add');
        const button = document.querySelector('.sendDataInDB');

        form.addEventListener('input', () => {
            const requiredInputs = form.querySelectorAll('input[required]');

            let allFieldsFilled = true;

            requiredInputs.forEach(input => {
                if (input.value.trim() === '') {
                    input.classList.add('error');
                    input.previousElementSibling.style = "color: red;"
                    input.nextElementSibling.style.display = 'block';
                    allFieldsFilled = false;
                } else {
                    input.classList.remove('error');
                    input.previousElementSibling.style = "color: rgb(25, 141, 15);"
                    input.nextElementSibling.style.display = 'none';
                }
            });
            const requiredSelects = form.querySelectorAll('select[required]');
            requiredSelects.forEach(select => {
                if (select.value.trim() === '') {
                    select.classList.add('error');
                    select.previousElementSibling.style = "color: red;"
                    select.nextElementSibling.style.display = 'block';
                } else {
                    select.classList.remove('error');
                    select.classList.add('valid');
                    select.previousElementSibling.style = "color: rgb(25, 141, 15);"
                    select.nextElementSibling.style.display = 'none';
                }
            });

            button.disabled = !allFieldsFilled;
        });
    </script>
    <!-- изменение (вывод данных) -->
    <script>
        // Вывод списка номеров, на основе выбранного  фио; вывод списка статусов на основе выбранного номера
        $(document).ready(function () {
            // вывод фио на основе prodOrderNum
            function userFio() {
                var prodOrderNum = $('#prodOrderNum').val();
                $.ajax({
                    type: 'POST',
                    url: 'php/getNumbers.php',
                    data: {
                        prodOrderNum: prodOrderNum,
                    },
                    success: function (data) {
                        $('#userFio').html(data);
                        var userFioSelect = $('#userFio');
                        userFioSelect.html('<option value="" selected="" disabled>Выберите ФИО</option>' + data);
                        $('#userFio').trigger('change');
                    }
                });
            }
            // вывод типов изделия на основе userFio и prodOrderNum
            function prodType() {
                var selectedNumber2 = $('#userFio').val();
                var selected_prodOrderNum = $('#prodOrderNum').val();
                $.ajax({
                    type: 'POST',
                    url: 'php/getProdType.php',
                    data: {
                        userFio: selectedNumber2,
                        prodOrderNum: selected_prodOrderNum,
                    },
                    success: function (data) {
                        // console.log(data);
                        // $('#prodType').empty();
                        // $('#prodType').append(data);
                        $('#prodType').html(data);
                    }
                });
            }
            // вывод статусов на основе userFio и prodOrderNum
            function Status() {
                var selectedNumber = $('#userFio').val();
                var selected_prodOrderNum = $('#prodOrderNum').val();
                $.ajax({
                    type: 'POST',
                    url: 'php/getStatus.php',
                    data: {
                        userFio: selectedNumber,
                        prodOrderNum: selected_prodOrderNum,
                    },
                    success: function (data) {
                        $('#status').html(data);
                    }
                });
            }

            function Curators() {
                var selectedNumber3 = $('#userFio').val();
                var selected_prodOrderNum = $('#prodOrderNum').val();
                $.ajax({
                    type: 'POST',
                    url: 'php/getCurators.php',
                    data: {
                        userFio: selectedNumber3,
                        prodOrderNum: selected_prodOrderNum,
                    },
                    success: function (data) {
                        // console.log(data);
                        $('#curator').html(data);
                    }
                });
            }

            $('#prodOrderNum').change(function () {
                userFio();
            });
            $('#userFio').change(function () {
                prodType();
            });

            $('#userFio').change(function () {
                Status();
            });
            $('#userFio').change(function () {
                Curators();
            });

            userFio();
            prodType();
            Status();
            Curators();

            // Вывод остальных данных, на основе выбранного  фио и номера
            $("#userFio").change(function () {
                var userFio = $(this).val();
                var prodOrderNum = $('#prodOrderNum').val();
                var dataString = {
                    prodOrderNum: prodOrderNum,
                    userFio: userFio,
                };
                $.ajax({
                    url: 'php/dataAjax.php',
                    type: 'POST',
                    data: dataString,
                    dataType: 'json',
                    success: function (response) {

                        var vals = response;
                        $("input[name='id']").val(vals.id);
                        $("input[name='productName']").val(vals.productName);
                        $("input[name='factoryNumber']").val(vals.factoryNumber);
                        $("input[name='kd_code']").val(vals.kd_code);
                        $("input[name='zkNumber']").val(vals.zkNumber);
                        $("input[name='constructor']").val(vals.constructor);
                        $("input[name='director']").val(vals.director);
                        $("input[name='buildStart']").val(vals.buildStart);
                        $("input[name='buildEnd']").val(vals.buildEnd);
                    }
                });
            });
        });
    </script>
    <!-- изменение (сохранение + увед) -->
    <script>
        $(document).ready(function () {
            //получение данных по ФИО и сохранение в бд
            function updateFormData() {
                var userFio = $('#userFio').val();
                $.ajax({
                    type: 'POST',
                    url: 'php/get_data.php',
                    data: {
                        userFio: userFio
                    },
                    success: function (response) {
                        var data = JSON.parse(response);
                        $('#id').val(data.id);
                        $('input.prodOrderNumChange').val(data.prodOrderNum);
                        $('input.productNameChange').val(data.productName);
                        $('input.factoryNumberChange').val(data.factoryNumber);
                        $('#userProdTypeChange').val(data.prodType);
                        $('input.zkNumberChange').val(data.zkNumber);
                        $('input.buildStartChange').val(data.buildStart);
                        $('input.buildEndChange').val(data.buildEnd);
                        $('input.kd_codeChange').val(data.kd_code);
                        $('input.constructorChange').val(data.constructor);
                        $('input.directorChange').val(data.director);
                        $('#userStatusChange').val(data.status);
                        $('#curatorChange').val(data.curator);
                    },
                    error: function (xhr, prodType, status, curator, error) {
                        alert('Error: ' + error);
                    }
                });
            }
            //сохранение изменений + отправка уведомления
            $('.notify').click(function (event) {
                event.preventDefault();
                var formData = $('#form').serialize();
                $.ajax({
                    type: 'POST',
                    url: 'php/process_form.php',
                    data: formData,
                    success: function (response) {
                        // Создаем и стилизуем popup элемент
                        var popup = $('<div class="popup">Изменения успешно внесены.</div>');
                        $('body').append(popup);
                        popup.fadeIn();
                        // Закрываем popup 
                        setTimeout(function () {
                            // Плавно скрываем и удаляем popup
                            popup.fadeOut(function () {
                                $(this).remove();
                            });
                        }, 1200);

                        setTimeout(function () {
                            window.location.reload();
                        }, 1800);

                        updateFormData();
                    },
                    error: function (xhr, prodType, status, curator, error) {
                        alert('Error: ' + error);
                    }
                });
            });
            $('#userFio').change(function () {
                updateFormData();
            });
            updateFormData();
        });
    </script>
    <!-- удаление -->
    <script>
        $(document).ready(function () {
            $('button.sendDelete').on('click', function () {
                var v_prodOrderNumDelete = $('input.prodOrderNumDelete').val();
                console.log(v_prodOrderNumDelete);

                $.ajax({
                    method: "POST",
                    url: "php/deleteData.php",
                    data: {
                        prodOrderNumDelete: v_prodOrderNumDelete
                    }

                })
                    .done(function () {
                        // Создаем и стилизуем popup элемент
                        var popup = $('<div class="popup">Запись была удалена</div>');
                        $('body').append(popup);
                        popup.fadeIn();
                        // Закрываем popup 
                        setTimeout(function () {
                            // Плавно скрываем и удаляем popup
                            popup.fadeOut(function () {
                                $(this).remove();
                            });
                        }, 1200);

                        setTimeout(function () {
                            window.location.reload();
                        }, 1800);
                    });

                $('input.prodOrderNumDelete').val('');
            })
        });
    </script>

<!-- Модальное окно обратной связи -->
    <div class="form-container" id="feedbackModal">
        <div class="form-container--bck">
            <span class="close" id="closeModal">&times;</span>
            <form id="feedbackForm">
            <label class="Feedback" id="Feedback">Обратная связь</label>
            <br>
                <div class="form-group">
                    <label for="comment">Ваш комментарий (Максимум 600 символов):</label>
                    <textarea class="form-control" id="comment" name="comment" rows="4" maxlength="600"></textarea>
                </div>
                <div class="form-group">
                    <label for="rating">Оцените удобство пользования (от 1 до 10):</label>
                    <output id="ratingValue">6</output>
                    <input type="range" class="form-control-range" id="rating" name="rating" min="1" max="10" step="1">
                </div>
                <button type="submit" class="btn btn-primary-form">Отправить</button>
                <br><br>
                <label>Также вы можете написать нам на почту по адресу: <a id="mailAPS" class="mailAPS" href="mailto:aps@kst-energo.ru">aps@kst-energo.ru</a></label>
            </form>
        </div>
    </div>

<!-- Модальные окна для единичного удаления для таблицы InformationBoard -->
    <div class="DeleteModal">
        <div class="DeleteModal-content d-flex flex-column align-items-center">
            <span class="close-button badge bg-danger">&times;</span>
            <p class="textDeleteQuestion">Вы уверены, что хотите удалить?</p>
            <span class="textDeleteQuestion"> Номер заказа производства: <?php echo $prodOrderNum;?> </span>
            <div class="d-flex">
                <button id="confirmDeleteButton" class="confirmDeleteButton btn btn-success">Да</button>
                <button id="cancelDeleteButton" class="cancelDeleteButton btn btn-danger">Нет</button>
            </div>
        </div>
    </div>

<!-- Модальные окна для единичного удаления для таблицы Products -->
    <div class="DeleteModal_Products">
        <div class="DeleteModal_Products-content d-flex flex-column align-items-center">
            <span class="close_Products-button badge bg-danger">&times;</span>
            <p class="textDeleteQuestion_Products">Вы уверены, что хотите удалить?</p>
            <span class="textDeleteQuestion_Products"> Номер заказа производства: <?php echo $prodOrderNum;?> </span>
            <div class="d-flex">
                <button id="confirmDeleteButton_Products" class="confirmDeleteButton_Products btn btn-success">Да</button>
                <button id="cancelDeleteButton_Products" class="cancelDeleteButton_Products btn btn-danger">Нет</button>
            </div>
        </div>
    </div>

<!-- Модальное окно для множественного удаления для таблицы InformationBoard -->
    <div class="MultiDeleteModal">
        <div class="MultiDeleteModal-content d-flex flex-column align-items-center">
            <span class="multiClose-button badge bg-danger">&times;</span>
            <p class="textMultiDeleteQuestion">Вы уверены, что хотите удалить эти записи?</p>
            <!-- <span class="textMultiDeleteQuestion"> Номера заказа производства: <?php /*echo $id;*/?> </span> -->
            <div class="d-flex">
                <button id="confirmMultiDeleteButton" class="confirmMultiDeleteButton btn btn-success">Да</button>
                <button id="cancelMultiDeleteButton" class="cancelMultiDeleteButton btn btn-danger">Нет</button>
            </div>
        </div>
    </div>

<!-- Модальное окно для множественного удаления для таблицы Products -->
    <div class="MultiDeleteModal_Products">
        <div class="MultiDeleteModal_Products-content d-flex flex-column align-items-center">
            <span class="multiClose_Products-button badge bg-danger">&times;</span>
            <p class="textMultiDeleteQuestion_Products">Вы уверены, что хотите удалить эти записи?</p>
            <!-- <span class="textMultiDeleteQuestion"> Номера заказа производства: <?php /*echo $id;*/?> </span> -->
            <div class="d-flex">
                <button id="confirmMultiDeleteButton_Products" class="confirmMultiDeleteButton_Products btn btn-success">Да</button>
                <button id="cancelMultiDeleteButton_Products" class="cancelMultiDeleteButton_Products btn btn-danger">Нет</button>
            </div>
        </div>
    </div>

</footer>
