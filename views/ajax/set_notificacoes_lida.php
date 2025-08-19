<?php
// /GRNacoes/views/ajax/set_notificacoes_lida.php
require_once '../../const.php';
session_start();

header('Content-Type: application/json; charset=utf-8');

try {
    $id_notificacao = isset($_REQUEST['id_notificacao']) ? (int)$_REQUEST['id_notificacao'] : null;

    // Tenta pegar do request; se não tiver, usa sessão
    $id_usuario = isset($_REQUEST['id_usuario']) ? (int)$_REQUEST['id_usuario'] : null;
    if (empty($id_usuario) && !empty($_SESSION['id_usuario'])) {
        $id_usuario = (int)$_SESSION['id_usuario'];
    }

    if (empty($id_notificacao)) {
        http_response_code(400);
        echo json_encode(['ok' => false, 'error' => "Parâmetro 'id_notificacao' é obrigatório."]);
        exit;
    }

    if (empty($id_usuario)) {
        http_response_code(400);
        echo json_encode(['ok' => false, 'error' => "Não foi possível identificar o 'id_usuario'. Envie no request ou mantenha sessão ativa."]);
        exit;
    }

    $Notificacao = new Notificacao_Chamado();
    $usuario_logado = isset($_SESSION['id_usuario']) ? (int)$_SESSION['id_usuario'] : $id_usuario;

    $Notificacao->marcarComoLida($id_notificacao, $id_usuario, $usuario_logado);

    echo json_encode(['ok' => true, 'message' => 'Notificação marcada como lida.']);
    exit;

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
    exit;
}
