<!-- страница ВХОД -->
<?php
session_start();
if (!isset($_SESSION['username']) && !isset($_SESSION['id'])) {  ?>
  <!DOCTYPE html>
  <html>

  <head>
    <title>Вход | APS Личный Кабинет | КСТ Энерго Инжиниринг</title>
    <link rel="stylesheet" href="./css/503_error.css" media="all">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <link rel="stylesheet" href="..\node_modules\bootstrap\dist\css\bootstrap.min.css">
    <script src="..\node_modules\bootstrap\dist\js\bootstrap.bundle.min.js"></script>
    <script src="..\node_modules\jquery\dist\jquery.min.js"></script>

    <link rel="shortcut icon" href="/images/favicon.ico">
  </head>

  <body>
    <!-- <div class="header">
      <h3>ActiveState</h3>
    </div> -->
      <div class="center-content">
        <div class="container">
          <img src="/images/503.jpg" width="600px" height="400px">
          <!-- <h1 class="screenText">x _ x</h1> -->
          <div>
            <h1>Ведутся технические работы...</h1>
            <h2>Приложение APS временно недоступно</h2>
            <p>Мы сообщим вам по почте, когда приложение вновь заработает
          </div>
      </div>
      
    </div>
    

    <?php 
 ?>
  </body>
  </html>

<?php } else {

  if ($_SESSION['role'] == 'admin') {
    header("Location: ../index.php");
    // header("Location: ../kst-screen/index.html");
  } elseif ($_SESSION['role'] == 'director') {
    header("Location: ../index.php");
  } elseif ($_SESSION['role'] == 'curator') {
    header("Location: ../index.php");
  } elseif ($_SESSION['role'] == 'master') {
    header("Location: ../index.php");
  } elseif ($_SESSION['role'] == 'installer') {
    header("Location: ../index.php");
  } else {
    header("Location: ../index.php");
    header("Location: ../index3.php");
  }
  exit;
} ?>