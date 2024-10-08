<?php
require '../class/Connection.php';
require '../class/Conexao.php';

try {
    // Obtém o valor do parâmetro enviado via GET
    $empresaId = isset($_GET['empresaId']) ? (int)$_GET['empresaId'] : 0;

    // Prepara a consulta dependendo do valor de $empresaId
    if ($empresaId === 0) {
        // Caso seja 0, mostra todas as captações por empresa da semana atual
        $query = "
            SELECT id_empresa, COUNT(id_captado) as total
            FROM captados
            WHERE id_empresa IN (1, 3, 5)
            AND deleted_at IS NULL
            AND YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)
            GROUP BY id_empresa
        ";
    } else {
        // Caso seja 1, 3 ou 5, mostra captações por nome do captador da semana atual para a empresa específica
        $query = "
            SELECT u.nome AS captador_nome, COUNT(c.id_captado) as total
            FROM captados c
            INNER JOIN usuarios u ON c.id_captador = u.id_usuario
            WHERE c.id_empresa = :empresaId
            AND c.deleted_at IS NULL
            AND YEARWEEK(c.created_at, 1) = YEARWEEK(CURDATE(), 1)
            GROUP BY u.nome
        ";
    }
    
    $stmt = $pdo->prepare($query);

    // Se for uma empresa específica, vinculamos o parâmetro
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
            // Se for para todas as empresas, preserva as cores específicas definidas
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
            // Se for para uma empresa específica, montamos o array com os nomes dos captadores e suas cores
            $data = []; // Reinicia o array para captadores específicos
            $cores = [
                [
                    "color" => "#007bff", // Azul
                    "hoverColor" => "#0056b3"
                ],
                [
                    "color" => "#28a745", // Verde
                    "hoverColor" => "#218838"
                ],
                [
                    "color" => "#ffc107", // Amarelo
                    "hoverColor" => "#e0a800"
                ],
                [
                    "color" => "#dc3545", // Vermelho
                    "hoverColor" => "#c82333"
                ],
                [
                    "color" => "#6f42c1", // Roxo
                    "hoverColor" => "#5a32a3"
                ],
            ];
            
            $colorIndex = 0;
            foreach ($resultados as $resultado) {
                // Pega a cor do array de cores e reseta se exceder o tamanho do array
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
    // Em caso de erro, retorna uma mensagem de erro
    echo json_encode(['error' => 'Erro ao buscar os dados: ' . $e->getMessage()]);
}
?>
