<?php

require '../vendor/phpoffice/phpspreadsheet/src/PhpSpreadsheet/Spreadsheet.php';

require_once('../vendor/autoload.php');

 // Crie uma nova instância da classe Spreadsheet
 $spreadsheet = new Spreadsheet();

 // Obtenha a planilha ativa
 $activeWorksheet = $spreadsheet->getActiveSheet();

 // Defina o valor da célula A1
 $activeWorksheet->setCellValue('A1', 'Hello World !');

 // Crie um objeto Writer para salvar a planilha em um arquivo Excel
 $writer = new Xlsx($spreadsheet);

 // Salve a planilha em um arquivo Excel
 $writer->save('hello_world.xlsx');

?>
