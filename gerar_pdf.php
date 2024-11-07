<?php
// Inclui o autoload do Composer
require 'vendor/tcpdf/tcpdf.php';

// Usa a classe TCPDF
use TCPDF;

// Cria uma nova instância do TCPDF
$pdf = new TCPDF();

// Define as configurações do PDF
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('GRNacoes System');
$pdf->SetTitle('comprovante_pagamento');
$pdf->SetSubject('Comprovante de Pagamento Ótica +Visão e Clínica de Olhos Nações');

// Remove cabeçalho e rodapé padrão
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Define as margens
$pdf->SetMargins(15, 0, 15);
$pdf->SetAutoPageBreak(TRUE, 10);

// Adiciona uma página
$pdf->AddPage();


// Captura a data atual
$data_pagamento = date('d/m/Y');

// Define o conteúdo HTML com estilo básico inline e logos em base64
$html = <<<EOD
<div style="text-align: center;">
    <h1 style="font-size: 24px; color: #000000; margin-top: 20px;">Comprovante de Desconto</h1>
    <h2 style="font-size: 18px; color: #000000;"><span style="color: blue">Ótica +Visão</span> e <span style="color: blue">Clínica de Olhos Nações</span></h2>

    <p>O Paciente, <b>Kaique Rodrigues de Souza</b>, realizou o pagamento com desconto conforme os detalhes abaixo:</p>

    <table style="width: 100%; text-align: left; border-collapse: collapse; margin-top: 20px;">
        <tr>
            <td style="border: 1px solid #000; padding: 8px;">Descrição do Produto</td>
            <td style="border: 1px solid #000; padding: 8px;">Óculos de Grau M.C</td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 8px;">Valor Total</td>
            <td style="border: 1px solid #000; padding: 8px;">R$ 500,00</td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 8px;">Desconto Aplicado (Convênio)</td>
            <td style="border: 1px solid #000; padding: 8px;">R$ 50,00</td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 8px;">Valor Final</td>
            <td style="border: 1px solid #000; padding: 8px;">R$ 450,00</td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 8px;">Data de Pagamento</td>
            <td style="border: 1px solid #000; padding: 8px;">$data_pagamento</td>
        </tr>
    </table>

    <p style="margin-top: 20px; font-size: 12px; color: #555;">Este é um comprovante de pagamento emitido automaticamente. Guarde-o para suas referências.</p>
</div>
EOD;

// Escreve o conteúdo HTML no PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Fecha e envia o PDF para o navegador
$pdf->Output('comprovante_pagamento.pdf', 'I');
