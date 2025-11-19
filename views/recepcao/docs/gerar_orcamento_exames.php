<?php
require_once 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// =========================
// PEGAR EXAMES SELECIONADOS
// =========================

$Exame = new Exame();
$listaExames = $Exame->listar();

$selecionados = $_GET['exames'] ?? [];
$tipo = $_GET['tipo'] ?? 'particular';

$nomePaciente = ucwords(strtolower($_GET['nome'])) ?? '';
$dataHoje = date('d/m/Y');

$examesFiltrados = [];
$total = 0;

// Pacotes selecionados
$pacotesSelecionados = $_GET['pacotes'] ?? [];

$PacoteExame = new Pacote_Exame();

$pacotesFiltrados = [];
$examesEmPacotes = [];

// Monta dados dos pacotes
foreach ($pacotesSelecionados as $idPacote) {

    $idPacote = (int)$idPacote;

    // busca o pacote
    $sqlPacote = $PacoteExame->pdo->prepare('SELECT * FROM exames_pacotes WHERE id_exames_pacote = :id AND deleted_at IS NULL');
    $sqlPacote->bindParam(':id', $idPacote, PDO::PARAM_INT);
    $sqlPacote->execute();
    $pacote = $sqlPacote->fetch(PDO::FETCH_OBJ);

    if (!$pacote) {
        continue;
    }

    // exames que fazem parte do pacote
    $idsExamesPacote = $PacoteExame->listarExamesDoPacote($idPacote);
    $examesEmPacotes = array_merge($examesEmPacotes, $idsExamesPacote);

    // nomes dos exames para listar embaixo do pacote
    $nomesExamesPacote = [];
    foreach ($listaExames as $ex) {
        if (in_array($ex->id_exame, $idsExamesPacote)) {
            $nomesExamesPacote[] = $ex->exame;
        }
    }

    // valor do pacote (aqui estou usando valor_fidelidade como base)
    $valorPacote = ($tipo == 'fidelidade')
        ? floatval(str_replace(',', '.', $pacote->valor_fidelidade))
        : (isset($pacote->valor_particular)
            ? floatval(str_replace(',', '.', $pacote->valor_particular))
            : floatval(str_replace(',', '.', $pacote->valor_fidelidade)));

    $pacotesFiltrados[] = [
        'nome'   => $pacote->pacote,
        'valor'  => number_format($valorPacote, 2, ',', '.'),
        'exames' => $nomesExamesPacote,
    ];

    $total += $valorPacote;
}

// ids de exames que estão dentro de algum pacote
$examesEmPacotes = array_unique($examesEmPacotes);


// Filtrar os exames escolhidos (apenas os que NÃO estão dentro de pacotes)
foreach ($listaExames as $ex) {

    if (in_array($ex->id_exame, $selecionados) && !in_array($ex->id_exame, $examesEmPacotes)) {

        $valor = ($tipo == 'fidelidade')
            ? floatval(str_replace(',', '.', $ex->valor_fidelidade))
            : floatval(str_replace(',', '.', $ex->valor_particular));

        $examesFiltrados[] = [
            'nome'  => $ex->exame,
            'valor' => number_format($valor, 2, ',', '.')
        ];

        $total += $valor;
    }
}


// =========================
// ENDEREÇO POR EMPRESA
// =========================

$empresa = $_SESSION['id_empresa'] ?? 1;

switch ($empresa) {
    case 1:
    case 2:
        $endereco = "Rua Oratório 2036, Parque das Nações — Santo André — SP";
        break;

    case 3:
    case 4:
        $endereco = "R. Princesa Isabel, 256 — Vila Bocaina — Mauá — SP";
        break;

    case 5:
    case 6:
        $endereco = "R. Mal. Hermes, 302 — Jardim — Santo André — SP — 09090-230";
        break;

    default:
        $endereco = "Endereço não cadastrado";
        break;
}

// =========================
// LOGO
// =========================

$logo = '<img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTCNtSDda9Etg_U4vKgnN09xeZoTn5QIa7j_Q&s"
         style="width:170px; margin-bottom:10px;">';

// =========================
// HTML DO PDF (modelo modernizado)
// =========================

$html = '
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>

body {
    font-family: Arial, sans-serif;
    font-size: 12pt;
    color: #333;
}

.container {
    width: 90%;
    margin: 0 auto;
}

.header {
    text-align: center;
    margin-bottom: 15px;
}

.header-info {
    width: 100%;
    margin-top: 5px;
    font-size: 12pt;
}

.header-info .nome {
    float: left;
}

.nome{
    margin-bottom: 20px;
}

.header-info .data {
    float: right;
}

.title {
    font-size: 18pt;
    font-weight: bold;
    margin-top: 20px;
    text-transform: uppercase;
    clear: both;
}

.aviso {
    color: red;
    text-align: center;
    font-weight: bold;
    margin: 10px 0 20px 0;
    font-size: 12pt;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 5px;
}

th {
    background: #f0f0f0;
    padding: 8px;
    text-align: left;
    border-bottom: 2px solid #ccc;
}

td {
    padding: 8px;
    border-bottom: 1px solid #ddd;
}

.total {
    text-align: right;
    font-size: 14pt;
    font-weight: bold;
    margin-top: 20px;
}

.parcelado {
    margin-top: 10px;
    margin-left: 15%;
    font-size: 10pt;
    font-style: italic;
}

.footer {
    text-align: center;
    margin-top: 35px;
    font-size: 11pt;
    color: #444;
}

</style>
</head>

<body>
<div class="container">

    <div class="header">
    ' . $logo . '

        <div class="header-info">
            <span class="nome">Nome: ' . htmlspecialchars($nomePaciente) . '</span>
            <span class="data">Data: ' . $dataHoje . '</span>
        </div>

        <div class="title">Orçamento de Exames</div>
    </div>


    <div class="aviso">
        ESTE DOCUMENTO NÃO É UM PEDIDO MÉDICO — APENAS UM ORÇAMENTO.<br>
        VALIDADE DE 30 DIAS.
    </div>

    <table>
        <tr>
            <th>Descrição</th>
            <th style="text-align:right;">Valor (R$)</th>
        </tr>';

// PACOTES
foreach ($pacotesFiltrados as $p) {
    $html .= '
        <tr>
            <td><strong>Pacote: ' . htmlspecialchars($p["nome"]) . '</strong></td>
            <td style="text-align:right;"><strong>' . $p["valor"] . '</strong></td>
        </tr>';

    // exames dentro do pacote (sem valor)
    foreach ($p['exames'] as $nomeExamePacote) {
        $html .= '
        <tr>
            <td style="padding-left:25px;">- ' . htmlspecialchars($nomeExamePacote) . '</td>
            <td></td>
        </tr>';
    }
}

// EXAMES AVULSOS (fora de pacotes)
if (!empty($examesFiltrados)) {
    $html .= '
        <tr>
            <td colspan="2" style="padding-top:10px;"><strong>Exames Avulsos</strong></td>
        </tr>';

    foreach ($examesFiltrados as $e) {
        $html .= '
        <tr>
            <td>' . htmlspecialchars($e["nome"]) . '</td>
            <td style="text-align:right;">' . $e["valor"] . '</td>
        </tr>';
    }
}

$html .= '
    </table>

    <div class="total">
        Total: R$ ' . number_format($total, 2, ',', '.') . '
    </div>

    <div class="parcelado">
        *Aceitamos pagamento parcelado no cartão de crédito, consulte disponibilidade.
    </div>

    <div class="footer">
        ' . $endereco . '<br>
        Telefone e WhatsApp para agendamentos: (11) 4478-2828
    </div>

</div>
</body>
</html>
';

// =========================
// GERAR PDF
// =========================

$options = new Options();
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Exibir no navegador
header("Content-Type: application/pdf");
echo $dompdf->output();
exit;
