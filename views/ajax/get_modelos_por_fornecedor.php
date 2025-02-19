<?php 

    require_once '../../const.php';

    $pdo = Conexao::conexao();

    if (isset($_GET['id_fornecedor'])) {
        $idFornecedor = $_GET['id_fornecedor'];

        if ($idFornecedor) {
            // Listar modelos de lentes de um fornecedor específico
            $sql = $pdo->prepare('SELECT * FROM lente_contato_modelos WHERE id_fornecedor = :id_fornecedor AND deleted_at IS NULL ORDER BY modelo');
            $sql->bindParam(':id_fornecedor', $idFornecedor);
            $sql->execute();
            $modelos = $sql->fetchAll(PDO::FETCH_OBJ);

            foreach ($modelos as $modelo) {
                echo '<option value="' . $modelo->id_lente_contato_modelo . '">' . $modelo->modelo .' ('.$modelo->codigo_simah.')</option>';
            }
        }
    }
    
?>