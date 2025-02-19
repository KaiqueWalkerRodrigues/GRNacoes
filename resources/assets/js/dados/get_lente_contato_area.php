<?php

require '../../../../const.php';

$pdo = Conexao::conexao();

if (isset($_GET['data']) && isset($_GET['id_empresa'])) {
    $data = $_GET['data'];
    $id_empresa = $_GET['id_empresa'];

    // Converte a data em um objeto DateTime para definir o início e o fim da semana
    $dataInicio = new DateTime($data);
    $dataInicio->modify('monday this week'); // Início da semana (segunda-feira)
    $dataFim = clone $dataInicio;
    $dataFim->modify('sunday this week'); // Fim da semana (domingo)

    $dadosSemana = []; // Array para armazenar os dados de cada dia da semana

    // Loop para cada dia da semana, de segunda-feira até domingo
    for ($dia = clone $dataInicio; $dia <= $dataFim; $dia->modify('+1 day')) {
        $query = "
            SELECT 
                COUNT(CASE WHEN status = 0 THEN 1 END) as pedidos,
                COUNT(CASE WHEN status = 1 THEN 1 END) as pagos,
                COUNT(CASE WHEN status = 2 THEN 1 END) as entregues,
                COUNT(CASE WHEN valor IS NULL THEN 1 END) as testes,
                COUNT(id_lente_contato_orcamento) as total_orcamentos,
                COUNT(CASE WHEN valor IS NOT NULL THEN 1 END) as total_lentes
            FROM lente_contato_orcamentos
            WHERE DATE(created_at) = :dataDia
              AND deleted_at IS NULL
        ";

        // Se o id_empresa for informado (diferente de 0), filtra por empresa
        if ($id_empresa != 0) {
            $query .= " AND id_empresa = :id_empresa";
        }

        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':dataDia', $dia->format('Y-m-d'));
        
        if ($id_empresa != 0) {
            $stmt->bindValue(':id_empresa', $id_empresa);
        }

        $stmt->execute();
        $resultado = $stmt->fetch();

        // Adiciona os dados do dia atual ao array $dadosSemana
        $dadosSemana[] = [
            'data'              => $dia->format('Y-m-d'),
            'pedidos'           => $resultado['pedidos'] ?? 0,
            'pagos'             => $resultado['pagos'] ?? 0,
            'entregues'         => $resultado['entregues'] ?? 0,
            'testes'            => $resultado['testes'] ?? 0,
            'total_orcamentos'  => $resultado['total_orcamentos'] ?? 0,
            'total_lentes'      => $resultado['total_lentes'] ?? 0
        ];
    }

    // Retorna os dados da semana inteira como um array JSON
    echo json_encode($dadosSemana);
} else {
    echo json_encode(['error' => 'Data ou empresa não fornecida']);
}
