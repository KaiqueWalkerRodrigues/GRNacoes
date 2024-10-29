<?php
header('Content-Type: application/json'); // Define o cabeçalho para JSON

require '../class/Connection.php';
require '../class/Conexao.php';
require '../class/Captacao.php';
require '../class/Helper.php';

$Captacao = new Captacao();

try {
    // Obtém o valor do parâmetro enviado via GET
    $empresaId = isset($_GET['empresaId']) && $_GET['empresaId'] != 0 ? $_GET['empresaId'] : null;

    // Consulta SQL para obter os captadores e suas empresas
    $query = "
        SELECT u.nome AS captador_nome, 
               c.id_empresa, 
               SUM(CASE WHEN c.captado = 1 THEN 1 ELSE 0 END) AS captados,
               COUNT(c.id_captado) AS total_pacientes
        FROM captados c
        INNER JOIN usuarios u ON c.id_captador = u.id_usuario
        WHERE c.deleted_at IS NULL
        AND YEARWEEK(c.created_at, 1) = YEARWEEK(CURDATE(), 1)
    ";

    // Adiciona o filtro por empresa se um id_empresa válido for informado
    if ($empresaId != null) {
        $query .= " AND c.id_empresa = :empresaId ";
    }

    $query .= " GROUP BY u.nome, c.id_empresa ORDER BY u.nome ASC";  // Ordenar por nome

    $stmt = $pdo->prepare($query);

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
