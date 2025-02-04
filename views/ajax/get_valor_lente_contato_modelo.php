<?php 
require_once '../../const.php';

$pdo = Conexao::conexao();

if (isset($_GET['id_modelo']) && isset($_GET['olhos'])) {
    $id_modelo = $_GET['id_modelo'];
    $olhos = $_GET['olhos'];

    // Busca o valor da lente de contato
    $sql = $pdo->prepare('SELECT valor_venda FROM lente_contato_modelos WHERE id_lente_contato_modelo = :id_modelo');
    $sql->bindParam(':id_modelo', $id_modelo);
    $sql->execute();
    $modelo = $sql->fetch(PDO::FETCH_OBJ);

    if ($modelo) {
        $valor_venda = $modelo->valor_venda;

        // Calcula o valor total com base na opção de olhos
        if ($olhos == 0) { // Ambos os olhos
            $valor_total = $valor_venda * 2;
        } else { // Um olho
            $valor_total = $valor_venda;
        }

        echo json_encode(['valor_total' => $valor_total]);
    } else {
        echo json_encode(['error' => 'Modelo não encontrado']);
    }
} else {
    echo json_encode(['error' => 'Parâmetros inválidos']);
}
?>