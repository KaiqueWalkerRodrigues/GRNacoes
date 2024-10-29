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

$formatoReal = '_-"R$" * #,##0.00_-;-"R$" * #,##0.00_-;_-"R$" * "-"??_-;_-@_-';

$row = 1;

$spreadsheet = new Spreadsheet();
$captacao = $spreadsheet->getActiveSheet();
$captacao->setTitle('CAPTAÇÃO');

// Aplicar a fonte "Aptos Narrow" para toda a planilha
$spreadsheet->getDefaultStyle()->getFont()->setName('Aptos Narrow');
// Cria um estilo de fonte com tamanho 11
$spreadsheet->getDefaultStyle()->getFont()->setSize(11);

// Definir o zoom para 80%
$captacao->getSheetView()->setZoomScale(80);

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
    $captacao->setCellValue('E'.$row,'CAPTAÇÃO  CLÍNICA - (PARQUE)');
    $captacao->getStyle('E'.$row)->applyFromArray($titulo);
    $captacao->mergeCells('E'.$row.':H'.$row);
    $captacao->getStyle('E'.$row.':H'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $captacao->setCellValue('C'.$row,'SISTEMA');
    $captacao->getStyle('C'.$row)->applyFromArray($titulo);
    $captacao->getStyle('C'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $captacao->setCellValue('D'.$row,'CAPTAÇÃO');
    $captacao->getStyle('D'.$row)->applyFromArray($titulo);
    $captacao->getStyle('D'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $row++;

    $row_inicio = $row;

    $col = 'B';

    //Cabeçalhos
    $captacao->setCellValue($col.$row,'CAPTADOR');
    $col++;
    $captacao->setCellValue($col.$row,'CAPTADOS');
    $col++;
    $captacao->setCellValue($col.$row,'CAPTADOS');
    $col++;
    $captacao->setCellValue($col.$row,'(%) CAPTADOS');
    $col++;
    $captacao->setCellValue($col.$row,'CONVERTIDOS');
    $col++;
    $captacao->setCellValue($col.$row,'(%) CONVERTIDOS');
    $col++;
    $captacao->setCellValue($col.$row,'VENDIDOS');
    $col++;
    $captacao->setCellValue($col.$row,'(%) VENDIDOS');
    $col++;
    $captacao->setCellValue($col.$row,'LENTES');
    $col++;
    $captacao->setCellValue($col.$row,'GARANTIAS');
    $col++;
    $captacao->setCellValue($col.$row,'COMISSÕES (R$2,00)');
    $col++;
    $captacao->setCellValue($col.$row,'PREMIAÇÃO (%)');
    $captacao->getStyle('B'.$row.':M'.$row)->applyFromArray($cabecalho_parque);
    $captacao->getStyle('B'.$row.':M'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $sistema = False;

    foreach($Captacao->listarCaptadoresPorEmpresa(1) as $captador){
        if($Captacao->contarCaptacoesNoIntervalo($captador->id_usuario,$inicio,$fim) > 0){
            $col = 'B';
            $row++;

            $Captados = $Captacao->contarCaptacoesDoPeriodo($inicio,$fim,$captador->id_usuario,1);
            $NaoCaptados = $Captacao->contarNaoCaptacoesDoPeriodo($inicio,$fim,$captador->id_usuario,1);
            $Captaveis = $Captacao->contarCaptaveisDoPeriodo($inicio,$fim,$captador->id_usuario,1);
            $Pacientes = $Captacao->contarPacientesDoPeriodo($inicio,$fim,$captador->id_usuario,1);

            $captacao->setCellValue($col.$row,Helper::primeiroNomeMaisculo($captador->nome));
            $captacao->getStyle($col.$row)->applyFromArray($titulo);

            $col++;
        
            if($sistema == True){
                $captacao->setCellValue($col.$row,'=C'.$row_primeiro);
            }else{
                $row_primeiro = $row;
                $sistema = True;
            }

            $col++;
            
            $captacao->setCellValue($col.$row,$Pacientes);

            $col++;

            $captacao->setCellValue($col.$row, '=IFERROR(D'.$row.'/C'.$row.',0)');
            $captacao->getStyle($col.$row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE);

            $col++;

            $captacao->setCellValue($col.$row,$Captados);

            $col++;

            $captacao->setCellValue($col.$row, '=IFERROR(F'.$row.'/C'.$row.',0)');
            $captacao->getStyle($col.$row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE);
            
            $col++;
            $col++;

            $captacao->setCellValue($col.$row,'=IFERROR(H'.$row.'/D'.$row.',0)');
            $captacao->getStyle($col.$row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE);

            $col++;
            
            $captacao->setCellValue($col.$row,$Captacao->contarLentesDoPeriodo($inicio,$fim,$captador->id_usuario,1));
            
            $col++;
            
            $captacao->setCellValue($col.$row,$Captacao->contarGarantiasDoPeriodo($inicio,$fim,$captador->id_usuario,1));

            $col++;

            $captacao->setCellValue($col.$row,'=H'.$row.'*2');
            $captacao->getStyle($col.$row)->getNumberFormat()->setFormatCode($formatoReal);

            $col++;

            $captacao->setCellValue($col.$row, '=IFERROR(IF(F'.$row.'/C'.$row.'>0.80, 250, IF(F'.$row.'/C'.$row.'>0.70, 200, IF(F'.$row.'/C'.$row.'>0.60, 150, IF(F'.$row.'/C'.$row.'>0.50, 100, 0)))), "-")');
            $captacao->getStyle($col.$row)->getNumberFormat()->setFormatCode($formatoReal);

            $col++;

            $captacao->setCellValue($col.$row,'=SUM(L'.$row.':M'.$row.')');
            $captacao->getStyle($col.$row)->getNumberFormat()->setFormatCode($formatoReal);
            
            $captacao->getStyle('B'.$row.':N'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }
    }

    $row++;

    $col = 'B';
    
    $TotalCaptados = $Captacao->contarTotalCaptacoesDoPeriodo($inicio,$fim,1);
    $TotalNaoCaptados = $Captacao->contarTotalNaoCaptacoesDoPeriodo($inicio,$fim,1);
    $TotalCaptaveis = $Captacao->contarTotalCaptaveisDoPeriodo($inicio,$fim,1);
    $TotalPacientes= $Captacao->contarTotalPacientesDoPeriodo($inicio,$fim,1);

    $captacao->setCellValue($col.$row,'TOTAL');

    $col++;
    
    $captacao->setCellValue($col.$row,'=C'.$row_primeiro);

    $col++;

    $captacao->setCellValue($col.$row,$TotalPacientes);

    $col++;

    $captacao->setCellValue($col.$row,'=IFERROR(D'.$row.'/C'.$row.',0)');
    $captacao->getStyle($col.$row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE);

    $col++;

    $captacao->setCellValue($col.$row,$TotalCaptados);

    $col++;

    $captacao->setCellValue($col.$row,$TotalCaptados/$TotalCaptaveis);
    $captacao->getStyle($col.$row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE);

    
    $col++;
    
    $captacao->setCellValue($col.$row,'=SUM('.$col.$row_primeiro.':'.$col.($row-1).')');

    $col++;
    
    $captacao->setCellValue($col.$row,'=IFERROR(H'.$row.'/D'.$row.',0)');
    $captacao->getStyle($col.$row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE);
    
    $col++;
    
    $captacao->setCellValue($col.$row,$Captacao->contarTotalLentesDoPeriodo($inicio,$fim,1));
    
    $col++;
    
    $captacao->setCellValue($col.$row,$Captacao->contarTotalGarantiasDoPeriodo($inicio,$fim,1));

    $col++;

    $captacao->setCellValue($col.$row,'=H'.$row.'*2');
    $captacao->getStyle($col.$row)->getNumberFormat()->setFormatCode($formatoReal);

    $col++;

    $captacao->setCellValue($col.$row,'=SUM('.$col.$row_primeiro.':'.$col.($row-1).')');
    $captacao->getStyle($col.$row)->getNumberFormat()->setFormatCode($formatoReal);

    $captacao->getStyle('B'.$row.':M'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $captacao->getStyle('B'.$row.':M'.$row)->applyFromArray($cabecalho_parque);

    $captacao->getStyle('B'.$row_inicio.':M'.$row)->applyFromArray($bordas);
}

$row++;
$row++;

// CLÍNICA MAUÁ
{
    //Título
    $captacao->setCellValue('E'.$row,'CAPTAÇÃO  CLÍNICA - (MAUÁ)');
    $captacao->getStyle('E'.$row)->applyFromArray($titulo);
    $captacao->mergeCells('E'.$row.':H'.$row);
    $captacao->getStyle('E'.$row.':H'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $captacao->setCellValue('C'.$row,'SISTEMA');
    $captacao->getStyle('C'.$row)->applyFromArray($titulo);
    $captacao->getStyle('C'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $captacao->setCellValue('D'.$row,'CAPTAÇÃO');
    $captacao->getStyle('D'.$row)->applyFromArray($titulo);
    $captacao->getStyle('D'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $row++;

    $row_inicio = $row;

    $col = 'B';

    //Cabeçalhos
    $captacao->setCellValue($col.$row,'CAPTADOR');
    $col++;
    $captacao->setCellValue($col.$row,'CAPTADOS');
    $col++;
    $captacao->setCellValue($col.$row,'CAPTADOS');
    $col++;
    $captacao->setCellValue($col.$row,'(%) CAPTADOS');
    $col++;
    $captacao->setCellValue($col.$row,'CONVERTIDOS');
    $col++;
    $captacao->setCellValue($col.$row,'(%) CONVERTIDOS');
    $col++;
    $captacao->setCellValue($col.$row,'VENDIDOS');
    $col++;
    $captacao->setCellValue($col.$row,'(%) VENDIDOS');
    $col++;
    $captacao->setCellValue($col.$row,'LENTES');
    $col++;
    $captacao->setCellValue($col.$row,'GARANTIAS');
    $col++;
    $captacao->setCellValue($col.$row,'COMISSÕES (R$2,00)');
    $col++;
    $captacao->setCellValue($col.$row,'PREMIAÇÃO (%)');
    $captacao->getStyle('B'.$row.':M'.$row)->applyFromArray($cabecalho_parque);
    $captacao->getStyle('B'.$row.':M'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $sistema = False;

    foreach($Captacao->listarCaptadoresPorEmpresa(3) as $captador){
        if($Captacao->contarCaptacoesNoIntervalo($captador->id_usuario,$inicio,$fim) > 0){
            $col = 'B';
            $row++;

            $Captados = $Captacao->contarCaptacoesDoPeriodo($inicio,$fim,$captador->id_usuario,3);
            $NaoCaptados = $Captacao->contarNaoCaptacoesDoPeriodo($inicio,$fim,$captador->id_usuario,3);
            $Captaveis = $Captacao->contarCaptaveisDoPeriodo($inicio,$fim,$captador->id_usuario,3);
            $Pacientes = $Captacao->contarPacientesDoPeriodo($inicio,$fim,$captador->id_usuario,3);

            $captacao->setCellValue($col.$row,Helper::primeiroNomeMaisculo($captador->nome));
            $captacao->getStyle($col.$row)->applyFromArray($titulo);

            $col++;
        
            if($sistema == True){
                $captacao->setCellValue($col.$row,'=C'.$row_primeiro);
            }else{
                $row_primeiro = $row;
                $sistema = True;
            }

            $col++;
            
            $captacao->setCellValue($col.$row,$Pacientes);

            $col++;

            $captacao->setCellValue($col.$row, '=IFERROR(D'.$row.'/C'.$row.',0)');
            $captacao->getStyle($col.$row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE);

            $col++;

            $captacao->setCellValue($col.$row,$Captados);

            $col++;

            $captacao->setCellValue($col.$row, '=IFERROR(F'.$row.'/C'.$row.',0)');
            $captacao->getStyle($col.$row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE);
            
            $col++;
            $col++;

            $captacao->setCellValue($col.$row,'=IFERROR(H'.$row.'/D'.$row.',0)');
            $captacao->getStyle($col.$row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE);

            $col++;
            
            $captacao->setCellValue($col.$row,$Captacao->contarLentesDoPeriodo($inicio,$fim,$captador->id_usuario,3));
            
            $col++;
            
            $captacao->setCellValue($col.$row,$Captacao->contarGarantiasDoPeriodo($inicio,$fim,$captador->id_usuario,3));

            $col++;

            $captacao->setCellValue($col.$row,'=H'.$row.'*2');
            $captacao->getStyle($col.$row)->getNumberFormat()->setFormatCode($formatoReal);

            $col++;

            $captacao->setCellValue($col.$row, '=IFERROR(IF(F'.$row.'/C'.$row.'>0.80, 250, IF(F'.$row.'/C'.$row.'>0.70, 200, IF(F'.$row.'/C'.$row.'>0.60, 150, IF(F'.$row.'/C'.$row.'>0.50, 100, 0)))), "-")');
            $captacao->getStyle($col.$row)->getNumberFormat()->setFormatCode($formatoReal);

            $col++;

            $captacao->setCellValue($col.$row,'=SUM(L'.$row.':M'.$row.')');
            $captacao->getStyle($col.$row)->getNumberFormat()->setFormatCode($formatoReal);
            
            $captacao->getStyle('B'.$row.':N'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }
    }

    $row++;

    $col = 'B';
    
    $TotalCaptados = $Captacao->contarTotalCaptacoesDoPeriodo($inicio,$fim,3);
    $TotalNaoCaptados = $Captacao->contarTotalNaoCaptacoesDoPeriodo($inicio,$fim,3);
    $TotalCaptaveis = $Captacao->contarTotalCaptaveisDoPeriodo($inicio,$fim,3);
    $TotalPacientes= $Captacao->contarTotalPacientesDoPeriodo($inicio,$fim,3);

    $captacao->setCellValue($col.$row,'TOTAL');

    $col++;
    
    $captacao->setCellValue($col.$row,'=C'.$row_primeiro);

    $col++;

    $captacao->setCellValue($col.$row,$TotalPacientes);

    $col++;

    $captacao->setCellValue($col.$row,'=IFERROR(D'.$row.'/C'.$row.',0)');
    $captacao->getStyle($col.$row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE);

    $col++;

    $captacao->setCellValue($col.$row,$TotalCaptados);

    $col++;

    $captacao->setCellValue($col.$row,$TotalCaptados/$TotalCaptaveis);
    $captacao->getStyle($col.$row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE);

    
    $col++;
    
    $captacao->setCellValue($col.$row,'=SUM('.$col.$row_primeiro.':'.$col.($row-1).')');

    $col++;
    
    $captacao->setCellValue($col.$row,'=IFERROR(H'.$row.'/D'.$row.',0)');
    $captacao->getStyle($col.$row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE);
    
    $col++;
    
    $captacao->setCellValue($col.$row,$Captacao->contarTotalLentesDoPeriodo($inicio,$fim,3));
    
    $col++;
    
    $captacao->setCellValue($col.$row,$Captacao->contarTotalGarantiasDoPeriodo($inicio,$fim,3));

    $col++;

    $captacao->setCellValue($col.$row,'=H'.$row.'*2');
    $captacao->getStyle($col.$row)->getNumberFormat()->setFormatCode($formatoReal);

    $col++;

    $captacao->setCellValue($col.$row,'=SUM('.$col.$row_primeiro.':'.$col.($row-1).')');
    $captacao->getStyle($col.$row)->getNumberFormat()->setFormatCode($formatoReal);

    $captacao->getStyle('B'.$row.':M'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $captacao->getStyle('B'.$row.':M'.$row)->applyFromArray($cabecalho_parque);

    $captacao->getStyle('B'.$row_inicio.':M'.$row)->applyFromArray($bordas);
}

$row++;
$row++;

// CLÍNICA JARDIM
{
    //Título
    $captacao->setCellValue('E'.$row,'CAPTAÇÃO  CLÍNICA - (JARDIM)');
    $captacao->getStyle('E'.$row)->applyFromArray($titulo);
    $captacao->mergeCells('E'.$row.':H'.$row);
    $captacao->getStyle('E'.$row.':H'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $captacao->setCellValue('C'.$row,'SISTEMA');
    $captacao->getStyle('C'.$row)->applyFromArray($titulo);
    $captacao->getStyle('C'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $captacao->setCellValue('D'.$row,'CAPTAÇÃO');
    $captacao->getStyle('D'.$row)->applyFromArray($titulo);
    $captacao->getStyle('D'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $row++;

    $row_inicio = $row;

    $col = 'B';

    //Cabeçalhos
    $captacao->setCellValue($col.$row,'CAPTADOR');
    $col++;
    $captacao->setCellValue($col.$row,'CAPTADOS');
    $col++;
    $captacao->setCellValue($col.$row,'CAPTADOS');
    $col++;
    $captacao->setCellValue($col.$row,'(%) CAPTADOS');
    $col++;
    $captacao->setCellValue($col.$row,'CONVERTIDOS');
    $col++;
    $captacao->setCellValue($col.$row,'(%) CONVERTIDOS');
    $col++;
    $captacao->setCellValue($col.$row,'VENDIDOS');
    $col++;
    $captacao->setCellValue($col.$row,'(%) VENDIDOS');
    $col++;
    $captacao->setCellValue($col.$row,'LENTES');
    $col++;
    $captacao->setCellValue($col.$row,'GARANTIAS');
    $col++;
    $captacao->setCellValue($col.$row,'COMISSÕES (R$2,00)');
    $col++;
    $captacao->setCellValue($col.$row,'PREMIAÇÃO (%)');
    $captacao->getStyle('B'.$row.':M'.$row)->applyFromArray($cabecalho_parque);
    $captacao->getStyle('B'.$row.':M'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $sistema = False;

    foreach($Captacao->listarCaptadoresPorEmpresa(5) as $captador){
        if($Captacao->contarCaptacoesNoIntervalo($captador->id_usuario,$inicio,$fim) > 0){
            $col = 'B';
            $row++;

            $Captados = $Captacao->contarCaptacoesDoPeriodo($inicio,$fim,$captador->id_usuario,5);
            $NaoCaptados = $Captacao->contarNaoCaptacoesDoPeriodo($inicio,$fim,$captador->id_usuario,5);
            $Captaveis = $Captacao->contarCaptaveisDoPeriodo($inicio,$fim,$captador->id_usuario,5);
            $Pacientes = $Captacao->contarPacientesDoPeriodo($inicio,$fim,$captador->id_usuario,5);

            $captacao->setCellValue($col.$row,Helper::primeiroNomeMaisculo($captador->nome));
            $captacao->getStyle($col.$row)->applyFromArray($titulo);

            $col++;
        
            if($sistema == True){
                $captacao->setCellValue($col.$row,'=C'.$row_primeiro);
            }else{
                $row_primeiro = $row;
                $sistema = True;
            }

            $col++;
            
            $captacao->setCellValue($col.$row,$Pacientes);

            $col++;

            $captacao->setCellValue($col.$row, '=IFERROR(D'.$row.'/C'.$row.',0)');
            $captacao->getStyle($col.$row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE);

            $col++;

            $captacao->setCellValue($col.$row,$Captados);

            $col++;

            $captacao->setCellValue($col.$row, '=IFERROR(F'.$row.'/C'.$row.',0)');
            $captacao->getStyle($col.$row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE);
            
            $col++;
            $col++;

            $captacao->setCellValue($col.$row,'=IFERROR(H'.$row.'/D'.$row.',0)');
            $captacao->getStyle($col.$row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE);

            $col++;
            
            $captacao->setCellValue($col.$row,$Captacao->contarLentesDoPeriodo($inicio,$fim,$captador->id_usuario,5));
            
            $col++;
            
            $captacao->setCellValue($col.$row,$Captacao->contarGarantiasDoPeriodo($inicio,$fim,$captador->id_usuario,5));

            $col++;

            $captacao->setCellValue($col.$row,'=H'.$row.'*2');
            $captacao->getStyle($col.$row)->getNumberFormat()->setFormatCode($formatoReal);

            $col++;

            $captacao->setCellValue($col.$row, '=IFERROR(IF(F'.$row.'/C'.$row.'>0.80, 250, IF(F'.$row.'/C'.$row.'>0.70, 200, IF(F'.$row.'/C'.$row.'>0.60, 150, IF(F'.$row.'/C'.$row.'>0.50, 100, 0)))), "-")');
            $captacao->getStyle($col.$row)->getNumberFormat()->setFormatCode($formatoReal);

            $col++;

            $captacao->setCellValue($col.$row,'=SUM(L'.$row.':M'.$row.')');
            $captacao->getStyle($col.$row)->getNumberFormat()->setFormatCode($formatoReal);
            
            $captacao->getStyle('B'.$row.':N'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }
    }

    $row++;

    $col = 'B';
    
    $TotalCaptados = $Captacao->contarTotalCaptacoesDoPeriodo($inicio,$fim,5);
    $TotalNaoCaptados = $Captacao->contarTotalNaoCaptacoesDoPeriodo($inicio,$fim,5);
    $TotalCaptaveis = $Captacao->contarTotalCaptaveisDoPeriodo($inicio,$fim,5);
    $TotalPacientes= $Captacao->contarTotalPacientesDoPeriodo($inicio,$fim,5);

    $captacao->setCellValue($col.$row,'TOTAL');

    $col++;
    
    $captacao->setCellValue($col.$row,'=C'.$row_primeiro);

    $col++;

    $captacao->setCellValue($col.$row,$TotalPacientes);

    $col++;

    $captacao->setCellValue($col.$row,'=IFERROR(D'.$row.'/C'.$row.',0)');
    $captacao->getStyle($col.$row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE);

    $col++;

    $captacao->setCellValue($col.$row,$TotalCaptados);

    $col++;

    $captacao->setCellValue($col.$row,$TotalCaptados/$TotalCaptaveis);
    $captacao->getStyle($col.$row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE);

    
    $col++;
    
    $captacao->setCellValue($col.$row,'=SUM('.$col.$row_primeiro.':'.$col.($row-1).')');

    $col++;
    
    $captacao->setCellValue($col.$row,'=IFERROR(H'.$row.'/D'.$row.',0)');
    $captacao->getStyle($col.$row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE);
    
    $col++;
    
    $captacao->setCellValue($col.$row,$Captacao->contarTotalLentesDoPeriodo($inicio,$fim,5));
    
    $col++;
    
    $captacao->setCellValue($col.$row,$Captacao->contarTotalGarantiasDoPeriodo($inicio,$fim,5));

    $col++;

    $captacao->setCellValue($col.$row,'=H'.$row.'*2');
    $captacao->getStyle($col.$row)->getNumberFormat()->setFormatCode($formatoReal);

    $col++;

    $captacao->setCellValue($col.$row,'=SUM('.$col.$row_primeiro.':'.$col.($row-1).')');
    $captacao->getStyle($col.$row)->getNumberFormat()->setFormatCode($formatoReal);

    $captacao->getStyle('B'.$row.':M'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $captacao->getStyle('B'.$row.':M'.$row)->applyFromArray($cabecalho_parque);

    $captacao->getStyle('B'.$row_inicio.':M'.$row)->applyFromArray($bordas);
}

//AutoSize
{
    $col = 'A';
    for($i = 0;$i < 15;$i++){
        $captacao->getColumnDimension($col)->setAutoSize(true);
        $col++;
    }
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