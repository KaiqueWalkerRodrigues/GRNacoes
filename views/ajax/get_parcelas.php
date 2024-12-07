<?php
    require '../../const.php';

    $Financeiro_Contrato = new Financeiro_Contrato();

    if (isset($_GET['id_financeiro_contrato'])) {
        $idContrato = $_GET['id_financeiro_contrato'];
        $parcelas = $Financeiro_Contrato->listarParcelas($idContrato);

        if ($parcelas) {
            echo json_encode([
                'success' => true,
                'parcelas' => $parcelas
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Nenhuma parcela encontrada.'
            ]);
        }
        exit;
    }else{
        echo '<b>Nenhum contrato Selecionado</b>';
    }