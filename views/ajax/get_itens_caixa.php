<?php
    require_once '../../const.php';

    // Define a conexão usando a classe Conexao
    $pdo = Conexao::conexao();

    // Verifica se o parâmetro id_nota foi informado
    if (isset($_GET['id_caixa'])) {
        $id_caixa = $_GET['id_caixa'];

        // Consulta os itens da nota informada
        $sql = $pdo->prepare('SELECT * FROM arquivo_morto_itens WHERE id_caixa = :id_caixa AND deleted_at IS NULL ORDER BY created_at DESC');
        $sql->bindParam(':id_caixa', $id_caixa, PDO::PARAM_INT);
        $sql->execute();

        $itens = $sql->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($itens);
    } else {
        echo json_encode(['error' => 'Parâmetro id_caixa não informado']);
    }
    ?>
