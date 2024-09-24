<?php

require '../vendor/autoload.php';
require '../const.php';

$id_campanha = $_GET['id_campanha'];
$id_vendedor = $_GET['id_vendedor'];

$Usuarios = new Usuario();
$Boletos = new Financeiro_Boletos();

// Criando uma nova planilha
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

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
    $sheet->getStyle('A1:G1')->applyFromArray($cabecalho)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
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

// Se um vendedor específico foi selecionado (id_vendedor > 0), apenas mostra os boletos desse vendedor
if ($id_vendedor > 0) {
    $vendedor = $Usuarios->mostrar($id_vendedor); // Busca os dados do vendedor específico

    // Criar uma nova aba para o vendedor
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle(getPrimeiroUltimoNome($vendedor->nome));

    // Configurar cabeçalho das colunas
    $sheet->setCellValue('A1', 'Vendedor');
    $sheet->setCellValue('B1', 'N° Boleto');
    $sheet->setCellValue('C1', 'Empresa');
    $sheet->setCellValue('D1', 'Cliente');
    $sheet->setCellValue('E1', 'Data Venda');
    $sheet->setCellValue('F1', 'Valor');
    $sheet->setCellValue('G1', 'Status');

    configurarCabecalho($sheet);

    $row = 2; // Começa a preencher os dados na linha 2

    // Iterar pelos boletos do vendedor
    foreach ($Boletos->listarBoletosPorVendedor($id_campanha, $vendedor->id_usuario) as $boleto) {
        $sheet->setCellValue('A' . $row, $vendedor->nome);
        $sheet->setCellValue('B' . $row, $boleto->n_boleto);
        $sheet->setCellValue('C' . $row, Helper::mostrar_empresa($boleto->id_empresa));
        $sheet->setCellValue('D' . $row, $boleto->cliente);
        $sheet->setCellValue('E' . $row, date("d/m/Y", strtotime($boleto->data_venda)));
        $sheet->setCellValue('F' . $row, 'R$ ' . number_format($boleto->valor, 2, ',', '.'));

        // Definir status
        if ($boleto->valor_pago == $boleto->valor) {
            $status = 'Convertido';
        } elseif ($boleto->valor_pago == 0) {
            $status = 'Pendente';
        } else {
            $status = 'Erro';
        }
        $sheet->setCellValue('G' . $row, $status);

        $row++;
    }

} else {
    // Se nenhum vendedor específico foi selecionado (id_usuario == 0), mostra de todos os vendedores
    foreach ($Usuarios->listarVendedores() as $vendedor) {
        // Criar uma nova aba para o vendedor
        if ($sheetIndex > 0) {
            $sheet = $spreadsheet->createSheet($sheetIndex);
        } else {
            $sheet = $spreadsheet->getActiveSheet();
        }

        // Definir o título da planilha com o primeiro e último nome do vendedor
        $sheet->setTitle(getPrimeiroUltimoNome($vendedor->nome));

        // Configurar cabeçalho das colunas
        $sheet->setCellValue('A1', 'Vendedor');
        $sheet->setCellValue('B1', 'N° Boleto');
        $sheet->setCellValue('C1', 'Empresa');
        $sheet->setCellValue('D1', 'Cliente');
        $sheet->setCellValue('E1', 'Data Venda');
        $sheet->setCellValue('F1', 'Valor');
        $sheet->setCellValue('G1', 'Status');

        configurarCabecalho($sheet);

        $row = 2; // Começa a preencher os dados na linha 2

        // Iterar pelos boletos do vendedor
        foreach ($Boletos->listarBoletosPorVendedor($id_campanha, $vendedor->id_usuario) as $boleto) {
            $sheet->setCellValue('A' . $row, $vendedor->nome);
            $sheet->setCellValue('B' . $row, $boleto->n_boleto);
            $sheet->setCellValue('C' . $row, Helper::mostrar_empresa($boleto->id_empresa));
            $sheet->setCellValue('D' . $row, $boleto->cliente);
            $sheet->setCellValue('E' . $row, date("d/m/Y", strtotime($boleto->data_venda)));
            $sheet->setCellValue('F' . $row, 'R$ ' . number_format($boleto->valor, 2, ',', '.'));

            // Definir status
            if ($boleto->valor_pago == $boleto->valor) {
                $status = 'Convertido';
            } elseif ($boleto->valor_pago == 0) {
                $status = 'Pendente';
            } else {
                $status = 'Erro';
            }
            $sheet->setCellValue('G' . $row, $status);

            $row++;
        }

        $sheetIndex++;
    }
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
}

// Salvar o arquivo Excel
$writer = new Xlsx($spreadsheet);
$nome_arquivo = "resumo_campanha_vendedor.xlsx";
$writer->save($nome_arquivo);

// Redirecionar para download
header('Location: ' . $nome_arquivo);
?>
