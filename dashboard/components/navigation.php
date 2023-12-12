<div class="navigation cd-side-navigation">
    <ul>
        <li data-bs-toggle="tooltip" data-bs-placement="bottom" title="Перейти на главную">
            <a href="index.php">
                <span class="icon"><img src="/images/logo.svg"></span>
            </a>
        </li>
        <li data-bs-toggle="tooltip" data-bs-placement="bottom" title="Страница дашбордов">
            <a href="data.php" class="transition">
                <span class="icon"><ion-icon name="bar-chart-outline"></span>
                <span class="title">Дашборды</span>
            </a>
        </li>
        <li data-bs-toggle="tooltip" data-bs-placement="bottom" title="Страница монтажников">
            <a href="installers.php">
                <span class="icon"><ion-icon name="people-outline"></ion-icon></span>
                <span class="title">Монтажники</span>
            </a>
        </li>
        <li data-bs-toggle="tooltip" data-bs-placement="bottom" title="Обучение и руководство">
            <a data-bs-toggle='modal' data-bs-target='#helpModal'>
                <span class="icon"><ion-icon name="information-circle-outline"></ion-icon></span>
                <span class="title">Помощь</span>
            </a>
        </li>
        <li data-bs-toggle="tooltip" data-bs-placement="bottom" title="Вернуться в личный кабинет">
            <a href="<?php
                        echo determineRedirect($_SESSION['role']);
                        function determineRedirect($role)
                        {
                            switch ($role) {
                                case 'admin':
                                    return '../mainPage.php';
                                case 'director':
                                    return '../directorPage.php';
                                case 'curator':
                                    return '../curatorPage.php';
                                case 'master':
                                    return '../masterPage.php';
                                case 'installer':
                                    return '../installerPage.php';
                                default:
                                    return '../index.php';
                            }
                        }
                        ?>">
                <span class="icon"><ion-icon name="log-out-outline"></ion-icon></span>
                <span class="title">Вернуться назад</span>
            </a>
        </li>
    </ul>
</div>

<!-- модалка поиощь -->
<div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="helpModalLabel">Руководство пользователя<span></span></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex flex-column">
                
                <div class="d-flex">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Начать обучение</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Скачать руководство</button>
                </div>
            </div>
        </div>
    </div>
</div>