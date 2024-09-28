<?php
require 'class/Connection.php';

$id_usuario = $_GET['id_usuario'];

// Consulta SQL para contar o número de mensagens não lidas
$sql = $pdo->prepare("
    SELECT COUNT(*) as total_nao_lidas
    FROM mensagens m
    JOIN conversas_mensagens cm ON cm.id_mensagem = m.id_mensagem
    JOIN conversas c ON c.id_conversa = cm.id_conversa
    JOIN participantes p ON p.id_conversa = c.id_conversa
    LEFT JOIN mensagens_lidas ml ON ml.id_mensagem = m.id_mensagem AND ml.id_usuario = :id_usuario
    WHERE p.id_usuario = :id_usuario
    AND m.id_usuario != :id_usuario
    AND ml.id_mensagem IS NULL
    AND m.deleted_at IS NULL
    AND p.deleted_at IS NULL
");

// Bind do parâmetro
$sql->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
$sql->execute();
$result = $sql->fetch();

echo $result['total_nao_lidas']; // Retorna o número de mensagens não lidas
?>
