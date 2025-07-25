<?php
require_once '../../const.php';

$pdo = Conexao::conexao();

// Verifica se os parâmetros necessários foram enviados
if (isset($_GET['semana']) && isset($_GET['empresa'])) {
    $semana = $_GET['semana'];    // Exemplo: "2025-W06"
    $empresa = $_GET['empresa'];  // "0" para todas ou o id da empresa (ex.: 1, 3, 5)

    // Converte o parâmetro semana para obter o ano e o número da semana
    $parts = explode('-W', $semana);
    if (count($parts) != 2) {
        echo json_encode(["error" => "Formato de semana inválido."]);
        exit;
    }
    $year = intval($parts[0]);
    $week = intval($parts[1]);

    // Determina o intervalo de datas da semana (segunda-feira a domingo)
    $dto = new DateTime();
    $dto->setISODate($year, $week);
    $startDate = $dto->format('Y-m-d'); // Início da semana (segunda-feira)
    $dto->modify('+6 days');
    $endDate = $dto->format('Y-m-d');   // Fim da semana (domingo)

    // Monta a query para somar os valores de venda dentro do período
    $sql = "SELECT SUM(valor) AS total_faturamento 
            FROM lente_contato_orcamentos 
            WHERE deleted_at IS NULL 
              AND DATE(created_at) BETWEEN :startDate AND :endDate";
    
    // Se for selecionada uma empresa específica (valor diferente de "0"), adiciona o filtro
    if ($empresa != "0") {
        $sql .= " AND id_empresa = :empresa";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':startDate', $startDate);
    $stmt->bindParam(':endDate', $endDate);
    if ($empresa != "0") {
        $stmt->bindParam(':empresa', $empresa, PDO::PARAM_INT);
    }
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_OBJ);

    // Caso não haja registros, a soma poderá vir como NULL; usamos 0 nesse caso.
    $total = $result->total_faturamento !== null ? floatval($result->total_faturamento) : 0;
    
    echo json_encode(["total_faturamento" => $total]);
} else {
    echo json_encode(["error" => "Parâmetros inválidos."]);
}
?>
