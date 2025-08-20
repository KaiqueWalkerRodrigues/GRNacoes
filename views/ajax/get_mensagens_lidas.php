<?php
require '../../const.php';
header('Content-Type: application/json; charset=utf-8');

try {
    $pdo = Conexao::conexao();

    $id_conversa = isset($_GET['id_conversa']) ? (int)$_GET['id_conversa'] : 0;
    $id_usuario  = isset($_GET['id_usuario'])  ? (int)$_GET['id_usuario']  : 0;

    if (empty($id_conversa) || empty($id_usuario)) {
        http_response_code(400);
        echo json_encode(['ok' => false, 'error' => 'Parâmetros obrigatórios ausentes.']);
        exit;
    }

    // Lista minhas mensagens desta conversa que tenham registro de leitura por OUTRO usuário
    $sql = $pdo->prepare("
        SELECT DISTINCT m.id_mensagem
        FROM conversas_mensagens cm
        INNER JOIN mensagens m ON m.id_mensagem = cm.id_mensagem
        INNER JOIN mensagens_lidas ml ON ml.id_mensagem = m.id_mensagem
        WHERE cm.id_conversa = :id_conversa
          AND m.deleted_at IS NULL
          AND m.id_usuario = :id_usuario           -- minhas mensagens
          AND ml.id_usuario <> :id_usuario         -- lidas por outra pessoa
    ");
    $sql->bindParam(':id_conversa', $id_conversa, PDO::PARAM_INT);
    $sql->bindParam(':id_usuario',  $id_usuario,  PDO::PARAM_INT);
    $sql->execute();

    $ids = $sql->fetchAll(PDO::FETCH_COLUMN, 0);

    echo json_encode(['ok' => true, 'ids_lidas' => array_map('intval', $ids)]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Erro interno.']);
}
