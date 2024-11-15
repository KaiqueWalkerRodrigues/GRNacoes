<?php

$empresa = $_GET['empresa'];

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

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Calculation\LookupRef\Formula;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat\Wizard\Accounting;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

$Compra_nota = new Compra_Nota();
$Compra_Fornecedor = new Compra_Fornecedor();
$Compra_Categoria = new Compra_Categoria();

$real = new Accounting(
    'R$',
    2,
    true,
    true,
    true
);

$azul_claro = [
    'font' => [
        'color' => [
            'rgb' => 'DAEEF3', // azul_claro
        ],
    ],
];

$verde_claro = [
    'font' => [
        'color' => [
            'rgb' => 'EBF1DE', // verde_claro
        ],
    ],
];

$vermelho_claro = [
    'font' => [
        'color' => [
            'rgb' => 'F2DCDB', // vermelho_claro
        ],
    ],
];

$red = [
    'font' => [
        'bold' => true,
        'color' => [
            'rgb' => 'FF0000', // Vermelho
        ],
    ],
];

$mes_style_clinica = [
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => '538DD5',
        ],
    ],
    'font' => [
        'bold' => true,
    ],
];

$mes_style_otica = [
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => '00B050',
        ],
    ],
    'font' => [
        'bold' => true,
    ],
];

$titulo = [
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => '538DD5',
        ],
    ],
    'font' => [
        'bold' => true,  
        'size' => 14,        
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

$total_dark = [
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => '000000',
        ],
    ],
    'font' => [
        'bold' => true,
        'color' => [
            'rgb' => 'FFFFFF', // Branco
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

$titulo_otica = [
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => '00B050',
        ],
    ],
    'font' => [
        'bold' => true,  
        'size' => 14,        
    ],
];

$total_amarelo = [
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => 'FFFF00', //Amarelo
        ],
    ],
    'font' => [
        'bold' => true,
    ],
];

$estilo_compra = [
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => 'DAEEF3', //Azul
        ],
    ],
];

$estilo_venda = [
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => 'EBF1DE', //Roxo
        ],
    ],
];

$estilo_estoque = [
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => 'F2DCDB', //Vermelho
        ],
    ],
];

$spreadsheet = new Spreadsheet();
$activeWorksheet = $spreadsheet->getActiveSheet();
$spreadsheet->getActiveSheet()->setTitle('Valor');

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

$row_categoria = 1;
if($empresa == 0 OR $empresa == 1){

     /* 
        CLINICA PARQUE
    */

    $row_titulo = $row_categoria;

    $activeWorksheet->mergeCells('A'.$row_categoria.':N'.$row_categoria);
    $activeWorksheet->getStyle('A'.$row_categoria)->applyFromArray($titulo);
    $activeWorksheet->getStyle('A'.$row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $activeWorksheet->setCellValue('A'.$row_categoria, 'RELATORIO RESUMO GERAL CLINICA PARQUE - ' . date('Y'));
    $row_categoria++;
    $activeWorksheet->setCellValue('A'.$row_categoria,'FORNECEDOR');
    $activeWorksheet->getStyle('A'.$row_categoria)->applyFromArray($mes_style_clinica);

    $col = 'B';
    
    foreach ($meses as $mes) {
        $activeWorksheet->setCellValue($col . $row_categoria, $mes);
        $activeWorksheet->getStyle($col . $row_categoria)->applyFromArray($mes_style_clinica); 
        $activeWorksheet->getStyle($col . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $col++;
    }
    $activeWorksheet->getStyle('A2')->applyFromArray($mes_style_clinica); 
    $activeWorksheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $activeWorksheet->getStyle('N2')->applyFromArray($mes_style_clinica); 
    $activeWorksheet->getStyle('N2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $activeWorksheet->setCellValue('N' . $row_categoria, 'TOTAL');
    $activeWorksheet->getStyle('N' . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $activeWorksheet->getStyle('N' . $row_categoria)->getFont()->setBold(true);
    $row_categoria++;

    //Lista todas as categorias com notas cadastradas para a empresa e ano específicos
    foreach ($Compra_Categoria->listar() as $cc) {
        $cca = strtoupper($cc->categoria);
        
        // Verifica se há notas associadas a esta categoria para a empresa e ano específicos
        if ($Compra_nota->totalNotasCategoria(1, $cc->id_compra_categoria, date('Y')) > 0) {
            $activeWorksheet->setCellValue('A' . $row_categoria,$cca.':');
            $activeWorksheet->getStyle('A'.$row_categoria.':N'.$row_categoria)->applyFromArray($grey);
            $row_categoria++;
            foreach($Compra_Fornecedor->listarPorCategoria(1,$cc->id_compra_categoria,date('Y')) as $f){
                $activeWorksheet->setCellValue('A' . $row_categoria, $f->fornecedor);
                
                $col = 'B';
                for($i = 1; $i < 12; $i++){
                    $total_f_mes = $Compra_nota->totalFornecedorMes(1,$f->id_compra_fornecedor,$i);
                    if($total_f_mes > 0){$activeWorksheet->setCellValue($col.$row_categoria,$total_f_mes);}
                    $activeWorksheet->getCell($col.$row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
                    $col = chr(ord($col) + 1);
                }
                $activeWorksheet->setCellValue('N' . $row_categoria,$Compra_nota->totalFornecedorAnual(1,$f->id_compra_fornecedor,date('Y')));
                $activeWorksheet->getCell('N' . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
                $activeWorksheet->getStyle('N' . $row_categoria)->applyFromArray($red);
                $row_categoria++;
            }

            $activeWorksheet->setCellValue('A' . $row_categoria,'TOTAL '.$cca.':');
            $activeWorksheet->getStyle('A'.$row_categoria.':N'.$row_categoria)->applyFromArray($total_dark);

            $col = 'B';
            //Lista todos valores de categorias por mês
            for ($i = 1; $i <= 12; $i++) {
                $valor_total = $Compra_nota->totalCategoria(1, $cc->id_compra_categoria, $i, date('Y'));
                $activeWorksheet->setCellValue($col . $row_categoria, $valor_total);
                $activeWorksheet->getCell($col . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
                $col = chr(ord($col) + 1);
            }

            //Lita o total de cada categoria
            $activeWorksheet->setCellValue('N' . $row_categoria, $Compra_nota->totalCategoriaAnual(1, $cc->id_compra_categoria, date('Y')));
            $activeWorksheet->getCell('N' . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
            $activeWorksheet->getStyle('N' . $row_categoria)->applyFromArray($red);

            $row_categoria++;
        }
    }

    $activeWorksheet->setCellValue('A' . $row_categoria, 'TOTAL:');
    $activeWorksheet->getStyle('A' . $row_categoria)->applyFromArray($total_amarelo);
    $activeWorksheet->getStyle('N' . $row_categoria)->applyFromArray($total_amarelo);
    $mes = 1; // Ajustado para 1 em vez de 01
    $row_mês = 'B';

    //Valores total por categoria
    for ($i = 1; $i <= 12; $i++) {
        $total = $Compra_nota->totalMes(1, $mes, date('Y'));
        $activeWorksheet->setCellValue($row_mês . $row_categoria, $total); 
        $activeWorksheet->getStyle($row_mês . $row_categoria)->applyFromArray($total_amarelo);
        $activeWorksheet->getCell($row_mês . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
        $row_mês = chr(ord($row_mês) + 1);
        $mes++;
    }

    $totalempresa = $Compra_nota->totalEmpresa(1, date('Y'));
    $activeWorksheet->setCellValue($row_mês . $row_categoria, $totalempresa);
    $activeWorksheet->getCell($row_mês . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
    $activeWorksheet->getStyle('N' . $row_categoria)->applyFromArray($red);


    // Aplicar bordas pretas até o total
    $activeWorksheet->getStyle('A'.$row_titulo.':N' . $row_categoria)->applyFromArray($border_black);
    $row_categoria++;
    $row_categoria++;

    /* 
        CLINICA MAUÁ
    */

    $row_titulo = $row_categoria;

    $activeWorksheet->mergeCells('A' . $row_categoria . ':N' . $row_categoria);
    $activeWorksheet->getStyle('A' . $row_categoria)->applyFromArray($titulo);
    $activeWorksheet->setCellValue('A' . $row_categoria, 'RELATORIO RESUMO GERAL CLINICA MAUÁ - ' . date('Y'));
    $activeWorksheet->getStyle('A' . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $row_categoria++;
    $activeWorksheet->setCellValue('A' . $row_categoria, 'FORNECEDOR');
    $activeWorksheet->getStyle('A' . $row_categoria)->applyFromArray($mes_style_clinica);

    $col = 'B';
    //Lista todos os meses
    foreach ($meses as $mes) {
        $activeWorksheet->setCellValue($col . $row_categoria, $mes);
        $activeWorksheet->getStyle($col . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $activeWorksheet->getStyle($col . $row_categoria)->applyFromArray($mes_style_clinica);
        $col++;
    }
    $activeWorksheet->getStyle('A'.$row_categoria)->applyFromArray($mes_style_clinica); 
    $activeWorksheet->getStyle('A'.$row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $activeWorksheet->getStyle('N'.$row_categoria)->applyFromArray($mes_style_clinica); 
    $activeWorksheet->getStyle('N'.$row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $activeWorksheet->setCellValue('N' . $row_categoria, 'TOTAL:');

    $row_categoria++;

    //Lista todas as categorias
    foreach ($Compra_Categoria->listar() as $cc) {
        $cca = strtoupper($cc->categoria);

        // Verifica se há notas associadas a esta categoria para a empresa e ano específicos
        if ($Compra_nota->totalNotasCategoria(3, $cc->id_compra_categoria, date('Y')) > 0) {
            $activeWorksheet->setCellValue('A' . $row_categoria,$cca.':');
            $activeWorksheet->getStyle('A'.$row_categoria.':N'.$row_categoria)->applyFromArray($grey);
            $row_categoria++;
            foreach($Compra_Fornecedor->listarPorCategoria(3,$cc->id_compra_categoria,date('Y')) as $f){
                $activeWorksheet->setCellValue('A' . $row_categoria, $f->fornecedor);
                
                $col = 'B';
                for($i = 1; $i <=12; $i++){
                    $total_f_mes = $Compra_nota->totalFornecedorMes(3,$f->id_compra_fornecedor,$i);
                    if($total_f_mes > 0){$activeWorksheet->setCellValue($col.$row_categoria,$total_f_mes);}
                    $activeWorksheet->getCell($col.$row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
                    $col = chr(ord($col) + 1);
                }
                $activeWorksheet->setCellValue('N' . $row_categoria,$Compra_nota->totalFornecedorAnual(3,$f->id_compra_fornecedor,date('Y')));
                $activeWorksheet->getCell('N' . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
                $activeWorksheet->getStyle('N' . $row_categoria)->applyFromArray($red);
                $row_categoria++;
            }

            $activeWorksheet->setCellValue('A' . $row_categoria,'TOTAL '.$cca.':');
            $activeWorksheet->getStyle('A'.$row_categoria.':N'.$row_categoria)->applyFromArray($total_dark);

            $col = 'B';
            //Lista todos valores de categorias por mês
            for ($i = 1; $i <= 12; $i++) {
                $valor_total = $Compra_nota->totalCategoria(3, $cc->id_compra_categoria, $i, date('Y'));
                $activeWorksheet->setCellValue($col . $row_categoria, $valor_total);
                $activeWorksheet->getCell($col . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
                $col = chr(ord($col) + 1);
            }

            //Lita o total de cada categoria
            $activeWorksheet->setCellValue('N' . $row_categoria, $Compra_nota->totalCategoriaAnual(3, $cc->id_compra_categoria, date('Y')));
            $activeWorksheet->getCell('N' . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
            $activeWorksheet->getStyle('N' . $row_categoria)->applyFromArray($red);

            $row_categoria++;
        }
    }

    $activeWorksheet->setCellValue('A' . $row_categoria, 'TOTAL:');
    $activeWorksheet->getStyle('A' . $row_categoria)->applyFromArray($total_amarelo);
    $activeWorksheet->getStyle('N' . $row_categoria)->applyFromArray($total_amarelo);
    $mes = 1;
    $row_mes = 'B';

    for ($i = 1; $i <= 12; $i++) {
        $total = $Compra_nota->totalMes(3, $mes, date('Y'));
        $activeWorksheet->setCellValue($row_mes . $row_categoria, $total); 
        $activeWorksheet->getStyle($row_mes . $row_categoria)->applyFromArray($total_amarelo);
        $activeWorksheet->getCell($row_mes . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
        $row_mes = chr(ord($row_mes) + 1);
        $mes++;
    }

    $totalempresa = $Compra_nota->totalEmpresa(3, date('Y'));
    $activeWorksheet->setCellValue($row_mes . $row_categoria, $totalempresa);
    $activeWorksheet->getCell($row_mes . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
    $activeWorksheet->getStyle('N' . $row_categoria)->applyFromArray($red);

    // Aplicar bordas pretas até o total
    $activeWorksheet->getStyle('A'.$row_titulo.':N' . $row_categoria)->applyFromArray($border_black);
    $row_categoria++;
    $row_categoria++;
    /*
        CLINICA JARDIM
    */

    $row_titulo = $row_categoria;

    $activeWorksheet->mergeCells('A' . $row_categoria . ':N' . $row_categoria);
    $activeWorksheet->setCellValue('A' . $row_categoria, 'RELATORIO RESUMO GERAL CLINICA JARDIM - ' . date('Y'));
    $activeWorksheet->getStyle('A' . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $activeWorksheet->getStyle('A' . $row_categoria)->applyFromArray($titulo);
    $row_categoria++;
    $activeWorksheet->setCellValue('A' . $row_categoria, 'FORNECEDOR');
    $activeWorksheet->getStyle('A' . $row_categoria)->applyFromArray($mes_style_clinica);

    $col = 'B';
    //Lista todos os meses
    foreach ($meses as $mes) {
        $activeWorksheet->setCellValue($col . $row_categoria, $mes);
        $activeWorksheet->getStyle($col . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $activeWorksheet->getStyle($col . $row_categoria)->applyFromArray($mes_style_clinica);
        $col++;
    }
    $activeWorksheet->getStyle('A'.$row_categoria)->applyFromArray($mes_style_clinica); 
    $activeWorksheet->getStyle('A'.$row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $activeWorksheet->getStyle('N'.$row_categoria)->applyFromArray($mes_style_clinica); 
    $activeWorksheet->getStyle('N'.$row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $activeWorksheet->setCellValue('N' . $row_categoria, 'TOTAL:');

    $row_categoria++;

    //Lista todas as categorias
    foreach ($Compra_Categoria->listar() as $cc) {
        $cca = strtoupper($cc->categoria);

        // Verifica se há notas associadas a esta categoria para a empresa e ano específicos
        if ($Compra_nota->totalNotasCategoria(5, $cc->id_compra_categoria, date('Y')) > 0) {
            $activeWorksheet->setCellValue('A' . $row_categoria,$cca.':');
            $activeWorksheet->getStyle('A'.$row_categoria.':N'.$row_categoria)->applyFromArray($grey);
            $row_categoria++;
            foreach($Compra_Fornecedor->listarPorCategoria(5,$cc->id_compra_categoria,date('Y')) as $f){
                $activeWorksheet->setCellValue('A' . $row_categoria, $f->fornecedor);
                
                $col = 'B';
                for($i = 1; $i <=12; $i++){
                    $total_f_mes = $Compra_nota->totalFornecedorMes(5,$f->id_compra_fornecedor,$i);
                    if($total_f_mes > 0){$activeWorksheet->setCellValue($col.$row_categoria,$total_f_mes);}
                    $activeWorksheet->getCell($col.$row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
                    $col = chr(ord($col) + 1);
                }
                $activeWorksheet->setCellValue('N' . $row_categoria,$Compra_nota->totalFornecedorAnual(5,$f->id_compra_fornecedor,date('Y')));
                $activeWorksheet->getCell('N' . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
                $activeWorksheet->getStyle('N' . $row_categoria)->applyFromArray($red);
                $row_categoria++;
            }

            $activeWorksheet->setCellValue('A' . $row_categoria,'TOTAL '.$cca.':');
            $activeWorksheet->getStyle('A'.$row_categoria.':N'.$row_categoria)->applyFromArray($total_dark);

            $col = 'B';
            //Lista todos valores de categorias por mês
            for ($i = 1; $i <= 12; $i++) {
                $valor_total = $Compra_nota->totalCategoria(5, $cc->id_compra_categoria, $i, date('Y'));
                $activeWorksheet->setCellValue($col . $row_categoria, $valor_total);
                $activeWorksheet->getCell($col . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
                $col = chr(ord($col) + 1);
            }

            //Lita o total de cada categoria
            $activeWorksheet->setCellValue('N' . $row_categoria, $Compra_nota->totalCategoriaAnual(5, $cc->id_compra_categoria, date('Y')));
            $activeWorksheet->getCell('N' . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
            $activeWorksheet->getStyle('N' . $row_categoria)->applyFromArray($red);

            $row_categoria++;
        }
    }

    $activeWorksheet->setCellValue('A' . $row_categoria, 'TOTAL:');
    $activeWorksheet->getStyle('A' . $row_categoria)->applyFromArray($total_amarelo);
    $activeWorksheet->getStyle('N' . $row_categoria)->applyFromArray($total_amarelo);
    $mes = 1;
    $row_mes = 'B';

    for ($i = 1; $i <= 12; $i++) {
        $total = $Compra_nota->totalMes(5, $mes, date('Y'));
        $activeWorksheet->setCellValue($row_mes . $row_categoria, $total); 
        $activeWorksheet->getStyle($row_mes . $row_categoria)->applyFromArray($total_amarelo);
        $activeWorksheet->getCell($row_mes . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
        $row_mes = chr(ord($row_mes) + 1);
        $mes++;
    }

    $totalempresa = $Compra_nota->totalEmpresa(5, date('Y'));
    $activeWorksheet->setCellValue($row_mes . $row_categoria, $totalempresa);
    $activeWorksheet->getCell($row_mes . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
    $activeWorksheet->getStyle('N' . $row_categoria)->applyFromArray($red);

    // Aplicar bordas pretas até o total
    $activeWorksheet->getStyle('A'.$row_titulo.':N' . $row_categoria)->applyFromArray($border_black);
    $row_categoria++;
    $row_categoria++;

}

if($empresa == 0 OR $empresa == 2){
    /*
        ÓTICA MATRIZ
    */

    $row_titulo = $row_categoria;

    $activeWorksheet->mergeCells('A' . $row_categoria . ':N' . $row_categoria);
    $activeWorksheet->setCellValue('A' . $row_categoria, 'RELATORIO RESUMO GERAL ÓTICA MATRIZ - ' . date('Y'));
    $activeWorksheet->getStyle('A' . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $activeWorksheet->getStyle('A' . $row_categoria)->applyFromArray($titulo_otica);
    $row_categoria++;
    $activeWorksheet->setCellValue('A' . $row_categoria, 'FORNECEDOR');
    $activeWorksheet->getStyle('A' . $row_categoria)->applyFromArray($mes_style_otica);


    $col = 'B';
    //Lista todos os meses
    foreach ($meses as $mes) {
        $activeWorksheet->setCellValue($col . $row_categoria, $mes);
        $activeWorksheet->getStyle($col . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $activeWorksheet->getStyle($col . $row_categoria)->applyFromArray($mes_style_otica);
        $col++;
    }
    $activeWorksheet->getStyle('A'.$row_categoria)->applyFromArray($mes_style_otica); 
    $activeWorksheet->getStyle('A'.$row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $activeWorksheet->getStyle('N'.$row_categoria)->applyFromArray($mes_style_otica); 
    $activeWorksheet->getStyle('N'.$row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $activeWorksheet->setCellValue('N' . $row_categoria, 'TOTAL:');

    $row_categoria++;

    //Lista todas as categorias
    foreach ($Compra_Categoria->listar() as $cc) {
        $cca = strtoupper($cc->categoria);

        // Verifica se há notas associadas a esta categoria para a empresa e ano específicos
        if ($Compra_nota->totalNotasCategoria(2, $cc->id_compra_categoria, date('Y')) > 0) {
            $activeWorksheet->setCellValue('A' . $row_categoria,$cca.':');
            $activeWorksheet->getStyle('A'.$row_categoria.':N'.$row_categoria)->applyFromArray($grey);
            $row_categoria++;
            foreach($Compra_Fornecedor->listarPorCategoria(2,$cc->id_compra_categoria,date('Y')) as $f){
                $activeWorksheet->setCellValue('A' . $row_categoria, $f->fornecedor);
                
                $col = 'B';
                for($i = 1; $i <=12; $i++){
                    $total_f_mes = $Compra_nota->totalFornecedorMes(2,$f->id_compra_fornecedor,$i);
                    if($total_f_mes > 0){$activeWorksheet->setCellValue($col.$row_categoria,$total_f_mes);}
                    $activeWorksheet->getCell($col.$row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
                    $col = chr(ord($col) + 1);
                }
                $activeWorksheet->setCellValue('N' . $row_categoria,$Compra_nota->totalFornecedorAnual(2,$f->id_compra_fornecedor,date('Y')));
                $activeWorksheet->getCell('N' . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
                $activeWorksheet->getStyle('N' . $row_categoria)->applyFromArray($red);
                $row_categoria++;
            }

            $activeWorksheet->setCellValue('A' . $row_categoria,'TOTAL '.$cca.':');
            $activeWorksheet->getStyle('A'.$row_categoria.':N'.$row_categoria)->applyFromArray($total_dark);

            $col = 'B';
            //Lista todos valores de categorias por mês
            for ($i = 1; $i <= 12; $i++) {
                $valor_total = $Compra_nota->totalCategoria(2, $cc->id_compra_categoria, $i, date('Y'));
                $activeWorksheet->setCellValue($col . $row_categoria, $valor_total);
                $activeWorksheet->getCell($col . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
                $col = chr(ord($col) + 1);
            }

            //Lita o total de cada categoria
            $activeWorksheet->setCellValue('N' . $row_categoria, $Compra_nota->totalCategoriaAnual(2, $cc->id_compra_categoria, date('Y')));
            $activeWorksheet->getCell('N' . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
            $activeWorksheet->getStyle('N' . $row_categoria)->applyFromArray($red);

            $row_categoria++;
        }
    }

    $activeWorksheet->setCellValue('A' . $row_categoria, 'TOTAL:');
    $activeWorksheet->getStyle('A' . $row_categoria)->applyFromArray($total_amarelo);
    $mes = 1;
    $row_mes = 'B';

    for ($i = 1; $i <= 12; $i++) {
        $total = $Compra_nota->totalMes(2, $mes, date('Y'));
        $activeWorksheet->setCellValue($row_mes . $row_categoria, $total); 
        $activeWorksheet->getStyle($row_mes . $row_categoria)->applyFromArray($total_amarelo);
        $activeWorksheet->getCell($row_mes . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
        $row_mes = chr(ord($row_mes) + 1);
        $mes++;
    }

    $totalempresa = $Compra_nota->totalEmpresa(2, date('Y'));
    $activeWorksheet->setCellValue($row_mes . $row_categoria, $totalempresa);
    $activeWorksheet->getCell($row_mes . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
    $activeWorksheet->getStyle('N' . $row_categoria)->applyFromArray($red);
    $activeWorksheet->getStyle('A' . $row_categoria)->applyFromArray($total_amarelo);
    $activeWorksheet->getStyle('N' . $row_categoria)->applyFromArray($total_amarelo);

    // Aplicar bordas pretas até o total
    $activeWorksheet->getStyle('A'.$row_titulo.':N' . $row_categoria)->applyFromArray($border_black);
    $row_categoria++;
    $row_categoria++;

    /*
        ÓTICA PRESTIGIO
    */

    $row_titulo = $row_categoria;

    $activeWorksheet->mergeCells('A' . $row_categoria . ':N' . $row_categoria);
    $activeWorksheet->setCellValue('A' . $row_categoria, 'RELATORIO RESUMO GERAL ÓTICA PRESTIGIO - ' . date('Y'));
    $activeWorksheet->getStyle('A' . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $activeWorksheet->getStyle('A' . $row_categoria)->applyFromArray($titulo_otica);
    $row_categoria++;
    $activeWorksheet->setCellValue('A' . $row_categoria, 'FORNECEDOR');
    $activeWorksheet->getStyle('A' . $row_categoria)->applyFromArray($mes_style_otica);


    $col = 'B';
    //Lista todos os meses
    foreach ($meses as $mes) {
        $activeWorksheet->setCellValue($col . $row_categoria, $mes);
        $activeWorksheet->getStyle($col . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $activeWorksheet->getStyle($col . $row_categoria)->applyFromArray($mes_style_otica);
        $col++;
    }
    $activeWorksheet->getStyle('A'.$row_categoria)->applyFromArray($mes_style_otica); 
    $activeWorksheet->getStyle('A'.$row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $activeWorksheet->getStyle('N'.$row_categoria)->applyFromArray($mes_style_otica); 
    $activeWorksheet->getStyle('N'.$row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $activeWorksheet->setCellValue('N' . $row_categoria, 'TOTAL:');

    $row_categoria++;

    //Lista todas as categorias
    foreach ($Compra_Categoria->listar() as $cc) {
        $cca = strtoupper($cc->categoria);

        // Verifica se há notas associadas a esta categoria para a empresa e ano específicos
        if ($Compra_nota->totalNotasCategoria(4, $cc->id_compra_categoria, date('Y')) > 0) {
            $activeWorksheet->setCellValue('A' . $row_categoria,$cca.':');
            $activeWorksheet->getStyle('A'.$row_categoria.':N'.$row_categoria)->applyFromArray($grey);
            $row_categoria++;
            foreach($Compra_Fornecedor->listarPorCategoria(4,$cc->id_compra_categoria,date('Y')) as $f){
                $activeWorksheet->setCellValue('A' . $row_categoria, $f->fornecedor);
                
                $col = 'B';
                for($i = 1; $i <=12; $i++){
                    $total_f_mes = $Compra_nota->totalFornecedorMes(4,$f->id_compra_fornecedor,$i);
                    if($total_f_mes > 0){$activeWorksheet->setCellValue($col.$row_categoria,$total_f_mes);}
                    $activeWorksheet->getCell($col.$row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
                    $col = chr(ord($col) + 1);
                }
                $activeWorksheet->setCellValue('N' . $row_categoria,$Compra_nota->totalFornecedorAnual(4,$f->id_compra_fornecedor,date('Y')));
                $activeWorksheet->getCell('N' . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
                $activeWorksheet->getStyle('N' . $row_categoria)->applyFromArray($red);
                $row_categoria++;
            }

            $activeWorksheet->setCellValue('A' . $row_categoria,'TOTAL '.$cca.':');
            $activeWorksheet->getStyle('A'.$row_categoria.':N'.$row_categoria)->applyFromArray($total_dark);

            $col = 'B';
            //Lista todos valores de categorias por mês
            for ($i = 1; $i <= 12; $i++) {
                $valor_total = $Compra_nota->totalCategoria(4, $cc->id_compra_categoria, $i, date('Y'));
                $activeWorksheet->setCellValue($col . $row_categoria, $valor_total);
                $activeWorksheet->getCell($col . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
                $col = chr(ord($col) + 1);
            }

            //Lita o total de cada categoria
            $activeWorksheet->setCellValue('N' . $row_categoria, $Compra_nota->totalCategoriaAnual(4, $cc->id_compra_categoria, date('Y')));
            $activeWorksheet->getCell('N' . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
            $activeWorksheet->getStyle('N' . $row_categoria)->applyFromArray($red);

            $row_categoria++;
        }
    }

    $activeWorksheet->setCellValue('A' . $row_categoria, 'TOTAL:');
    $activeWorksheet->getStyle('A' . $row_categoria)->applyFromArray($total_amarelo);
    $mes = 1;
    $row_mes = 'B';

    for ($i = 1; $i <= 12; $i++) {
        $total = $Compra_nota->totalMes(4, $mes, date('Y'));
        $activeWorksheet->setCellValue($row_mes . $row_categoria, $total); 
        $activeWorksheet->getStyle($row_mes . $row_categoria)->applyFromArray($total_amarelo);
        $activeWorksheet->getCell($row_mes . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
        $row_mes = chr(ord($row_mes) + 1);
        $mes++;
    }

    $totalempresa = $Compra_nota->totalEmpresa(4, date('Y'));
    $activeWorksheet->setCellValue($row_mes . $row_categoria, $totalempresa);
    $activeWorksheet->getCell($row_mes . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
    $activeWorksheet->getStyle('N' . $row_categoria)->applyFromArray($red);
    $activeWorksheet->getStyle('A' . $row_categoria)->applyFromArray($total_amarelo);
    $activeWorksheet->getStyle('N' . $row_categoria)->applyFromArray($total_amarelo);

    // Aplicar bordas pretas até o total
    $activeWorksheet->getStyle('A'.$row_titulo.':N' . $row_categoria)->applyFromArray($border_black);
    $row_categoria++;
    $row_categoria++;
    /*
        ÓTICA DAILY
    */
    $row_titulo = $row_categoria;

    $activeWorksheet->mergeCells('A' . $row_categoria . ':N' . $row_categoria);
    $activeWorksheet->setCellValue('A' . $row_categoria, 'RELATORIO RESUMO GERAL ÓTICA DAILY - ' . date('Y'));
    $activeWorksheet->getStyle('A' . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $activeWorksheet->getStyle('A' . $row_categoria)->applyFromArray($titulo_otica);
    $row_categoria++;
    $activeWorksheet->setCellValue('A' . $row_categoria, 'FORNECEDOR');
    $activeWorksheet->getStyle('A' . $row_categoria)->applyFromArray($mes_style_otica);


    $col = 'B';
    //Lista todos os meses
    foreach ($meses as $mes) {
        $activeWorksheet->setCellValue($col . $row_categoria, $mes);
        $activeWorksheet->getStyle($col . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $activeWorksheet->getStyle($col . $row_categoria)->applyFromArray($mes_style_otica);
        $col++;
    }
    $activeWorksheet->getStyle('A'.$row_categoria)->applyFromArray($mes_style_otica); 
    $activeWorksheet->getStyle('A'.$row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $activeWorksheet->getStyle('N'.$row_categoria)->applyFromArray($mes_style_otica); 
    $activeWorksheet->getStyle('N'.$row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $activeWorksheet->setCellValue('N' . $row_categoria, 'TOTAL:');

    $row_categoria++;

    //Lista todas as categorias
    foreach ($Compra_Categoria->listar() as $cc) {
        $cca = strtoupper($cc->categoria);

        // Verifica se há notas associadas a esta categoria para a empresa e ano específicos
        if ($Compra_nota->totalNotasCategoria(6, $cc->id_compra_categoria, date('Y')) > 0) {
            $activeWorksheet->setCellValue('A' . $row_categoria,$cca.':');
            $activeWorksheet->getStyle('A'.$row_categoria.':N'.$row_categoria)->applyFromArray($grey);
            $row_categoria++;
            foreach($Compra_Fornecedor->listarPorCategoria(6,$cc->id_compra_categoria,date('Y')) as $f){
                $activeWorksheet->setCellValue('A' . $row_categoria, $f->fornecedor);
                
                $col = 'B';
                for($i = 1; $i <=12; $i++){
                    $total_f_mes = $Compra_nota->totalFornecedorMes(6,$f->id_compra_fornecedor,$i);
                    if($total_f_mes > 0){$activeWorksheet->setCellValue($col.$row_categoria,$total_f_mes);}
                    $activeWorksheet->getCell($col.$row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
                    $col = chr(ord($col) + 1);
                }
                $activeWorksheet->setCellValue('N' . $row_categoria,$Compra_nota->totalFornecedorAnual(6,$f->id_compra_fornecedor,date('Y')));
                $activeWorksheet->getCell('N' . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
                $activeWorksheet->getStyle('N' . $row_categoria)->applyFromArray($red);
                $row_categoria++;
            }

            $activeWorksheet->setCellValue('A' . $row_categoria,'TOTAL '.$cca.':');
            $activeWorksheet->getStyle('A'.$row_categoria.':N'.$row_categoria)->applyFromArray($total_dark);

            $col = 'B';
            //Lista todos valores de categorias por mês
            for ($i = 1; $i <= 12; $i++) {
                $valor_total = $Compra_nota->totalCategoria(6, $cc->id_compra_categoria, $i, date('Y'));
                $activeWorksheet->setCellValue($col . $row_categoria, $valor_total);
                $activeWorksheet->getCell($col . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
                $col = chr(ord($col) + 1);
            }

            //Lita o total de cada categoria
            $activeWorksheet->setCellValue('N' . $row_categoria, $Compra_nota->totalCategoriaAnual(6, $cc->id_compra_categoria, date('Y')));
            $activeWorksheet->getCell('N' . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
            $activeWorksheet->getStyle('N' . $row_categoria)->applyFromArray($red);

            $row_categoria++;
        }
    }

    $activeWorksheet->setCellValue('A' . $row_categoria, 'TOTAL:');
    $activeWorksheet->getStyle('A' . $row_categoria)->applyFromArray($total_amarelo);
    $mes = 1;
    $row_mes = 'B';

    for ($i = 1; $i <= 12; $i++) {
        $total = $Compra_nota->totalMes(6, $mes, date('Y'));
        $activeWorksheet->setCellValue($row_mes . $row_categoria, $total); 
        $activeWorksheet->getStyle($row_mes . $row_categoria)->applyFromArray($total_amarelo);
        $activeWorksheet->getCell($row_mes . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
        $row_mes = chr(ord($row_mes) + 1);
        $mes++;
    }

    $totalempresa = $Compra_nota->totalEmpresa(6, date('Y'));
    $activeWorksheet->setCellValue($row_mes . $row_categoria, $totalempresa);
    $activeWorksheet->getCell($row_mes . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
    $activeWorksheet->getStyle('N' . $row_categoria)->applyFromArray($red);
    $activeWorksheet->getStyle('A' . $row_categoria)->applyFromArray($total_amarelo);
    $activeWorksheet->getStyle('N' . $row_categoria)->applyFromArray($total_amarelo);


    // Aplicar bordas pretas até o total
    $activeWorksheet->getStyle('A'.$row_titulo.':N' . $row_categoria)->applyFromArray($border_black);
    $row_categoria++;
    $row_categoria++;
    /*
        FIM EMPRESAS
    */


    $row_categoria++;
}

/*
    Total Anual:
*/

if($empresa == 0 OR $empresa == 1){
    $activeWorksheet->setCellValue('A'.$row_categoria,'Total Clínicas:');
    $activeWorksheet->getStyle('A' . $row_categoria)->applyFromArray($mes_style_clinica);
    $activeWorksheet->setCellValue('B'.$row_categoria,$Compra_nota->totalClinicas());
    $activeWorksheet->getStyle('B' . $row_categoria)->applyFromArray($mes_style_clinica);
    $activeWorksheet->getCell('B'.$row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);

    $row_categoria++;
}


if($empresa == 0 OR $empresa == 2){
    $activeWorksheet->setCellValue('A'.$row_categoria,'Total Óticas:');
    $activeWorksheet->getStyle('A' . $row_categoria)->applyFromArray($mes_style_otica);
    $activeWorksheet->setCellValue('B'.$row_categoria,$Compra_nota->totalOticas());
    $activeWorksheet->getStyle('B' . $row_categoria)->applyFromArray($mes_style_otica);
    $activeWorksheet->getCell('B'.$row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
}

$row_alinhamento = 'A';
for ($i = 1; $i <= 16; $i++) {
    $spreadsheet->getActiveSheet()->getColumnDimension($row_alinhamento)->setAutoSize(true);
    $row_alinhamento = chr(ord($row_alinhamento) + 1);
}

// --------------------------------------------------------------------------------------------------------------------------

// Planilha Quantidade
if($empresa == 0 OR $empresa == 2){
    {
        $newSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Quantidade');
        $spreadsheet->addSheet($newSheet);

        $row_categoria = 1;

        $row_titulo = $row_categoria;

        /*
            ÓTICA MATRIZ
        */
        $newSheet->mergeCells('A' . $row_categoria . ':AK' . $row_categoria);
        $newSheet->setCellValue('A' . $row_categoria, 'RELATORIO QUANTIDADE ÓTICA MATRIZ - ' . date('Y'));
        $newSheet->getStyle('A' . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $newSheet->getStyle('A' . $row_categoria)->applyFromArray($titulo_otica);
        $row_categoria++;
        $newSheet->setCellValue('A' . $row_categoria, 'FORNECEDOR');
        $newSheet->getStyle('A' . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $newSheet->getStyle('A' . $row_categoria)->applyFromArray($mes_style_otica);

        $col = 'B';
        //Lista todos os meses
        foreach ($meses as $mes) {
            $col_inicio = $col;
            $newSheet->setCellValue($col . $row_categoria, $mes);
            $newSheet->getStyle($col . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $newSheet->getStyle($col . $row_categoria)->applyFromArray($mes_style_otica);
            $col++;
            $col++;
            $newSheet->mergeCells($col_inicio . $row_categoria . ':'. $col . $row_categoria);
            $col++;
        }

        $row_categoria++;
        
        $coluna = 'B';
        
        for ($i = 0; $i < 12; $i++) {
            $newSheet->setCellValue($coluna . $row_categoria, "COM");
            $newSheet->getStyle($coluna . $row_categoria)->applyFromArray($estilo_compra);
            $newSheet->getStyle($coluna . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $coluna = getNextColumn($coluna);
            
            $newSheet->setCellValue($coluna . $row_categoria, "VEN");
            $newSheet->getStyle($coluna . $row_categoria)->applyFromArray($estilo_venda);
            $newSheet->getStyle($coluna . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $coluna = getNextColumn($coluna);
            
            $newSheet->setCellValue($coluna . $row_categoria, "EST");
            $newSheet->getStyle($coluna . $row_categoria)->applyFromArray($estilo_estoque);
            $newSheet->getStyle($coluna . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $coluna = getNextColumn($coluna);
        }
        

        $row_categoria++;

        //Lista todas as categorias
        foreach ($Compra_Categoria->listar() as $cc) {
            $cca = strtoupper($cc->categoria);
        
            // Verifica se há compras associadas a esta categoria para a empresa e ano específicos
            if ($Compra_nota->totalNotasCategoria(2, $cc->id_compra_categoria, date('Y')) > 0) {
                $newSheet->setCellValue('A' . $row_categoria, $cca . ':');
                $newSheet->getStyle('A' . $row_categoria . ':AK' . $row_categoria)->applyFromArray($grey);
                $row_categoria++;
        
                if($cc->id_compra_categoria == 5){
                    foreach ($Compra_Fornecedor->listarTudoPorCategoria(2, $cc->id_compra_categoria, date('Y')) as $f){
                        $newSheet->setCellValue('A' . $row_categoria, $f->fornecedor);
            
                        $col = 'B';
                        for ($i = 1; $i <= 12; $i++) {
                            $total_f_mes = $Compra_nota->totalQntdFornecedorMes(2, $f->id_compra_fornecedor, $i);
                            if ($total_f_mes > 0) {
                                $newSheet->setCellValue($col . $row_categoria, $total_f_mes);
                            }
                            $newSheet->getStyle($col . $row_categoria)->applyFromArray($estilo_compra);
                            $newSheet->getCell($col . $row_categoria)->getStyle()->getNumberFormat();
                            $col = getNextColumn($col);
                            $newSheet->getStyle($col . $row_categoria)->applyFromArray($estilo_venda);
                            $col = getNextColumn($col);
                            $newSheet->getStyle($col . $row_categoria)->applyFromArray($estilo_estoque);
                            $col = getNextColumn($col);
                        }
                        $row_categoria++;
                    }
                }else{
                    foreach ($Compra_Fornecedor->listarPorCategoria(2, $cc->id_compra_categoria, date('Y')) as $f){
                        $newSheet->setCellValue('A' . $row_categoria, $f->fornecedor);
            
                        $col = 'B';
                        for ($i = 1; $i <= 12; $i++) {
                            $total_f_mes = $Compra_nota->totalQntdFornecedorMes(2, $f->id_compra_fornecedor, $i);
                            if ($total_f_mes > 0) {
                                $newSheet->setCellValue($col . $row_categoria, $total_f_mes);
                            }
                            $newSheet->getStyle($col . $row_categoria)->applyFromArray($estilo_compra);
                            $newSheet->getCell($col . $row_categoria)->getStyle()->getNumberFormat();
                            $col = getNextColumn($col);
                            $newSheet->getStyle($col . $row_categoria)->applyFromArray($estilo_venda);
                            $col = getNextColumn($col);
                            $newSheet->getStyle($col . $row_categoria)->applyFromArray($estilo_estoque);
                            $col = getNextColumn($col);
                        }
                        $row_categoria++;
                    }
                }
        
                $newSheet->setCellValue('A' . $row_categoria, 'TOTAL ' . $cca . ':');
                $newSheet->getStyle('A' . $row_categoria . ':AK' . $row_categoria)->applyFromArray($total_dark);
        
                $col = 'B';
                // Lista todos os valores de categorias por mês
                for ($i = 1; $i <= 12; $i++) {
                    $valor_total = $Compra_nota->totalQntdCategoria(2, $cc->id_compra_categoria, $i, date('Y'));
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
        $newSheet->getStyle('A' . $row_categoria)->applyFromArray($total_amarelo);
        $mes = 1;
        $col_mes = 'B';

        for ($i = 1; $i <= 12; $i++) {
            $total = $Compra_nota->totalQntdMes(2, $mes, date('Y'));
            $newSheet->setCellValue($col_mes . $row_categoria, $total); 
            $newSheet->getStyle($col_mes . $row_categoria)->applyFromArray($total_amarelo);
            $newSheet->getCell($col_mes . $row_categoria)->getStyle()->getNumberFormat();
            $col_mes = getNextColumn($col_mes);
            $newSheet->getStyle($col_mes . $row_categoria)->applyFromArray($total_amarelo);
            $col_mes = getNextColumn($col_mes);
            $newSheet->getStyle($col_mes . $row_categoria)->applyFromArray($total_amarelo);
            $col_mes = getNextColumn($col_mes);
            $mes++;
        }

        $newSheet->getStyle('A'.$row_titulo.':AK' . $row_categoria)->applyFromArray($border_black);
        $row_categoria++;
        $row_categoria++;

        /*
            ÓTICA PRESTIGIO
        */

        $row_titulo = $row_categoria;

        $newSheet->mergeCells('A' . $row_categoria . ':AK' . $row_categoria);
        $newSheet->setCellValue('A' . $row_categoria, 'RELATORIO QUANTIDADE ÓTICA PRESTIGIO - ' . date('Y'));
        $newSheet->getStyle('A' . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $newSheet->getStyle('A' . $row_categoria)->applyFromArray($titulo_otica);
        $row_categoria++;
        $newSheet->setCellValue('A' . $row_categoria, 'FORNECEDOR');
        $newSheet->getStyle('A' . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $newSheet->getStyle('A' . $row_categoria)->applyFromArray($mes_style_otica);

        $col = 'B';
        //Lista todos os meses
        foreach ($meses as $mes) {
            $col_inicio = $col;
            $newSheet->setCellValue($col . $row_categoria, $mes);
            $newSheet->getStyle($col . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $newSheet->getStyle($col . $row_categoria)->applyFromArray($mes_style_otica);
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
        foreach ($Compra_Categoria->listar() as $cc) {
            $cca = strtoupper($cc->categoria);
        
            // Verifica se há compras associadas a esta categoria para a empresa e ano específicos
            if ($Compra_nota->totalNotasCategoria(4, $cc->id_compra_categoria, date('Y')) > 0) {
                $newSheet->setCellValue('A' . $row_categoria, $cca . ':');
                $newSheet->getStyle('A' . $row_categoria . ':AK' . $row_categoria)->applyFromArray($grey);
                $row_categoria++;
        
                if($cc->id_compra_categoria == 5){
                    foreach ($Compra_Fornecedor->listarTudoPorCategoria(4, $cc->id_compra_categoria, date('Y')) as $f){
                        $newSheet->setCellValue('A' . $row_categoria, $f->fornecedor);
            
                        $col = 'B';
                        for ($i = 1; $i <= 12; $i++) {
                            $total_f_mes = $Compra_nota->totalQntdFornecedorMes(4, $f->id_compra_fornecedor, $i);
                            if ($total_f_mes > 0) {
                                $newSheet->setCellValue($col . $row_categoria, $total_f_mes);
                            }
                            $newSheet->getStyle($col . $row_categoria)->applyFromArray($estilo_compra);
                            $newSheet->getCell($col . $row_categoria)->getStyle()->getNumberFormat();
                            $col = getNextColumn($col);
                            $newSheet->getStyle($col . $row_categoria)->applyFromArray($estilo_venda);
                            $col = getNextColumn($col);
                            $newSheet->getStyle($col . $row_categoria)->applyFromArray($estilo_estoque);
                            $col = getNextColumn($col);
                        }
                        $row_categoria++;
                    }
                }else{
                    foreach ($Compra_Fornecedor->listarPorCategoria(4, $cc->id_compra_categoria, date('Y')) as $f){
                        $newSheet->setCellValue('A' . $row_categoria, $f->fornecedor);
            
                        $col = 'B';
                        for ($i = 1; $i <= 12; $i++) {
                            $total_f_mes = $Compra_nota->totalQntdFornecedorMes(4, $f->id_compra_fornecedor, $i);
                            if ($total_f_mes > 0) {
                                $newSheet->setCellValue($col . $row_categoria, $total_f_mes);
                            }
                            $newSheet->getStyle($col . $row_categoria)->applyFromArray($estilo_compra);
                            $newSheet->getCell($col . $row_categoria)->getStyle()->getNumberFormat();
                            $col = getNextColumn($col);
                            $newSheet->getStyle($col . $row_categoria)->applyFromArray($estilo_venda);
                            $col = getNextColumn($col);
                            $newSheet->getStyle($col . $row_categoria)->applyFromArray($estilo_estoque);
                            $col = getNextColumn($col);
                        }
                        $row_categoria++;
                    }
                }
        
                $newSheet->setCellValue('A' . $row_categoria, 'TOTAL ' . $cca . ':');
                $newSheet->getStyle('A' . $row_categoria . ':AK' . $row_categoria)->applyFromArray($total_dark);
        
                $col = 'B';
                // Lista todos os valores de categorias por mês
                for ($i = 1; $i <= 12; $i++) {
                    $valor_total = $Compra_nota->totalQntdCategoria(4, $cc->id_compra_categoria, $i, date('Y'));
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
        $newSheet->getStyle('A' . $row_categoria)->applyFromArray($total_amarelo);
        $mes = 1;
        $col_mes = 'B';

        for ($i = 1; $i <= 12; $i++) {
            $total = $Compra_nota->totalQntdMes(4, $mes, date('Y'));
            $newSheet->setCellValue($col_mes . $row_categoria, $total); 
            $newSheet->getStyle($col_mes . $row_categoria)->applyFromArray($total_amarelo);
            $newSheet->getCell($col_mes . $row_categoria)->getStyle()->getNumberFormat();
            $col_mes = getNextColumn($col_mes);
            $newSheet->getStyle($col_mes . $row_categoria)->applyFromArray($total_amarelo);
            $col_mes = getNextColumn($col_mes);
            $newSheet->getStyle($col_mes . $row_categoria)->applyFromArray($total_amarelo);
            $col_mes = getNextColumn($col_mes);
            $mes++;
        }

        $newSheet->getStyle('A'.$row_titulo.':AK' . $row_categoria)->applyFromArray($border_black);
        $row_categoria++;
        $row_categoria++;

        /*
            ÓTICA DAILY
        */

        $row_titulo = $row_categoria;

        $newSheet->mergeCells('A' . $row_categoria . ':AK' . $row_categoria);
        $newSheet->setCellValue('A' . $row_categoria, 'RELATORIO QUANTIDADE ÓTICA DAILY - ' . date('Y'));
        $newSheet->getStyle('A' . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $newSheet->getStyle('A' . $row_categoria)->applyFromArray($titulo_otica);
        $row_categoria++;
        $newSheet->setCellValue('A' . $row_categoria, 'FORNECEDOR');
        $newSheet->getStyle('A' . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $newSheet->getStyle('A' . $row_categoria)->applyFromArray($mes_style_otica);

        $col = 'B';
        //Lista todos os meses
        foreach ($meses as $mes) {
            $col_inicio = $col;
            $newSheet->setCellValue($col . $row_categoria, $mes);
            $newSheet->getStyle($col . $row_categoria)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $newSheet->getStyle($col . $row_categoria)->applyFromArray($mes_style_otica);
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
        foreach ($Compra_Categoria->listar() as $cc) {
            $cca = strtoupper($cc->categoria);
        
            // Verifica se há compras associadas a esta categoria para a empresa e ano específicos
            if ($Compra_nota->totalNotasCategoria(6, $cc->id_compra_categoria, date('Y')) > 0) {
                $newSheet->setCellValue('A' . $row_categoria, $cca . ':');
                $newSheet->getStyle('A' . $row_categoria . ':AK' . $row_categoria)->applyFromArray($grey);
                $row_categoria++;
        
                if($cc->id_compra_categoria == 5){
                    foreach ($Compra_Fornecedor->listarTudoPorCategoria(6, $cc->id_compra_categoria, date('Y')) as $f){
                        $newSheet->setCellValue('A' . $row_categoria, $f->fornecedor);
            
                        $col = 'B';
                        for ($i = 1; $i <= 12; $i++) {
                            $total_f_mes = $Compra_nota->totalQntdFornecedorMes(6, $f->id_compra_fornecedor, $i);
                            if ($total_f_mes > 0) {
                                $newSheet->setCellValue($col . $row_categoria, $total_f_mes);
                            }
                            $newSheet->getStyle($col . $row_categoria)->applyFromArray($estilo_compra);
                            $newSheet->getCell($col . $row_categoria)->getStyle()->getNumberFormat();
                            $col = getNextColumn($col);
                            $newSheet->getStyle($col . $row_categoria)->applyFromArray($estilo_venda);
                            $col = getNextColumn($col);
                            $newSheet->getStyle($col . $row_categoria)->applyFromArray($estilo_estoque);
                            $col = getNextColumn($col);
                        }
                        $row_categoria++;
                    }
                }else{
                    foreach ($Compra_Fornecedor->listarPorCategoria(6, $cc->id_compra_categoria, date('Y')) as $f){
                        $newSheet->setCellValue('A' . $row_categoria, $f->fornecedor);
            
                        $col = 'B';
                        for ($i = 1; $i <= 12; $i++) {
                            $total_f_mes = $Compra_nota->totalQntdFornecedorMes(6, $f->id_compra_fornecedor, $i);
                            if ($total_f_mes > 0) {
                                $newSheet->setCellValue($col . $row_categoria, $total_f_mes);
                            }
                            $newSheet->getStyle($col . $row_categoria)->applyFromArray($estilo_compra);
                            $newSheet->getCell($col . $row_categoria)->getStyle()->getNumberFormat();
                            $col = getNextColumn($col);
                            $newSheet->getStyle($col . $row_categoria)->applyFromArray($estilo_venda);
                            $col = getNextColumn($col);
                            $newSheet->getStyle($col . $row_categoria)->applyFromArray($estilo_estoque);
                            $col = getNextColumn($col);
                        }
                        $row_categoria++;
                    }
                }
        
                $newSheet->setCellValue('A' . $row_categoria, 'TOTAL ' . $cca . ':');
                $newSheet->getStyle('A' . $row_categoria . ':AK' . $row_categoria)->applyFromArray($total_dark);
        
                $col = 'B';
                // Lista todos os valores de categorias por mês
                for ($i = 1; $i <= 12; $i++) {
                    $valor_total = $Compra_nota->totalQntdCategoria(6, $cc->id_compra_categoria, $i, date('Y'));
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
        $newSheet->getStyle('A' . $row_categoria)->applyFromArray($total_amarelo);
        $mes = 1;
        $col_mes = 'B';

        for ($i = 1; $i <= 12; $i++) {
            $total = $Compra_nota->totalQntdMes(6, $mes, date('Y'));
            $newSheet->setCellValue($col_mes . $row_categoria, $total); 
            $newSheet->getStyle($col_mes . $row_categoria)->applyFromArray($total_amarelo);
            $newSheet->getCell($col_mes . $row_categoria)->getStyle()->getNumberFormat();
            $col_mes = getNextColumn($col_mes);
            $newSheet->getStyle($col_mes . $row_categoria)->applyFromArray($total_amarelo);
            $col_mes = getNextColumn($col_mes);
            $newSheet->getStyle($col_mes . $row_categoria)->applyFromArray($total_amarelo);
            $col_mes = getNextColumn($col_mes);
            $mes++;
        }

        $newSheet->getStyle('A'.$row_titulo.':AK' . $row_categoria)->applyFromArray($border_black);
        $row_categoria++;
        $row_categoria++;

        $newSheet->getColumnDimension('A')->setAutoSize(true);
    }

    {
        $newSheet->getStyle('B')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $newSheet->getStyle('D')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $newSheet->getStyle('E')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $newSheet->getStyle('F')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $newSheet->getStyle('G')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $newSheet->getStyle('H')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $newSheet->getStyle('I')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $newSheet->getStyle('K')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $newSheet->getStyle('L')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $newSheet->getStyle('M')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $newSheet->getStyle('N')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $newSheet->getStyle('O')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $newSheet->getStyle('P')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $newSheet->getStyle('Q')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $newSheet->getStyle('R')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $newSheet->getStyle('S')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $newSheet->getStyle('T')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $newSheet->getStyle('U')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $newSheet->getStyle('V')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $newSheet->getStyle('W')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $newSheet->getStyle('X')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $newSheet->getStyle('Y')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $newSheet->getStyle('Z')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $newSheet->getStyle('AA')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $newSheet->getStyle('AB')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $newSheet->getStyle('AC')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $newSheet->getStyle('AD')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $newSheet->getStyle('AE')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $newSheet->getStyle('AF')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $newSheet->getStyle('AG')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $newSheet->getStyle('AH')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $newSheet->getStyle('AI')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $newSheet->getStyle('AJ')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $newSheet->getStyle('AK')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }
}

$spreadsheet->setActiveSheetIndexByName('Valor');

// Define o caminho completo do diretório e nome do arquivo
$directory = __DIR__.'/'; // Define o diretório relativo ao arquivo atual
$filename = 'relatorio_fornecedores_' . date('Y') . '.xlsx';
$filepath = $directory . $filename;

// Verifica se o diretório existe; caso contrário, cria o diretório
if (!file_exists($directory)) {
    mkdir($directory, 0777, true); // Cria o diretório com permissões totais
}

// Verifica se o arquivo já existe no local especificado
if (file_exists($filepath)) {
    // Se existir, exclui o arquivo antigo
    unlink($filepath);
}

// Salva o novo arquivo no diretório especificado
$writer = new Xlsx($spreadsheet);
$writer->save($filepath);

// Redireciona o usuário para o arquivo salvo
header('Location: /GRNacoes/views/compras/relatorios/'.$filename);