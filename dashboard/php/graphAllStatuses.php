<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<!-- Скрипт для получения графика кол-ва изделий по всем статусам на текущий момент-->
<?php
$getAllStatusesAndCounts = "SELECT status.name, COUNT(informationBoard.status_id) as count 
    FROM status 
    JOIN informationBoard ON status.id = informationBoard.status_id 
    GROUP BY status.name";

$resultGetAllStatuses = mysqli_query($conn, $getAllStatusesAndCounts);
$arrayAllStatuses = array();
while ($row = mysqli_fetch_assoc($resultGetAllStatuses)) {
    $arrayAllStatuses[] = $row['name'];
}
mysqli_free_result($resultGetAllStatuses);


$resultGetCountAllStatuses = mysqli_query($conn, $getAllStatusesAndCounts);
$arrayCountAllStatuses = array();
while ($row = mysqli_fetch_assoc($resultGetCountAllStatuses)) {
    $arrayCountAllStatuses[] = $row['count'];
}
mysqli_free_result($resultGetCountAllStatuses);
?>

<canvas class="d-flex" id="graphAllStatuses"></canvas>

<script>
    const arrayStatuses = <?php echo json_encode($arrayAllStatuses, JSON_UNESCAPED_UNICODE); ?>;
    const arrayCountAllStatuses = <?php echo json_encode($arrayCountAllStatuses, JSON_UNESCAPED_UNICODE); ?>;

    console.table(arrayStatuses);
    console.table(arrayCountAllStatuses);

    const ctxAllStatuses = document.getElementById('graphAllStatuses');
    var graphAllStatuses = new Chart(ctxAllStatuses, {
        type: 'doughnut',
        data: {
            labels: arrayStatuses,
            datasets: [{
                label: 'Кол-во статусов',
                data: arrayCountAllStatuses,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
        }
    });

    function downloadPDF2() {
        const pdfChart = document.getElementById('graphAllStatuses');
        // создать изображение 
        const canvasImage = pdfChart.toDataURL('image/png', 1.0);

        var chosenMonth = (new Date).getMonth() + 1;

        function GetMonthName(monthNumber) {
            var months = ['Января', 'Февраля', 'Марта', 'Апреля', 'Мая', 'Июня',
                'Июля', 'Августа', 'Сентября', 'Октября', 'Ноября', 'Декабря'
            ];
            return months[monthNumber - 1];
        }
        var currentTime = (new Date).getDay() + ' ' + GetMonthName(chosenMonth) + ' ' + (new Date).getFullYear();

        var docInfo = {
            content: [{
                image: canvasImage,
                width: 800
            }, ],

            pageSize: 'A4',
            pageOrientation: 'landscape', //'portrait'
            pageMargins: [60, 60, 30, 60],

            header: function(currentPage, pageCount) {
                return {
                    text: 'Статистика по статусам ' + currentTime,
                    alignment: 'right',
                    margin: [0, 30, 10, 50]
                }
            },
        }
        pdfMake.createPdf(docInfo).download('Статистика по статусам ' + currentTime + '.pdf');
    }

    // функция для генерации файла Excel и его скачивания
    function downloadExcel2() {
        // создаем новую книгу
        var book = XLSX.utils.book_new();
        // создаем массив данных, который будем записывать в файл Excel
        // каждый подмассив = строка в Excel
        var data = [];

        var chosenMonth = (new Date).getMonth() + 1;
        function GetMonthName(monthNumber) {
            var months = ['Января', 'Февраля', 'Марта', 'Апреля', 'Мая', 'Июня',
                'Июля', 'Августа', 'Сентября', 'Октября', 'Ноября', 'Декабря'
            ];
            return months[monthNumber - 1];
        }
        var currentTime = (new Date).getDay() + ' ' + GetMonthName(chosenMonth) + ' ' + (new Date).getFullYear()
        // добавляем в массив первую строку с заголовками столбцов
        data.push(['ФИО', 'Статистика по статусам ' + ' ' + currentTime]);
        // добавляем данные с графикa lдля года
        for (var i = 0; i < arrayStatuses.length; i++) {
            data.push([arrayStatuses[i], arrayCountAllStatuses[i]]);
        }
        // создаем лист
        var list = XLSX.utils.aoa_to_sheet(data);
        // добавляем лист в книгу
        XLSX.utils.book_append_sheet(book, list, "Страница 1");
        // генерируем файл и инициируем его скачивание
        XLSX.writeFile(book, 'Статистика статусы' + ' ' + currentTime + '.xlsx');
    }
</script>