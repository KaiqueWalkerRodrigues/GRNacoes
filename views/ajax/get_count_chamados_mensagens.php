<?php 
   session_start();
   
   require '../../const.php';

   $pdo = Conexao::conexao();

   $id_chamado = $_GET['id_chamado'];

   // Consultar apenas a contagem de mensagens
   $sql = $pdo->prepare("
       SELECT COUNT(*) as total_mensagens
       FROM mensagens m
       INNER JOIN chamados_mensagens cm ON cm.id_mensagem = m.id_mensagem
       WHERE cm.id_chamado = :id_chamado AND m.deleted_at IS NULL
   ");
   $sql->bindParam(':id_chamado', $id_chamado, PDO::PARAM_INT);
   $sql->execute();

   $result = $sql->fetch();
   
   // Retorna apenas o número de mensagens
   echo $result['total_mensagens'];
?>

