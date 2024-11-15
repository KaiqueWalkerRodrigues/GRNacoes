<?php
    require_once '../../const.php';

    // Define a conexão usando a classe Conexao
    $pdo = Conexao::conexao();

    if (isset($_GET['categoria'])) {
        $categoria = $_GET['categoria'];

        // Consulta filtrada por categoria, renomeando as colunas para manter o padrão
        $sql = $pdo->prepare('SELECT f.id_compra_fornecedor AS id_fornecedor, f.fornecedor, c.categoria 
                                FROM compras_fornecedores f 
                                JOIN compras_categorias c ON f.id_categoria = c.id_compra_categoria 
                                WHERE c.categoria = :categoria
                                ORDER BY fornecedor');
        $sql->bindParam(':categoria', $categoria);
        $sql->execute();

        $fornecedores = $sql->fetchAll(PDO::FETCH_ASSOC);

        // Retorna os fornecedores em JSON no formato correto
        echo json_encode($fornecedores);
    } else {
        // Consulta para todos os fornecedores, renomeando as colunas para manter o padrão
        $sql = $pdo->prepare('SELECT id_compra_fornecedor AS id_fornecedor, fornecedor, c.categoria 
                                FROM compras_fornecedores f
                                JOIN compras_categorias c ON f.id_categoria = c.id_compra_categoria
                                ORDER BY fornecedor');
        $sql->execute();

        $fornecedores = $sql->fetchAll(PDO::FETCH_ASSOC);

        // Retorna os fornecedores em JSON no formato correto
        echo json_encode($fornecedores);
    }
?>
