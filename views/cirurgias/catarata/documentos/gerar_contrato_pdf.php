<?php

require_once 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Carregar os dados do contrato (substitua por seu método de recuperação de dados)
$id_paciente = $_GET['id_paciente'];
$nome_paciente = $_GET['nome_paciente'];

// Configurar o DOMPDF
$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('defaultPaperSize', 'a4'); // Configurar o tamanho do papel
$dompdf = new Dompdf($options);

function removerAcentos($string) {
    $mapa = [
        'á' => 'a', 'à' => 'a', 'ã' => 'a', 'â' => 'a', 'ä' => 'a',
        'Á' => 'A', 'À' => 'A', 'Ã' => 'A', 'Â' => 'A', 'Ä' => 'A',
        'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
        'É' => 'E', 'È' => 'E', 'Ê' => 'E', 'Ë' => 'E',
        'í' => 'i', 'ì' => 'i', 'î' => 'i', 'ï' => 'i',
        'Í' => 'I', 'Ì' => 'I', 'Î' => 'I', 'Ï' => 'I',
        'ó' => 'o', 'ò' => 'o', 'õ' => 'o', 'ô' => 'o', 'ö' => 'o',
        'Ó' => 'O', 'Ò' => 'O', 'Õ' => 'O', 'Ô' => 'O', 'Ö' => 'O',
        'ú' => 'u', 'ù' => 'u', 'û' => 'u', 'ü' => 'u',
        'Ú' => 'U', 'Ù' => 'U', 'Û' => 'U', 'Ü' => 'U',
        'ç' => 'c', 'Ç' => 'C',
        'ñ' => 'n', 'Ñ' => 'N'
    ];
    return strtr($string, $mapa);
}

// Conteúdo do contrato (HTML gerado dinamicamente com os dados)
$html = "
<!DOCTYPE html>
<html lang='pt-br'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH' crossorigin='anonymous'>
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js' integrity='sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz' crossorigin='anonymous'></script>
    <style>
    </style>
</head>
<body>
    <img class='top-right' style='width: 30%; margin-top: -10%;margin-bottom:-13%' src='https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTCNtSDda9Etg_U4vKgnN09xeZoTn5QIa7j_Q&s' alt='Logo'>
    <div class='p-3'>
        <div class='text-center'>
            <b style='font-size: 22px;'>VALE INDICAÇÃO</b>
        </div>
        <br>
        <div class='text-start'>
            <b style='font-size: 18px;'>NOME DO PACIENTE:</b>&nbsp;<span style='font-size: 16px;'>".strtoupper($nome_paciente)."</span>
        </div>
        <div class='text-start mt-3'>
            <b style='font-size: 18px;'>INDICAÇÃO:</b><span></span>
        </div>
        <br>
        <div class='text-end'>
            <div style='font-size: 18px;margin-right:5%;'><b>CÓDIGO:</b>&nbsp;<span style='font-size: 16px;'>".strtoupper($id_paciente)."</span></div>
        </div>
    </div>
    <br>
    <br>
    <br>
    <br>
    <br>
    <img class='top-right' style='width: 30%; margin-top: -10%;margin-bottom:-13%' src='https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTCNtSDda9Etg_U4vKgnN09xeZoTn5QIa7j_Q&s' alt='Logo'>
    <div class='p-3'>
        <div class='text-center'>
            <b style='font-size: 22px;'>VALE INDICAÇÃO</b>
        </div>
        <br>
        <div class='text-start'>
            <b style='font-size: 18px;'>NOME DO PACIENTE:</b>&nbsp;<span style='font-size: 16px;'>".strtoupper($nome_paciente)."</span>
        </div>
        <div class='text-start mt-3'>
            <b style='font-size: 18px;'>INDICAÇÃO:</b><span></span>
        </div>
        <br>
        <div class='text-end'>
            <div style='font-size: 18px;margin-right:5%;'><b>CÓDIGO:</b>&nbsp;<span style='font-size: 16px;'>".strtoupper($id_paciente)."</span></div>
        </div>
    </div>
    <br>
    <br>
    <br>
    <br>
    <br>
    <img class='top-right' style='width: 30%; margin-top: -10%;margin-bottom:-13%' src='https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTCNtSDda9Etg_U4vKgnN09xeZoTn5QIa7j_Q&s' alt='Logo'>
    <div class='p-3'>
        <div class='text-center'>
            <b style='font-size: 22px;'>VALE INDICAÇÃO</b>
        </div>
        <br>
        <div class='text-start'>
            <b style='font-size: 18px;'>NOME DO PACIENTE:</b>&nbsp;<span style='font-size: 16px;'>".strtoupper($nome_paciente)."</span>
        </div>
        <div class='text-start mt-3'>
            <b style='font-size: 18px;'>INDICAÇÃO:</b><span></span>
        </div>
        <br>
        <div class='text-end'>
            <div style='font-size: 18px;margin-right:5%;'><b>CÓDIGO:</b>&nbsp;<span style='font-size: 16px;'>".strtoupper($id_paciente)."</span></div>
        </div>
    </div>
</body>
</html>
";

// Carregar o HTML no DOMPDF
$dompdf->loadHtml($html);

// Configurar o papel e renderizar
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Enviar o PDF para ser exibido no navegador
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="vale_indicacao.pdf"');
echo $dompdf->output();
exit;
