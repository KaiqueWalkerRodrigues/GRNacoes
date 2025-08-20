<?php
require_once '../../const.php';
session_start();

header('Content-Type: application/json; charset=utf-8');

$id_usuario = (int)($_SESSION['id_usuario'] ?? 0);
if ($id_usuario <= 0) {
    echo json_encode(['ok' => false, 'error' => 'sem_sessao']); exit;
}

$pdo = Conexao::conexao();

/*
  CONTADOR DE NÃO LIDAS (apenas chats "normais" onde EU participo):
  - m.id_usuario <> :id_usuario       -> mensagens de OUTRAS pessoas
  - ml.id_mensagem IS NULL            -> ainda não lidas por MIM
  - INNER JOIN participantes p        -> GARANTE meu vínculo à conversa
  - INNER JOIN conversas c            -> filtra tipo de conversa
  - COUNT(DISTINCT m.id_mensagem)     -> evita duplicidade por joins
*/
$sql = $pdo->prepare("
    SELECT COUNT(DISTINCT m.id_mensagem) AS qtd
    FROM conversas_mensagens cm
    INNER JOIN mensagens m
            ON m.id_mensagem = cm.id_mensagem
           AND m.deleted_at IS NULL
    INNER JOIN conversas c
            ON c.id_conversa = cm.id_conversa
           /* ajuste conforme seus tipos válidos para chat normal */
           AND c.tipo IN ('normal', 'privado')
           /* se houver soft delete em conversas, descomente:
           AND c.deleted_at IS NULL
           */
    INNER JOIN participantes p
            ON p.id_conversa = cm.id_conversa
           AND p.id_usuario  = :id_usuario
           /* se houver soft delete em participantes, descomente:
           AND (p.deleted_at IS NULL OR p.deleted_at = '0000-00-00 00:00:00')
           */
    LEFT JOIN mensagens_lidas ml
           ON ml.id_mensagem = m.id_mensagem
          AND ml.id_usuario  = :id_usuario
    WHERE m.id_usuario <> :id_usuario
      AND ml.id_mensagem IS NULL
");

$sql->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
$sql->execute();

$row = $sql->fetch(PDO::FETCH_ASSOC);

echo json_encode([
    'ok' => true,
    'total_nao_lidas' => (int)($row['qtd'] ?? 0)
]);
