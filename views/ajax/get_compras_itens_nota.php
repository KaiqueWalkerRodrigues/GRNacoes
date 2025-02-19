<?php
    require_once '../../const.php';

    // Define a conexão usando a classe Conexao
    $pdo = Conexao::conexao();

    // Verifica se o parâmetro id_nota foi informado
    if (isset($_GET['id_nota'])) {
        $id_nota = $_GET['id_nota'];

        // Consulta os itens da nota informada
        $sql = $pdo->prepare('SELECT 
                                    id_compras_notas_itens, 
                                    id_nota, 
                                    item, 
                                    valor_uni, 
                                    quantidade, 
                                    descricao, 
                                    created_at, 
                                    updated_at, 
                                    deleted_at 
                            FROM compras_notas_itens 
                            WHERE id_nota = :id_nota 
                            ORDER BY id_compras_notas_itens');
        $sql->bindParam(':id_nota', $id_nota, PDO::PARAM_INT);
        $sql->execute();

        $itens = $sql->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($itens);
    } else {
        echo json_encode(['error' => 'Parâmetro id_nota não informado']);
    }
    ?>
