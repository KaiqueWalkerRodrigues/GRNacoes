<?php

require '../../../../const.php';

$pdo = Conexao::conexao();

$Captacao = new Captacao();

$id_empresa = isset($_GET['id_empresa']) ? $_GET['id_empresa'] : null;

$motivosMaisUtilizados = $Captacao->listarMotivosMaisUtilizados($id_empresa);

$resultado = [];

foreach ($motivosMaisUtilizados as $motivo) {
    $resultado[] = [
        'label' => Helper::Motivo($motivo->id_motivo),
        'total' => $motivo->total
    ];
}

header('Content-Type: application/json');
echo json_encode($resultado);
