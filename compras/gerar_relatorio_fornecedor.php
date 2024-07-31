<?php

function getNextColumn($col) {
    $length = strlen($col);
    for ($i = $length - 1; $i >= 0; $i--) {
        if ($col[$i] !== 'Z') {
            $col[$i] = chr(ord($col[$i]) + 1);
            return $col;
        }
        $col[$i] = 'A';
    }
    return 'A' . $col;
}

require '../vendor/autoload.php';
require '../const.php';

use PhpOffice\PhpSpreadsheet\Calculation\LookupRef\Formula;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat\Wizard\Accounting;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

$Compras_notas = new Compras_Notas();
$Compras_Fornecedor = new Compras_Fornecedores();
$Compras_Categoria = new Compras_Categorias();

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

$titulo = [
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => '4682B4',
        ],
    ],
];

$grey = [
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => 'd3d3d3',
        ],
    ],
    'font' => [
        'bold' => true,
    ],
];

$titulo_otica = [
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => '32CD32',
        ],
    ],
];

$total_azul_claro = [
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => 'ADD8E6', // Azul Claro
        ],
    ],
];

$total_verde = [
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => '98FB98', // Azul Claro
        ],
    ],
    'font' => [
        'bold' => true,
    ],
];

$spreadsheet = new Spreadsheet();
$activeWorksheet = $spreadsheet->getActiveSheet();
$spreadsheet->getActiveSheet()->setTitle('Valor');

$activeWorksheet->mergeCells('A1:N1');
$activeWorksheet->getStyle('A1')->applyFromArray($titulo);
$activeWorksheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$activeWorksheet->setCellValue('A1', 'RELATORIO RESUMO GERAL CLINICA PARQUE - ' . date('Y'));
$activeWorksheet->setCellValue('A2','FORNECEDOR');
$activeWorksheet->getStyle('A2')->applyFromArray($mes_style);

$col = 'B';
$row_categoria = 3;
//Lista todos os meses
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

foreach ($meses as $mes) {
    $activeWorksheet->setCellValue($col . '2', $mes);
    $activeWorksheet->getStyle($col . '2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $activeWorksheet->getStyle($col . '2')->applyFromArray($mes_style);
    $col++;
}

$activeWorksheet->setCellValue('N2','TOTAL:');

//Lista todas as categorias com notas cadastradas para a empresa e ano específicos
foreach ($Compras_Categoria->listar() as $cc) {
    $cca = strtoupper($cc->categoria);
    
    // Verifica se há notas associadas a esta categoria para a empresa e ano específicos
    if ($Compras_notas->totalNotasCategoria(1, $cc->id_compra_categoria, date('Y')) > 0) {
        foreach($Compras_Fornecedor->listarPorCategoria(1,$cc->id_compra_categoria,date('Y')) as $f){
            $activeWorksheet->setCellValue('A' . $row_categoria, $f->fornecedor);
            
            $col = 'B';
            for($i = 1; $i <=12; $i++){
                $total_f_mes = $Compras_notas->totalFornecedorMes(1,$f->id_compra_fornecedor,$i);
                if($total_f_mes > 0){$activeWorksheet->setCellValue($col.$row_categoria,$total_f_mes);}
                $activeWorksheet->getCell($col.$row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
                $col = chr(ord($col) + 1);
            }
            $row_categoria++;
        }

        $activeWorksheet->setCellValue('A' . $row_categoria,'TOTAL '.$cca.':');
        $activeWorksheet->getStyle('A'.$row_categoria.':N'.$row_categoria)->applyFromArray($grey);

        $col = 'B';
        //Lista todos valores de categorias por mês
        for ($i = 1; $i <= 12; $i++) {
            $valor_total = $Compras_notas->totalCategoria(1, $cc->id_compra_categoria, $i, date('Y'));
            $activeWorksheet->setCellValue($col . $row_categoria, $valor_total);
            $activeWorksheet->getCell($col . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
            $col = chr(ord($col) + 1);
        }

        //Lita o total de cada categoria
        $activeWorksheet->setCellValue('N' . $row_categoria, $Compras_notas->totalCategoriaAnual(1, $cc->id_compra_categoria, date('Y')));
        $activeWorksheet->getCell('N' . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
        $activeWorksheet->getStyle('N' . $row_categoria)->applyFromArray($red);

        $row_categoria++;
    }
}

$activeWorksheet->setCellValue('A' . $row_categoria, 'TOTAL:');
$activeWorksheet->getStyle('A' . $row_categoria)->applyFromArray($total_azul_claro);
$mes = 1; // Ajustado para 1 em vez de 01
$row_mês = 'B';

//Valores total por categoria
for ($i = 1; $i <= 12; $i++) {
    $total = $Compras_notas->totalMes(1, $mes, date('Y'));
    $activeWorksheet->setCellValue($row_mês . $row_categoria, $total); 
    $activeWorksheet->getStyle($row_mês . $row_categoria)->applyFromArray($total_azul_claro);
    $activeWorksheet->getCell($row_mês . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
    $row_mês = chr(ord($row_mês) + 1);
    $mes++;
}

$totalempresa = $Compras_notas->totalEmpresa(1, date('Y'));
$activeWorksheet->setCellValue($row_mês . $row_categoria, $totalempresa);
$activeWorksheet->getCell($row_mês . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
$activeWorksheet->getStyle('N' . $row_categoria)->applyFromArray($red);

$row_categoria++;
$row_categoria++;

/* 
    CLINICA MAUÁ
*/

$activeWorksheet->mergeCells('A' . $row_categoria . ':N' . $row_categoria);
$activeWorksheet->getStyle('A' . $row_categoria)->applyFromArray($titulo);
$activeWorksheet->setCellValue('A' . $row_categoria, 'RELATORIO RESUMO GERAL CLINICA MAUÁ - ' . date('Y'));
$activeWorksheet->getStyle('A' . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$row_categoria++;
$activeWorksheet->setCellValue('A' . $row_categoria, 'FORNECEDOR');
$activeWorksheet->getStyle('A' . $row_categoria)->applyFromArray($mes_style);

$col = 'B';
//Lista todos os meses
foreach ($meses as $mes) {
    $activeWorksheet->setCellValue($col . $row_categoria, $mes);
    $activeWorksheet->getStyle($col . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $activeWorksheet->getStyle($col . $row_categoria)->applyFromArray($mes_style);
    $col++;
}

$activeWorksheet->setCellValue('N' . $row_categoria, 'TOTAL:');

$row_categoria++;

//Lista todas as categorias
foreach ($Compras_Categoria->listar() as $cc) {
    $cca = strtoupper($cc->categoria);

    // Verifica se há notas associadas a esta categoria para a empresa e ano específicos
    if ($Compras_notas->totalNotasCategoria(3, $cc->id_compra_categoria, date('Y')) > 0) {
        foreach($Compras_Fornecedor->listarPorCategoria(3,$cc->id_compra_categoria,date('Y')) as $f){
            $activeWorksheet->setCellValue('A' . $row_categoria, $f->fornecedor);
            
            $col = 'B';
            for($i = 1; $i <=12; $i++){
                $total_f_mes = $Compras_notas->totalFornecedorMes(3,$f->id_compra_fornecedor,$i);
                if($total_f_mes > 0){$activeWorksheet->setCellValue($col.$row_categoria,$total_f_mes);}
                $activeWorksheet->getCell($col.$row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
                $col = chr(ord($col) + 1);
            }
            $row_categoria++;
        }

        $activeWorksheet->setCellValue('A' . $row_categoria,'TOTAL '.$cca.':');
        $activeWorksheet->getStyle('A'.$row_categoria.':N'.$row_categoria)->applyFromArray($grey);

        $col = 'B';
        //Lista todos valores de categorias por mês
        for ($i = 1; $i <= 12; $i++) {
            $valor_total = $Compras_notas->totalCategoria(3, $cc->id_compra_categoria, $i, date('Y'));
            $activeWorksheet->setCellValue($col . $row_categoria, $valor_total);
            $activeWorksheet->getCell($col . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
            $col = chr(ord($col) + 1);
        }

        //Lita o total de cada categoria
        $activeWorksheet->setCellValue('N' . $row_categoria, $Compras_notas->totalCategoriaAnual(1, $cc->id_compra_categoria, date('Y')));
        $activeWorksheet->getCell('N' . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
        $activeWorksheet->getStyle('N' . $row_categoria)->applyFromArray($red);

        $row_categoria++;
    }
}

$activeWorksheet->setCellValue('A' . $row_categoria, 'TOTAL:');
$activeWorksheet->getStyle('A' . $row_categoria)->applyFromArray($total_azul_claro);
$mes = 1;
$row_mes = 'B';

for ($i = 1; $i <= 12; $i++) {
    $total = $Compras_notas->totalMes(3, $mes, date('Y'));
    $activeWorksheet->setCellValue($row_mes . $row_categoria, $total); 
    $activeWorksheet->getStyle($row_mes . $row_categoria)->applyFromArray($total_azul_claro);
    $activeWorksheet->getCell($row_mes . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
    $row_mes = chr(ord($row_mes) + 1);
    $mes++;
}

$totalempresa = $Compras_notas->totalEmpresa(3, date('Y'));
$activeWorksheet->setCellValue($row_mes . $row_categoria, $totalempresa);
$activeWorksheet->getCell($row_mes . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
$activeWorksheet->getStyle('N' . $row_categoria)->applyFromArray($red);

$row_categoria++;
$row_categoria++;

/*
    CLINICA JARDIM
*/

$activeWorksheet->mergeCells('A' . $row_categoria . ':N' . $row_categoria);
$activeWorksheet->setCellValue('A' . $row_categoria, 'RELATORIO RESUMO GERAL CLINICA JARDIM - ' . date('Y'));
$activeWorksheet->getStyle('A' . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$activeWorksheet->getStyle('A' . $row_categoria)->applyFromArray($titulo);
$row_categoria++;
$activeWorksheet->setCellValue('A' . $row_categoria, 'FORNECEDOR');
$activeWorksheet->getStyle('A' . $row_categoria)->applyFromArray($mes_style);

$col = 'B';
//Lista todos os meses
foreach ($meses as $mes) {
    $activeWorksheet->setCellValue($col . $row_categoria, $mes);
    $activeWorksheet->getStyle($col . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $activeWorksheet->getStyle($col . $row_categoria)->applyFromArray($mes_style);
    $col++;
}

$activeWorksheet->setCellValue('N' . $row_categoria, 'TOTAL:');

$row_categoria++;

//Lista todas as categorias
foreach ($Compras_Categoria->listar() as $cc) {
    $cca = strtoupper($cc->categoria);

    // Verifica se há notas associadas a esta categoria para a empresa e ano específicos
    if ($Compras_notas->totalNotasCategoria(5, $cc->id_compra_categoria, date('Y')) > 0) {
        foreach($Compras_Fornecedor->listarPorCategoria(5,$cc->id_compra_categoria,date('Y')) as $f){
            $activeWorksheet->setCellValue('A' . $row_categoria, $f->fornecedor);
            
            $col = 'B';
            for($i = 1; $i <=12; $i++){
                $total_f_mes = $Compras_notas->totalFornecedorMes(5,$f->id_compra_fornecedor,$i);
                if($total_f_mes > 0){$activeWorksheet->setCellValue($col.$row_categoria,$total_f_mes);}
                $activeWorksheet->getCell($col.$row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
                $col = chr(ord($col) + 1);
            }
            $row_categoria++;
        }

        $activeWorksheet->setCellValue('A' . $row_categoria,'TOTAL '.$cca.':');
        $activeWorksheet->getStyle('A'.$row_categoria.':N'.$row_categoria)->applyFromArray($grey);

        $col = 'B';
        //Lista todos valores de categorias por mês
        for ($i = 1; $i <= 12; $i++) {
            $valor_total = $Compras_notas->totalCategoria(5, $cc->id_compra_categoria, $i, date('Y'));
            $activeWorksheet->setCellValue($col . $row_categoria, $valor_total);
            $activeWorksheet->getCell($col . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
            $col = chr(ord($col) + 1);
        }

        //Lita o total de cada categoria
        $activeWorksheet->setCellValue('N' . $row_categoria, $Compras_notas->totalCategoriaAnual(1, $cc->id_compra_categoria, date('Y')));
        $activeWorksheet->getCell('N' . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
        $activeWorksheet->getStyle('N' . $row_categoria)->applyFromArray($red);

        $row_categoria++;
    }
}

$activeWorksheet->setCellValue('A' . $row_categoria, 'TOTAL:');
$activeWorksheet->getStyle('A' . $row_categoria)->applyFromArray($total_azul_claro);
$mes = 1;
$row_mes = 'B';

for ($i = 1; $i <= 12; $i++) {
    $total = $Compras_notas->totalMes(5, $mes, date('Y'));
    $activeWorksheet->setCellValue($row_mes . $row_categoria, $total); 
    $activeWorksheet->getStyle($row_mes . $row_categoria)->applyFromArray($total_azul_claro);
    $activeWorksheet->getCell($row_mes . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
    $row_mes = chr(ord($row_mes) + 1);
    $mes++;
}

$totalempresa = $Compras_notas->totalEmpresa(5, date('Y'));
$activeWorksheet->setCellValue($row_mes . $row_categoria, $totalempresa);
$activeWorksheet->getCell($row_mes . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
$activeWorksheet->getStyle('N' . $row_categoria)->applyFromArray($red);

$row_categoria++;
$row_categoria++;

/*
    ÓTICA MATRIZ
*/

$activeWorksheet->mergeCells('A' . $row_categoria . ':N' . $row_categoria);
$activeWorksheet->setCellValue('A' . $row_categoria, 'RELATORIO RESUMO GERAL ÓTICA MATRIZ - ' . date('Y'));
$activeWorksheet->getStyle('A' . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$activeWorksheet->getStyle('A' . $row_categoria)->applyFromArray($titulo_otica);
$row_categoria++;
$activeWorksheet->setCellValue('A' . $row_categoria, 'FORNECEDOR');
$activeWorksheet->getStyle('A' . $row_categoria)->applyFromArray($mes_style);

$col = 'B';
//Lista todos os meses
foreach ($meses as $mes) {
    $activeWorksheet->setCellValue($col . $row_categoria, $mes);
    $activeWorksheet->getStyle($col . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $activeWorksheet->getStyle($col . $row_categoria)->applyFromArray($mes_style);
    $col++;
}

$activeWorksheet->setCellValue('N' . $row_categoria, 'TOTAL:');

$row_categoria++;

//Lista todas as categorias
foreach ($Compras_Categoria->listar() as $cc) {
    $cca = strtoupper($cc->categoria);

    // Verifica se há notas associadas a esta categoria para a empresa e ano específicos
    if ($Compras_notas->totalNotasCategoria(2, $cc->id_compra_categoria, date('Y')) > 0) {
        foreach($Compras_Fornecedor->listarPorCategoria(2,$cc->id_compra_categoria,date('Y')) as $f){
            $activeWorksheet->setCellValue('A' . $row_categoria, $f->fornecedor);
            
            $col = 'B';
            for($i = 1; $i <=12; $i++){
                $total_f_mes = $Compras_notas->totalFornecedorMes(2,$f->id_compra_fornecedor,$i);
                if($total_f_mes > 0){$activeWorksheet->setCellValue($col.$row_categoria,$total_f_mes);}
                $activeWorksheet->getCell($col.$row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
                $col = chr(ord($col) + 1);
            }
            $row_categoria++;
        }

        $activeWorksheet->setCellValue('A' . $row_categoria,'TOTAL '.$cca.':');
        $activeWorksheet->getStyle('A'.$row_categoria.':N'.$row_categoria)->applyFromArray($grey);

        $col = 'B';
        //Lista todos valores de categorias por mês
        for ($i = 1; $i <= 12; $i++) {
            $valor_total = $Compras_notas->totalCategoria(2, $cc->id_compra_categoria, $i, date('Y'));
            $activeWorksheet->setCellValue($col . $row_categoria, $valor_total);
            $activeWorksheet->getCell($col . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
            $col = chr(ord($col) + 1);
        }

        //Lita o total de cada categoria
        $activeWorksheet->setCellValue('N' . $row_categoria, $Compras_notas->totalCategoriaAnual(1, $cc->id_compra_categoria, date('Y')));
        $activeWorksheet->getCell('N' . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
        $activeWorksheet->getStyle('N' . $row_categoria)->applyFromArray($red);

        $row_categoria++;
    }
}

$activeWorksheet->setCellValue('A' . $row_categoria, 'TOTAL:');
$activeWorksheet->getStyle('A' . $row_categoria)->applyFromArray($total_verde);
$mes = 1;
$row_mes = 'B';

for ($i = 1; $i <= 12; $i++) {
    $total = $Compras_notas->totalMes(2, $mes, date('Y'));
    $activeWorksheet->setCellValue($row_mes . $row_categoria, $total); 
    $activeWorksheet->getStyle($row_mes . $row_categoria)->applyFromArray($total_verde);
    $activeWorksheet->getCell($row_mes . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
    $row_mes = chr(ord($row_mes) + 1);
    $mes++;
}

$totalempresa = $Compras_notas->totalEmpresa(2, date('Y'));
$activeWorksheet->setCellValue($row_mes . $row_categoria, $totalempresa);
$activeWorksheet->getCell($row_mes . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
$activeWorksheet->getStyle('N' . $row_categoria)->applyFromArray($red);

$row_categoria++;
$row_categoria++;

/*
    ÓTICA PRESTIGIO
*/

$activeWorksheet->mergeCells('A' . $row_categoria . ':N' . $row_categoria);
$activeWorksheet->setCellValue('A' . $row_categoria, 'RELATORIO RESUMO GERAL ÓTICA PRESTIGIO - ' . date('Y'));
$activeWorksheet->getStyle('A' . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$activeWorksheet->getStyle('A' . $row_categoria)->applyFromArray($titulo_otica);
$row_categoria++;
$activeWorksheet->setCellValue('A' . $row_categoria, 'FORNECEDOR');
$activeWorksheet->getStyle('A' . $row_categoria)->applyFromArray($mes_style);

$col = 'B';
//Lista todos os meses
foreach ($meses as $mes) {
    $activeWorksheet->setCellValue($col . $row_categoria, $mes);
    $activeWorksheet->getStyle($col . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $activeWorksheet->getStyle($col . $row_categoria)->applyFromArray($mes_style);
    $col++;
}

$activeWorksheet->setCellValue('N' . $row_categoria, 'TOTAL:');

$row_categoria++;

//Lista todas as categorias
foreach ($Compras_Categoria->listar() as $cc) {
    $cca = strtoupper($cc->categoria);

    // Verifica se há notas associadas a esta categoria para a empresa e ano específicos
    if ($Compras_notas->totalNotasCategoria(4, $cc->id_compra_categoria, date('Y')) > 0) {
        foreach($Compras_Fornecedor->listarPorCategoria(4,$cc->id_compra_categoria,date('Y')) as $f){
            $activeWorksheet->setCellValue('A' . $row_categoria, $f->fornecedor);
            
            $col = 'B';
            for($i = 1; $i <=12; $i++){
                $total_f_mes = $Compras_notas->totalFornecedorMes(4,$f->id_compra_fornecedor,$i);
                if($total_f_mes > 0){$activeWorksheet->setCellValue($col.$row_categoria,$total_f_mes);}
                $activeWorksheet->getCell($col.$row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
                $col = chr(ord($col) + 1);
            }
            $row_categoria++;
        }

        $activeWorksheet->setCellValue('A' . $row_categoria,'TOTAL '.$cca.':');
        $activeWorksheet->getStyle('A'.$row_categoria.':N'.$row_categoria)->applyFromArray($grey);

        $col = 'B';
        //Lista todos valores de categorias por mês
        for ($i = 1; $i <= 12; $i++) {
            $valor_total = $Compras_notas->totalCategoria(4, $cc->id_compra_categoria, $i, date('Y'));
            $activeWorksheet->setCellValue($col . $row_categoria, $valor_total);
            $activeWorksheet->getCell($col . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
            $col = chr(ord($col) + 1);
        }

        //Lita o total de cada categoria
        $activeWorksheet->setCellValue('N' . $row_categoria, $Compras_notas->totalCategoriaAnual(1, $cc->id_compra_categoria, date('Y')));
        $activeWorksheet->getCell('N' . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
        $activeWorksheet->getStyle('N' . $row_categoria)->applyFromArray($red);

        $row_categoria++;
    }
}

$activeWorksheet->setCellValue('A' . $row_categoria, 'TOTAL:');
$activeWorksheet->getStyle('A' . $row_categoria)->applyFromArray($total_verde);
$mes = 1;
$row_mes = 'B';

for ($i = 1; $i <= 12; $i++) {
    $total = $Compras_notas->totalMes(4, $mes, date('Y'));
    $activeWorksheet->setCellValue($row_mes . $row_categoria, $total); 
    $activeWorksheet->getStyle($row_mes . $row_categoria)->applyFromArray($total_verde);
    $activeWorksheet->getCell($row_mes . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
    $row_mes = chr(ord($row_mes) + 1);
    $mes++;
}

$totalempresa = $Compras_notas->totalEmpresa(4, date('Y'));
$activeWorksheet->setCellValue($row_mes . $row_categoria, $totalempresa);
$activeWorksheet->getCell($row_mes . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
$activeWorksheet->getStyle('N' . $row_categoria)->applyFromArray($red);

$row_categoria++;
$row_categoria++;

/*
    ÓTICA DAILY
*/

$activeWorksheet->mergeCells('A' . $row_categoria . ':N' . $row_categoria);
$activeWorksheet->setCellValue('A' . $row_categoria, 'RELATORIO RESUMO GERAL ÓTICA DAILY - ' . date('Y'));
$activeWorksheet->getStyle('A' . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$activeWorksheet->getStyle('A' . $row_categoria)->applyFromArray($titulo_otica);
$row_categoria++;
$activeWorksheet->setCellValue('A' . $row_categoria, 'FORNECEDOR');
$activeWorksheet->getStyle('A' . $row_categoria)->applyFromArray($mes_style);

$col = 'B';
//Lista todos os meses
foreach ($meses as $mes) {
    $activeWorksheet->setCellValue($col . $row_categoria, $mes);
    $activeWorksheet->getStyle($col . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $activeWorksheet->getStyle($col . $row_categoria)->applyFromArray($mes_style);
    $col++;
}

$activeWorksheet->setCellValue('N' . $row_categoria, 'TOTAL:');

$row_categoria++;

//Lista todas as categorias
foreach ($Compras_Categoria->listar() as $cc) {
    $cca = strtoupper($cc->categoria);

    // Verifica se há notas associadas a esta categoria para a empresa e ano específicos
    if ($Compras_notas->totalNotasCategoria(6, $cc->id_compra_categoria, date('Y')) > 0) {
        foreach($Compras_Fornecedor->listarPorCategoria(6,$cc->id_compra_categoria,date('Y')) as $f){
            $activeWorksheet->setCellValue('A' . $row_categoria, $f->fornecedor);
            
            $col = 'B';
            for($i = 1; $i <=12; $i++){
                $total_f_mes = $Compras_notas->totalFornecedorMes(6,$f->id_compra_fornecedor,$i);
                if($total_f_mes > 0){$activeWorksheet->setCellValue($col.$row_categoria,$total_f_mes);}
                $activeWorksheet->getCell($col.$row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
                $col = chr(ord($col) + 1);
            }
            $row_categoria++;
        }

        $activeWorksheet->setCellValue('A' . $row_categoria,'TOTAL '.$cca.':');
        $activeWorksheet->getStyle('A'.$row_categoria.':N'.$row_categoria)->applyFromArray($grey);

        $col = 'B';
        //Lista todos valores de categorias por mês
        for ($i = 1; $i <= 12; $i++) {
            $valor_total = $Compras_notas->totalCategoria(6, $cc->id_compra_categoria, $i, date('Y'));
            $activeWorksheet->setCellValue($col . $row_categoria, $valor_total);
            $activeWorksheet->getCell($col . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
            $col = chr(ord($col) + 1);
        }

        //Lita o total de cada categoria
        $activeWorksheet->setCellValue('N' . $row_categoria, $Compras_notas->totalCategoriaAnual(1, $cc->id_compra_categoria, date('Y')));
        $activeWorksheet->getCell('N' . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
        $activeWorksheet->getStyle('N' . $row_categoria)->applyFromArray($red);

        $row_categoria++;
    }
}

$activeWorksheet->setCellValue('A' . $row_categoria, 'TOTAL:');
$activeWorksheet->getStyle('A' . $row_categoria)->applyFromArray($total_verde);
$mes = 1;
$row_mes = 'B';

for ($i = 1; $i <= 12; $i++) {
    $total = $Compras_notas->totalMes(6, $mes, date('Y'));
    $activeWorksheet->setCellValue($row_mes . $row_categoria, $total); 
    $activeWorksheet->getStyle($row_mes . $row_categoria)->applyFromArray($total_verde);
    $activeWorksheet->getCell($row_mes . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
    $row_mes = chr(ord($row_mes) + 1);
    $mes++;
}

$totalempresa = $Compras_notas->totalEmpresa(6, date('Y'));
$activeWorksheet->setCellValue($row_mes . $row_categoria, $totalempresa);
$activeWorksheet->getCell($row_mes . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
$activeWorksheet->getStyle('N' . $row_categoria)->applyFromArray($red);

$row_categoria++;
$row_categoria++;
/*
    FIM EMPRESAS
*/

$row_categoria++;

/*
    Total Anual:
*/

$activeWorksheet->setCellValue('A'.$row_categoria,'Total Clínicas:');
$activeWorksheet->setCellValue('B'.$row_categoria,$Compras_notas->totalClinicas());
$activeWorksheet->getCell('B'.$row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);

$row_categoria++;

$activeWorksheet->setCellValue('A'.$row_categoria,'Total Óticas:');
$activeWorksheet->setCellValue('B'.$row_categoria,$Compras_notas->totalOticas());
$activeWorksheet->getCell('B'.$row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);

$row_alinhamento = 'A';
for ($i = 1; $i <= 16; $i++) {
    $spreadsheet->getActiveSheet()->getColumnDimension($row_alinhamento)->setAutoSize(true);
    $row_alinhamento = chr(ord($row_alinhamento) + 1);
}

// --------------------------------------------------------------------------------------------------------------------------

// Planilha Quantidade
{
    $newSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Quantidade');
    $spreadsheet->addSheet($newSheet);

    $row_categoria = 1;

    /*
        ÓTICA MATRIZ
    */
    $newSheet->mergeCells('A' . $row_categoria . ':AK' . $row_categoria);
    $newSheet->setCellValue('A' . $row_categoria, 'RELATORIO QUANTIDADE ÓTICA MATRIZ - ' . date('Y'));
    $newSheet->getStyle('A' . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $newSheet->getStyle('A' . $row_categoria)->applyFromArray($titulo_otica);
    $row_categoria++;
    $newSheet->setCellValue('A' . $row_categoria, 'FORNECEDOR');
    $newSheet->getStyle('A' . $row_categoria)->applyFromArray($mes_style);

    $col = 'B';
    //Lista todos os meses
    foreach ($meses as $mes) {
        $col_inicio = $col;
        $newSheet->setCellValue($col . $row_categoria, $mes);
        $newSheet->getStyle($col . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $newSheet->getStyle($col . $row_categoria)->applyFromArray($mes_style);
        $col++;
        $col++;
        $newSheet->mergeCells($col_inicio . $row_categoria . ':'. $col . $row_categoria);
        $col++;
    }

    $row_categoria++;
    
    $coluna = 'B';
    
    for ($i = 0; $i < 12; $i++) {
        $newSheet->setCellValue($coluna . $row_categoria, "COM");
        $newSheet->getStyle($coluna . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $coluna = getNextColumn($coluna);
        
        $newSheet->setCellValue($coluna . $row_categoria, "VEN");
        $newSheet->getStyle($coluna . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $coluna = getNextColumn($coluna);
        
        $newSheet->setCellValue($coluna . $row_categoria, "EST");
        $newSheet->getStyle($coluna . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $coluna = getNextColumn($coluna);
    }
    

    $row_categoria++;

    //Lista todas as categorias
    foreach ($Compras_Categoria->listar() as $cc) {
        $cca = strtoupper($cc->categoria);

        // Verifica se há notas associadas a esta categoria para a empresa e ano específicos
        if ($Compras_notas->totalNotasCategoria(2, $cc->id_compra_categoria, date('Y')) > 0) {
            foreach($Compras_Fornecedor->listarPorCategoria(2,$cc->id_compra_categoria,date('Y')) as $f){
                $newSheet->setCellValue('A' . $row_categoria, $f->fornecedor);
                
                $col = 'B';
                for($i = 1; $i <=12; $i++){
                    $total_f_mes = $Compras_notas->totalQntdFornecedorMes(2,$f->id_compra_fornecedor,$i);
                    if($total_f_mes > 0){$newSheet->setCellValue($col.$row_categoria,$total_f_mes);}
                    $newSheet->getCell($col.$row_categoria)->getStyle()->getNumberFormat();
                    $col = getNextColumn($col);
                    $col = getNextColumn($col);
                    $col = getNextColumn($col);
                }
                $row_categoria++;
            }
            
            $newSheet->setCellValue('A' . $row_categoria,'TOTAL '.$cca.':');
            $newSheet->getStyle('A'.$row_categoria.':AK'.$row_categoria)->applyFromArray($grey);

            $col = 'B';
            //Lista todos valores de categorias por mês
            for ($i = 1; $i <= 12; $i++) {
                $valor_total = $Compras_notas->totalQntdCategoria(2, $cc->id_compra_categoria, $i, date('Y'));
                $newSheet->setCellValue($col . $row_categoria, $valor_total);
                $newSheet->getCell($col . $row_categoria)->getStyle()->getNumberFormat();
                $col = getNextColumn($col);
                $col = getNextColumn($col);
                $col = getNextColumn($col);
            }
            
            $row_categoria++;
        }
    }

    $newSheet->setCellValue('A' . $row_categoria, 'TOTAL:');
    $newSheet->getStyle('A' . $row_categoria)->applyFromArray($total_verde);
    $mes = 1;
    $col_mes = 'B';

    for ($i = 1; $i <= 12; $i++) {
        $total = $Compras_notas->totalQntdMes(2, $mes, date('Y'));
        $newSheet->setCellValue($col_mes . $row_categoria, $total); 
        $newSheet->getStyle($col_mes . $row_categoria)->applyFromArray($total_verde);
        $newSheet->getCell($col_mes . $row_categoria)->getStyle()->getNumberFormat();
        $col_mes = getNextColumn($col_mes);
        $newSheet->getStyle($col_mes . $row_categoria)->applyFromArray($total_verde);
        $col_mes = getNextColumn($col_mes);
        $newSheet->getStyle($col_mes . $row_categoria)->applyFromArray($total_verde);
        $col_mes = getNextColumn($col_mes);
        $mes++;
    }

    $row_categoria++;
    $row_categoria++;

    /*
        ÓTICA PRESTIGIO
    */
    $newSheet->mergeCells('A' . $row_categoria . ':AK' . $row_categoria);
    $newSheet->setCellValue('A' . $row_categoria, 'RELATORIO QUANTIDADE ÓTICA PRESTIGIO - ' . date('Y'));
    $newSheet->getStyle('A' . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $newSheet->getStyle('A' . $row_categoria)->applyFromArray($titulo_otica);
    $row_categoria++;
    $newSheet->setCellValue('A' . $row_categoria, 'FORNECEDOR');
    $newSheet->getStyle('A' . $row_categoria)->applyFromArray($mes_style);

    $col = 'B';
    //Lista todos os meses
    foreach ($meses as $mes) {
        $col_inicio = $col;
        $newSheet->setCellValue($col . $row_categoria, $mes);
        $newSheet->getStyle($col . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $newSheet->getStyle($col . $row_categoria)->applyFromArray($mes_style);
        $col++;
        $col++;
        $newSheet->mergeCells($col_inicio . $row_categoria . ':'. $col . $row_categoria);
        $col++;
    }

    $row_categoria++;
    
    $coluna = 'B';
    
    for ($i = 0; $i < 12; $i++) {
        $newSheet->setCellValue($coluna . $row_categoria, "COM");
        $newSheet->getStyle($coluna . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $coluna = getNextColumn($coluna);
        
        $newSheet->setCellValue($coluna . $row_categoria, "VEN");
        $newSheet->getStyle($coluna . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $coluna = getNextColumn($coluna);
        
        $newSheet->setCellValue($coluna . $row_categoria, "EST");
        $newSheet->getStyle($coluna . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $coluna = getNextColumn($coluna);
    }
    

    $row_categoria++;

    //Lista todas as categorias
    foreach ($Compras_Categoria->listar() as $cc) {
        $cca = strtoupper($cc->categoria);

        // Verifica se há notas associadas a esta categoria para a empresa e ano específicos
        if ($Compras_notas->totalNotasCategoria(2, $cc->id_compra_categoria, date('Y')) > 0) {
            foreach($Compras_Fornecedor->listarPorCategoria(4,$cc->id_compra_categoria,date('Y')) as $f){
                $newSheet->setCellValue('A' . $row_categoria, $f->fornecedor);
                
                $col = 'B';
                for($i = 1; $i <=12; $i++){
                    $total_f_mes = $Compras_notas->totalQntdFornecedorMes(4,$f->id_compra_fornecedor,$i);
                    if($total_f_mes > 0){$newSheet->setCellValue($col.$row_categoria,$total_f_mes);}
                    $newSheet->getCell($col.$row_categoria)->getStyle()->getNumberFormat();
                    $col = getNextColumn($col);
                    $col = getNextColumn($col);
                    $col = getNextColumn($col);
                }
                $row_categoria++;
            }

            $newSheet->setCellValue('A' . $row_categoria,'TOTAL '.$cca.':');
            $newSheet->getStyle('A'.$row_categoria.':AK'.$row_categoria)->applyFromArray($grey);

            $col = 'B';
            //Lista todos valores de categorias por mês
            for ($i = 1; $i <= 12; $i++) {
                $valor_total = $Compras_notas->totalQntdCategoria(4, $cc->id_compra_categoria, $i, date('Y'));
                $newSheet->setCellValue($col . $row_categoria, $valor_total);
                $newSheet->getCell($col . $row_categoria)->getStyle()->getNumberFormat();
                $col = getNextColumn($col);
                $col = getNextColumn($col);
                $col = getNextColumn($col);
            }
            
            $row_categoria++;
        }
    }

    $newSheet->setCellValue('A' . $row_categoria, 'TOTAL:');
    $newSheet->getStyle('A' . $row_categoria)->applyFromArray($total_verde);
    $mes = 1;
    $col_mes = 'B';

    for ($i = 1; $i <= 12; $i++) {
        $total = $Compras_notas->totalQntdMes(4, $mes, date('Y'));
        $newSheet->setCellValue($col_mes . $row_categoria, $total); 
        $newSheet->getStyle($col_mes . $row_categoria)->applyFromArray($total_verde);
        $newSheet->getCell($col_mes . $row_categoria)->getStyle()->getNumberFormat();
        $col_mes = getNextColumn($col_mes);
        $newSheet->getStyle($col_mes . $row_categoria)->applyFromArray($total_verde);
        $col_mes = getNextColumn($col_mes);
        $newSheet->getStyle($col_mes . $row_categoria)->applyFromArray($total_verde);
        $col_mes = getNextColumn($col_mes);
        $mes++;
    }

    $row_categoria++;
    $row_categoria++;

    /*
        ÓTICA DAILY
    */
    $newSheet->mergeCells('A' . $row_categoria . ':AK' . $row_categoria);
    $newSheet->setCellValue('A' . $row_categoria, 'RELATORIO QUANTIDADE ÓTICA DAILY - ' . date('Y'));
    $newSheet->getStyle('A' . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $newSheet->getStyle('A' . $row_categoria)->applyFromArray($titulo_otica);
    $row_categoria++;
    $newSheet->setCellValue('A' . $row_categoria, 'FORNECEDOR');
    $newSheet->getStyle('A' . $row_categoria)->applyFromArray($mes_style);

    $col = 'B';
    //Lista todos os meses
    foreach ($meses as $mes) {
        $col_inicio = $col;
        $newSheet->setCellValue($col . $row_categoria, $mes);
        $newSheet->getStyle($col . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $newSheet->getStyle($col . $row_categoria)->applyFromArray($mes_style);
        $col++;
        $col++;
        $newSheet->mergeCells($col_inicio . $row_categoria . ':'. $col . $row_categoria);
        $col++;
    }

    $row_categoria++;
    
    $coluna = 'B';
    
    for ($i = 0; $i < 12; $i++) {
        $newSheet->setCellValue($coluna . $row_categoria, "COM");
        $newSheet->getStyle($coluna . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $coluna = getNextColumn($coluna);
        
        $newSheet->setCellValue($coluna . $row_categoria, "VEN");
        $newSheet->getStyle($coluna . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $coluna = getNextColumn($coluna);
        
        $newSheet->setCellValue($coluna . $row_categoria, "EST");
        $newSheet->getStyle($coluna . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $coluna = getNextColumn($coluna);
    }
    

    $row_categoria++;

    //Lista todas as categorias
    foreach ($Compras_Categoria->listar() as $cc) {
        $cca = strtoupper($cc->categoria);

        // Verifica se há notas associadas a esta categoria para a empresa e ano específicos
        if ($Compras_notas->totalNotasCategoria(2, $cc->id_compra_categoria, date('Y')) > 0) {
            foreach($Compras_Fornecedor->listarPorCategoria(6,$cc->id_compra_categoria,date('Y')) as $f){
                $newSheet->setCellValue('A' . $row_categoria, $f->fornecedor);
                
                $col = 'B';
                for($i = 1; $i <=12; $i++){
                    $total_f_mes = $Compras_notas->totalQntdFornecedorMes(6,$f->id_compra_fornecedor,$i);
                    if($total_f_mes > 0){$newSheet->setCellValue($col.$row_categoria,$total_f_mes);}
                    $newSheet->getCell($col.$row_categoria)->getStyle()->getNumberFormat();
                    $col = getNextColumn($col);
                    $col = getNextColumn($col);
                    $col = getNextColumn($col);
                }
                $row_categoria++;
            }

            $newSheet->setCellValue('A' . $row_categoria,'TOTAL '.$cca.':');
            $newSheet->getStyle('A'.$row_categoria.':AK'.$row_categoria)->applyFromArray($grey);

            $col = 'B';
            //Lista todos valores de categorias por mês
            for ($i = 1; $i <= 12; $i++) {
                $valor_total = $Compras_notas->totalQntdCategoria(6, $cc->id_compra_categoria, $i, date('Y'));
                $newSheet->setCellValue($col . $row_categoria, $valor_total);
                $newSheet->getCell($col . $row_categoria)->getStyle()->getNumberFormat();
                $col = getNextColumn($col);
                $col = getNextColumn($col);
                $col = getNextColumn($col);
            }
            
            $row_categoria++;
        }
    }

    $newSheet->setCellValue('A' . $row_categoria, 'TOTAL:');
    $newSheet->getStyle('A' . $row_categoria)->applyFromArray($total_verde);
    $mes = 1;
    $col_mes = 'B';

    for ($i = 1; $i <= 12; $i++) {
        $total = $Compras_notas->totalQntdMes(6, $mes, date('Y'));
        $newSheet->setCellValue($col_mes . $row_categoria, $total); 
        $newSheet->getStyle($col_mes . $row_categoria)->applyFromArray($total_verde);
        $newSheet->getCell($col_mes . $row_categoria)->getStyle()->getNumberFormat();
        $col_mes = getNextColumn($col_mes);
        $newSheet->getStyle($col_mes . $row_categoria)->applyFromArray($total_verde);
        $col_mes = getNextColumn($col_mes);
        $newSheet->getStyle($col_mes . $row_categoria)->applyFromArray($total_verde);
        $col_mes = getNextColumn($col_mes);
        $mes++;
    }

    $row_categoria++;
    $row_categoria++;

    $newSheet->getColumnDimension('A')->setAutoSize(true);
}

//Salva o Arquivo
$filename = 'gerar_relatorio_fornecedores_' . date('Y') . '.xlsx';

// Verifica se o arquivo já existe
if (file_exists($filename)) {
    // Se existir, exclui o arquivo antigo
    unlink($filename);
}

// Salva o novo arquivo
$writer = new Xlsx($spreadsheet);
$writer->save($filename);

header('location:relatorios?f');
