<!-- страница ВХОД -->
<?php
session_start();
if (!isset($_SESSION['username']) && !isset($_SESSION['id'])) {   ?>
  <!DOCTYPE html>
  <html>

  <head>
    <title>Вход | APS Личный Кабинет | КСТ Энерго Инжиниринг</title>
    <?php require('header.php'); ?>
  </head>

  <body class="LogInPage">
    <main>
      <div class="wrapper">
        <div class="logo d-flex flex-column align-items-center">
          <img src="images/logo-white.png">
        </div>
        <form class="login" action="php/check-login.php" method="post">
          <p class="title text-center">Вход</p>

          <?php if (isset($_GET['error'])) { ?>
            <div class="alert alert-danger" role="alert">
              <?= $_GET['error'] ?>
            </div>
          <?php } ?>
          <div class="form-floating">
            <input type="text" name="username" id="username" placeholder="Логин" autofocus />
            <i class="fa fa-user"></i>
          </div>
          <div class="form-floating password">
            <input type="password" name="password" id="password" placeholder="Пароль" />
            <i class="fa fa-key"></i>
            <a href="#" class="password-control show-hide"></a>
          </div>
          <!-- <div class="typeOfUser">
            <label class="form-label">Выберите тип пользователя:</label>
            <select class="form-select" name="role" aria-label="Default select example">
              <option selected value="admin">Администратор</option>
              <option value="director">Руководитель</option>
              <option value="curator">Куратор</option>
              <option value="master">Мастер</option>
              <option value="installer">Монтажник</option>
            </select>
          </div> -->
          <div class="d-flex flex-column">
            <button>
              <span class="entry btn state" type="submit">Войти</span>
            </button> 
          </div>
        </form>
        <button id='feedbackButton3' class='btn btn-primary'>Обратная связь</button>
      </div>
    </main>
    
    <!-- Модальное окно -->
    <div class="form-container" id="feedbackModal">
        <div class="form-container--bck">
            <span class="close" id="closeModal">&times;</span>
            <form id="feedbackForm">
            <label class="Feedback" id="Feedback">Обратная связь</label>
            <br>
                <div class="form-group d-flex flex-column">
                    <label for="email">Введите ваш email:</label>
                    <input type="text" id="email" name="email">
                    <br>
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
    <!-- Скрипт отправки Обратной связи -->
    <script>
        // Получение элементов модального окна и кнопки
        const feedbackModal3 = document.getElementById("feedbackModal");
        const feedbackButton3 = document.getElementById("feedbackButton3");
        const closeModal3 = document.getElementById("closeModal");

         // Получение элементов ползунка и элемента для отображения значения
         const ratingInput3 = document.getElementById("rating");
        const ratingValue3 = document.getElementById("ratingValue");

        // Обработчик события input для обновления значения в <output>
        ratingInput3.addEventListener("input", () => {
            ratingValue3.textContent = ratingInput3.value;
        });

        // Обработчик клика на кнопку
        feedbackButton3.addEventListener("click", () => {
            feedbackModal3.style.display = "flex";
        });

        // Обработчик клика на крестик для закрытия модального окна
        closeModal3.addEventListener("click", () => {
            feedbackModal3.style.display = "none";
        });

        // Обработчик клика на фон модального окна для закрытия модального окна
        feedbackModal3.addEventListener("click", (event) => {
            if (event.target === feedbackModal) {
                feedbackModal3.style.display = "none";
            }
        });

        // Обработчик отправки формы
        const feedbackForm3 = document.getElementById("feedbackForm");
        feedbackForm3.addEventListener("submit", (event) => {
            event.preventDefault();
            let formData = new FormData(feedbackForm3);
            $.ajax({
                url: "php/send_feedback_Login.php",
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
            feedbackModal3.style.display = "none";
        });

              // показать скрыть пароль
      $('body').on('click', '.password-control', function() {
        if ($('#password').attr('type') == 'password') {
          $(this).addClass('view');
          $('#password').attr('type', 'text');
        } else {
          $(this).removeClass('view');
          $('#password').attr('type', 'password');
        }
        return false;
      });

      // После начала ввода пароля
      $('#password').on('input', function() {
        if (this.value.length > 0) {
          $('.password-control').addClass('visible');
        } else {
          $('.password-control').removeClass('visible view');
          $('#password').attr('type', 'password');
        }
      });

    </script>

    <?php require('footer.php'); ?>
  </body>
  </html>

  <?php } else {
  if ($_SESSION['role'] == 'admin') {
    header("Location: ../mainPage.php");
    header("Location: ../kst-screen/index.html");
    header("Location: dashboard/build/index.html");
  } elseif ($_SESSION['role'] == 'director') {
    header("Location: ../directorPage.php");
  } elseif ($_SESSION['role'] == 'curator') {
    header("Location: ../curatorPage.php");
  } elseif ($_SESSION['role'] == 'master') {
    header("Location: ../masterPage.php");
  } elseif ($_SESSION['role'] == 'installer') {
    header("Location: ../installerPage.php");
  }
  exit;
} ?>