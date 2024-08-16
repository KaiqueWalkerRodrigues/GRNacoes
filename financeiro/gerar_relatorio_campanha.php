<?php

require '../vendor/autoload.php';

require '../const.php';

 $id_campanha = $_GET['id'];

 $Usuarios = new Usuario();
 $Campanhas = new Financeiro_Campanhas();
 $Boletos = new Financeiro_Boletos();

 $Campanha = $Campanhas->mostrar($id_campanha);

 $n_campanha = preg_replace('/\D/', '', $Campanha->nome);

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Borders;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

// Cria uma nova planilha
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$titulo_campanha = 'RESUMO CAMPANHA '.$n_campanha;

// Estilo
[
    $pos = [
        'font' => [
            'color' => [
                'argb' => \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED,
            ],
        ],
    ],
    $titulo = [
        'font' => [
            'bold' => true,
        ],
    ],
    $sub_titulo = [
        'font' => [
            'bold' => true,
        ],
        'size' => 10,
    ],
    $cabecalho = [
        'font' => [
            'bold' => true,
            'color' => [
                'argb' => \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE,
            ],
            'size' => 10,
        ],
        'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'rotation' => 90,
        'startColor' => [
            'argb' => '002060',
        ],
        ],
    ],
    $bordas = [
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN, // Você pode ajustar o estilo da borda, por exemplo, BORDER_THIN, BORDER_MEDIUM, etc.
                'color' => ['argb' => '000000'], // Cor da borda (preto)
            ],
        ],
    ],
];

$formatoReal = '_-"R$" * #,##0.00_-;-"R$" * #,##0.00_-;_-"R$" * "-"??_-;_-@_-';

// Definir o nome da planilha
$sheet->setTitle($titulo_campanha);

// Aplicar a fonte "Aptos Narrow" para toda a planilha
$spreadsheet->getDefaultStyle()->getFont()->setName('Aptos Narrow');

// Remover as margens
$sheet->getPageMargins()->setTop(0);
$sheet->getPageMargins()->setRight(0);
$sheet->getPageMargins()->setLeft(0);
$sheet->getPageMargins()->setBottom(0);

// Definir fundo branco para todas as células
$spreadsheet->getDefaultStyle()->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFFFFF');

//Campanha X
$sheet->setCellValue('F2', strtoupper($Campanha->nome));
$sheet->getStyle('F2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$sheet->getStyle('F2')->applyFromArray($titulo);

//Periodo 
$sheet->setCellValue('E3', 'PERÍODO:');
// Centraliza o texto horizontalmente na célula E3
$sheet->getStyle('E3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
$sheet->getStyle('E3')->applyFromArray($titulo);

$periodo_inicio = date("d/m/Y", strtotime($Campanha->periodo_inicio));
$periodo_fim = date("d/m/Y", strtotime($Campanha->periodo_fim));
$periodo = "$periodo_inicio - $periodo_fim";
$sheet->setCellValue('F3', $periodo);
$sheet->getStyle('F3')->applyFromArray($titulo);
// Centraliza o texto horizontalmente na célula E3
$sheet->getStyle('F3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

//Pagamento
$sheet->setCellValue('E4', 'PAGAMENTO:');
// Centraliza o texto horizontalmente na célula E3
$sheet->getStyle('E4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
$sheet->getStyle('E4')->applyFromArray($titulo);


$data_pagamento = date("d/m/Y", strtotime($Campanha->data_pagamento));
$sheet->setCellValue('F4', $data_pagamento);
$sheet->getStyle('F4')->applyFromArray($titulo);
// Centraliza o texto horizontalmente na célula E3
$sheet->getStyle('F4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

//Matriz
{    
    $sheet->setCellValue('F6', "VENDA EM BOLETOS - MATRIZ");
    $sheet->getStyle('F6')->applyFromArray($titulo)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $sheet->setCellValue('B7', "VENDEDOR");
    $sheet->setCellValue('C7', "VENDAS EM BOLETO");
    $sheet->setCellValue('D7', "CONVERTIDAS (3%)");
    $sheet->setCellValue('E7', "NÃO CONVERTIDAS (1%)");
    $sheet->setCellValue('F7', "NÃO CONVERTIDAS (%)");
    $sheet->setCellValue('G7', "COMISSÃO (1%)");
    $sheet->setCellValue('H7', "CONVERSÃO PÓS FECH");
    $sheet->setCellValue('I7', "COMISSÃO (2%)-PÓS-FECH");
    $sheet->getStyle('B7:I7')->applyFromArray($cabecalho)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $row = 8;
    foreach($Usuarios->listarVendedores(2) as $vendedor){
        $row_two = $row;
        $nome_completo = explode(" ", $vendedor->nome);
        $primeiro_nome = $nome_completo[0];
        $primeiro_nome = ucfirst(strtolower(($primeiro_nome)));
        $sheet->setCellValue('B'.$row, $primeiro_nome);
        $sheet->getStyle('B'.$row)->applyFromArray($sub_titulo); 

        //Total por Vendedor
        $sheet->setCellValue('C'.$row, $Boletos->totalPorVendedor($id_campanha,$vendedor->id_usuario));
        $row++;
    }

    $sheet->getStyle('C'.$row_two.':I'.$row)->getNumberFormat()->setFormatCode($formatoReal);
    $sheet->getStyle('D'.$row_two.':G'.$row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE);
    $sheet->getStyle('D'.$row_two.':G'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('I'.$row_two.':I'.$row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE);
    $sheet->getStyle('I'.$row_two.':I'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('H'.$row_two.':I'.$row-1)->applyFromArray($pos);
    $sheet->setCellValue('B'.$row, "TOTAL");

    $sheet->getStyle('B'.$row.':I'.$row)->applyFromArray($cabecalho);
    $sheet->getStyle('B7:I'.$row)->applyFromArray($bordas);
}

$row++;

//Prestigio
{    
    $sheet->setCellValue('F'.$row, "VENDA EM BOLETOS - PRESTIGIO");
    $sheet->getStyle('F'.$row)->applyFromArray($titulo)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $row++;

    $row_one = $row;

    $sheet->setCellValue('B'.$row, "VENDEDOR");
    $sheet->setCellValue('C'.$row, "VENDAS EM BOLETO");
    $sheet->setCellValue('D'.$row, "CONVERTIDAS (3%)");
    $sheet->setCellValue('E'.$row, "NÃO CONVERTIDAS (1%)");
    $sheet->setCellValue('F'.$row, "NÃO CONVERTIDAS (%)");
    $sheet->setCellValue('G'.$row, "COMISSÃO (1%)");
    $sheet->setCellValue('H'.$row, "CONVERSÃO PÓS FECH");
    $sheet->setCellValue('I'.$row, "COMISSÃO (2%)-PÓS-FECH");
    $sheet->getStyle('B'.$row.':I'.$row)->applyFromArray($cabecalho)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $row++;

    foreach($Usuarios->listarVendedores(4) as $vendedor){
        $row_two = $row;
        $nome_completo = explode(" ", $vendedor->nome);
        $primeiro_nome = $nome_completo[0];
        $primeiro_nome = ucfirst(strtolower(($primeiro_nome)));
        $sheet->setCellValue('B'.$row, $primeiro_nome);
        $sheet->getStyle('B'.$row)->applyFromArray($sub_titulo); 
        $row++;
    }

    $sheet->getStyle('C'.$row_two.':I'.$row)->getNumberFormat()->setFormatCode($formatoReal);
    $sheet->getStyle('D'.$row_two.':G'.$row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE);
    $sheet->getStyle('D'.$row_two.':G'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('I'.$row_two.':I'.$row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE);
    $sheet->getStyle('I'.$row_two.':I'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('H'.$row_two.':I'.$row-1)->applyFromArray($pos);
    $sheet->setCellValue('B'.$row, "TOTAL");

    $sheet->getStyle('B'.$row.':I'.$row)->applyFromArray($cabecalho);
    $sheet->getStyle('B'.$row_one.':I'.$row)->applyFromArray($bordas);
}

$row++;

//Daily
{    
    $sheet->setCellValue('F'.$row, "VENDA EM BOLETOS - DAILY");
    $sheet->getStyle('F'.$row)->applyFromArray($titulo)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $row++;

    $row_one = $row;

    $sheet->setCellValue('B'.$row, "VENDEDOR");
    $sheet->setCellValue('C'.$row, "VENDAS EM BOLETO");
    $sheet->setCellValue('D'.$row, "CONVERTIDAS (3%)");
    $sheet->setCellValue('E'.$row, "NÃO CONVERTIDAS (1%)");
    $sheet->setCellValue('F'.$row, "NÃO CONVERTIDAS (%)");
    $sheet->setCellValue('G'.$row, "COMISSÃO (1%)");
    $sheet->setCellValue('H'.$row, "CONVERSÃO PÓS FECH");
    $sheet->setCellValue('I'.$row, "COMISSÃO (2%)-PÓS-FECH");
    $sheet->getStyle('B'.$row.':I'.$row)->applyFromArray($cabecalho)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $row++;

    foreach($Usuarios->listarVendedores(6) as $vendedor){
        $row_two = $row;
        $nome_completo = explode(" ", $vendedor->nome);
        $primeiro_nome = $nome_completo[0];
        $primeiro_nome = ucfirst(strtolower(($primeiro_nome)));
        $sheet->setCellValue('B'.$row, $primeiro_nome);
        $sheet->getStyle('B'.$row)->applyFromArray($sub_titulo); 
        $row++;
    }

    $sheet->getStyle('C'.$row_two.':I'.$row)->getNumberFormat()->setFormatCode($formatoReal);
    $sheet->getStyle('D'.$row_two.':G'.$row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE);
    $sheet->getStyle('D'.$row_two.':G'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('I'.$row_two.':I'.$row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE);
    $sheet->getStyle('I'.$row_two.':I'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('H'.$row_two.':I'.$row-1)->applyFromArray($pos);
    $sheet->setCellValue('B'.$row, "TOTAL");

    $sheet->getStyle('B'.$row.':I'.$row)->applyFromArray($cabecalho);
    $sheet->getStyle('B'.$row_one.':I'.$row)->applyFromArray($bordas);
}

//AutoSize
{
    $sheet->getColumnDimension('A')->setAutoSize(true);
    $sheet->getColumnDimension('B')->setAutoSize(true);
    $sheet->getColumnDimension('C')->setAutoSize(true);
    $sheet->getColumnDimension('D')->setAutoSize(true);
    $sheet->getColumnDimension('E')->setAutoSize(true);
    $sheet->getColumnDimension('F')->setAutoSize(true);
    $sheet->getColumnDimension('G')->setAutoSize(true);
    $sheet->getColumnDimension('H')->setAutoSize(true);
    $sheet->getColumnDimension('I')->setAutoSize(true);
}

// Salvar o arquivo Excel
$writer = new Xlsx($spreadsheet);

$nome_arquivo = "resumo_campanha_$n_campanha.xlsx";
$writer->save($nome_arquivo);

$url = 'Location:campanha?c='.$id_campanha.'&s';
header($url);

?>
