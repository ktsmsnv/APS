<div class="topbar d-flex justify-content-between align-items-center">
    <div class="toggle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Свернуть меню">
        <ion-icon name="menu-outline"></ion-icon>
    </div>
    <!-- Пользователь -->
    <div class="profile d-flex align-items-center">
        <h5 class="userName">
            <?php $_SESSION['role'];
            if ($_SESSION['role'] == 'admin') {
                echo ('Администратор -');
            } elseif ($_SESSION['role'] == 'director') {
                echo ('Руководитель -');
            } elseif ($_SESSION['role'] == 'curator') {
                echo ('Куратор -');
            } elseif ($_SESSION['role'] == 'master') {
                echo ('Мастер -');
            } elseif ($_SESSION['role'] == 'installer') {
                echo ('Монтажник -');
            }
            ?>
            <?= $_SESSION['fio'] ?>
        </h5>
        <div class="userPhoto">
            <img src="images/usericon2.png">
        </div>
    </div>
</div>