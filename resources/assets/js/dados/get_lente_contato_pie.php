<?php

require '../../../../const.php';

$pdo = Conexao::conexao();

try {
    // Obtém os parâmetros enviados via GET
    $empresaId = isset($_GET['empresaId']) ? (int)$_GET['empresaId'] : 0;
    $dataInicio = isset($_GET['dataInicio']) ? $_GET['dataInicio'] : null;

    if ($dataInicio === null) {
        echo json_encode(['error' => 'Data de início da semana não fornecida']);
        exit;
    }

    // Define a data de início e fim da semana com base na data informada
    $dataInicioSemana = new DateTime($dataInicio);
    $dataFimSemana = clone $dataInicioSemana;
    $dataFimSemana->modify('+6 days'); // Define o fim da semana (domingo)

    // Prepara a consulta de acordo com o valor de $empresaId
    if ($empresaId === 0) {
        // Consulta para todas as empresas (1, 3 e 5) na semana especificada para registros de lente contato
        $query = "
            SELECT id_empresa, COUNT(id_lente_contato_orcamento) as total
            FROM lente_contato_orcamentos
            WHERE id_empresa IN (1, 3, 5)
            AND deleted_at IS NULL
            AND DATE(created_at) BETWEEN :dataInicio AND :dataFim
            GROUP BY id_empresa
        ";
    } else {
        // Consulta para uma empresa específica: agrupa os registros de lente contato por 'nome'
        $query = "
            SELECT nome, COUNT(id_lente_contato_orcamento) as total
            FROM lente_contato_orcamentos
            WHERE id_empresa = :empresaId
            AND deleted_at IS NULL
            AND DATE(created_at) BETWEEN :dataInicio AND :dataFim
            GROUP BY nome
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

    // Cria o array de dados com cores fixas para cada clínica (quando todas as empresas são exibidas)
    if ($empresaId === 0) {
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

        // Atribui os totais retornados aos respectivos índices (id_empresa)
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
        // Para uma empresa específica, monta o array utilizando os nomes (responsáveis) e atribui cores alternadas
        $data = []; // Reinicia o array para os registros específicos de lente contato
        $cores = [
            ["color" => "#007bff", "hoverColor" => "#0056b3"],
            ["color" => "#28a745", "hoverColor" => "#218838"],
            ["color" => "#ffc107", "hoverColor" => "#e0a800"],
            ["color" => "#dc3545", "hoverColor" => "#c82333"],
            ["color" => "#6f42c1", "hoverColor" => "#5a32a3"]
        ];

        $colorIndex = 0;
        foreach ($resultados as $resultado) {
            $cor = $cores[$colorIndex % count($cores)];
            $data[$resultado['nome']] = [
                "total" => $resultado['total'],
                "color" => $cor['color'],
                "hoverColor" => $cor['hoverColor']
            ];
            $colorIndex++;
        }
    }

    // Retorna os dados em formato JSON
    echo json_encode($data);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Erro ao buscar os dados: ' . $e->getMessage()]);
}
?>
