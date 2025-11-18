<?php
// Requer o autoload do Composer
require_once 'vendor/autoload.php';

// Usa as classes do Dompdf
use Dompdf\Dompdf;
use Dompdf\Options;

// --- 1. Obter Variáveis via GET ---
// Adiciona valores padrão para evitar erros
$nome_empresa = $_GET['nome_empresa'] ?? 'Empresa Não Informada';
$cnpj = $_GET['cnpj'] ?? '00.000.000/0000-00';
$nome_empregado = $_GET['nomem'] ?? 'Funcionário Não Informado';
$cpf = $_GET['cpf'] ?? '000.000.000-00';
$id_empresa = $_GET['id_empresa'] ?? ''; // Espera uma URL completa (http://...)

if($id_empresa == 1 AND $id_empresa == 3 AND $id_empresa == 5){
    $logoUrl = "logo_clinica.png";
}
if($id_empresa == 2 AND $id_empresa == 4 AND $id_empresa == 6){
    $logoUrl = "logo_otica.png";
}

// --- 2. Configurar o Dompdf (baseado no seu script funcional) ---
$options = new Options();
$options->set('isRemoteEnabled', true); // Essencial para carregar a URL do logo
$options->set('defaultPaperSize', 'a4');
$dompdf = new Dompdf($options);

// --- 3. Preparar o Logo HTML ---
// Simplesmente usa a URL passada via GET no 'src' da imagem
// Exatamente como seu script funcional faz.
$logoHtml = '';
if ($id_empresa == 1 OR $id_empresa == 3 OR $id_empresa == 5) {
$logoHtml = '<img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTCNtSDda9Etg_U4vKgnN09xeZoTn5QIa7j_Q&s" style="width: 180px; height: auto;">';
}
if ($id_empresa == 2 OR $id_empresa == 4 OR $id_empresa == 6) {
$logoHtml = '<img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTGQvCFdx73lo4L_PqQZEIKnaMpXpTUvPwiiA&s" style="width: 180px; height: auto;">';
}

// --- 4. Definir o Conteúdo HTML ---
// HTML baseado no documento "AVISO PRÈVIO DE FÉRIAS.docx"
$html = '
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style>
    body { 
        font-family: Arial, sans-serif; 
        font-size: 12pt;
    }
    .container { 
        width: 90%; 
        margin: 0 auto; 
    }
    .header { 
        text-align: center; 
        margin-bottom: 20px; 
    }
    .title { 
        font-size: 16pt; 
        font-weight: bold; 
        margin-top: 15px;
    }
    .date { 
        text-align: right; 
        margin-bottom: 30px; 
    }
    .content p { 
        line-height: 1.5;
        font-size: 12pt;
    }
    .signatures { 
        margin-top: 100px; 
        width: 100%;
    }
    /* Ajuste para usar table para alinhar assinaturas */
    .signatures table {
        width: 100%;
        border-collapse: collapse;
    }
    .signatures td {
        width: 45%;
        text-align: center;
        border-top: 1px solid #000;
        padding-top: 8px;
    }
    .signatures .spacer {
        width: 10%;
        border-top: none;
    }

</style>
</head>
<body>
    <div class="container">
        
        <div class="header">
            ' . $logoHtml . '
            <div class="title">AVISO PRÈVIO DE FÉRIAS</div>
        </div>

        <div class="date">
            Santo André, 20 de novembro de 2025.
        </div>

        <div class="content">
            <p><strong>Empregador:</strong> ' . htmlspecialchars($nome_empresa) . '<br>
               <strong>CNPJ:</strong> ' . htmlspecialchars($cnpj) . '</p>

            <p><strong>Nome do empregado:</strong> ' . htmlspecialchars($nome_empregado) . '<br>
               <strong>CPF:</strong> ' . htmlspecialchars($cpf) . '</p>
            
            <br>
            <p>Pelo presente, comunicamos que suas férias regulamentares serão concedidas
               conforme abaixo:</p>
            
            <p><strong>Período de Gozo:</strong> 22 de dezembro 2025 a 04 de janeiro de 2026.</p>
        </div>

        <div class="signatures">
            <table>
                <tr>
                    <td>' . htmlspecialchars($nome_empresa) . '</td>
                    <td class="spacer"></td>
                    <td>' . htmlspecialchars($nome_empregado) . '</td>
                </tr>
            </table>
        </div>

    </div>
</body>
</html>
';

// --- 5. Renderizar o PDF ---

// Carregar o HTML no DOMPDF
$dompdf->loadHtml($html);

// Configurar o papel (redundante se 'defaultPaperSize' foi setado, mas garante)
$dompdf->setPaper('A4', 'portrait');

// Renderiza o HTML para PDF
$dompdf->render();

// --- 6. Enviar o PDF para o Navegador (método do seu script) ---
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="aviso_ferias.pdf"');
echo $dompdf->output();
exit;

?>