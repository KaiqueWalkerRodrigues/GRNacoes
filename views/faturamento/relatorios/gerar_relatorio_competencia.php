<?php

require 'vendor/autoload.php';

$id_competencia = $_GET['id_competencia'];

$hoje = date('Y-m-d');

$Convenio = new Convenio();
$Faturamento_Competencia = new Faturamento_Competencia();
$Faturamento_Nota_Servico = new Faturamento_Nota_Servico();

$competencia = $Faturamento_Competencia->mostrar($id_competencia);

// Criando uma nova planilha
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat\Wizard\Accounting;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

$titulo = [
    'font' => [
        'bold' => true,
        'size' => 14,
    ],
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => [
            'argb' => 'FFFF00',
        ],
    ]
];
$subtitulo = [
    'font' => [
        'bold' => true,
        'size' => 12,
    ],
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => [
            'argb' => 'D9D9D9',
        ],
    ],
];
$real = new Accounting(
    'R$',
    2,
    true,
    true,
    true
);
$negrito = [
    'font' => [
        'bold' => true,
        'size' => 11,
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


// Criação de uma nova planilha
$planilha = new Spreadsheet();

$pagina1 = $planilha->getActiveSheet();
$pagina1->setTitle('Competencia');

$row = 1;

$periodo_inicio_formatado = Helper::formatarData($competencia->periodo_inicio);
$periodo_fim_formatado = Helper::formatarData($competencia->periodo_fim);
$data_pagamento = DateTime::createFromFormat('Y-m-d', $competencia->mes_pagamento);

$formatter = new IntlDateFormatter(
    'pt_BR',
    IntlDateFormatter::NONE,
    IntlDateFormatter::NONE,
    'America/Sao_Paulo',
    IntlDateFormatter::GREGORIAN,
    'MMMM yyyy'
);

$mes_pagamento_formatado = strtoupper($formatter->format($data_pagamento));

$tituloPagina1 = "COMPETÊNCIA DE $periodo_inicio_formatado a $periodo_fim_formatado - PGTO EM $mes_pagamento_formatado";

$pagina1->setCellValue('A' . $row, $tituloPagina1);
$pagina1->mergeCells('A' . $row . ':L' . $row);
$pagina1->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$pagina1->getStyle('A' . $row)->applyFromArray($titulo);

$row++;

$pagina1->setCellValue('A' . $row, 'CONVÊNIO');
$pagina1->setCellValue('B' . $row, 'DIA');
$pagina1->setCellValue('C' . $row, 'BF/NF');
$pagina1->setCellValue('D' . $row, 'FATURAMENTO');
$pagina1->setCellValue('E' . $row, 'IMPOSTO');
$pagina1->setCellValue('F' . $row, 'A RECEBER');
$pagina1->setCellValue('G' . $row, 'RECURSO');
$pagina1->setCellValue('H' . $row, 'BANCO');
$pagina1->setCellValue('I' . $row, 'DATA');
$pagina1->setCellValue('J' . $row, 'GLOSA');
$pagina1->setCellValue('K' . $row, 'FEEDBACK');

$pagina1->getStyle('A' . $row . ':K' . $row)->applyFromArray($subtitulo)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$pagina1->getStyle('G' . $row)->getFont()->setColor(
    new \PhpOffice\PhpSpreadsheet\Style\Color(
        \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED
    )
);
$pagina1->getStyle('J' . $row . ':K' . $row)->getFont()->setColor(
    new \PhpOffice\PhpSpreadsheet\Style\Color(
        \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED
    )
);

$row++;

$row_um = $row;

foreach ($Faturamento_Nota_Servico->listar($id_competencia) as $nota) {
    $nome_convenio = $Convenio->mostrar($nota->id_convenio)->convenio;
    switch ($nota->tipo) {
        case 0:
            break;
        case 1:
            $nome_convenio .= " Consultas";
            break;
        case 2:
            $nome_convenio .= " Exames";
            break;
    }
    $data_pagamento_previsto_formatado = Helper::formatarData($nota->data_pagamento_previsto);
    $valor_a_receber = $nota->valor_faturado - $nota->valor_imposto;
    $valor_glosa = $valor_a_receber - $nota->valor_pago;
    $data_pago_formatado = Helper::formatarData($nota->data_pago);
    $feedback_formatado = Helper::formatarData($nota->feedback);

    $pagina1->setCellValue('A' . $row, $nome_convenio);
    $pagina1->setCellValue('B' . $row, $data_pagamento_previsto_formatado);
    $pagina1->setCellValue('C' . $row, $nota->bf_nf);
    $pagina1->setCellValue('D' . $row, $nota->valor_faturado);
    $pagina1->setCellValue('E' . $row, $nota->valor_imposto);
    $pagina1->setCellValue('F' . $row, $valor_a_receber);
    $pagina1->setCellValue('G' . $row, ($nota->data_pagamento_previsto < $hoje or $nota->valor_pago > 0 and $nota->valor_pago < $nota->valor_faturado) ? $valor_glosa : 0);
    $pagina1->setCellValue('H' . $row, ($nota->valor_pago) ? $nota->valor_pago : 0);
    $pagina1->setCellValue('I' . $row, $data_pago_formatado);
    $pagina1->setCellValue('J' . $row, ($nota->data_pagamento_previsto < $hoje and $nota->valor_pago >= 0 and $nota->valor_pago < $valor_a_receber) ? $valor_glosa : 'SEM GLOSA');
    $pagina1->setCellValue('K' . $row, ($nota->data_pagamento_previsto < $hoje and $nota->valor_pago >= 0 and $nota->valor_pago < $valor_a_receber) ? (($nota->feedback != '0000-00-00') ? $feedback_formatado : '') : 'SEM GLOSA');
    $pagina1->getStyle('B' . $row . ':C' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $pagina1->getStyle('I' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $pagina1->getStyle('D' . $row . ':H' . $row)->getNumberFormat()->setFormatCode($real);

    $pagina1->getStyle('A' . $row . ':K' . $row)->applyFromArray($negrito);
    $pagina1->getStyle('G' . $row)->getFont()->setColor(
        new \PhpOffice\PhpSpreadsheet\Style\Color(
            \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED
        )
    );

    if ($nota->data_pagamento_previsto < $hoje or $nota->valor_pago > 0 and $nota->valor_pago < $nota->valor_faturado) {
        $pagina1->getStyle('J' . $row)->getNumberFormat()->setFormatCode($real);
        $pagina1->getStyle('J' . $row)->getFont()->setColor(
            new \PhpOffice\PhpSpreadsheet\Style\Color(
                \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED
            )
        );
    } else {
        $pagina1->getStyle('J' . $row)->getFont()->setColor(
            new \PhpOffice\PhpSpreadsheet\Style\Color(
                \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLUE
            )
        );
    }

    if ($nota->data_pagamento_previsto < $hoje or $nota->valor_pago > 0 and $nota->valor_pago < $nota->valor_faturado) {
    } else {
        $pagina1->getStyle('K' . $row)->getFont()->setColor(
            new \PhpOffice\PhpSpreadsheet\Style\Color(
                \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLUE
            )
        );
    }

    $pagina1->getStyle('J' . $row . ':K' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $row++;
}
$pagina1->setCellValue('A' . $row, 'TOTAL');
$pagina1->setCellValue('D' . $row, '=SUM(D' . $row_um . ':D' . ($row - 1) . ')');
$pagina1->setCellValue('E' . $row, '=SUM(E' . $row_um . ':E' . ($row - 1) . ')');
$pagina1->setCellValue('F' . $row, '=SUM(F' . $row_um . ':F' . ($row - 1) . ')');
$pagina1->setCellValue('G' . $row, '=SUM(G' . $row_um . ':G' . ($row - 1) . ')');
$pagina1->setCellValue('H' . $row, '=SUM(H' . $row_um . ':H' . ($row - 1) . ')');
$pagina1->setCellValue('J' . $row, '=SUM(J' . $row_um . ':J' . ($row - 1) . ')');

$pagina1->getStyle('D' . $row . ':H' . $row)->getNumberFormat()->setFormatCode($real);
$pagina1->getStyle('D' . $row . ':H' . $row)->getFont()->setColor(
    new \PhpOffice\PhpSpreadsheet\Style\Color(
        \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED
    )
);
$pagina1->getStyle('J' . $row)->getNumberFormat()->setFormatCode($real);
$pagina1->getStyle('J' . $row)->getFont()->setColor(
    new \PhpOffice\PhpSpreadsheet\Style\Color(
        \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED
    )
);

$pagina1->getStyle('A' . $row . ':K' . $row)->applyFromArray($negrito);

$pagina1->getStyle('A1:K' . $row)->applyFromArray($border_black);

$pagina1->getColumnDimension('A')->setAutoSize(true);
$pagina1->getColumnDimension('B')->setAutoSize(true);
$pagina1->getColumnDimension('C')->setAutoSize(true);
$pagina1->getColumnDimension('D')->setAutoSize(true);
$pagina1->getColumnDimension('E')->setAutoSize(true);
$pagina1->getColumnDimension('F')->setAutoSize(true);
$pagina1->getColumnDimension('G')->setAutoSize(true);
$pagina1->getColumnDimension('H')->setAutoSize(true);
$pagina1->getColumnDimension('I')->setAutoSize(true);
$pagina1->getColumnDimension('J')->setAutoSize(true);
$pagina1->getColumnDimension('K')->setAutoSize(true);

$pagina1->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
$pagina1->getPageSetup()->setFitToWidth(1);
$pagina1->getPageSetup()->setFitToHeight(0);

// Define o caminho completo do diretório e nome do arquivo
$directory = __DIR__ . '/'; // Define o diretório relativo ao arquivo atual
$filename = "relatorio_competencia.xlsx";
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

// Salvar o arquivo Excel
$writer = new Xlsx($planilha);
$writer->save($filepath);

// Redirecionar para download
header('Location: /GRNacoes/views/faturamento/relatorios/' . $filename);
