<?php
// /GRNacoes/views/ajax/list_notificacoes_chamados.php
require_once '../../const.php';
session_start();

header('Content-Type: application/json; charset=utf-8');

try {
    $id_setor   = isset($_GET['id_setor'])   ? (int)$_GET['id_setor']   : ($_SESSION['id_setor']   ?? null);
    $id_usuario = isset($_GET['id_usuario']) ? (int)$_GET['id_usuario'] : ($_SESSION['id_usuario'] ?? null);
    $limit      = isset($_GET['limit'])      ? (int)$_GET['limit']      : 10;

    // Se vier unread=1, filtra como NÃO LIDAS para o usuário da sessão (ou o enviado)
    $unread     = isset($_GET['unread']) ? (int)$_GET['unread'] : 0;
    $usuario_visualizador = $unread ? ($id_usuario ?? ($_SESSION['id_usuario'] ?? null)) : null;

    $Notificacao_Chamado = new Notificacao_Chamado();
    $lista = $Notificacao_Chamado->listar(
        $id_setor ?: null,
        $id_usuario ?: null,
        $limit,
        $usuario_visualizador ?: null // 4º parâmetro: apenas_nao_lidas_para_usuario
    );

    echo json_encode(['ok' => true, 'data' => $lista]);
    exit;

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
    exit;

}
