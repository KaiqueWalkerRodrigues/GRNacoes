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

$captacao->setCellValue('A'.$row,'PERIODO:');
$captacao->getStyle('A'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
$captacao->getStyle('A'.$row)->applyFromArray($titulo);
$captacao->setCellValue('B'.$row,$inicio_formatado.' - '.$fim_formatado);
$captacao->getStyle('B'.$row)->applyFromArray($titulo);

$row++;

$captacao->setCellValue('A'.$row,'RELATÓRIO GERADO EM:');
$captacao->getStyle('A'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
$captacao->getStyle('A'.$row)->applyFromArray($titulo);
$captacao->setCellValue('B'.$row,date('d/m/Y').' ('.date('H:i'.')'));
$captacao->getStyle('B'.$row)->applyFromArray($titulo);

$row++;
$row++;

// CLÍNICA MATRIZ
{
    //Título
    $captacao->setCellValue('B'.$row,'CAPTAÇÃO  CLÍNICA - (PARQUE)');
    $captacao->getStyle('B'.$row)->applyFromArray($titulo);
    $captacao->mergeCells('B'.$row.':J'.$row);
    $captacao->getStyle('B'.$row.':J'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $row++;

    $row_inicio = $row;

    //Cabeçalhos
    $captacao->setCellValue('B'.$row,'CAPTADOR');
    $captacao->setCellValue('C'.$row,'PACIENTES');
    $captacao->setCellValue('D'.$row,'CAPTAVEIS');
    $captacao->setCellValue('E'.$row,'CAPTADOS');
    $captacao->setCellValue('F'.$row,'NÃO CAPTADOS');
    $captacao->setCellValue('G'.$row,'LENTES');
    $captacao->setCellValue('H'.$row,'GARANTIAS');
    $captacao->setCellValue('I'.$row,'(%) CAPTADO');
    $captacao->setCellValue('J'.$row,'(%) NÃO CAPTADO');
    $captacao->getStyle('B'.$row.':J'.$row)->applyFromArray($cabecalho_parque);
    $captacao->getStyle('B'.$row.':J'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    foreach($Captacao->listarCaptadoresPorEmpresa(1) as $captador){
        if($Captacao->contarCaptacoesNoIntervalo($captador->id_usuario,$inicio,$fim) > 0){
            $row++;

            $captacao->setCellValue('B'.$row,Helper::primeiroNomeMaisculo($captador->nome));
            $captacao->getStyle('B'.$row)->applyFromArray($titulo);

            $Captados = $Captacao->contarCaptacoesDoPeriodo($inicio,$fim,$captador->id_usuario,1);
            $NaoCaptados = $Captacao->contarNaoCaptacoesDoPeriodo($inicio,$fim,$captador->id_usuario,1);
            $Captaveis = $Captacao->contarCaptaveisDoPeriodo($inicio,$fim,$captador->id_usuario,1);
            
            $captacao->setCellValue('C'.$row,$Captacao->contarPacientesDoPeriodo($inicio,$fim,$captador->id_usuario,1));

            $captacao->setCellValue('D'.$row,$Captaveis);

            $captacao->setCellValue('E'.$row,$Captados);

            $captacao->setCellValue('F'.$row,$NaoCaptados);

            $captacao->setCellValue('G'.$row,$Captacao->contarLentesDoPeriodo($inicio,$fim,$captador->id_usuario,1));

            $captacao->setCellValue('H'.$row,$Captacao->contarGarantiasDoPeriodo($inicio,$fim,$captador->id_usuario,1));  

            $captacao->setCellValue('I'.$row,($Captados/$Captaveis));
            $captacao->getStyle('I'.$row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE);

            $captacao->setCellValue('J'.$row,($NaoCaptados/$Captaveis));
            $captacao->getStyle('J'.$row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE);

            $captacao->getStyle('B'.$row.':J'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }
    }

    $row++;

    $captacao->setCellValue('B'.$row,'TOTAL');
    
    $TotalCaptados = $Captacao->contarTotalCaptacoesDoPeriodo($inicio,$fim,1);
    $TotalNaoCaptados = $Captacao->contarTotalNaoCaptacoesDoPeriodo($inicio,$fim,1);
    $TotalCaptaveis = $Captacao->contarTotalCaptaveisDoPeriodo($inicio,$fim,1);
    
    $captacao->setCellValue('C'.$row,$Captacao->contarTotalPacientesDoPeriodo($inicio,$fim,1));

    $captacao->setCellValue('D'.$row,$TotalCaptaveis);

    $captacao->setCellValue('E'.$row,$TotalCaptados);

    $captacao->setCellValue('F'.$row,$TotalNaoCaptados);

    $captacao->setCellValue('G'.$row,$Captacao->contarTotalLentesDoPeriodo($inicio,$fim,1));

    $captacao->setCellValue('H'.$row,$Captacao->contarTotalGarantiasDoPeriodo($inicio,$fim,1));  

    $captacao->setCellValue('I'.$row,($TotalCaptados/$TotalCaptaveis));
    $captacao->getStyle('I'.$row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE);

    $captacao->setCellValue('J'.$row,($TotalNaoCaptados/$TotalCaptaveis));
    $captacao->getStyle('J'.$row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE);

    $captacao->getStyle('B'.$row.':J'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $captacao->getStyle('B'.$row.':J'.$row)->applyFromArray($cabecalho_parque);

    $captacao->getStyle('B'.$row_inicio.':J'.$row)->applyFromArray($bordas);
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
    $captacao->getColumnDimension('I')->setAutoSize(true);
    $captacao->getColumnDimension('J')->setAutoSize(true);
}

//FIM
$spreadsheet->setActiveSheetIndexByName('CAPTAÇÃO');

// Converter a data para o formato (dia-mês-ano)
$inicio_formatado = DateTime::createFromFormat('Y-m-d', $inicio)->format('d-m-Y');
$fim_formatado = DateTime::createFromFormat('Y-m-d', $fim)->format('d-m-Y');

$filename = 'relatorio_captacao_geral_'.$inicio_formatado.'a'.$fim_formatado.'.xlsx';

// Verifica se existe algum arquivo que começa com "relatorio_captacao_geral"
$files = glob('relatorio_captacao_geral*.xlsx');

if (!empty($files)) {
    // Se existirem, exclui todos os arquivos correspondentes
    foreach ($files as $file) {
        unlink($file);
    }
}

// Salva o novo arquivo
$writer = new Xlsx($spreadsheet);
$writer->save($filename);

header("Location: $filename");