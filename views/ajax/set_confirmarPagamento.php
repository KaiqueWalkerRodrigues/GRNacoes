<?php
require '../../const.php';

$Financeiro_Contrato = new Financeiro_Contrato();

if (isset($_POST['id_parcela'], $_POST['usuario_logado'], $_POST['valor_pago'])) {
    $id_parcela = $_POST['id_parcela'];
    $usuario_logado = $_POST['usuario_logado'];
    $valor_pago = (float)$_POST['valor_pago'];

    try {
        $id_contrato = $Financeiro_Contrato->confirmarPagamento($id_parcela, $usuario_logado, $valor_pago);

        echo json_encode([
            'success' => true,
            'message' => 'Pagamento confirmado com sucesso!',
            'id_contrato' => $id_contrato
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Erro ao confirmar pagamento: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Dados incompletos. ID da parcela, valor pago ou usuário não fornecido.'
    ]);
}
