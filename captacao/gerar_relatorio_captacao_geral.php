<?php

$inicio = $_GET['inicio'];
$fim = $_GET['fim'];

// Converter a data para o formato (dia-mês-ano)
$inicio_formatado = DateTime::createFromFormat('Y-m-d', $inicio)->format('d/m/Y');
$fim_formatado = DateTime::createFromFormat('Y-m-d', $fim)->format('d/m/Y');

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
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Borders;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

$Captacao = new Captacao();

$real = new Accounting(
    'R$',
    2,
    true,
    true,
    true
);

$titulo = [
    'font' => [
        'bold' => true,
    ]
];

$cabecalho_parque = [
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => '002060', //Azul
        ],
    ],
    'font' => [
        'bold' => true,  
        'size' => 11,    
        'color' => [
            'argb' => \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE,
        ],    
    ],
];

$border_black = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['rgb' => '000000'], // Preto
        ],
    ],
];

$bordas = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['argb' => '000000'], // Cor da borda (preto)
        ],
    ],
];

$row = 1;

$spreadsheet = new Spreadsheet();
$captacao = $spreadsheet->getActiveSheet();
$captacao->setTitle('CAPTAÇÃO');

// Aplicar a fonte "Aptos Narrow" para toda a planilha
$spreadsheet->getDefaultStyle()->getFont()->setName('Aptos Narrow');
// Cria um estilo de fonte com tamanho 11
$spreadsheet->getDefaultStyle()->getFont()->setSize(11);

// Remover as margens
$captacao->getPageMargins()->setTop(0);
$captacao->getPageMargins()->setRight(0);
$captacao->getPageMargins()->setLeft(0);
$captacao->getPageMargins()->setBottom(0);

// Definir fundo branco para todas as células
$spreadsheet->getDefaultStyle()->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFFFFF');

$row++;

$captacao->setCellValue('E'.$row,'PERIODO:');
$captacao->getStyle('E'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
$captacao->getStyle('E'.$row)->applyFromArray($titulo);
$captacao->setCellValue('F'.$row,$inicio_formatado.' - '.$fim_formatado);
$captacao->getStyle('F'.$row)->applyFromArray($titulo);

$row++;

$captacao->setCellValue('E'.$row,'RELATÓRIO GERADO EM:');
$captacao->getStyle('E'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
$captacao->getStyle('E'.$row)->applyFromArray($titulo);
$captacao->setCellValue('F'.$row,date('d/m/Y').' ('.date('H:i'.')'));
$captacao->getStyle('F'.$row)->applyFromArray($titulo);

$row++;
$row++;

// CLÍNICA MATRIZ
{
    //Título
    $captacao->setCellValue('B'.$row,'CAPTAÇÃO  CLÍNICA - (PARQUE)');
    $captacao->getStyle('B'.$row)->applyFromArray($titulo);
    $captacao->mergeCells('B'.$row.':H'.$row);
    $captacao->getStyle('B'.$row.':H'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $row++;

    $row_inicio = $row;

    //Cabeçalhos
    $captacao->setCellValue('B'.$row,'CAPTADOR');
    $captacao->setCellValue('C'.$row,'RECEITAS');
    $captacao->setCellValue('D'.$row,'CAPTADOS');
    $captacao->setCellValue('E'.$row,'NÃO CAPTADOS');
    $captacao->setCellValue('F'.$row,'(%) CAPTAÇÃO');
    $captacao->setCellValue('G'.$row,'LENTES');
    $captacao->setCellValue('H'.$row,'GARANTIAS');
    $captacao->getStyle('B'.$row.':H'.$row)->applyFromArray($cabecalho_parque);

    foreach($Captacao->listarCaptadoresPorEmpresa(1) as $captador){
        if($Captacao->contarCaptacoesNoIntervalo($captador->id_usuario,$inicio,$fim) > 0){
            $row++;

            $captacao->setCellValue('B'.$row,Helper::primeiroNomeMaisculo($captador->nome));
            $captacao->getStyle('B'.$row)->applyFromArray($titulo);
        }
    }

    $row++;

    $captacao->setCellValue('B'.$row,'TOTAL');
    $captacao->getStyle('B'.$row.':H'.$row)->applyFromArray($cabecalho_parque);

    $captacao->getStyle('B'.$row_inicio.':H'.$row)->applyFromArray($bordas);
}

//AutoSize
{
    $captacao->getColumnDimension('A')->setAutoSize(true);
    $captacao->getColumnDimension('B')->setAutoSize(true);
    $captacao->getColumnDimension('C')->setAutoSize(true);
    $captacao->getColumnDimension('D')->setAutoSize(true);
    $captacao->getColumnDimension('E')->setAutoSize(true);
    $captacao->getColumnDimension('F')->setAutoSize(true);
    $captacao->getColumnDimension('G')->setAutoSize(true);
    $captacao->getColumnDimension('H')->setAutoSize(true);
}

//FIM

$spreadsheet->setActiveSheetIndexByName('CAPTAÇÃO');

// Converter a data para o formato (dia-mês-ano)
$inicio_formatado = DateTime::createFromFormat('Y-m-d', $inicio)->format('d-m-Y');
$fim_formatado = DateTime::createFromFormat('Y-m-d', $fim)->format('d-m-Y');

$filename = 'relatorio_captacao_geral_'.$inicio_formatado.'a'.$fim_formatado.'.xlsx';

// Verifica se o arquivo já existe
if (file_exists($filename)) {
    // Se existir, exclui o arquivo antigo
    unlink($filename);
}

// Salva o novo arquivo
$writer = new Xlsx($spreadsheet);
$writer->save($filename);

header('Location: ' . $filename);