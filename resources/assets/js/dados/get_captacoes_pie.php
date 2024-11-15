<?php

require '../../../../const.php';

$pdo = Conexao::conexao();

try {
    // Obtém o valor dos parâmetros enviados via GET
    $empresaId = isset($_GET['empresaId']) ? (int)$_GET['empresaId'] : 0;
    $dataInicio = isset($_GET['dataInicio']) ? $_GET['dataInicio'] : null;

    if ($dataInicio === null) {
        echo json_encode(['error' => 'Data de início da semana não fornecida']);
        exit;
    }

    // Define a data de início e fim da semana com base na data de início
    $dataInicioSemana = new DateTime($dataInicio);
    $dataFimSemana = clone $dataInicioSemana;
    $dataFimSemana->modify('+6 days'); // Final da semana (domingo)

    // Prepara a consulta dependendo do valor de $empresaId
    if ($empresaId === 0) {
        // Mostra todas as captações por empresa da semana especificada
        $query = "
            SELECT id_empresa, COUNT(id_captado) as total
            FROM captados
            WHERE id_empresa IN (1, 3, 5)
            AND deleted_at IS NULL
            AND DATE(created_at) BETWEEN :dataInicio AND :dataFim
            GROUP BY id_empresa
        ";
    } else {
        // Mostra captações por nome do captador da semana especificada para uma empresa específica
        $query = "
            SELECT u.nome AS captador_nome, COUNT(c.id_captado) as total
            FROM captados c
            INNER JOIN usuarios u ON c.id_captador = u.id_usuario
            WHERE c.id_empresa = :empresaId
            AND c.deleted_at IS NULL
            AND DATE(c.created_at) BETWEEN :dataInicio AND :dataFim
            GROUP BY u.nome
        ";
    }
    
    $stmt = $pdo->prepare($query);

    // Vincula os parâmetros de data de início e fim da semana
    $stmt->bindValue(':dataInicio', $dataInicioSemana->format('Y-m-d'));
    $stmt->bindValue(':dataFim', $dataFimSemana->format('Y-m-d'));

    // Se for uma empresa específica, vincula o parâmetro de ID da empresa
    if ($empresaId !== 0) {
        $stmt->bindParam(':empresaId', $empresaId, PDO::PARAM_INT);
    }
    
    $stmt->execute();
    
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Cria um array associativo com as cores fixas para cada empresa
    $data = [
        "Clinica Parque" => [
            "total" => 0,
            "color" => "#4e73df", // Azul
            "hoverColor" => "#2e59d9"
        ],
        "Clinica Mauá" => [
            "total" => 0,
            "color" => "#dc3545", // Vermelho
            "hoverColor" => "#c82333"
        ],
        "Clinica Jardim" => [
            "total" => 0,
            "color" => "#28a745", // Verde
            "hoverColor" => "#218838"
        ],
    ];

    // Popula os dados retornados no array associativo, se houver
    if (!empty($resultados)) {
        if ($empresaId === 0) {
            // Para todas as empresas, preserva as cores específicas definidas
            foreach ($resultados as $resultado) {
                switch ($resultado['id_empresa']) {
                    case 1:
                        $data["Clinica Parque"]["total"] = $resultado['total'];
                        break;
                    case 3:
                        $data["Clinica Mauá"]["total"] = $resultado['total'];
                        break;
                    case 5:
                        $data["Clinica Jardim"]["total"] = $resultado['total'];
                        break;
                }
            }
        } else {
            // Para uma empresa específica, monta o array com os nomes dos captadores e suas cores
            $data = []; // Reinicia o array para captadores específicos
            $cores = [
                ["color" => "#007bff", "hoverColor" => "#0056b3"],
                ["color" => "#28a745", "hoverColor" => "#218838"],
                ["color" => "#ffc107", "hoverColor" => "#e0a800"],
                ["color" => "#dc3545", "hoverColor" => "#c82333"],
                ["color" => "#6f42c1", "hoverColor" => "#5a32a3"]
            ];

            $colorIndex = 0;
            foreach ($resultados as $resultado) {
                $color = $cores[$colorIndex % count($cores)];
                $data[$resultado['captador_nome']] = [
                    "total" => $resultado['total'],
                    "color" => $color['color'],
                    "hoverColor" => $color['hoverColor']
                ];
                $colorIndex++;
            }
        }
    }

    // Retorna os dados em formato JSON
    echo json_encode($data);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Erro ao buscar os dados: ' . $e->getMessage()]);
}
?>
