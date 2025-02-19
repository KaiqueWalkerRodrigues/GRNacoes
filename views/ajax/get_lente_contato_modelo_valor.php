<?php
// require_once '../../const.php';

$pdo = Conexao::conexao();

if (isset($_GET['id_modelo']) && isset($_GET['olhos'])) {
    $id_modelo = $_GET['id_modelo'];
    $olhos     = $_GET['olhos'];

    $stmt = $pdo->prepare("SELECT valor_venda FROM lente_contato_modelos WHERE id_lente_contato_modelo = :id_modelo AND deleted_at IS NULL");
    $stmt->bindParam(':id_modelo', $id_modelo, PDO::PARAM_INT);
    $stmt->execute();
    $modelo = $stmt->fetch(PDO::FETCH_OBJ);

    if ($modelo) {
        $valor = floatval($modelo->valor_venda);
        // Se o parâmetro "olhos" for "0" (ambos) multiplica por 2; 
        // mas para o cálculo individual (cada olho) passe "1"
        $valor_total = ($olhos == "0") ? $valor * 2 : $valor;
        echo json_encode(["valor_total" => $valor_total]);
    } else {
        echo json_encode(["error" => "Modelo não encontrado."]);
    }
} else {
    echo json_encode(["error" => "Parâmetros inválidos."]);
}
?>
