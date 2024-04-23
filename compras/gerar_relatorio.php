<?php

require '../vendor/autoload.php';
require '../const.php';

use PhpOffice\PhpSpreadsheet\Calculation\LookupRef\Formula;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat\Wizard\Accounting;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

$real = new Accounting(
    'R$',
    2,
    true,
    true,
    true
);

$red = [
    'font' => [
        'bold' => true,
        'color' => [
            'rgb' => 'FF0000', // Vermelho
        ],
    ],
];

$mes_style = [
    'font' => [
        'bold' => true,
    ],
];

$Compras_notas = new Compras_Notas();
$Compras_Fornecedor = new Compras_Fornecedores();
$Compras_Categoria = new Compras_Categorias();

$ano_atual = date('Y');

$meses = [
    'JANEIRO',
    'FEVEREIRO',
    'MARÇO',
    'ABRIL',
    'MAIO',
    'JUNHO',
    'JULHO',
    'AGOSTO',
    'SETEMBRO',
    'OUTUBRO',
    'NOVEMBRO',
    'DEZEMBRO'
];

$titulo = [
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => 'ADD8E6',
        ],
    ],
];

$spreadsheet = new Spreadsheet();
$activeWorksheet = $spreadsheet->getActiveSheet();

$activeWorksheet->mergeCells('A1:N1');
$activeWorksheet->getStyle('A1')->applyFromArray($titulo);
$activeWorksheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$activeWorksheet->setCellValue('A1', 'RELATORIO RESUMO GERAL CLINICA PARQUE - '.$ano_atual);
$activeWorksheet->setCellValue('A2','DESCRIÇÃO');

$col = 'B';
$row_categoria = 3;
//Lista todos os meses
foreach ($meses as $mes) {
    $activeWorksheet->setCellValue($col.'2', $mes);
    $activeWorksheet->getStyle($col.'2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $activeWorksheet->getStyle($col.'2')->applyFromArray($mes_style);
    $col++;
}

$activeWorksheet->setCellValue('N2','TOTAL:');


//Lista todas as categorias
foreach($Compras_Categoria->listar() as $cc){
    $cca = strtoupper($cc->categoria);
    $activeWorksheet->setCellValue('A'.$row_categoria,$cca);

    
    $col = 'B';
    //Lista todos valores de categorias por mês
    for($i = 1; $i <= 12;$i++){
        $valor_total = $Compras_notas->totalCategoria(1, $cc->id_compra_categoria,$i,$ano_atual);
        $activeWorksheet->setCellValue($col.$row_categoria, $valor_total);
        $activeWorksheet->getCell($col.$row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
        $col = chr(ord($col) + 1);
    }
    
    //Lita o total de cada categoria
    $activeWorksheet->setCellValue('N'.$row_categoria,$Compras_notas->totalCategoriaAnual(1, $cc->id_compra_categoria,$ano_atual));
    $activeWorksheet->getCell('N'.$row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
    $activeWorksheet->getStyle('N'.$row_categoria)->applyFromArray($red);

    $row_categoria++;
}

$activeWorksheet->setCellValue('A' . $row_categoria, 'TOTAL:');
$mes = 1; // Ajustado para 1 em vez de 01
$row_mês = 'B';

//Valores total por categoria
for ($i = 1; $i <= 12; $i++) {
    $total = $Compras_notas->totalMes(1, $mes, $ano_atual);
    $activeWorksheet->setCellValue($row_mês . $row_categoria, $total); // Corrigido $i para $row_mês
    $activeWorksheet->getCell($row_mês . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real); // Corrigido $i para $row_mês
    $row_mês = chr(ord($row_mês) + 1);
    $mes++;
}

$totalempresa = $Compras_notas->totalEmpresa(1,$ano_atual);
$activeWorksheet->setCellValue($row_mês.$row_categoria,$totalempresa);
$activeWorksheet->getCell($row_mês . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
$activeWorksheet->getStyle('N'.$row_categoria)->applyFromArray($red);

$row_categoria++;
$row_categoria++;

/* 
    CLINICA MAUÁ
*/

$activeWorksheet->mergeCells('A'.$row_categoria.':N'.$row_categoria);
$activeWorksheet->getStyle('A'.$row_categoria)->applyFromArray($titulo);
$activeWorksheet->setCellValue('A'.$row_categoria, 'RELATORIO RESUMO GERAL CLINICA MAUÁ - '.$ano_atual);
$activeWorksheet->getStyle('A'.$row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$row_categoria++;
$activeWorksheet->setCellValue('A'.$row_categoria,'DESCRIÇÃO');

$col = 'B';
//Lista todos os meses
foreach ($meses as $mes) {
    $activeWorksheet->setCellValue($col.$row_categoria,$mes);
    $activeWorksheet->getStyle($col.$row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $activeWorksheet->getStyle($col.$row_categoria)->applyFromArray($mes_style);
    $col++;
}

$activeWorksheet->setCellValue('N'.$row_categoria,'TOTAL:');

$row_categoria++;

//Lista todas as categorias
foreach($Compras_Categoria->listar() as $cc){
    $cca = strtoupper($cc->categoria);
    $activeWorksheet->setCellValue('A'.$row_categoria,$cca);

    
    $col = 'B';
    //Lista todos valores de categorias por mês
    for($i = 1; $i <= 12;$i++){
        $valor_total = $Compras_notas->totalCategoria(3, $cc->id_compra_categoria,$i,$ano_atual);
        $activeWorksheet->setCellValue($col.$row_categoria, $valor_total);
        $activeWorksheet->getCell($col.$row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
        $col = chr(ord($col) + 1);
    }
    
    //Lita o total de cada categoria
    $activeWorksheet->setCellValue('N'.$row_categoria,$Compras_notas->totalCategoriaAnual(3, $cc->id_compra_categoria,$ano_atual));
    $activeWorksheet->getCell('N'.$row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
    $activeWorksheet->getStyle('N'.$row_categoria)->applyFromArray($red);

    $row_categoria++;
}

$activeWorksheet->setCellValue('A' . $row_categoria, 'TOTAL:');
$mes = 1; // Ajustado para 1 em vez de 01
$row_mês = 'B';

for ($i = 1; $i <= 12; $i++) {
    $total = $Compras_notas->totalMes(3, $mes, $ano_atual);
    $activeWorksheet->setCellValue($row_mês . $row_categoria, $total); // Corrigido $i para $row_mês
    $activeWorksheet->getCell($row_mês . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real); // Corrigido $i para $row_mês
    $row_mês = chr(ord($row_mês) + 1);
    $mes++;
}

$totalempresa = $Compras_notas->totalEmpresa(3,$ano_atual);
$activeWorksheet->setCellValue($row_mês.$row_categoria,$totalempresa);
$activeWorksheet->getCell($row_mês . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
$activeWorksheet->getStyle('N'.$row_categoria)->applyFromArray($red);

$row_categoria++;
$row_categoria++;

/*
    CLINICA JARDIM
*/
$activeWorksheet->mergeCells('A'.$row_categoria.':N'.$row_categoria);
$activeWorksheet->setCellValue('A'.$row_categoria, 'RELATORIO RESUMO GERAL CLINICA JARDIM - '.$ano_atual);
$activeWorksheet->getStyle('A'.$row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$activeWorksheet->getStyle('A'.$row_categoria)->applyFromArray($titulo);
$row_categoria++;
$activeWorksheet->setCellValue('A'.$row_categoria,'DESCRIÇÃO');

$col = 'B';
//Lista todos os meses
foreach ($meses as $mes) {
    $activeWorksheet->setCellValue($col.$row_categoria,$mes);
    $activeWorksheet->getStyle($col.$row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $activeWorksheet->getStyle($col.$row_categoria)->applyFromArray($mes_style);
    $col++;
}

$activeWorksheet->setCellValue('N'.$row_categoria,'TOTAL:');

$row_categoria++;

//Lista todas as categorias
foreach($Compras_Categoria->listar() as $cc){
    $cca = strtoupper($cc->categoria);
    $activeWorksheet->setCellValue('A'.$row_categoria,$cca);

    
    $col = 'B';
    //Lista todos valores de categorias por mês
    for($i = 1; $i <= 12;$i++){
        $valor_total = $Compras_notas->totalCategoria(5, $cc->id_compra_categoria,$i,$ano_atual);
        $activeWorksheet->setCellValue($col.$row_categoria, $valor_total);
        $activeWorksheet->getCell($col.$row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
        $col = chr(ord($col) + 1);
    }
    
    //Lita o total de cada categoria
    $activeWorksheet->setCellValue('N'.$row_categoria,$Compras_notas->totalCategoriaAnual(5, $cc->id_compra_categoria,$ano_atual));
    $activeWorksheet->getCell('N'.$row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
    $activeWorksheet->getStyle('N'.$row_categoria)->applyFromArray($red);

    $row_categoria++;
}

$activeWorksheet->setCellValue('A' . $row_categoria, 'TOTAL:');
$mes = 1;
$row_mes = 'B';

for ($i = 1; $i <= 12; $i++) {
    $total = $Compras_notas->totalMes(5, $mes, $ano_atual);
    $activeWorksheet->setCellValue($row_mes . $row_categoria, $total); 
    $activeWorksheet->getCell($row_mes . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
    $row_mes = chr(ord($row_mes) + 1);
    $mes++;
}

$totalempresa = $Compras_notas->totalEmpresa(5,$ano_atual);
$activeWorksheet->setCellValue($row_mês.$row_categoria,$totalempresa);
$activeWorksheet->getCell($row_mês . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
$activeWorksheet->getStyle('N'.$row_categoria)->applyFromArray($red);

$row_categoria++;
$row_categoria++;

$row_alinhamento = 'A';
for ($i = 1; $i <= 16; $i++) {
    $spreadsheet->getActiveSheet()->getColumnDimension($row_alinhamento)->setAutoSize(true);
    $row_alinhamento = chr(ord($row_alinhamento) + 1);
}

$filename = 'relatorio_compras_2024.xlsx';

// Verifica se o arquivo já existe
if (file_exists($filename)) {
    // Se existir, exclui o arquivo antigo
    unlink($filename);
}

// Salva o novo arquivo
$writer = new Xlsx($spreadsheet);
$writer->save($filename);

header('location:relatorios?s');
