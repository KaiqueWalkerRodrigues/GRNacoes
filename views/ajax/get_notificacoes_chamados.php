<?php
// /GRNacoes/views/ajax/get_notificacoes_chamados.php
require_once '../../const.php';
session_start();

header('Content-Type: application/json; charset=utf-8');

try {
    $id_setor   = isset($_GET['id_setor'])   ? (int)$_GET['id_setor']   : ($_SESSION['id_setor']   ?? null);
    $id_usuario = isset($_GET['id_usuario']) ? (int)$_GET['id_usuario'] : ($_SESSION['id_usuario'] ?? null);
    $limit      = isset($_GET['limit'])      ? (int)$_GET['limit']      : 10;

    $unread     = isset($_GET['unread']) ? (int)$_GET['unread'] : 0;
    $usuario_visualizador = $unread ? ($id_usuario ?? ($_SESSION['id_usuario'] ?? null)) : null;

    $Notificacao_Chamado = new Notificacao_Chamado();

    // Lista limitada (como já fazia)
    $lista = $Notificacao_Chamado->listar(
        $id_setor ?: null,
        $id_usuario ?: null,
        $limit,
        $usuario_visualizador ?: null
    );

    $total_unread = null;
    if ($unread && !empty($usuario_visualizador)) {
        $total_unread = $Notificacao_Chamado->contarNaoLidas(
            (int)$usuario_visualizador,
            $id_setor ?: null,
            $id_usuario ?: null
        );
    }

    echo json_encode([
        'ok' => true,
        'data' => $lista,
        'total_unread' => $total_unread,
    ]);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
    exit;
}
