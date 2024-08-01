<?php
include_once('../const.php');

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dados = [
        'titulo' => $_POST['titulo'],
        'empresa' => $_POST['empresa'],
        'setor' => $_POST['setor'],
        'urgencia' => $_POST['urgencia'],
        'link' => $_POST['link'],
        'descricao' => $_POST['descricao'],
        'usuario_logado' => $_SESSION['id_usuario'] // Supondo que você tenha uma variável de sessão para o usuário logado
    ];

    $comprasPedidos = new Compras_Pedidos();
    $comprasPedidos->cadastrar($dados);
}
?>
