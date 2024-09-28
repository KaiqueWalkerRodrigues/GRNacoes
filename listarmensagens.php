<?php
include_once('const.php');
include_once('class/Mensagens.php');

$Mensagem = new Mensagem();

if (isset($_GET['id'])) {
    $id_chat = $_GET['id'];
    $mensagens = $Mensagem->listar($id_chat,$_SESSION['id_usuario']);
    echo json_encode($mensagens);
}
?>
