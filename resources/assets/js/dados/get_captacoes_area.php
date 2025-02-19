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
                COUNT(CASE WHEN captado IN (1,2) THEN 1 END) as captados,
                COUNT(CASE WHEN captado = 0 THEN 1 END) as naoCaptados,
                COUNT(CASE WHEN captado IN (2, 3) THEN 1 END) as lentes,
                COUNT(CASE WHEN captado = 4 THEN 1 END) as garantia,
                COUNT(id_captado) as receitas,
                COUNT(CASE WHEN captado IN (0, 1, 2) THEN 1 END) as receitasValidas
            FROM captados
            WHERE DATE(created_at) = :dataDia
            AND deleted_at IS NULL
        ";

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
            'data' => $dia->format('Y-m-d'),
            'captados' => $resultado['captados'] ?? 0,
            'naoCaptados' => $resultado['naoCaptados'] ?? 0,
            'lentes' => $resultado['lentes'] ?? 0,
            'garantia' => $resultado['garantia'] ?? 0,
            'receitas' => $resultado['receitas'] ?? 0,
            'receitasValidas' => $resultado['receitasValidas'] ?? 0
        ];
    }

    // Retorna os dados da semana inteira como um array JSON
    echo json_encode($dadosSemana);
} else {
    echo json_encode(['error' => 'Data ou empresa não fornecida']);
}
