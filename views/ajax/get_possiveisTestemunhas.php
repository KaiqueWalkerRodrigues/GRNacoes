<?php
require_once '../../const.php';

// Define a conexão usando a classe Conexao
$pdo = Conexao::conexao();

// Verifica se o parâmetro id_empresa foi enviado
if (isset($_GET['id_empresa'])) {
    $id_empresa = $_GET['id_empresa'];

    // Consulta para listar possíveis testemunhas filtradas por empresa
    $sql = $pdo->prepare('
        SELECT u.id_usuario, u.nome
        FROM usuarios u
        INNER JOIN usuarios_setores us ON u.id_usuario = us.id_usuario
        WHERE u.deleted_at IS NULL
			AND u.ativo = 1
            AND (us.id_setor = 13 OR us.id_setor = 15)
            AND empresa = :id_empresa
        ORDER BY nome;
    ');

    $sql->bindParam(':id_empresa', $id_empresa, PDO::PARAM_INT);
    $sql->execute();

    $testemunhas = $sql->fetchAll(PDO::FETCH_ASSOC);

    // Retorna as testemunhas em formato JSON
    echo json_encode($testemunhas);
} else {
    echo json_encode(['error' => 'Parâmetro id_empresa não fornecido.']);
}
