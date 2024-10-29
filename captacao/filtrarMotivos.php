<?php
require '../class/Connection.php';
require '../class/Conexao.php';
require '../class/Captacao.php';
require '../class/Helper.php';

$Captacao = new Captacao();

// Verifica se o id_empresa foi enviado
$id_empresa = isset($_GET['id_empresa']) && $_GET['id_empresa'] != 0 ? $_GET['id_empresa'] : null;

// Pega os motivos mais utilizados com base na empresa selecionada
$motivosMaisUtilizados = $Captacao->listarMotivosMaisUtilizados($id_empresa);

// Verifica se existem motivos
if (count($motivosMaisUtilizados) > 0) {
    // Extraímos os valores de 'total' para calcular o máximo
    $totais = array_column($motivosMaisUtilizados, 'total');
    
    // Verifica se há valores em $totais e calcula o máximo, ou define como 1 para evitar divisão por zero
    $maxTotal = !empty($totais) ? max($totais) : 1;

    foreach ($motivosMaisUtilizados as $motivo) {
        ?>
        <h4 class="small font-weight-bold">
            <?php Helper::Motivo($motivo->id_motivo); ?>
            <span class="float-right"><?php echo $motivo->total; ?></span>
        </h4>
        <div class="progress mb-4">
            <div class="progress-bar bg-dark" role="progressbar" style="width: <?php echo ($motivo->total / $maxTotal) * 100; ?>%" aria-valuenow="<?php echo $motivo->total; ?>" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <?php
    }
} else {
    echo "<p>Nenhum motivo registrado.</p>";
}
?>