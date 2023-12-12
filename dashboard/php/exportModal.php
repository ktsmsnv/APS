<?php
mb_internal_encoding("UTF-8");

include "../db_conn.php";

require __DIR__ . '/../vendor/autoload.php';
$phpWord = new \PhpOffice\PhpWord\PhpWord();

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

$fio = isset($_POST['fio']) ? $_POST['fio'] : '';
$dateFrom = isset($_POST['dateFrom']) ? $_POST['dateFrom'] : '';
$dateTo = isset($_POST['dateTo']) ? $_POST['dateTo'] : '';
$total = isset($_POST['total']) ? $_POST['total'] : '';
$finished = isset($_POST['finished']) ? $_POST['finished'] : '';
$products = isset($_POST['products']) ? json_decode($_POST['products'], true) : [];


if (isset($_POST['dateFrom']) && isset($_POST['dateTo']) && isset($_POST['fio'])  && isset($_POST['word'])) {

    header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
    header('Content-Disposition: attachment;filename="Статистика' . $fio . '.docx"');
    header('Cache-Control: max-age=0');

    $productsList = '';
    foreach ($products as $product) {
        $productsList .= "<li>{$product}</li>";
    }

    $html = "<div> 
    <p>Статистика монтажника: {$fio}</p>
    <p>От: {$dateFrom}</p>
    <p>До: {$dateTo}</p>
    <p>Всего: {$total}</p>
    <p>Готово: {$finished}</p>
    <p>Список изделий:</p>
    <ul>
       {$productsList}
    </ul>
    </div>";

    $phpWord = new PhpWord();
    $section = $phpWord->addSection();
    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $html, false, false);
    $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
    $objWriter->save('php://output');
}

else if (isset($_POST['dateFrom']) && isset($_POST['dateTo']) && isset($_POST['fio']) && isset($_POST['excel'])) {
    Settings::setOutputEscapingEnabled(true);
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValueExplicit(
        'A1',
        'Статистика монтажника: ' . $fio,
        DataType::TYPE_STRING2
    );
    $sheet->setCellValue('A2', 'От: ' . $dateFrom);
    $sheet->setCellValue('A3', 'До: ' . $dateTo);
    $sheet->setCellValue('A4', 'Всего: ' . $total);
    $sheet->setCellValue('A5', 'Готово: ' . $finished);

    $sheet->setCellValue('A6', 'Список изделий:');
    $rowNumber = 7;
    foreach ($products as $product) {
        $sheet->setCellValue('A' . $rowNumber, $product);
        $rowNumber++;
    }

    $writer = new Xls($spreadsheet);

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Статистика_' . $fio . '.xls"');

    $writer->save('php://output');
    exit();
}
