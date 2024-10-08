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
            'size' => 10,
        ],
    ],
    $titulo = [
        'font' => [
            'bold' => true,
        ],
        'size' => 10,
    ],
    $sub_titulo = [
        'font' => [
            'bold' => true,
        ],
        'size' => 10,
    ],
    $titulo_final = [
        'font' => [
            'bold' => true,
            'color' => [
                'argb' => \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED,
            ],
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
// Cria um estilo de fonte com tamanho 10
$spreadsheet->getDefaultStyle()->getFont()->setSize(10);


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

    $sheet->setCellValue('H6', "PAGAMENTO PÓS:");
    $sheet->getStyle('H6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
    $sheet->getStyle('H6')->applyFromArray($titulo);

    $data_pagamento_pos = date("d/m/Y", strtotime($Campanha->data_pagamento_pos));
    $sheet->setCellValue('I6', $data_pagamento_pos);
    $sheet->getStyle('I6')->applyFromArray($titulo);
    // Centraliza o texto horizontalmente na célula E3
    $sheet->getStyle('I6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $sheet->setCellValue('B7', "VENDEDOR");
    $sheet->setCellValue('C7', "VENDAS EM BOLETO");
    $sheet->setCellValue('D7', "CONVERTIDAS (3%)");
    $sheet->setCellValue('E7', "NÃO CONVERTIDAS (1%)");
    $sheet->setCellValue('F7', "NÃO CONVERTIDAS (%)");
    $sheet->setCellValue('G7', "COMISSÃO (1%)");
    $sheet->setCellValue('H7', "CONVERSÃO PÓS FECH");
    $sheet->setCellValue('I7', "COMISSÃO (2%)-PÓS-FECH");
    $sheet->getStyle('B7:I7')->applyFromArray($cabecalho)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $total_vendas_matriz = 0;
    $total_convertidas_matriz = 0;
    $total_nao_convertidas_matriz = 0;
    $total_porcentagem_nao_convertidas_matriz = 0;
    $total_comissao_matriz = 0;
    $total_conversao_pos_matriz = 0;
    $total_comissao_pos_matriz = 0;

    $row = 8;

    $row_two = $row;
    foreach($Usuarios->listarVendedores(2) as $vendedor){
        // Calcula o total de vendas do vendedor
        $total_vendedor = $Boletos->totalPorVendedor($id_campanha, $vendedor->id_usuario, 2, $Campanha->periodo_fim) ?? 0;

        // Só continua se o total de vendas for maior que zero
        if ($total_vendedor > 0) {
            $nome_completo = explode(" ", $vendedor->nome);
            $primeiro_nome = $nome_completo[0];
            $primeiro_nome = ucfirst(strtolower(($primeiro_nome)));
            $sheet->setCellValue('B'.$row, $primeiro_nome);
            $sheet->getStyle('B'.$row)->applyFromArray($sub_titulo); 

            // Total por Vendedor
            $sheet->setCellValue('C'.$row, $total_vendedor);
            $sheet->setCellValue('D'.$row, $Boletos->totalConvertidoPorVendedor($id_campanha, $vendedor->id_usuario, 2, $Campanha->periodo_fim) ?? 0);
            $sheet->setCellValue('E'.$row, $Boletos->totalNaoConvertidoPorVendedor($id_campanha, $vendedor->id_usuario, 2, $Campanha->periodo_fim) ?? 0);
            $sheet->setCellValue('F'.$row, $Boletos->totalPorcentagemNaoConvertidoPorVendedor($id_campanha, $vendedor->id_usuario, 2, $Campanha->periodo_fim) ?? 0);
            $sheet->getStyle('F'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->setCellValue('G'.$row, $Boletos->totalComissaoPorVendedor($id_campanha, $vendedor->id_usuario, 2, $Campanha->periodo_fim) ?? 0);
            $sheet->setCellValue('H'.$row, $Boletos->totalConvertidoPosFechPorVendedor($id_campanha, $vendedor->id_usuario, 2, $Campanha->periodo_fim) ?? 0);
            $sheet->setCellValue('I'.$row, $Boletos->totalComissaoConvertidoPosFechPorVendedor($id_campanha, $vendedor->id_usuario, 2, $Campanha->periodo_fim) ?? 0);

            // Atualiza os totais da matriz
            $total_vendas_matriz += $total_vendedor;
            $total_convertidas_matriz += $Boletos->totalConvertidoPorVendedor($id_campanha, $vendedor->id_usuario, 2, $Campanha->periodo_fim) ?? 0;
            $total_nao_convertidas_matriz += $Boletos->totalNaoConvertidoPorVendedor($id_campanha, $vendedor->id_usuario, 2, $Campanha->periodo_fim) ?? 0;
            $total_porcentagem_nao_convertidas_matriz = ($total_nao_convertidas_matriz/$total_vendas_matriz);
            $total_comissao_matriz += $Boletos->totalComissaoPorVendedor($id_campanha, $vendedor->id_usuario, 2, $Campanha->periodo_fim) ?? 0;
            $total_conversao_pos_matriz += $Boletos->totalConvertidoPosFechPorVendedor($id_campanha, $vendedor->id_usuario, 2, $Campanha->periodo_fim) ?? 0;
            $total_comissao_pos_matriz += $Boletos->totalComissaoConvertidoPosFechPorVendedor($id_campanha, $vendedor->id_usuario, 2, $Campanha->periodo_fim) ?? 0;

            $row++;
        }
    }

    $sheet->getStyle('C'.$row_two.':I'.$row)->getNumberFormat()->setFormatCode($formatoReal);
    $sheet->getStyle('F'.$row_two.':F'.$row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE);
    $sheet->getStyle('H'.$row_two.':I'.$row-1)->applyFromArray($pos);
    $sheet->setCellValue('B'.$row, "TOTAL");

    $sheet->setCellValue('C'.$row, $total_vendas_matriz);
    $sheet->setCellValue('D'.$row, $total_convertidas_matriz);
    $sheet->setCellValue('E'.$row, $total_nao_convertidas_matriz);
    $sheet->setCellValue('F'.$row, $total_porcentagem_nao_convertidas_matriz);
    $sheet->getStyle('F'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->setCellValue('G'.$row, $total_comissao_matriz);
    $sheet->setCellValue('H'.$row, $total_conversao_pos_matriz);
    $sheet->setCellValue('I'.$row, $total_comissao_pos_matriz);

    $sheet->getStyle('B'.$row.':I'.$row)->applyFromArray($cabecalho);
    $sheet->getStyle('B7:I'.$row)->applyFromArray($bordas);

    $row++;
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

    $total_vendas_prestigio = 0;
    $total_convertidas_prestigio = 0;
    $total_nao_convertidas_prestigio = 0;
    $total_porcentagem_nao_convertidas_prestigio = 0;
    $total_comissao_prestigio = 0;
    $total_conversao_pos_prestigio = 0;
    $total_comissao_pos_prestigio = 0;

    $row++;

    $row_two = $row;
    foreach($Usuarios->listarVendedores(4) as $vendedor){
        // Calcula o total de vendas do vendedor
        $total_vendedor = $Boletos->totalPorVendedor($id_campanha, $vendedor->id_usuario, 4, $Campanha->periodo_fim) ?? 0;
        
        // Só continua se o total de vendas for maior que zero
        if ($total_vendedor > 0) {
            $nome_completo = explode(" ", $vendedor->nome);
            $primeiro_nome = $nome_completo[0];
            $primeiro_nome = ucfirst(strtolower(($primeiro_nome)));
            $sheet->setCellValue('B'.$row, $primeiro_nome);
            $sheet->getStyle('B'.$row)->applyFromArray($sub_titulo); 

            // Total por Vendedor
            $sheet->setCellValue('C'.$row, $total_vendedor);
            $sheet->setCellValue('D'.$row, $Boletos->totalConvertidoPorVendedor($id_campanha, $vendedor->id_usuario, 4, $Campanha->periodo_fim) ?? 0);
            $sheet->setCellValue('E'.$row, $Boletos->totalNaoConvertidoPorVendedor($id_campanha, $vendedor->id_usuario, 4, $Campanha->periodo_fim) ?? 0);
            $sheet->setCellValue('F'.$row, $Boletos->totalPorcentagemNaoConvertidoPorVendedor($id_campanha, $vendedor->id_usuario, 4, $Campanha->periodo_fim) ?? 0);
            $sheet->getStyle('F'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->setCellValue('G'.$row, $Boletos->totalComissaoPorVendedor($id_campanha, $vendedor->id_usuario, 4, $Campanha->periodo_fim) ?? 0);
            $sheet->setCellValue('H'.$row, $Boletos->totalConvertidoPosFechPorVendedor($id_campanha, $vendedor->id_usuario, 4, $Campanha->periodo_fim) ?? 0);
            $sheet->setCellValue('I'.$row, $Boletos->totalComissaoConvertidoPosFechPorVendedor($id_campanha, $vendedor->id_usuario, 4, $Campanha->periodo_fim) ?? 0);

            // Atualiza os totais da prestigio
            $total_vendas_prestigio += $total_vendedor;
            $total_convertidas_prestigio += $Boletos->totalConvertidoPorVendedor($id_campanha, $vendedor->id_usuario, 4, $Campanha->periodo_fim) ?? 0;
            $total_nao_convertidas_prestigio += $Boletos->totalNaoConvertidoPorVendedor($id_campanha, $vendedor->id_usuario, 4, $Campanha->periodo_fim) ?? 0;
            $total_porcentagem_nao_convertidas_prestigio = ($total_nao_convertidas_prestigio/$total_vendas_prestigio);
            $total_comissao_prestigio += $Boletos->totalComissaoPorVendedor($id_campanha, $vendedor->id_usuario, 4, $Campanha->periodo_fim) ?? 0;
            $total_conversao_pos_prestigio += $Boletos->totalConvertidoPosFechPorVendedor($id_campanha, $vendedor->id_usuario, 4, $Campanha->periodo_fim) ?? 0;
            $total_comissao_pos_prestigio += $Boletos->totalComissaoConvertidoPosFechPorVendedor($id_campanha, $vendedor->id_usuario, 4, $Campanha->periodo_fim) ?? 0;

            $row++;
        }
    }

    $sheet->getStyle('C'.$row_two.':I'.$row)->getNumberFormat()->setFormatCode($formatoReal);
    $sheet->getStyle('F'.$row_two.':F'.$row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE);
    $sheet->getStyle('H'.$row_two.':I'.$row-1)->applyFromArray($pos);
    $sheet->setCellValue('B'.$row, "TOTAL");

    $sheet->setCellValue('C'.$row, $total_vendas_prestigio);
    $sheet->setCellValue('D'.$row, $total_convertidas_prestigio);
    $sheet->setCellValue('E'.$row, $total_nao_convertidas_prestigio);
    $sheet->setCellValue('F'.$row, $total_porcentagem_nao_convertidas_prestigio);
    $sheet->getStyle('F'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->setCellValue('G'.$row, $total_comissao_prestigio);
    $sheet->setCellValue('H'.$row, $total_conversao_pos_prestigio);
    $sheet->setCellValue('I'.$row, $total_comissao_pos_prestigio);

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

    $total_vendas_daily = 0;
    $total_convertidas_daily = 0;
    $total_nao_convertidas_daily = 0;
    $total_porcentagem_nao_convertidas_daily = 0;
    $total_comissao_daily = 0;
    $total_conversao_pos_daily = 0;
    $total_comissao_pos_daily = 0;

    $row++;

    $row_two = $row;
    foreach($Usuarios->listarVendedores(6) as $vendedor){
        // Calcula o total de vendas do vendedor
        $total_vendedor = $Boletos->totalPorVendedor($id_campanha, $vendedor->id_usuario, 6, $Campanha->periodo_fim) ?? 0;
        
        // Só continua se o total de vendas for maior que zero
        if ($total_vendedor > 0) {
            $nome_completo = explode(" ", $vendedor->nome);
            $primeiro_nome = $nome_completo[0];
            $primeiro_nome = ucfirst(strtolower(($primeiro_nome)));
            $sheet->setCellValue('B'.$row, $primeiro_nome);
            $sheet->getStyle('B'.$row)->applyFromArray($sub_titulo); 

            // Total por Vendedor
            $sheet->setCellValue('C'.$row, $total_vendedor);
            $sheet->setCellValue('D'.$row, $Boletos->totalConvertidoPorVendedor($id_campanha, $vendedor->id_usuario, 6, $Campanha->periodo_fim) ?? 0);
            $sheet->setCellValue('E'.$row, $Boletos->totalNaoConvertidoPorVendedor($id_campanha, $vendedor->id_usuario, 6, $Campanha->periodo_fim) ?? 0);
            $sheet->setCellValue('F'.$row, $Boletos->totalPorcentagemNaoConvertidoPorVendedor($id_campanha, $vendedor->id_usuario, 6, $Campanha->periodo_fim) ?? 0);
            $sheet->getStyle('F'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->setCellValue('G'.$row, $Boletos->totalComissaoPorVendedor($id_campanha, $vendedor->id_usuario, 6, $Campanha->periodo_fim) ?? 0);
            $sheet->setCellValue('H'.$row, $Boletos->totalConvertidoPosFechPorVendedor($id_campanha, $vendedor->id_usuario, 6, $Campanha->periodo_fim) ?? 0);
            $sheet->setCellValue('I'.$row, $Boletos->totalComissaoConvertidoPosFechPorVendedor($id_campanha, $vendedor->id_usuario, 6, $Campanha->periodo_fim) ?? 0);

            // Atualiza os totais da daily
            $total_vendas_daily += $total_vendedor;
            $total_convertidas_daily += $Boletos->totalConvertidoPorVendedor($id_campanha, $vendedor->id_usuario, 6, $Campanha->periodo_fim) ?? 0;
            $total_nao_convertidas_daily += $Boletos->totalNaoConvertidoPorVendedor($id_campanha, $vendedor->id_usuario, 6, $Campanha->periodo_fim) ?? 0;
            $total_porcentagem_nao_convertidas_daily = ($total_nao_convertidas_daily/$total_vendas_daily);
            $total_comissao_daily += $Boletos->totalComissaoPorVendedor($id_campanha, $vendedor->id_usuario, 6, $Campanha->periodo_fim) ?? 0;
            $total_conversao_pos_daily += $Boletos->totalConvertidoPosFechPorVendedor($id_campanha, $vendedor->id_usuario, 6, $Campanha->periodo_fim) ?? 0;
            $total_comissao_pos_daily += $Boletos->totalComissaoConvertidoPosFechPorVendedor($id_campanha, $vendedor->id_usuario, 6, $Campanha->periodo_fim) ?? 0;

            $row++;
        }
    }

    $sheet->getStyle('C'.$row_two.':I'.$row)->getNumberFormat()->setFormatCode($formatoReal);
    $sheet->getStyle('F'.$row_two.':F'.$row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE);
    $sheet->getStyle('H'.$row_two.':I'.$row-1)->applyFromArray($pos);
    $sheet->setCellValue('B'.$row, "TOTAL");

    $sheet->setCellValue('C'.$row, $total_vendas_daily);
    $sheet->setCellValue('D'.$row, $total_convertidas_daily);
    $sheet->setCellValue('E'.$row, $total_nao_convertidas_daily);
    $sheet->setCellValue('F'.$row, $total_porcentagem_nao_convertidas_daily);
    $sheet->getStyle('F'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->setCellValue('G'.$row, $total_comissao_daily);
    $sheet->setCellValue('H'.$row, $total_conversao_pos_daily);
    $sheet->setCellValue('I'.$row, $total_comissao_pos_daily);

    $sheet->getStyle('B'.$row.':I'.$row)->applyFromArray($cabecalho);
    $sheet->getStyle('B'.$row_one.':I'.$row)->applyFromArray($bordas);
}

//Resumo Geral
{
    $row++;

    $sheet->setCellValue('F'.$row, "RESUMO GERAL VENDAS EM BOLETOS");
    $sheet->getStyle('F'.$row)->applyFromArray($titulo)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $row++;

    $row_three = $row;

    $sheet->setCellValue('B'.$row, "EMPRESA");
    $sheet->setCellValue('C'.$row, "VENDAS EM BOLETO");
    $sheet->setCellValue('D'.$row, "CONVERTIDAS (3%)");
    $sheet->setCellValue('E'.$row, "NÃO CONVERTIDAS (1%)");
    $sheet->setCellValue('F'.$row, "NÃO CONVERTIDAS (%)");
    $sheet->setCellValue('G'.$row, "COMISSÃO (1%)");
    $sheet->setCellValue('H'.$row, "CONVERSÃO PÓS FECH");
    $sheet->setCellValue('I'.$row, "COMISSÃO (2%)-PÓS-FECH");
    $sheet->getStyle('B'.$row.':I'.$row)->applyFromArray($cabecalho)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $row++;

    $row_two = $row;

    $sheet->setCellValue('B'.$row, "MATRIZ");
    $sheet->getStyle('B'.$row)->applyFromArray($sub_titulo); 
    $sheet->setCellValue('C'.$row, $total_vendas_matriz);
    $sheet->setCellValue('D'.$row, $total_convertidas_matriz);
    $sheet->setCellValue('E'.$row, $total_nao_convertidas_matriz);
    $sheet->setCellValue('F'.$row, $total_porcentagem_nao_convertidas_matriz);
    $sheet->getStyle('F'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->setCellValue('G'.$row, $total_comissao_matriz);
    $sheet->setCellValue('H'.$row, $total_conversao_pos_matriz);
    $sheet->setCellValue('I'.$row, $total_comissao_pos_matriz);

    $row++;
    
    $sheet->setCellValue('B'.$row, "PRESTIGIO");
    $sheet->getStyle('B'.$row)->applyFromArray($sub_titulo); 
    $sheet->setCellValue('C'.$row, $total_vendas_prestigio);
    $sheet->setCellValue('D'.$row, $total_convertidas_prestigio);
    $sheet->setCellValue('E'.$row, $total_nao_convertidas_prestigio);
    $sheet->setCellValue('F'.$row, $total_porcentagem_nao_convertidas_prestigio);
    $sheet->getStyle('F'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->setCellValue('G'.$row, $total_comissao_prestigio);
    $sheet->setCellValue('H'.$row, $total_conversao_pos_prestigio);
    $sheet->setCellValue('I'.$row, $total_comissao_pos_prestigio);
    
    $row++;
    
    $sheet->setCellValue('B'.$row, "DAILY");
    $sheet->getStyle('B'.$row)->applyFromArray($sub_titulo); 
    $sheet->setCellValue('C'.$row, $total_vendas_daily);
    $sheet->setCellValue('D'.$row, $total_convertidas_daily);
    $sheet->setCellValue('E'.$row, $total_nao_convertidas_daily);
    $sheet->setCellValue('F'.$row, $total_porcentagem_nao_convertidas_daily);
    $sheet->getStyle('F'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->setCellValue('G'.$row, $total_comissao_daily);
    $sheet->setCellValue('H'.$row, $total_conversao_pos_daily);
    $sheet->setCellValue('I'.$row, $total_comissao_pos_daily);

    $row++;

    $sheet->getStyle('C'.$row_two.':I'.$row)->getNumberFormat()->setFormatCode($formatoReal);
    $sheet->getStyle('F'.$row_two.':F'.$row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE);
    $sheet->getStyle('H'.$row_two.':I'.$row-1)->applyFromArray($pos);
    $sheet->setCellValue('B'.$row, "TOTAL");
    
    $sheet->setCellValue('C'.$row, ($total_vendas_matriz+$total_vendas_prestigio+$total_vendas_daily));
    $sheet->setCellValue('D'.$row, ($total_convertidas_matriz+$total_convertidas_prestigio+$total_convertidas_daily));
    $sheet->setCellValue('E'.$row, ($total_nao_convertidas_matriz+$total_nao_convertidas_prestigio+$total_nao_convertidas_daily));

    $porcentagem_nao_convertidas_matriz = $total_vendas_matriz > 0 ? $total_nao_convertidas_matriz / $total_vendas_matriz : 0;
    $porcentagem_nao_convertidas_prestigio = $total_vendas_prestigio > 0 ? $total_nao_convertidas_prestigio / $total_vendas_prestigio : 0;
    $porcentagem_nao_convertidas_daily = $total_vendas_daily > 0 ? $total_nao_convertidas_daily / $total_vendas_daily : 0;

    $sheet->setCellValue('F'.$row, ($total_nao_convertidas_matriz+$total_nao_convertidas_prestigio+$total_nao_convertidas_daily)/($total_vendas_matriz+$total_vendas_prestigio+$total_vendas_daily));

    $sheet->getStyle('F'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->setCellValue('G'.$row, ($total_comissao_matriz+$total_comissao_prestigio+$total_comissao_daily));
    $sheet->setCellValue('H'.$row, ($total_conversao_pos_matriz+$total_conversao_pos_prestigio+$total_conversao_pos_daily));
    $sheet->setCellValue('I'.$row, ($total_comissao_pos_matriz+$total_comissao_pos_prestigio+$total_comissao_pos_daily));

    $sheet->getStyle('B'.$row.':I'.$row)->applyFromArray($cabecalho);
    $sheet->getStyle('B'.$row_three.':I'.$row)->applyFromArray($bordas);

    $row++;
}

//Resumo de Vendas em Boletos
{
    $row++;

    
    $sheet->setCellValue('B'.$row, "RESUMO DE VENDAS EM BOLETOS PERIODO ".$periodo_inicio." A ".$periodo_fim);
    $sheet->getStyle('B'.$row)->applyFromArray($titulo)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('B'.$row.':F'.$row);

    $row++;

    $row_three = $row;

    $sheet->setCellValue('B'.$row, "OTICAS");
    $sheet->setCellValue('C'.$row, "VENDEDOR");
    $sheet->setCellValue('D'.$row, "VENDAS EM BOLETO");
    $sheet->setCellValue('E'.$row, "CONVERTIDAS");
    $sheet->setCellValue('F'.$row, "NÃO CONVERTIDAS");
    $sheet->getStyle('B'.$row.':F'.$row)->applyFromArray($cabecalho)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    //Matriz
    foreach($Usuarios->listarVendedores(2) as $vendedor){
        
        $total_vendedor = $Boletos->totalPorVendedor($id_campanha, $vendedor->id_usuario, 2, $Campanha->periodo_fim) ?? 0;
        
        $row_two = $row;
        // Só continua se o total de vendas for maior que zero
        if ($total_vendedor > 0) {
            $row++;

            $sheet->setCellValue('B'.$row, "MATRIZ");
            $nome_completo = explode(" ", $vendedor->nome);
            $primeiro_nome = $nome_completo[0];
            $primeiro_nome = ucfirst(strtolower(($primeiro_nome)));
            $sheet->setCellValue('C'.$row, $primeiro_nome); 

            // Total por Vendedor
            $sheet->setCellValue('D'.$row, $total_vendedor);
            $sheet->setCellValue('E'.$row, $Boletos->totalConvertidoPorVendedor($id_campanha, $vendedor->id_usuario, 2, $Campanha->periodo_fim) ?? 0);
            $sheet->setCellValue('F'.$row, $Boletos->totalNaoConvertidoPorVendedor($id_campanha, $vendedor->id_usuario, 2, $Campanha->periodo_fim) ?? 0);
        }
    }

    $row++;

    //Prestigio
    foreach($Usuarios->listarVendedores(4) as $vendedor){
        
        $total_vendedor = $Boletos->totalPorVendedor($id_campanha, $vendedor->id_usuario, 4, $Campanha->periodo_fim) ?? 0;
        
        $row_two = $row;
        // Só continua se o total de vendas for maior que zero
        if ($total_vendedor > 0) {
            $row++;

            $sheet->setCellValue('B'.$row, "PRESTIGIO");
            $nome_completo = explode(" ", $vendedor->nome);
            $primeiro_nome = $nome_completo[0];
            $primeiro_nome = ucfirst(strtolower(($primeiro_nome)));
            $sheet->setCellValue('C'.$row, $primeiro_nome); 

            // Total por Vendedor
            $sheet->setCellValue('D'.$row, $total_vendedor);
            $sheet->setCellValue('E'.$row, $Boletos->totalConvertidoPorVendedor($id_campanha, $vendedor->id_usuario, 4, $Campanha->periodo_fim) ?? 0);
            $sheet->setCellValue('F'.$row, $Boletos->totalNaoConvertidoPorVendedor($id_campanha, $vendedor->id_usuario, 4, $Campanha->periodo_fim) ?? 0);
        }
    }
    
    $row++;

    //Daily
    foreach($Usuarios->listarVendedores(6) as $vendedor){
        
        $total_vendedor = $Boletos->totalPorVendedor($id_campanha, $vendedor->id_usuario, 6, $Campanha->periodo_fim) ?? 0;
        
        $row_two = $row;
        // Só continua se o total de vendas for maior que zero
        if ($total_vendedor > 0) {
            $row++;

            $sheet->setCellValue('B'.$row, "DAILY");
            $nome_completo = explode(" ", $vendedor->nome);
            $primeiro_nome = $nome_completo[0];
            $primeiro_nome = ucfirst(strtolower(($primeiro_nome)));
            $sheet->setCellValue('C'.$row, $primeiro_nome); 

            // Total por Vendedor
            $sheet->setCellValue('D'.$row, $total_vendedor);
            $sheet->setCellValue('E'.$row, $Boletos->totalConvertidoPorVendedor($id_campanha, $vendedor->id_usuario, 6, $Campanha->periodo_fim) ?? 0);
            $sheet->setCellValue('F'.$row, $Boletos->totalNaoConvertidoPorVendedor($id_campanha, $vendedor->id_usuario, 6, $Campanha->periodo_fim) ?? 0);
        }
    }

    $row++;

    $sheet->getStyle('C'.$row_three.':F'.$row)->getNumberFormat()->setFormatCode($formatoReal);
    $sheet->getStyle('E'.($row_three+1).':E'.($row-1))->applyFromArray($pos);
    $sheet->setCellValue('C'.$row, "TOTAL");
    $sheet->setCellValue('D'.$row, $total_vendas_matriz+$total_vendas_prestigio+$total_vendas_daily);
    $sheet->setCellValue('E'.$row, $total_convertidas_matriz+$total_convertidas_prestigio+$total_convertidas_daily);
    $sheet->setCellValue('F'.$row, $total_nao_convertidas_matriz+$total_nao_convertidas_prestigio+$total_nao_convertidas_daily);

    $sheet->getStyle('B'.$row.':F'.$row)->applyFromArray($cabecalho);
    $sheet->getStyle('B'.$row_three.':F'.$row)->applyFromArray($bordas);
    $sheet->getStyle('B'.($row_three+1).':F'.($row-1))->applyFromArray($sub_titulo);

    $row++;

    $sheet->setCellValue('D'.$row, "CONVERTIDO:");
    $sheet->getStyle('D'.$row)->applyFromArray($titulo_final)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->setCellValue('E'.$row, ($total_convertidas_matriz+$total_convertidas_prestigio+$total_convertidas_daily)/($total_vendas_matriz+$total_vendas_prestigio+$total_vendas_daily) ?? 0);
    $sheet->getStyle('E'.$row)->applyFromArray($titulo_final)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle('E'.$row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE);

    $row++;

    $sheet->setCellValue('D'.$row, "NÃO CONVERTIDO:");
    $sheet->getStyle('D'.$row)->applyFromArray($titulo_final)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->setCellValue('E'.$row, ($total_nao_convertidas_matriz+$total_nao_convertidas_prestigio+$total_nao_convertidas_daily)/($total_vendas_matriz+$total_vendas_prestigio+$total_vendas_daily) ?? 0);
    $sheet->getStyle('E'.$row)->applyFromArray($titulo_final)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle('E'.$row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE);
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

// Redirecionar para download
header('Location: ' . $nome_arquivo);
?>

