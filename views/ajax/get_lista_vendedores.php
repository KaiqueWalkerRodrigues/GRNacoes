<?php 

require_once '../../const.php';

    $pdo = Conexao::conexao();

    if (isset($_GET['id_empresa'])) {
        $idEmpresa = $_GET['id_empresa'];

        if ($idEmpresa) {

            //Listar Usuários Vendedores de uma empresa em Especifico.
            $sql = $pdo->prepare('SELECT * FROM usuarios WHERE deleted_at IS NULL AND empresa LIKE :empresa AND id_cargo = 9 OR id_cargo = 10 OR id_cargo = 11 AND deleted_at IS NULL AND ativo = 1 ORDER BY nome');
            $sql->bindParam(':empresa',$idEmpresa);
            $sql->execute();
            $vendedores = $sql->fetchAll(PDO::FETCH_OBJ);

        } else {

           //Listar todos os vendedores
           $sql = $pdo->prepare('SELECT * FROM usuarios WHERE deleted_at IS NULL AND id_cargo = 9 OR id_cargo = 10 OR id_cargo = 11 AND deleted_at IS NULL AND ativo = 1 ORDER BY nome');
           $sql->execute();
           $vendedores = $sql->fetchAll(PDO::FETCH_OBJ);
           
        }

        foreach ($vendedores as $vendedor) {
            echo '<option value="' . $vendedor->id_usuario . '">' . $vendedor->nome . '</option>';
        }
    }
?>
