<!-- Chart JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<!-- Pdf Make -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.40/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.40/vfs_fonts.js"></script>
<!-- Sheet JS (js-xlsx) -->
<script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>


<!-- Скрипт для получения графика изделий со статусом "Готов" у всех Мастеров -->
<?php
// Получение дат: Год, Месяц, Неделя.
$startDateYear = $_POST['startDate'] ?? date('Y-01-01');
$endDateYear = $_POST['endDate'] ?? date('Y-12-t');

$startDateMonth = $_POST['startDate'] ?? date('Y-m-01');
$endDateMonth = $_POST['endDate'] ?? date('Y-m-t');

$pastWeekDateNow = date('Y-m-d');
$pastWeekDate = date('Y-m-d', strtotime('-1 week')); //Неделю назад

// Получение всех ФИО со статусом изделия "Готов" за Год
$getAllInstallersYear = "SELECT userFio
                            FROM DataTable
                            WHERE status_id = 9 AND (buildStart >= '$startDateYear' AND buildEnd <= '$endDateYear')
                            GROUP BY userFio";

$resultGetAllInstallersYear = mysqli_query($conn, $getAllInstallersYear);
$arrayFioYear = array();

while ($row = mysqli_fetch_assoc($resultGetAllInstallersYear)) {
    $arrayFioYear[] = $row['userFio'];
}
mysqli_free_result($resultGetAllInstallersYear);
// Получение всех ФИО со статусом изделия "Готов" за Месяц
$getAllInstallersMonth = "SELECT userFio
                            FROM DataTable
                            WHERE status_id = 9 AND (buildStart >= '$startDateMonth' AND buildEnd <= '$endDateMonth')
                            GROUP BY userFio";

$resultGetAllInstallersMonth = mysqli_query($conn, $getAllInstallersMonth);
$arrayFioMonth = array();

while ($row = mysqli_fetch_assoc($resultGetAllInstallersMonth)) {
    $arrayFioMonth[] = $row['userFio'];
}
mysqli_free_result($resultGetAllInstallersMonth);
// Получение всех ФИО со статусом изделия "Готов" за Неделю
$getAllInstallersWeek = "SELECT userFio
                            FROM DataTable
                            WHERE status_id = 9 AND (buildStart >= '$pastWeekDate' AND buildEnd <= '$pastWeekDateNow')
                            GROUP BY userFio";

$resultGetAllInstallersWeek = mysqli_query($conn, $getAllInstallersWeek);
$arrayFioWeek = array();

while ($row = mysqli_fetch_assoc($resultGetAllInstallersWeek)) {
    $arrayFioWeek[] = $row['userFio'];
}
mysqli_free_result($resultGetAllInstallersWeek);


// Получение количеста изделий со статусом изделия "Готов" за Год
// Запрос получения одинаковых строк по столбцу factoryNumber за Год
$getCountYear = "SELECT COUNT(*) as count
                        FROM DataTable
                        WHERE status_id = 9 AND (buildStart >= '$startDateYear' AND buildEnd <= '$endDateYear')
                        GROUP BY userFio";

$resultGetCountYear = mysqli_query($conn, $getCountYear);

$arrayCountYear = array();

while ($row = mysqli_fetch_assoc($resultGetCountYear)) {
    $arrayCountYear[] = $row['count'];
}

mysqli_free_result($resultGetCountYear);

// Получение количеста изделий со статусом изделия "Готов" за Месяц
// Запрос получения одинаковых строк по столбцу factoryNumber за Месяц
$getCountMonth = "SELECT COUNT(*) as count
                        FROM DataTable
                        WHERE status_id = 9 AND (buildStart >= '$startDateMonth' AND buildEnd <= '$endDateMonth')
                        GROUP BY userFio";

$resultGetCountMonth = mysqli_query($conn, $getCountMonth);

$arrayCountMonth = array();

while ($row = mysqli_fetch_assoc($resultGetCountMonth)) {
    $arrayCountMonth[] = $row['count'];
}

mysqli_free_result($resultGetCountMonth);

// Получение количеста изделий со статусом изделия "Готов" за Неделю
// Запрос получения одинаковых строк по столбцу factoryNumber за Неделю
$getCountWeek = "SELECT COUNT(*) as count
                        FROM DataTable
                        WHERE status_id = 9 AND (buildStart >= '$pastWeekDate' AND buildEnd <= '$pastWeekDateNow')
                        GROUP BY userFio";

$resultGetCountWeek = mysqli_query($conn, $getCountWeek);

$arrayCountWeek = array();

while ($row = mysqli_fetch_assoc($resultGetCountWeek)) {
    $arrayCountWeek[] = $row['count'];
}

mysqli_free_result($resultGetCountWeek);
?>

<canvas id="graphFinishedProd"></canvas>

<script>
    // Скрипт графика кол-ва готовых изделий за Год у Монтажников
    const arrayFioYear = <?php echo json_encode($arrayFioYear); ?>;
    const arrayFioMonth = <?php echo json_encode($arrayFioMonth); ?>;
    const arrayFioWeek = <?php echo json_encode($arrayFioWeek); ?>;

    const getCountYear = <?php echo json_encode($arrayCountYear); ?>;
    const getCountMonth = <?php echo json_encode($arrayCountMonth); ?>;
    const getCountWeek = <?php echo json_encode($arrayCountWeek); ?>;

    const ctx = document.getElementById('graphFinishedProd');
    var graphFinishedProd = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: arrayFioYear,
            datasets: [{
                label: 'Кол-во изделий',
                data: getCountYear,
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    suggestedMin: 0,
                    suggestedMax: 10
                }
            },
        }
    });

    const selectPeriodFinishProd = document.getElementById('selectPeriodFinishProd');
    selectPeriodFinishProd.addEventListener('change', changePeriodFinishProd);

    function changePeriodFinishProd() {
        if (selectPeriodFinishProd.value == "Year") {
            // console.log(selectPeriodFinishProd.value);
            graphFinishedProd.data.labels = arrayFioYear;
            graphFinishedProd.data.datasets[0].data = getCountYear;
            graphFinishedProd.update();
        } else if (selectPeriodFinishProd.value == "Month") {
            // console.log(selectPeriodFinishProd.value);
            graphFinishedProd.data.labels = arrayFioMonth;
            graphFinishedProd.data.datasets[0].data = getCountMonth;
            graphFinishedProd.update();
        } else if (selectPeriodFinishProd.value == "Week") {
            // console.log(selectPeriodFinishProd.value);
            graphFinishedProd.data.labels = arrayFioWeek;
            graphFinishedProd.data.datasets[0].data = getCountWeek;
            graphFinishedProd.update();
        };
    }

    // функция для генерации файла Pdf и его скачивания
    function downloadPDF() {
        const pdfChart = document.getElementById('graphFinishedProd');
        // создать изображение 
        const canvasImage = pdfChart.toDataURL('image/png', 1.0);

        var chosenTime = $('#selectPeriodFinishProd').find(":selected").val();

        if (chosenTime == 'Year') {
            var currentTime = (new Date).getFullYear();
        } else if (chosenTime == 'Month') {
            var chosenMonth = (new Date).getMonth() + 1;

            function GetMonthName(monthNumber) {
                var months = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь',
                    'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'
                ];
                return months[monthNumber - 1];
            }
            var currentTime = GetMonthName(chosenMonth) + ' ' + (new Date).getFullYear();
        } else if (chosenTime == 'Week') {
            var d = new Date();
            var pointer = 1;
            var start = d.getTime() - 1000 * 3600 * 24 * 7 * pointer;
            var end = start + 1000 * 3600 * 24 * 7;

            var options = {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                monthDisplay: 'narrow'
            };
            var startDateFormatted = new Date(start).toLocaleDateString('ru-RU', options);
            var endDateFormatted = new Date(end).toLocaleDateString('ru-RU', options);

            var currentTime = startDateFormatted + "-" + endDateFormatted;
            console.log(currentTime);
        }

        var docInfo = {
            content: [{
                image: canvasImage,
                width: 700
            }, ],

            pageSize: 'A4',
            pageOrientation: 'landscape', //'portrait'
            pageMargins: [50, 50, 30, 60],

            header: function(currentPage, pageCount) {
                return {
                    text: 'Готовые изделия за ' + currentTime,
                    alignment: 'right',
                    margin: [0, 30, 10, 50]
                }
            },
        }
        pdfMake.createPdf(docInfo).download('Статистика ' + currentTime + '.pdf');
    }

    // функция для генерации файла Excel и его скачивания
    function downloadExcel() {
        // создаем новую книгу
        var book = XLSX.utils.book_new();
        // создаем массив данных, который будем записывать в файл Excel
        // каждый подмассив = строка в Excel
        var data = [];
        // добавляем в массив первую строку с заголовками столбцов

        var chosenTime = $('#selectPeriodFinishProd').find(":selected").val();

        if (chosenTime == 'Year') {
            var currentTime = (new Date).getFullYear() + ' год';

            data.push(['ФИО', 'Количество изделий за ' + ' ' + currentTime]);
            // добавляем данные с графикa lдля года
            for (var i = 0; i < arrayFioYear.length; i++) {
                data.push([arrayFioYear[i], getCountYear[i]]);
            }
            // создаем лист
            var list = XLSX.utils.aoa_to_sheet(data);
            // добавляем лист в книгу
            XLSX.utils.book_append_sheet(book, list, "Страница 1");
            // генерируем файл и инициируем его скачивание
            XLSX.writeFile(book, 'Статистика' + ' ' + currentTime + '.xlsx');


        } else if (chosenTime == 'Month') {
            var chosenMonth = (new Date).getMonth() + 1;
            function GetMonthName(monthNumber) {
                var months = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь',
                    'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'
                ];
                return months[monthNumber - 1];
            }
            var currentTime = GetMonthName(chosenMonth) + ' ' + (new Date).getFullYear();

            data.push(['ФИО', 'Количество изделий за ' + ' ' + currentTime]);
            // добавляем данные с графикa 
            for (var i = 0; i < arrayFioYear.length; i++) {
                data.push([arrayFioMonth[i], getCountMonth[i]]);
            }
            // создаем лист
            var list = XLSX.utils.aoa_to_sheet(data);
            // добавляем лист в книгу
            XLSX.utils.book_append_sheet(book, list, "Страница 1");
            // генерируем файл и инициируем его скачивание
            XLSX.writeFile(book, 'Статистика' + ' ' + currentTime + '.xlsx');
            

        } else if (chosenTime == 'Week') {
            var d = new Date();
            var pointer = 1;
            var start = d.getTime() - 1000 * 3600 * 24 * 7 * pointer;
            var end = start + 1000 * 3600 * 24 * 7;
            var options = {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                monthDisplay: 'narrow'
            };
            var startDateFormatted = new Date(start).toLocaleDateString('ru-RU', options);
            var endDateFormatted = new Date(end).toLocaleDateString('ru-RU', options);
            var currentTime = startDateFormatted + "-" + endDateFormatted;

            data.push(['ФИО', 'Количество изделий за ' + ' ' + currentTime]);
            // добавляем данные с графикa 
            for (var i = 0; i < arrayFioYear.length; i++) {
                data.push([arrayFioWeek[i], getCountWeek[i]]);
            }
            // создаем лист
            var list = XLSX.utils.aoa_to_sheet(data);
            // добавляем лист в книгу
            XLSX.utils.book_append_sheet(book, list, "Страница 1");
            // генерируем файл и инициируем его скачивание
            XLSX.writeFile(book, 'Статистика' + ' ' + currentTime + '.xlsx');
        }
    }
</script>