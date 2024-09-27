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
    foreach ($Compras_Categoria->listar() as $cc) {
        $cca = strtoupper($cc->categoria);
        
        // Verifica se há notas associadas a esta categoria para a empresa e ano específicos
        if ($Compras_notas->totalNotasCategoria(1, $cc->id_compra_categoria, date('Y')) > 0) {
            $activeWorksheet->setCellValue('A' . $row_categoria,$cca.':');
            $activeWorksheet->getStyle('A'.$row_categoria.':N'.$row_categoria)->applyFromArray($grey);
            $row_categoria++;
            foreach($Compras_Fornecedor->listarPorCategoria(1,$cc->id_compra_categoria,date('Y')) as $f){
                $activeWorksheet->setCellValue('A' . $row_categoria, $f->fornecedor);
                
                $col = 'B';
                for($i = 1; $i < 12; $i++){
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
    $activeWorksheet->getStyle('A' . $row_categoria)->applyFromArray($total_amarelo);
    $activeWorksheet->getStyle('N' . $row_categoria)->applyFromArray($total_amarelo);
    $mes = 1; // Ajustado para 1 em vez de 01
    $row_mês = 'B';

    //Valores total por categoria
    for ($i = 1; $i <= 12; $i++) {
        $total = $Compras_notas->totalMes(1, $mes, date('Y'));
        $activeWorksheet->setCellValue($row_mês . $row_categoria, $total); 
        $activeWorksheet->getStyle($row_mês . $row_categoria)->applyFromArray($total_amarelo);
        $activeWorksheet->getCell($row_mês . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
        $row_mês = chr(ord($row_mês) + 1);
        $mes++;
    }

    $totalempresa = $Compras_notas->totalEmpresa(1, date('Y'));
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
    foreach ($Compras_Categoria->listar() as $cc) {
        $cca = strtoupper($cc->categoria);

        // Verifica se há notas associadas a esta categoria para a empresa e ano específicos
        if ($Compras_notas->totalNotasCategoria(3, $cc->id_compra_categoria, date('Y')) > 0) {
            $activeWorksheet->setCellValue('A' . $row_categoria,$cca.':');
            $activeWorksheet->getStyle('A'.$row_categoria.':N'.$row_categoria)->applyFromArray($grey);
            $row_categoria++;
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
    $activeWorksheet->getStyle('A' . $row_categoria)->applyFromArray($total_amarelo);
    $activeWorksheet->getStyle('N' . $row_categoria)->applyFromArray($total_amarelo);
    $mes = 1;
    $row_mes = 'B';

    for ($i = 1; $i <= 12; $i++) {
        $total = $Compras_notas->totalMes(3, $mes, date('Y'));
        $activeWorksheet->setCellValue($row_mes . $row_categoria, $total); 
        $activeWorksheet->getStyle($row_mes . $row_categoria)->applyFromArray($total_amarelo);
        $activeWorksheet->getCell($row_mes . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
        $row_mes = chr(ord($row_mes) + 1);
        $mes++;
    }

    $totalempresa = $Compras_notas->totalEmpresa(3, date('Y'));
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
    foreach ($Compras_Categoria->listar() as $cc) {
        $cca = strtoupper($cc->categoria);

        // Verifica se há notas associadas a esta categoria para a empresa e ano específicos
        if ($Compras_notas->totalNotasCategoria(5, $cc->id_compra_categoria, date('Y')) > 0) {
            $activeWorksheet->setCellValue('A' . $row_categoria,$cca.':');
            $activeWorksheet->getStyle('A'.$row_categoria.':N'.$row_categoria)->applyFromArray($grey);
            $row_categoria++;
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
    $activeWorksheet->getStyle('A' . $row_categoria)->applyFromArray($total_amarelo);
    $activeWorksheet->getStyle('N' . $row_categoria)->applyFromArray($total_amarelo);
    $mes = 1;
    $row_mes = 'B';

    for ($i = 1; $i <= 12; $i++) {
        $total = $Compras_notas->totalMes(5, $mes, date('Y'));
        $activeWorksheet->setCellValue($row_mes . $row_categoria, $total); 
        $activeWorksheet->getStyle($row_mes . $row_categoria)->applyFromArray($total_amarelo);
        $activeWorksheet->getCell($row_mes . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
        $row_mes = chr(ord($row_mes) + 1);
        $mes++;
    }

    $totalempresa = $Compras_notas->totalEmpresa(5, date('Y'));
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
    foreach ($Compras_Categoria->listar() as $cc) {
        $cca = strtoupper($cc->categoria);

        // Verifica se há notas associadas a esta categoria para a empresa e ano específicos
        if ($Compras_notas->totalNotasCategoria(2, $cc->id_compra_categoria, date('Y')) > 0) {
            $activeWorksheet->setCellValue('A' . $row_categoria,$cca.':');
            $activeWorksheet->getStyle('A'.$row_categoria.':N'.$row_categoria)->applyFromArray($grey);
            $row_categoria++;
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
    $activeWorksheet->getStyle('A' . $row_categoria)->applyFromArray($total_amarelo);
    $mes = 1;
    $row_mes = 'B';

    for ($i = 1; $i <= 12; $i++) {
        $total = $Compras_notas->totalMes(2, $mes, date('Y'));
        $activeWorksheet->setCellValue($row_mes . $row_categoria, $total); 
        $activeWorksheet->getStyle($row_mes . $row_categoria)->applyFromArray($total_amarelo);
        $activeWorksheet->getCell($row_mes . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
        $row_mes = chr(ord($row_mes) + 1);
        $mes++;
    }

    $totalempresa = $Compras_notas->totalEmpresa(2, date('Y'));
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
    foreach ($Compras_Categoria->listar() as $cc) {
        $cca = strtoupper($cc->categoria);

        // Verifica se há notas associadas a esta categoria para a empresa e ano específicos
        if ($Compras_notas->totalNotasCategoria(4, $cc->id_compra_categoria, date('Y')) > 0) {
            $activeWorksheet->setCellValue('A' . $row_categoria,$cca.':');
            $activeWorksheet->getStyle('A'.$row_categoria.':N'.$row_categoria)->applyFromArray($grey);
            $row_categoria++;
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
    $activeWorksheet->getStyle('A' . $row_categoria)->applyFromArray($total_amarelo);
    $mes = 1;
    $row_mes = 'B';

    for ($i = 1; $i <= 12; $i++) {
        $total = $Compras_notas->totalMes(4, $mes, date('Y'));
        $activeWorksheet->setCellValue($row_mes . $row_categoria, $total); 
        $activeWorksheet->getStyle($row_mes . $row_categoria)->applyFromArray($total_amarelo);
        $activeWorksheet->getCell($row_mes . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
        $row_mes = chr(ord($row_mes) + 1);
        $mes++;
    }

    $totalempresa = $Compras_notas->totalEmpresa(4, date('Y'));
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
    foreach ($Compras_Categoria->listar() as $cc) {
        $cca = strtoupper($cc->categoria);

        // Verifica se há notas associadas a esta categoria para a empresa e ano específicos
        if ($Compras_notas->totalNotasCategoria(6, $cc->id_compra_categoria, date('Y')) > 0) {
            $activeWorksheet->setCellValue('A' . $row_categoria,$cca.':');
            $activeWorksheet->getStyle('A'.$row_categoria.':N'.$row_categoria)->applyFromArray($grey);
            $row_categoria++;
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
    $activeWorksheet->getStyle('A' . $row_categoria)->applyFromArray($total_amarelo);
    $mes = 1;
    $row_mes = 'B';

    for ($i = 1; $i <= 12; $i++) {
        $total = $Compras_notas->totalMes(6, $mes, date('Y'));
        $activeWorksheet->setCellValue($row_mes . $row_categoria, $total); 
        $activeWorksheet->getStyle($row_mes . $row_categoria)->applyFromArray($total_amarelo);
        $activeWorksheet->getCell($row_mes . $row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);
        $row_mes = chr(ord($row_mes) + 1);
        $mes++;
    }

    $totalempresa = $Compras_notas->totalEmpresa(6, date('Y'));
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
    $activeWorksheet->setCellValue('B'.$row_categoria,$Compras_notas->totalClinicas());
    $activeWorksheet->getStyle('B' . $row_categoria)->applyFromArray($mes_style_clinica);
    $activeWorksheet->getCell('B'.$row_categoria)->getStyle()->getNumberFormat()->setFormatCode($real);

    $row_categoria++;
}


if($empresa == 0 OR $empresa == 2){
    $activeWorksheet->setCellValue('A'.$row_categoria,'Total Óticas:');
    $activeWorksheet->getStyle('A' . $row_categoria)->applyFromArray($mes_style_otica);
    $activeWorksheet->setCellValue('B'.$row_categoria,$Compras_notas->totalOticas());
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
        
            // Verifica se há compras associadas a esta categoria para a empresa e ano específicos
            if ($Compras_notas->totalNotasCategoria(2, $cc->id_compra_categoria, date('Y')) > 0) {
                $newSheet->setCellValue('A' . $row_categoria, $cca . ':');
                $newSheet->getStyle('A' . $row_categoria . ':AK' . $row_categoria)->applyFromArray($grey);
                $row_categoria++;
        
                foreach ($Compras_Fornecedor->listarPorCategoria(2, $cc->id_compra_categoria, date('Y')) as $f) {
                    $newSheet->setCellValue('A' . $row_categoria, $f->fornecedor);
        
                    $col = 'B';
                    for ($i = 1; $i <= 12; $i++) {
                        $total_f_mes = $Compras_notas->totalQntdFornecedorMes(2, $f->id_compra_fornecedor, $i);
                        if ($total_f_mes > 0) {
                            $newSheet->setCellValue($col . $row_categoria, $total_f_mes);
                        }
                        $newSheet->getCell($col . $row_categoria)->getStyle()->getNumberFormat();
                        $col = getNextColumn($col);
                        $col = getNextColumn($col);
                        $col = getNextColumn($col);
                    }
                    $row_categoria++;
                }
        
                $newSheet->setCellValue('A' . $row_categoria, 'TOTAL ' . $cca . ':');
                $newSheet->getStyle('A' . $row_categoria . ':AK' . $row_categoria)->applyFromArray($grey);
        
                $col = 'B';
                // Lista todos os valores de categorias por mês
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
        $newSheet->getStyle('A' . $row_categoria)->applyFromArray($total_amarelo);
        $mes = 1;
        $col_mes = 'B';

        for ($i = 1; $i <= 12; $i++) {
            $total = $Compras_notas->totalQntdMes(2, $mes, date('Y'));
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
        foreach ($Compras_Categoria->listar() as $cc) {
            $cca = strtoupper($cc->categoria);
        
            // Verifica se há compras associadas a esta categoria para a empresa e ano específicos
            if ($Compras_notas->totalNotasCategoria(4, $cc->id_compra_categoria, date('Y')) > 0) {
                $newSheet->setCellValue('A' . $row_categoria, $cca . ':');
                $newSheet->getStyle('A' . $row_categoria . ':AK' . $row_categoria)->applyFromArray($grey);
                $row_categoria++;
        
                foreach ($Compras_Fornecedor->listarPorCategoria(4, $cc->id_compra_categoria, date('Y')) as $f) {
                    $newSheet->setCellValue('A' . $row_categoria, $f->fornecedor);
        
                    $col = 'B';
                    for ($i = 1; $i <= 12; $i++) {
                        $total_f_mes = $Compras_notas->totalQntdFornecedorMes(4, $f->id_compra_fornecedor, $i);
                        if ($total_f_mes > 0) {
                            $newSheet->setCellValue($col . $row_categoria, $total_f_mes);
                        }
                        $newSheet->getCell($col . $row_categoria)->getStyle()->getNumberFormat();
                        $col = getNextColumn($col);
                        $col = getNextColumn($col);
                        $col = getNextColumn($col);
                    }
                    $row_categoria++;
                }
        
                $newSheet->setCellValue('A' . $row_categoria, 'TOTAL ' . $cca . ':');
                $newSheet->getStyle('A' . $row_categoria . ':AK' . $row_categoria)->applyFromArray($grey);
        
                $col = 'B';
                // Lista todos os valores de categorias por mês
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
        $newSheet->getStyle('A' . $row_categoria)->applyFromArray($total_amarelo);
        $mes = 1;
        $col_mes = 'B';

        for ($i = 1; $i <= 12; $i++) {
            $total = $Compras_notas->totalQntdMes(4, $mes, date('Y'));
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
        foreach ($Compras_Categoria->listar() as $cc) {
            $cca = strtoupper($cc->categoria);
        
            // Verifica se há compras associadas a esta categoria para a empresa e ano específicos
            if ($Compras_notas->totalNotasCategoria(6, $cc->id_compra_categoria, date('Y')) > 0) {
                $newSheet->setCellValue('A' . $row_categoria, $cca . ':');
                $newSheet->getStyle('A' . $row_categoria . ':AK' . $row_categoria)->applyFromArray($grey);
                $row_categoria++;
        
                foreach ($Compras_Fornecedor->listarPorCategoria(6, $cc->id_compra_categoria, date('Y')) as $f) {
                    $newSheet->setCellValue('A' . $row_categoria, $f->fornecedor);
        
                    $col = 'B';
                    for ($i = 1; $i <= 12; $i++) {
                        $total_f_mes = $Compras_notas->totalQntdFornecedorMes(6, $f->id_compra_fornecedor, $i);
                        if ($total_f_mes > 0) {
                            $newSheet->setCellValue($col . $row_categoria, $total_f_mes);
                        }
                        $newSheet->getCell($col . $row_categoria)->getStyle()->getNumberFormat();
                        $col = getNextColumn($col);
                        $col = getNextColumn($col);
                        $col = getNextColumn($col);
                    }
                    $row_categoria++;
                }
        
                $newSheet->setCellValue('A' . $row_categoria, 'TOTAL ' . $cca . ':');
                $newSheet->getStyle('A' . $row_categoria . ':AK' . $row_categoria)->applyFromArray($grey);
        
                $col = 'B';
                // Lista todos os valores de categorias por mês
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
        $newSheet->getStyle('A' . $row_categoria)->applyFromArray($total_amarelo);
        $mes = 1;
        $col_mes = 'B';

        for ($i = 1; $i <= 12; $i++) {
            $total = $Compras_notas->totalQntdMes(6, $mes, date('Y'));
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
}

$spreadsheet->setActiveSheetIndexByName('Valor');

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

header('Location: ' . $filename);