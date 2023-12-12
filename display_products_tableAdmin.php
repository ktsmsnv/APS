
<?php
$sql = "SELECT products.id,
products.prod_name, 
products.zkNum,
products.prodOrder,
products.factoryNum,
products.constructorName,
products.directorName,
products.quantity,
products.curatorName
FROM products
INNER JOIN `curators` ON products.curatorName = `curators`.curatorFio
/*WHERE `curators`.curatorFio = '$curatorRole'*/
ORDER BY id";
if ($result = mysqli_query($conn, $sql)) {

    $rowsCount = mysqli_num_rows($result); // количество полученных строк
    // echo "<p>Получено объектов: $rowsCount</p>";

    echo "<div class='table' id='products-table'>
    <div class='table-header'>
        <div class='header__item'  style='max-width:40px;'><a id='id' class='filter__link--number' href='#'>ID</a></div>
        <div class='header__item'><a id='' class='filter__link--number' href='#'>Куратор</a></div>
        <div class='header__item'  style='max-width:80px;'><a id='zk' class='filter__link--number' href='#'>ЗК №</a></div>
        <div class='header__item'  style='max-width:80px;'><a id='OrderNum' class='filter__link' href='#'>№ ЗПр.</a></div>
        <div class='header__item'><a id='factoryNum' class='filter__link' href='#'>Зав. №.</a></div>
        <div class='header__item'><a id='product' class='filter__link' href='#'>Наименование</a></div>
        <div class='header__item' style='max-width:80px;'><a id='quantity' class='filter__link' href='#'>Кол-во</a></div>
        <div class='header__item'><a id='const' class='filter__link' href='#'>Конструктор</a></div>
        <div class='header__item'><a id='dir' class='filter__link' href='#'>Мастер участка</a></div>
    </div>
    <div class='table-content'>";
    foreach ($result as $row) {
        echo "<div class='d-flex table-rowMain'>";
            echo "<div class='table-row' id='row_" . out_inform_Panel_data_protect($row["id"]) . "'>";
                echo "<div class='table-data'  style='max-width:40px;'> " . out_inform_Panel_data_protect($row["id"]) . "</div>";
                echo "<div class='table-data'> " . out_inform_Panel_data_protect($row["curatorName"]) . "</div>";
                echo "<div class='table-data'  style='max-width:80px;'> " . out_inform_Panel_data_protect($row["zkNum"]) . "</div>";
                echo "<div class='table-data'  style='max-width:80px;'> " . out_inform_Panel_data_protect($row["prodOrder"]) . "</div>";
                echo "<div class='table-data'> " . out_inform_Panel_data_protect($row["factoryNum"]) . "</div>";
                echo "<div class='table-data'> " . out_inform_Panel_data_protect($row["prod_name"]) . "</div>";
                echo "<div class='table-data' style='max-width:80px;'> " . out_inform_Panel_data_protect($row["quantity"]) . "</div>";
                echo "<div class='table-data'> " . out_inform_Panel_data_protect($row["constructorName"]) . "</div>";
                echo "<div class='table-data'  style='max-width: none;'> " . out_inform_Panel_data_protect($row["directorName"]) . "</div>";
                // echo "<div class='table-data'><a href='#' class='sendDeleteCurator' data-id=" . $row["id"] . " ><img src='images/delete.svg'></a></div>";
            echo "</div>";
        echo "</div>";
    }
    echo "</div>";

    echo "<div class='d-flex justify-content-end'>";
    echo "<button id='feedbackButton2' class='btn btn-primary'>Обратная связь</button>";
    echo "</div>";
    mysqli_free_result($result);
}
?>
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
            feedbackModal2.style.display = "none";
        });
    </script>
