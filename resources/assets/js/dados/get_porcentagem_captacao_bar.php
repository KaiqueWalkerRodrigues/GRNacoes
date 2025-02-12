<?php

header('Content-Type: application/json');

require '../../../../const.php';

$pdo = Conexao::conexao();

try {
    // Obtém o valor dos parâmetros enviados via GET
    $empresaId = isset($_GET['empresaId']) && $_GET['empresaId'] != 0 ? $_GET['empresaId'] : null;
    $dataInicio = isset($_GET['dataInicio']) ? $_GET['dataInicio'] : null;

    if ($dataInicio === null) {
        echo json_encode(['error' => 'Data de início da semana não fornecida']);
        exit;
    }

    // Define a data de fim da semana com base na data de início
    $dataInicioSemana = new DateTime($dataInicio);
    $dataFimSemana = clone $dataInicioSemana;
    $dataFimSemana->modify('+6 days'); // Final da semana (domingo)

    // Consulta SQL para obter os captadores e suas empresas
    $query = "
    SELECT u.nome AS captador_nome, 
        c.id_empresa, 
        SUM(CASE WHEN c.captado = 1 THEN 1 ELSE 0 END) AS captados,
        COUNT(c.id_captado) AS total_pacientes
    FROM captados c
    INNER JOIN usuarios u ON c.id_captador = u.id_usuario
    WHERE c.deleted_at IS NULL
    AND DATE(c.created_at) BETWEEN :dataInicio AND :dataFim
    ";

    // Adiciona o filtro por empresa se um id_empresa válido for informado
    if ($empresaId != null) {
        $query .= " AND c.id_empresa = :empresaId ";
    }

    $query .= " GROUP BY u.nome, c.id_empresa ORDER BY u.nome ASC";  // Ordenar por nome

    $stmt = $pdo->prepare($query);

    // Vincula os parâmetros de data de início e fim da semana
    $stmt->bindValue(':dataInicio', $dataInicioSemana->format('Y-m-d'));
    $stmt->bindValue(':dataFim', $dataFimSemana->format('Y-m-d'));

    // Se houver filtro por empresa, vincula o parâmetro
    if ($empresaId != null) {
        $stmt->bindParam(':empresaId', $empresaId, PDO::PARAM_INT);
    }

    $stmt->execute();
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $data = [];
    if (!empty($resultados)) {
        foreach ($resultados as $resultado) {
            $captados = (int)$resultado['captados'];
            $totalPacientes = (int)$resultado['total_pacientes'];

            if ($totalPacientes > 0) {
                // Calcula a porcentagem de captados e não captados
                $percentCaptado = ($captados / $totalPacientes) * 100;
                $percentNaoCaptado = 100 - $percentCaptado;

                // Extrai apenas o primeiro nome e o primeiro sobrenome
                $nomeCompleto = explode(" ", $resultado['captador_nome']);
                $primeiroNome = $nomeCompleto[0];
                $primeiroSobrenome = isset($nomeCompleto[1]) ? $nomeCompleto[1] : '';

                // Obter o nome da empresa
                $empresa = Helper::mostrar_empresa($resultado['id_empresa']);

                $data[] = [
                    'nome' => $primeiroNome . ' ' . $primeiroSobrenome . ' (' . $empresa . ')',
                    'captado' => number_format($percentCaptado, 2),
                    'nao_captado' => number_format($percentNaoCaptado, 2)
                ];
            }
        }
    }

    // Retorna os dados em formato JSON
    echo json_encode($data);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Erro ao buscar os dados: ' . $e->getMessage()]);
}
?>
