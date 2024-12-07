<?php

require_once 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

function formatarCPF($cpf) {
    // Remove qualquer caractere que não seja número
    $cpf = preg_replace('/\D/', '', $cpf);

    // Verifica se o CPF tem 11 dígitos
    if (strlen($cpf) !== 11) {
        return false; // CPF inválido
    }

    // Formata o CPF no padrão 000.000.000-00
    return substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
}

// Carregar os dados do contrato (substitua por seu método de recuperação de dados)
$id_contrato = $_GET['id'];
$Financeiro_Contrato = new Financeiro_Contrato();
$Usuario = new Usuario();
$contrato = $Financeiro_Contrato->mostrar($id_contrato);
$testemunha1 = $Usuario->mostrar($contrato->id_testemunha1);
$testemunha1->nome = strtoupper($testemunha1->nome);
// $testemunha2 = $Usuario->mostrar($contrato->id_testemunha2);
$testemunha1->cpf = formatarCPF($testemunha1->cpf);

// Configurar o DOMPDF
$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('defaultPaperSize', 'a4'); // Configurar o tamanho do papel
$dompdf = new Dompdf($options);

switch($contrato->id_empresa){
    case 1:
        $contrato->id_empresa = "Matriz - Santo André";
    break;
    case 2:
        $contrato->id_empresa = "FL 01 - Mauá";
    break;
    case 3:
        $contrato->id_empresa = "FL 02 - Santo André";
    break;
}

$data = $contrato->data;

function formatarDataPorExtenso($data) {
    $dataObj = new DateTime($data);

    // Traduzir os dias da semana
    $diasDaSemana = [
        'Sunday' => 'domingo',
        'Monday' => 'segunda-feira',
        'Tuesday' => 'terça-feira',
        'Wednesday' => 'quarta-feira',
        'Thursday' => 'quinta-feira',
        'Friday' => 'sexta-feira',
        'Saturday' => 'sábado',
    ];

    // Traduzir os meses
    $meses = [
        'January' => 'janeiro',
        'February' => 'fevereiro',
        'March' => 'março',
        'April' => 'abril',
        'May' => 'maio',
        'June' => 'junho',
        'July' => 'julho',
        'August' => 'agosto',
        'September' => 'setembro',
        'October' => 'outubro',
        'November' => 'novembro',
        'December' => 'dezembro',
    ];

    // Obter o dia da semana, dia do mês, mês e ano
    $diaSemana = $diasDaSemana[$dataObj->format('l')];
    $dia = $dataObj->format('j');
    $mes = $meses[$dataObj->format('F')];
    $ano = $dataObj->format('Y');

    // Retornar a data formatada
    return "$diaSemana, $dia de $mes de $ano";
}

$data = $contrato->data; // Exemplo: '2024-11-09'
$dataFormatada = formatarDataPorExtenso($data);

// Valor formatado em moeda
$valor_formatado = number_format($contrato->valor, 2, ',', '.');
$sinal_formatado = number_format($contrato->sinal_entrada, 2, ',', '.');

function numeroPorExtenso($valor) {
    $singulares = ["", "um", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove"];
    $dezenas = ["", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa"];
    $especiais = ["dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezessete", "dezoito", "dezenove"];
    $centenas = ["", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos"];
    $plural = ["", "mil", "milhões", "bilhões", "trilhões"];
    $moedaSingular = "real";
    $moedaPlural = "reais";
    $centavoSingular = "centavo";
    $centavoPlural = "centavos";

    $valor = number_format($valor, 2, '.', '');
    list($inteiro, $decimal) = explode('.', $valor);
    $inteiro = str_pad($inteiro, 3, '0', STR_PAD_LEFT);

    $texto = '';
    $posicao = 0;

    while (strlen($inteiro) > 0) {
        $grupo = substr($inteiro, -3);
        $inteiro = substr($inteiro, 0, -3);
        $grupoExtenso = '';
        $centena = (int)($grupo / 100);
        $dezena = (int)(($grupo % 100) / 10);
        $unidade = $grupo % 10;

        if ($centena) {
            $grupoExtenso .= ($centena == 1 && ($dezena > 0 || $unidade > 0)) ? "cento" : $centenas[$centena];
        }
        if ($dezena == 1) {
            $grupoExtenso .= " " . $especiais[$unidade];
        } else {
            $grupoExtenso .= $dezena > 0 ? " " . $dezenas[$dezena] : '';
            $grupoExtenso .= $unidade > 0 ? " " . $singulares[$unidade] : '';
        }

        if ($grupoExtenso) {
            $grupoExtenso .= " " . ($grupo > 1 ? $plural[$posicao] : $plural[$posicao]);
            $texto = $grupoExtenso . ($texto ? " e " . $texto : '');
        }

        $posicao++;
    }

    $texto .= " " . ($inteiro > 1 ? $moedaPlural : $moedaSingular);

    if ($decimal > 0) {
        $decimalExtenso = '';
        $dezenaDecimal = (int)($decimal / 10);
        $unidadeDecimal = $decimal % 10;

        if ($dezenaDecimal == 1) {
            $decimalExtenso .= $especiais[$unidadeDecimal];
        } else {
            $decimalExtenso .= $dezenas[$dezenaDecimal];
            $decimalExtenso .= $unidadeDecimal > 0 ? " " . $singulares[$unidadeDecimal] : '';
        }

        $texto .= " e " . $decimalExtenso . " " . ($decimal > 1 ? $centavoPlural : $centavoSingular);
    }

    return trim($texto);
}

$valor_extenso = numeroPorExtenso($contrato->valor);

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

$contrato->nome = strtoupper(removerAcentos($contrato->nome));
$contrato->endereco = strtoupper(removerAcentos($contrato->endereco));
$contrato->complemento = strtoupper(removerAcentos($contrato->complemento));
$contrato->bairro = strtoupper(removerAcentos($contrato->bairro));
$contrato->cidade = strtoupper(removerAcentos($contrato->cidade));
$valor_extenso = strtoupper($valor_extenso);
$contrato->cep = substr($contrato->cep, 0, 5) . '-' . substr($contrato->cep, 5, 3);

$parcelas = "";
$x = 1;

// Listar parcelas e montar a tabela
foreach($Financeiro_Contrato->listarParcelas($contrato->id_financeiro_contrato) as $parcela){
    $parcela->data = Helper::formatarData($parcela->data);
    $parcela->valor = number_format($parcela->valor, 2, ',', '.');

    // Iniciar nova linha para as colunas ímpares
    if($x % 2 != 0){
        $parcelas .= "<tr>";
    }

    // Adicionar a célula para o vencimento e valor
    $parcelas .= "<td>".$x."º Vencimento: $parcela->data</td>";
    $parcelas .= "<td>Valor: R$ $parcela->valor</td>";

    // Fechar a linha para as colunas pares
    if($x % 2 == 0){
        $parcelas .= "</tr>";
    }

    $x++;
}

// Completar até a 12ª parcela com células vazias
while($x <= 12){
    if($x % 2 != 0){
        $parcelas .= "<tr>";
    }

    // Adicionar células vazias
    $parcelas .= "<td>".$x."º Vencimento:</td>";
    $parcelas .= "<td>Valor:</td>";

    if($x % 2 == 0){
        $parcelas .= "</tr>";
    }

    $x++;
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
        @page {
            margin: 0;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            margin: 0;
        }
        .top-right {
            position: absolute;
            top: -25px;
            right: 25;
        }
        .info-line {
            display: flex;
            justify-content: center;
            gap: 7px;
        }
            table {
            width: 95%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 2px;
            text-align: left;
        }
    </style>
</head>
<body>
    <img class='top-right' style='width: 17%' src='https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTCNtSDda9Etg_U4vKgnN09xeZoTn5QIa7j_Q&s' alt='Logo'>
    <div class='p-3'>
        <br><br>
        <div class='text-center'>
            <b>Contrato de Crédito - Financiamento</b>
        </div>
        <br>

        <div class='text-start'>

            <div class='info-line'>
                <b>Unidade:</b> <span>$contrato->id_empresa</span>
                 | 
                <b>N° Contrato:</b> <span>$contrato->n_contrato</span>
            </div>

        </div>
        <br>

        <div class='text-center'>
            <b>Ficha de Cadastro - Pessoa Física</b> 
        </div>
        <br>

        <div class='text-start'>
            <div class='info-line'>
                <b>Nome:</b> <span>$contrato->nome</span>
                 | 
                <b>CPF</b>:</b> <span>$contrato->cpf</span>
            </div>
            
            <b>Data Nascimento</b>:</b> <span>".Helper::formatarData($contrato->data_nascimento)."</span>

            <div class='info-line'>
                <b>Endereço:</b> <span>$contrato->endereco</span>
                 | 
                <b>N°</b>:</b> <span>$contrato->numero</span>
            </div>

            <div class='info-line'>
                <b>Complemento:</b> <span>$contrato->complemento</span>
                 | 
                <b>Bairro</b>:</b> <span>$contrato->bairro</span>
            </div>

            <div class='info-line'>
                <b>Cidade:</b> <span>$contrato->cidade</span>
                 | 
                <b>UF</b>:</b> <span>$contrato->uf</span>
                 | 
                <b>CEP</b>:</b> <span>$contrato->cep</span>
            </div>

            <div class='info-line'>
                <b>Telefone Residencial</b>:</b> <span>$contrato->telefone_residencial</span>
                 | 
                <b>Telefone Comercial</b>:</b> <span>$contrato->telefone_comercial</span>
                 | 
                <b>Celular</b>:</b> <span>$contrato->celular1</span>
                 | 
                <b>Celular</b>:</b> <span>$contrato->celular2</span>
            </div>
        </div>

        <br>
        <div class='text-center'>
            <b>Termo do Contrato</b>
        </div>
        <br>
        <div>
            <span>1° O CLIENTE deseja financiar sua compra tendo como cedente a CLÍNICA DE OLHOS NAÇÕES ou quem esta segunda indicar.</span><br>
            <span>2° A CLÍNICA DE OLHOS NAÇÕES concorda em financiar o valor requerido para o CLIENTE desde que observe se as cláusulas e condições estipuladas:</span><br>
            <ul>
                <li>O CLIENTE fará o pagamento da parcela do financiamento através do boleto bancário. O CLIENTE deverá efetuar o seu pagamento em dia, para que não haja incidência de multa e juros de mora nos termos da lei e atualização monetária pela variação IGPM/(FGV).</li>
                <li>O não pagamento após o prazo limite impresso no boleto estará sujeito a cartório e eventuais despesas decorrentes de cobrança judicial ou extrajudicial, honorários advocatícios e outras, serão de total responsabilidade do CLIENTE.</li>
            </ul>
            <span>3° O CLIENTE autoriza a CLÍNICA DE OLHOS NAÇÕES a consultar as informações consolidadas relativas à sua pessoa, junto ao serviço de proteção ao crédito, ficando o financiamento sujeito a aprovação.</span><br>
            <span>4° Os exames relacionados à cirurgia serão realizados de forma particular, os quais serão arcados pelo CLIENTE no momento da contratação do serviço.</span><br>
            <span>5° O valor dos exames está embutido no valor total da cirurgia a ser realizada.</span><br>
            <span>6° Em caso de desistência da cirurgia por parte do CLIENTE, o valor dos exames realizados deverão ser pagos em sua integralidade correspondendo ao montante de R$ 1.500,00 (hum mil e quinhentos reais).</span><br>
        </div>
            <br>
        <div>
            <b>Valor do Financiamento:</b> R$ $valor_formatado ($valor_extenso)
            <br>
            <b>Sinal / Entrada:</b> R$ $sinal_formatado
        </div>

        <br>

        <div class='text-center'>
            <table>
                $parcelas
            </table>
        </div>

        <br>
        <b>Por estarem às partes, justas e contratadas assinam o presente na presença de 02 testemunhas.</b>
        <br><br>
        <span>Data: $dataFormatada</span>
        <br><br>
        <div style='margin-top: 50px; width: 100%; display: table;'>
            <div style='display: table-cell; text-align: center; width: 50%;'>
                <span>________________________________________</span><br>
                <span>$contrato->nome</span>
            </div>
            <div style='display: table-cell; text-align: center; width: 50%;'>
                <span>________________________________________</span><br>
                <span>CLINICA DE OLHOS NAÇÕES</span>
            </div>
        </div>
        <br><br>
        <div style='margin-top: 50px; width: 100%; display: table;'>
            <div style='display: table-cell; text-align: center; width: 50%;'>
                <span>________________________________________</span><br>
                <span>Testemunha 1: $testemunha1->nome</span>
                <br>
                <span>CPF: $testemunha1->cpf</span>
            </div>
            <div style='display: table-cell; text-align: center; width: 50%;'>
                <span>________________________________________</span><br>
                <span>Testemunha 2:</span>
                <br>
                <span>CPF: </span>
            </div>
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

// Enviar o PDF como download
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="contrato.pdf"');
echo $dompdf->output();
exit;