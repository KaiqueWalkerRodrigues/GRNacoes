<?php
require_once '../../const.php';

$pdo = Conexao::conexao();

// Verifica se os parâmetros necessários foram enviados
if (isset($_GET['semana']) && isset($_GET['empresa'])) {
    $semana  = $_GET['semana'];   // Exemplo: "2025-W06"
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

    /*
     * 1) Obtém o faturamento total da semana
     */
    $sqlFaturamento = "SELECT SUM(valor) AS total_faturamento 
                       FROM lente_contato_orcamentos 
                       WHERE deleted_at IS NULL 
                         AND DATE(created_at) BETWEEN :startDate AND :endDate";
    
    // Se for selecionada uma empresa específica, adiciona o filtro
    if ($empresa != "0") {
        $sqlFaturamento .= " AND id_empresa = :empresa";
    }
    
    $stmtF = $pdo->prepare($sqlFaturamento);
    $stmtF->bindParam(':startDate', $startDate);
    $stmtF->bindParam(':endDate', $endDate);
    if ($empresa != "0") {
        $stmtF->bindParam(':empresa', $empresa, PDO::PARAM_INT);
    }
    $stmtF->execute();
    $resultF = $stmtF->fetch(PDO::FETCH_OBJ);
    $totalFaturamento = $resultF->total_faturamento !== null ? floatval($resultF->total_faturamento) : 0;

    /*
     * 2) Obtém o custo total dos modelos das lentes que tiveram orçamento.
     *    Agora utiliza os campos id_modelo_esquerdo e id_modelo_direito para obter
     *    o custo dos modelos correspondentes na tabela lente_contato_modelos.
     */
    $sqlCusto = "SELECT SUM(
                    COALESCE(me.valor_custo, 0) + COALESCE(md.valor_custo, 0)
                  ) AS total_custo
                 FROM lente_contato_orcamentos o
                 LEFT JOIN lente_contato_modelos me ON o.id_modelo_esquerdo = me.id_lente_contato_modelo
                 LEFT JOIN lente_contato_modelos md ON o.id_modelo_direito = md.id_lente_contato_modelo
                 WHERE o.deleted_at IS NULL
                   AND DATE(o.created_at) BETWEEN :startDate AND :endDate";
    
    if ($empresa != "0") {
        $sqlCusto .= " AND o.id_empresa = :empresa";
    }
    
    $stmtC = $pdo->prepare($sqlCusto);
    $stmtC->bindParam(':startDate', $startDate);
    $stmtC->bindParam(':endDate', $endDate);
    if ($empresa != "0") {
        $stmtC->bindParam(':empresa', $empresa, PDO::PARAM_INT);
    }
    $stmtC->execute();
    $resultC = $stmtC->fetch(PDO::FETCH_OBJ);
    $totalCusto = $resultC->total_custo !== null ? floatval($resultC->total_custo) : 0;

    /*
     * 3) Calcula o lucro: faturamento - custo total dos modelos
     */
    $lucro = $totalFaturamento - $totalCusto;

    echo json_encode(["lucro" => $lucro]);
} else {
    echo json_encode(["error" => "Parâmetros inválidos."]);
}
?>
