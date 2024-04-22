<?php

require '../vendor/autoload.php';
require '../const.php';

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

$col = 'D'; //Colocando meses
foreach ($meses as $mes) {
    $activeWorksheet->setCellValue($col.'2', $mes);
    $col++;
}

$row_categoria = 3;
foreach($Compras_Categoria->listar() as $cc){
    $cca = strtoupper($cc->categoria);
    $activeWorksheet->setCellValue('A'.$row_categoria,$cca);

    
    $col = 'B';
    for($i = 1; $i <= 12;$i++){
        $valor_total = $Compras_notas->totalCategoria(1, $cc->id_compra_categoria,$i);
        $activeWorksheet->setCellValue($col.$row_categoria, $valor_total);
        $col = chr(ord($col) + 1);
    }

    $row_categoria++;
}

// $dados = $Compras_notas->listar();

// $row = 2;
// foreach($dados as $cl){
    
//     $row++;
// }


$writer = new Xlsx($spreadsheet);
$writer->save('Relatorio_compras_.xlsx');