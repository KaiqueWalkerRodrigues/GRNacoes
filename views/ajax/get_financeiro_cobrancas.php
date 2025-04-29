<?php
require_once '../../const.php';

$pdo = Conexao::conexao();

// Recupera o parâmetro id_empresa via GET, se não existir, assume 0 (todas)
$id_empresa = isset($_GET['id_empresa']) ? intval($_GET['id_empresa']) : 0;

$sqlStr = "
    SELECT 
        fc.data,
        DATE_FORMAT(fc.data, '%b/%y') AS mes,
        SUM(fcp.valor) AS vendas,
        SUM(fcp.valor_pago) AS valor_pago,
        SUM(fcp.valor) - SUM(fcp.valor_pago) AS a_vencer,
        SUM(CASE WHEN fcp.valor_pago = 0 AND fcp.pago_em IS NULL AND fcp.data < CURDATE() THEN fcp.valor ELSE 0 END) AS vencido
    FROM financeiro_contratos fc
    JOIN financeiro_contratos_parcelas fcp ON fc.id_financeiro_contrato = fcp.id_contrato
    WHERE fc.deleted_at IS NULL AND fcp.deleted_at IS NULL
";

// Se id_empresa for diferente de 0, adiciona o filtro na consulta
if ($id_empresa !== 0) {
    $sqlStr .= " AND fc.id_empresa = :id_empresa";
}

$sqlStr .= " GROUP BY YEAR(fc.data), MONTH(fc.data) ORDER BY fc.data ASC";

$sql = $pdo->prepare($sqlStr);

// Faz o bind do parâmetro caso o filtro seja aplicado
if ($id_empresa !== 0) {
    $sql->bindValue(':id_empresa', $id_empresa, PDO::PARAM_INT);
}

$sql->execute();

$dados = $sql->fetchAll(PDO::FETCH_ASSOC);

// Mapeamento dos 3 primeiros caracteres do mês para o número correspondente
$mapMes = [
    "Jan" => 1,
    "Feb" => 2,
    "Mar" => 3,
    "Apr" => 4,
    "May" => 5,
    "Jun" => 6,
    "Jul" => 7,
    "Aug" => 8,
    "Sep" => 9,
    "Oct" => 10,
    "Nov" => 11,
    "Dec" => 12
];


// Adiciona manualmente o campo "n_mes" baseado na coluna "mes"
foreach ($dados as &$row) {
    $mesAbreviado = substr($row['mes'], 0, 3);
    $row['n_mes'] = isset($mapMes[$mesAbreviado]) ? $mapMes[$mesAbreviado] : 0;
}

header('Content-Type: application/json');
echo json_encode($dados);
?>
