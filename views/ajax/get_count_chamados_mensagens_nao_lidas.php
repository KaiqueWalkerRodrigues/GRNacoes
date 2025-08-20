<?php
require_once '../../const.php';
session_start();

header('Content-Type: application/json; charset=utf-8');

$id_usuario = (int)($_SESSION['id_usuario'] ?? 0);
$id_setor   = (int)($_SESSION['id_setor'] ?? 0);
$id_chamado = isset($_GET['id_chamado']) ? (int)$_GET['id_chamado'] : 0;

if ($id_usuario <= 0 || $id_setor <= 0) {
    echo json_encode(['ok' => false, 'error' => 'sem_sessao']);
    exit;
}

if ($id_chamado <= 0) {
    echo json_encode(['ok' => false, 'error' => 'param_id_chamado']);
    exit;
}

$pdo = Conexao::conexao();

$sql = $pdo->prepare("
    SELECT COUNT(*) AS qtd
    FROM mensagens m
    JOIN chamados_mensagens cm
        ON cm.id_mensagem = m.id_mensagem
    JOIN chamados ch
        ON ch.id_chamado = cm.id_chamado
    LEFT JOIN mensagens_lidas ml
        ON ml.id_mensagem = m.id_mensagem
       AND ml.id_usuario  = :id_usuario
    WHERE
        (m.deleted_at IS NULL OR m.deleted_at = 0)
        AND (ch.deleted_at IS NULL OR ch.deleted_at = 0)
        AND (
            ch.id_usuario = :id_usuario
            OR ch.id_setor = :id_setor
        )
        AND cm.id_chamado = :id_chamado
        AND ml.id_mensagem IS NULL
");

$sql->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
$sql->bindParam(':id_setor',   $id_setor,   PDO::PARAM_INT);
$sql->bindParam(':id_chamado', $id_chamado, PDO::PARAM_INT);
$sql->execute();

$row = $sql->fetch(PDO::FETCH_ASSOC);

echo json_encode([
    'ok' => true,
    'id_chamado' => $id_chamado,
    'nao_lidas' => (int)($row['qtd'] ?? 0)
]);
