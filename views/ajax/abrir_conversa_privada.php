<?php
require_once '../../const.php';
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['ok' => false, 'error' => 'Usuário não logado']);
    exit;
}

$id_usuario = (int)$_SESSION['id_usuario'];
$id_destinatario = isset($_POST['id_destinatario']) ? (int)$_POST['id_destinatario'] : 0;

if ($id_destinatario <= 0 || $id_destinatario === $id_usuario) {
    echo json_encode(['ok' => false, 'error' => 'Destinatário inválido']);
    exit;
}

try {
    $pdo = Conexao::conexao();
    $pdo->beginTransaction();

    // 1) Procurar conversa privada já existente entre os dois
    $sql = $pdo->prepare("
        SELECT c.id_conversa
        FROM conversas c
        INNER JOIN participantes p1 ON p1.id_conversa = c.id_conversa AND p1.id_usuario = :u1
        INNER JOIN participantes p2 ON p2.id_conversa = c.id_conversa AND p2.id_usuario = :u2
        WHERE c.tipo = 'privado'
          AND (c.deleted_at IS NULL OR c.deleted_at = '0000-00-00 00:00:00')
        LIMIT 1
    ");
    $sql->execute([':u1' => $id_usuario, ':u2' => $id_destinatario]);
    $row = $sql->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        // Já existe
        $pdo->commit();
        echo json_encode(['ok' => true, 'id_conversa' => (int)$row['id_conversa']]);
        exit;
    }

    // 2) Criar conversa
    $sql = $pdo->prepare("INSERT INTO conversas (nome, tipo, created_at) VALUES (NULL, 'privado', NOW())");
    $sql->execute();
    $id_conversa = (int)$pdo->lastInsertId();

    // 3) Inserir participantes (os dois usuários)
    $sql = $pdo->prepare("
        INSERT INTO participantes (id_conversa, id_usuario, created_at)
        VALUES (:id_conversa, :u1, NOW()), (:id_conversa, :u2, NOW())
    ");
    $sql->execute([
        ':id_conversa' => $id_conversa,
        ':u1' => $id_usuario,
        ':u2' => $id_destinatario
    ]);

    $pdo->commit();
    echo json_encode(['ok' => true, 'id_conversa' => $id_conversa]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo json_encode(['ok' => false, 'error' => 'Erro interno: ' . $e->getMessage()]);
}
