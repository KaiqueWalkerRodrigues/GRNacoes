<?php

require '../vendor/autoload.php';
require '../const.php';

use PhpOffice\PhpSpreadsheet\Calculation\LookupRef\Formula;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$Compras_notas = new Compras_Notas();
$Compras_Fornecedor = new Compras_Fornecedores();
$Compras_Categoria = new Compras_Categorias();

$ano_atual = date('Y');

$meses = [
    'Janeiro',
    'Fevereiro',
    'Março',
    'Abril',
    'Maio',
    'Junho',
    'Julho',
    'Agosto',
    'Setembro',
    'Outubro',
    'Novembro',
    'Dezembro'
];

$spreadsheet = new Spreadsheet();
$activeWorksheet = $spreadsheet->getActiveSheet();
$activeWorksheet->setCellValue('A1', 'RELATORIO RESUMO GERAL CLINICAS - '.$ano_atual.' - MATRIZ');
$activeWorksheet->setCellValue('A2','DESCRIÇÃO');

$col = 'B';
foreach ($meses as $mes) {
    $activeWorksheet->setCellValue($col.'2', $mes);
    $col++;
}

$activeWorksheet->setCellValue('N2','Total');

$row_categoria = 3;
foreach($Compras_Categoria->listar() as $cc){
    $cca = strtoupper($cc->categoria);
    $activeWorksheet->setCellValue('A'.$row_categoria,$cca);

    
    $col = 'B';
    for($i = 1; $i <= 12;$i++){
        $valor_total = $Compras_notas->totalCategoria(1, $cc->id_compra_categoria,$i,$ano_atual);
        $activeWorksheet->setCellValue($col.$row_categoria, $valor_total);
        $col = chr(ord($col) + 1);
    }
    
    $activeWorksheet->setCellValue('N'.$row_categoria,$Compras_notas->totalCategoriaAnual(1, $cc->id_compra_categoria,$ano_atual));

    $row_categoria++;
}


$writer = new Xlsx($spreadsheet);
$writer->save('Relatorio_compras_.xlsx');