<?php
require '../../const.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $pdo = Conexao::conexao();

    $id_conversa = isset($_GET['id_conversa']) ? (int)$_GET['id_conversa'] : 0;

    if (empty($id_conversa)) {
        http_response_code(400);
        echo json_encode(['ok' => false, 'error' => "Parâmetro 'id_conversa' é obrigatório."]);
        exit;
    }

    // Conta apenas mensagens não deletadas da conversa
    $sql = $pdo->prepare("
        SELECT COUNT(*) AS total,
               MAX(m.id_mensagem) AS last_id,
               MAX(m.created_at) AS last_created_at
        FROM conversas_mensagens cm
        INNER JOIN mensagens m ON m.id_mensagem = cm.id_mensagem
        WHERE cm.id_conversa = :id_conversa
          AND m.deleted_at IS NULL
    ");
    $sql->bindParam(':id_conversa', $id_conversa, PDO::PARAM_INT);
    $sql->execute();

    $row = $sql->fetch(PDO::FETCH_ASSOC) ?: ['total' => 0, 'last_id' => null, 'last_created_at' => null];

    echo json_encode([
        'ok' => true,
        'total' => (int)$row['total'],
        'last_id' => $row['last_id'] ? (int)$row['last_id'] : null,
        'last_created_at' => $row['last_created_at']
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Erro interno.']);
}
