<?php

require 'vendor/autoload.php';

$id_campanha = $_GET['id_campanha'];
$id_vendedor = $_GET['id_vendedor'];

$Campanha = new Financeiro_Campanha();
$Usuarios = new Usuario();
$Boletos = new Financeiro_Boleto();

$periodo_inicio = $Campanha->mostrar($id_campanha)->periodo_inicio;
$periodo_fim = $Campanha->mostrar($id_campanha)->periodo_fim;

$campanha = $Campanha->Mostrar($id_campanha);

// Criando uma nova planilha
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

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
        'startColor' => [
            'argb' => '002060',
        ],
    ],
];

$titulo = [
    'font' => [
        'bold' => true,
    ],
];

// Função para configurar o cabeçalho da planilha
function configurarCabecalho($sheet) {
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
            'startColor' => [
                'argb' => '002060',
            ],
        ],
    ];
    $sheet->getStyle('A2:G2')->applyFromArray($cabecalho)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
}

// Função auxiliar para obter primeiro e último nome
function getPrimeiroUltimoNome($nomeCompleto) {
    $nomes = explode(' ', trim($nomeCompleto));
    $primeiroNome = $nomes[0];
    $ultimoNome = $nomes[count($nomes) - 1];
    return $primeiroNome . ' ' . $ultimoNome;
}

// Criação de uma nova planilha
$spreadsheet = new Spreadsheet();

if ($id_vendedor > 0) {
    // Se um vendedor específico foi selecionado (id_vendedor > 0)
    $vendedor = $Usuarios->mostrar($id_vendedor); // Busca os dados do vendedor específico

    // Verificar se o vendedor possui boletos
    $boletosVendedor = $Boletos->listarBoletosPorVendedor($id_campanha, $vendedor->id_usuario);

    $total_convertido = 0;
    $total_pendente = 0;
    $total = 0;

    if (!empty($boletosVendedor)) {
        // Criar uma nova aba para o vendedor
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle(getPrimeiroUltimoNome($vendedor->nome));

        $row = 1;

        $sheet->setCellValue('A'.$row,$campanha->nome);
        $sheet->getStyle('A'.$row)->applyFromArray($titulo);
        $sheet->getStyle('A'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $periodo_inicio = date("d/m/Y", strtotime($campanha->periodo_inicio));
        $periodo_fim = date("d/m/Y", strtotime($campanha->periodo_fim));
        $periodo = "$periodo_inicio - $periodo_fim";
        
        $sheet->setCellValue('B'.$row, 'PERÍODO:');
        $sheet->getStyle('B'.$row)->applyFromArray($titulo);
        $sheet->getStyle('B'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $sheet->setCellValue('C'.$row,$periodo);
        $sheet->getStyle('C'.$row)->applyFromArray($titulo);

        $sheet->setCellValue('D'.$row, 'PAGAMENTO:');
        $sheet->getStyle('D'.$row)->applyFromArray($titulo);
        $sheet->getStyle('D'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $data_pagamento = date("d/m/Y", strtotime($campanha->data_pagamento));
        $sheet->setCellValue('E'.$row, $data_pagamento);
        $sheet->getStyle('E'.$row)->applyFromArray($titulo);

        $sheet->setCellValue('F'.$row, 'PAGAMENTO PÓS:');
        $sheet->getStyle('F'.$row)->applyFromArray($titulo);
        $sheet->getStyle('F'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $data_pagamento_pos = date("d/m/Y", strtotime($campanha->data_pagamento_pos));
        $sheet->setCellValue('G'.$row, $data_pagamento_pos);
        $sheet->getStyle('G'.$row)->applyFromArray($titulo);

        $row++;

        // Configurar cabeçalho das colunas
        $sheet->setCellValue('A'.$row, 'Vendedor');
        $sheet->setCellValue('B'.$row, 'N° Boleto');
        $sheet->setCellValue('C'.$row, 'Empresa');
        $sheet->setCellValue('D'.$row, 'Cliente');
        $sheet->setCellValue('E'.$row, 'Data Venda');
        $sheet->setCellValue('F'.$row, 'Valor');
        $sheet->setCellValue('G'.$row, 'Status');

        $row++;

        configurarCabecalho($sheet);

        // Iterar pelos boletos do vendedor
        foreach ($boletosVendedor as $boleto) {
            $sheet->setCellValue('A' . $row, $vendedor->nome);
            $sheet->setCellValue('B' . $row, $boleto->n_boleto);
            $sheet->setCellValue('C' . $row, Helper::mostrar_empresa($boleto->id_empresa));
            $sheet->setCellValue('D' . $row, $boleto->cliente);
            $sheet->setCellValue('E' . $row, date("d/m/Y", strtotime($boleto->data_venda)));
            $sheet->setCellValue('F' . $row, 'R$ ' . number_format($boleto->valor, 2, ',', '.'));

            // Definir status
            if ($boleto->valor_pago == $boleto->valor) {
                // Verificar se o pagamento foi feito após o período
                if ($boleto->data_pago > $campanha->periodo_fim) {
                    $status = 'PÓS';
                } else {
                    $status = 'Convertido';
                }
                $total_convertido += $boleto->valor_pago;
            } elseif ($boleto->valor_pago == 0) {
                // Verificar se o boleto está atrasado
                if (strtotime($boleto->data_venda) < strtotime($campanha->periodo_fim) && strtotime(date('Y-m-d')) > strtotime($campanha->periodo_fim)) {
                    $status = 'Atrasado';
                    $total_pendente += $boleto->valor;
                } else {
                    $status = 'Pendente';
                    $total_pendente += $boleto->valor;
                }
            } else {
                $status = 'Erro';
            }
            $sheet->setCellValue('G' . $row, $status);

            $row++;
        }

        // AutoSize
        $sheet->getStyle('A1:G' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        {
            $sheet->getColumnDimension('A')->setAutoSize(true);
            $sheet->getColumnDimension('B')->setAutoSize(true);
            $sheet->getColumnDimension('C')->setAutoSize(true);
            $sheet->getColumnDimension('D')->setAutoSize(true);
            $sheet->getColumnDimension('E')->setAutoSize(true);
            $sheet->getColumnDimension('F')->setAutoSize(true);
            $sheet->getColumnDimension('G')->setAutoSize(true);
        }

        // Total Pendente
        $sheet->setCellValue('F' . $row, 'Total Pendente:');
        $sheet->setCellValue('G' . $row, 'R$ ' . number_format($total_pendente, 2, ',', '.'));
        // Aplicando a formatação do cabeçalho para os totais
        $sheet->getStyle('F' . $row . ':G' . $row)->applyFromArray($cabecalho)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $row++;

        // Total Convertido
        $sheet->setCellValue('F' . $row, 'Total Convertido:');
        $sheet->setCellValue('G' . $row, 'R$ ' . number_format($total_convertido, 2, ',', '.'));
        // Aplicando a formatação do cabeçalho para os totais
        $sheet->getStyle('F' . $row . ':G' . $row)->applyFromArray($cabecalho)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $row++;

        $total = $total_convertido + $total_pendente;

        // Total Geral
        $sheet->setCellValue('F' . $row, 'Total:');
        $sheet->setCellValue('G' . $row, 'R$ ' . number_format($total, 2, ',', '.'));
        // Aplicando a formatação do cabeçalho para os totais
        $sheet->getStyle('F' . $row . ':G' . $row)->applyFromArray($cabecalho)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);
    }
} else {
    // Se nenhum vendedor específico foi selecionado, mostra todos os vendedores
    $sheetIndex = 0;

    foreach ($Usuarios->listarVendedores() as $vendedor) {

        $total_convertido = 0;
        $total_pendente = 0;
        $total = 0;

        // Verificar se o vendedor possui boletos
        $boletosVendedor = $Boletos->listarBoletosPorVendedor($id_campanha, $vendedor->id_usuario);

        if (!empty($boletosVendedor)) {
            // Criar uma nova aba para o vendedor
            if ($sheetIndex > 0) {
                $sheet = $spreadsheet->createSheet($sheetIndex);
            } else {
                $sheet = $spreadsheet->getActiveSheet();
            }

            // Definir o título da planilha com o primeiro e último nome do vendedor
            $sheet->setTitle(getPrimeiroUltimoNome($vendedor->nome));

            $row = 1;

            $sheet->setCellValue('A'.$row,$campanha->nome);
            $sheet->getStyle('A'.$row)->applyFromArray($titulo);
            $sheet->getStyle('A'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            
            $periodo_inicio = date("d/m/Y", strtotime($campanha->periodo_inicio));
            $periodo_fim = date("d/m/Y", strtotime($campanha->periodo_fim));
            $periodo = "$periodo_inicio - $periodo_fim";
            
            $sheet->setCellValue('C'.$row, 'PERÍODO:');
            $sheet->getStyle('C'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle('C'.$row)->applyFromArray($titulo);

            $sheet->setCellValue('D'.$row,$periodo);
            $sheet->getStyle('D'.$row)->applyFromArray($titulo);

            $sheet->setCellValue('E'.$row, 'PAGAMENTO:');
            $sheet->getStyle('C'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle('E'.$row)->applyFromArray($titulo);

            $data_pagamento = date("d/m/Y", strtotime($campanha->data_pagamento));
            $sheet->setCellValue('F'.$row, $data_pagamento);
            $sheet->getStyle('F'.$row)->applyFromArray($titulo);

            $row++;

            // Configurar cabeçalho das colunas
            $sheet->setCellValue('A'.$row, 'Vendedor');
            $sheet->setCellValue('B'.$row, 'N° Boleto');
            $sheet->setCellValue('C'.$row, 'Empresa');
            $sheet->setCellValue('D'.$row, 'Cliente');
            $sheet->setCellValue('E'.$row, 'Data Venda');
            $sheet->setCellValue('F'.$row, 'Valor');
            $sheet->setCellValue('G'.$row, 'Status');

            $row++;

            configurarCabecalho($sheet);

            // Iterar pelos boletos do vendedor
            foreach ($boletosVendedor as $boleto) {
                $sheet->setCellValue('A' . $row, $vendedor->nome);
                $sheet->setCellValue('B' . $row, $boleto->n_boleto);
                $sheet->setCellValue('C' . $row, Helper::mostrar_empresa($boleto->id_empresa));
                $sheet->setCellValue('D' . $row, $boleto->cliente);
                $sheet->setCellValue('E' . $row, date("d/m/Y", strtotime($boleto->data_venda)));
                $sheet->setCellValue('F' . $row, 'R$ ' . number_format($boleto->valor, 2, ',', '.'));

                // Definir status
                if ($boleto->valor_pago == $boleto->valor) {
                    // Verificar se o pagamento foi feito após o período
                    if ($boleto->data_pago > $campanha->periodo_fim) {
                        $status = 'PÓS';
                    } else {
                        $status = 'Convertido';
                    }
                    $total_convertido += $boleto->valor_pago;
                } elseif ($boleto->valor_pago == 0) {
                    // Verificar se o boleto está atrasado
                    if (strtotime($boleto->data_venda) < strtotime($campanha->periodo_fim) && strtotime(date('Y-m-d')) > strtotime($campanha->periodo_fim)) {
                        $status = 'Atrasado';
                        $total_pendente += $boleto->valor;
                    } else {
                        $status = 'Pendente';
                        $total_pendente += $boleto->valor;
                    }
                } else {
                    $status = 'Erro';
                }
                $sheet->setCellValue('G' . $row, $status);

                $row++;
            }

            // Total Pendente
            $sheet->setCellValue('F' . $row, 'Total Pendente:');
            $sheet->setCellValue('G' . $row, 'R$ ' . number_format($total_pendente, 2, ',', '.'));
            // Aplicando a formatação do cabeçalho para os totais
            $sheet->getStyle('F' . $row . ':G' . $row)->applyFromArray($cabecalho)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $row++;

            // Total Convertido
            $sheet->setCellValue('F' . $row, 'Total Convertido:');
            $sheet->setCellValue('G' . $row, 'R$ ' . number_format($total_convertido, 2, ',', '.'));
            // Aplicando a formatação do cabeçalho para os totais
            $sheet->getStyle('F' . $row . ':G' . $row)->applyFromArray($cabecalho)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $row++;

            $total = $total_convertido + $total_pendente;

            // Total Geral
            $sheet->setCellValue('F' . $row, 'Total:');
            $sheet->setCellValue('G' . $row, 'R$ ' . number_format($total, 2, ',', '.'));
            // Aplicando a formatação do cabeçalho para os totais
            $sheet->getStyle('F' . $row . ':G' . $row)->applyFromArray($cabecalho)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
            $sheet->getPageSetup()->setFitToWidth(1);
            $sheet->getPageSetup()->setFitToHeight(0);

            $sheetIndex++;
            // AutoSize
            $sheet->getStyle('A1:G' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            {
                $sheet->getColumnDimension('A')->setAutoSize(true);
                $sheet->getColumnDimension('B')->setAutoSize(true);
                $sheet->getColumnDimension('C')->setAutoSize(true);
                $sheet->getColumnDimension('D')->setAutoSize(true);
                $sheet->getColumnDimension('E')->setAutoSize(true);
                $sheet->getColumnDimension('F')->setAutoSize(true);
                $sheet->getColumnDimension('G')->setAutoSize(true);
            }
        }
    }
}
// Define o caminho completo do diretório e nome do arquivo
$directory = __DIR__.'/'; // Define o diretório relativo ao arquivo atual
$filename = "resumo_campanha_vendedor.xlsx";
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
$writer = new Xlsx($spreadsheet);
$writer->save($filepath);

// Redirecionar para download
header('Location: /GRNacoes/views/financeiro/relatorios/'.$filename);
?>
