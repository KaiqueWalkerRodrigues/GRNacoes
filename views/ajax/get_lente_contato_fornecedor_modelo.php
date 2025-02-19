<?php
require_once '../../const.php';

$pdo = Conexao::conexao();

if (isset($_GET['id_modelo'])) {
    $id_modelo = $_GET['id_modelo'];

    // Consulta para buscar o id_fornecedor com base no id_modelo
    $stmt = $pdo->prepare("SELECT id_fornecedor FROM lente_contato_modelos WHERE id_lente_contato_modelo = :id_modelo AND deleted_at IS NULL");
    $stmt->bindParam(':id_modelo', $id_modelo, PDO::PARAM_INT);
    $stmt->execute();
    $modelo = $stmt->fetch(PDO::FETCH_OBJ);

    if ($modelo) {
        // Retorna o id_fornecedor em formato JSON
        echo json_encode(["id_fornecedor" => $modelo->id_fornecedor]);
    } else {
        // Retorna erro se o modelo não for encontrado
        echo json_encode(["error" => "Modelo não encontrado."]);
    }
} else {
    // Retorna erro se o parâmetro id_modelo não for passado
    echo json_encode(["error" => "Parâmetros inválidos."]);
}
?>