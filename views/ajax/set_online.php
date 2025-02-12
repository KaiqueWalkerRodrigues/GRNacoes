<?php

date_default_timezone_set('America/Sao_Paulo');

    require '../../const.php';

    $pdo = Conexao::conexao();

    session_start();

    $id_usuario = $_SESSION['id_usuario'];

    $agora = date("Y-m-d H:i:s");

    $sql = $pdo->prepare("UPDATE usuarios SET online_at = :online_at WHERE id_usuario = :id_usuario");
    $sql->bindParam(':id_usuario',$id_usuario);
    $sql->bindParam(':online_at',$agora);

    if($sql->execute()){
        echo 'Online';
    }

?>
