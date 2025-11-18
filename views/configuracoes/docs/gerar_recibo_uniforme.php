<?php
// Requer o autoload do Composer
require_once 'vendor/autoload.php';

// Usa as classes do Dompdf
use Dompdf\Dompdf;
use Dompdf\Options;

// --- 1. Obter Variáveis via GET ---
$nome_empresa = $_GET['nome_empresa'] ?? 'Empresa Não Informada';
$cnpj = $_GET['cnpj'] ?? '00.000.000/0000-00';
$nome_empregado = $_GET['nomem'] ?? 'Funcionário Não Informado';
$cpf = $_GET['cpf'] ?? '000.000.000-00';
$id_empresa = $_GET['id_empresa'] ?? '';
$rg = $_GET['rg'] ?? '_______________';

// --- 2. Lógica da Logo (corrigida)
$logoHtml = '';

if ($id_empresa == 1 OR $id_empresa == 3 OR $id_empresa == 5) {
    $logoHtml = '<img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTCNtSDda9Etg_U4vKgnN09xeZoTn5QIa7j_Q&s" style="width: 180px; height: auto;">';
}

if ($id_empresa == 2 OR $id_empresa == 4 OR $id_empresa == 6) {
    $logoHtml = '<img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTGQvCFdx73lo4L_PqQZEIKnaMpXpTUvPwiiA&s" style="width: 180px; height: auto;">';
}

// --- 3. Configurar Dompdf ---
$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('defaultPaperSize', 'a4');
$dompdf = new Dompdf($options);

// --- 4. HTML DO RECIBO ---
$html = '
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style>
    body {
        font-family: Arial, sans-serif;
        font-size: 12pt;
        line-height: 1.6;
    }

    .container {
        width: 80%;
        margin: 0 auto;
        margin-top: 35px;
    }

    .header {
        text-align: center;
        margin-bottom: 25px;
    }

    .title-center {
        text-align: center;
        font-size: 18pt;
        font-weight: bold;
        margin-bottom: 5px;
        letter-spacing: 1px;
    }

    .subtitle-center {
        text-align: center;
        font-size: 16pt;
        font-weight: bold;
        margin-bottom: 30px;
    }

    .line-input {
        margin-bottom: 18px;
        font-size: 12pt;
    }

    .checkbox-line {
        margin-top: 25px;
        margin-bottom: 25px;
        font-size: 12pt;
    }

    .signature-area {
        margin-top: 70px;
        text-align: center;
        font-size: 12pt;
    }

    .signature-line {
        width: 60%;
        margin: 0 auto;
        border-top: 1px solid #000;
        padding-top: 5px;
        font-weight: bold;
    }

</style>
</head>
<body>

<div class="container">

    <div class="header">
        ' . $logoHtml . '
    </div>

    <div class="title-center">RECIBO DE ENTREGA</div>
    <div class="subtitle-center">DE UNIFORME</div>

    <p class="line-input">
        Eu, <b>' . htmlspecialchars($nome_empregado) . '</b>
    </p>

    <p class="line-input">
        CPF: <b>' . htmlspecialchars($cpf) . '</b>
    </p>

    <p class="line-input">
        Colaborador(a) da unidade: <b>' . htmlspecialchars($nome_empresa) . '</b>
    </p>

    <p class="checkbox-line">
        Calça (  ) &nbsp;&nbsp; Jaleco (  ) &nbsp;&nbsp; Blusa de frio (  ) &nbsp;&nbsp; Camisa polo (  )
    </p>

    <p class="line-input">
        Data de entrega ___________________.
    </p>

    <div class="signature-area">
        <div class="signature-line">' . htmlspecialchars($nome_empregado) . '</div>
    </div>

</div>

</body>
</html>
';

// --- 5. Renderizar o PDF ---
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// --- 6. Enviar ---
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="recibo_uniforme.pdf"');
echo $dompdf->output();
exit;
?>
