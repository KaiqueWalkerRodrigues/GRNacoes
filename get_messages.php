<?php
include_once('const.php');

// Verifique se o ID da conversa foi fornecido
if (isset($_GET['id'])) {
    $id_chat = $_GET['id'];

    $Mensagem = new Mensagem();

    // Busca as mensagens da conversa
    $mensagens = $Mensagem->listar($id_chat);

    // Define o cabeçalho para JSON
    header('Content-Type: application/json');

    // Retorna as mensagens como JSON
    echo json_encode($mensagens);
} else {
    // Caso o ID não seja fornecido, retornar um erro
    header('Content-Type: application/json');
    echo json_encode(["error" => "ID da conversa não fornecido"]);
}
?>
```
