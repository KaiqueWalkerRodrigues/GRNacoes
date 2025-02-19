<?php

    require_once '../../const.php';

    $pdo = Conexao::conexao();

    if (isset($_GET['id_categoria'])) {
        $id_categoria = $_GET['id_categoria'];

        $sql = $pdo->prepare('SELECT * FROM compras_fornecedores WHERE id_categoria = :id_categoria AND deleted_at IS NULL ORDER BY fornecedor');
            $sql->bindParam(':id_categoria', $id_categoria);
            $sql->execute();
            $fornecedores = $sql->fetchAll(PDO::FETCH_OBJ);

            foreach ($fornecedores as $fornecedor) {
                echo '<option value="' . $fornecedor->id_compra_fornecedor . '">' . $fornecedor->fornecedor . '</option>';
            }

    }else{
        echo "<option>Erro, categoria não recebida</option>";
    }

?>