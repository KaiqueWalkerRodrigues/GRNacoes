<?php
require_once '../../const.php';

// Define a conexão usando a classe Conexao
$pdo = Conexao::conexao();

// Verifica se o parâmetro id_empresa foi enviado
if (isset($_GET['id_empresa'])) {
    $id_empresa = $_GET['id_empresa'];

    // Consulta para listar possíveis testemunhas filtradas por empresa
    $sql = $pdo->prepare('
        SELECT id_usuario, nome 
        FROM usuarios 
        WHERE deleted_at IS NULL 
          AND ativo = 1 
          AND (id_setor = 13 OR id_setor = 15) 
          AND empresa = :id_empresa
        ORDER BY nome
    ');

    $sql->bindParam(':id_empresa', $id_empresa, PDO::PARAM_INT);
    $sql->execute();

    $testemunhas = $sql->fetchAll(PDO::FETCH_ASSOC);

    // Retorna as testemunhas em formato JSON
    echo json_encode($testemunhas);
} else {
    echo json_encode(['error' => 'Parâmetro id_empresa não fornecido.']);
}
