<?php
require_once '../../const.php';

$pdo = Conexao::conexao();

// -------------------------
// 1) Entradas e validação
// -------------------------
$id_empresa = isset($_GET['id_empresa']) ? (int) $_GET['id_empresa'] : 0;
$mes        = isset($_GET['mes'])        ? (int) $_GET['mes']        : 0;
$ano        = isset($_GET['ano'])        ? (int) $_GET['ano']        : (int) date('Y');

header('Content-Type: application/json; charset=utf-8');

if ($id_empresa === 0 || $mes === 0) {
    echo json_encode(['html' => '']);
    exit;
}

// -------------------------------------------------------
// 2) Buscar fornecedores (categoria 5 = armações) ativos
// -------------------------------------------------------
$sql = $pdo->prepare("
    SELECT f.id_compra_fornecedor, f.fornecedor
    FROM compras_fornecedores f
    WHERE f.deleted_at IS NULL
      AND f.id_categoria = 5
    ORDER BY f.fornecedor
");
$sql->execute();
$fornecedores = $sql->fetchAll(PDO::FETCH_ASSOC);

// Se não houver fornecedores, retorna vazio
if (!$fornecedores) {
    echo json_encode(['html' => '']);
    exit;
}

// ------------------------------------------------------------------
// 3) Montar lista de IDs para IN (...) e parâmetros base (mes/ano)
// ------------------------------------------------------------------
$ids = array_column($fornecedores, 'id_compra_fornecedor');

$placeholders = [];
$params = [
    ':mes'        => $mes,
    ':ano'        => $ano,
    ':id_empresa' => $id_empresa,
];

foreach ($ids as $i => $id) {
    $key = ":id{$i}";
    $placeholders[] = $key;
    $params[$key] = (int) $id;
}
$inClause = implode(',', $placeholders);

// ----------------------------------------------------------------------
// 4) Buscar estoque/venda existentes por (empresa, fornecedor, mes, ano)
// ----------------------------------------------------------------------
$sqlVals = $pdo->prepare("
    SELECT id_fornecedor, estoque, venda
    FROM compras_estoque_venda
    WHERE deleted_at IS NULL
      AND mes = :mes
      AND ano = :ano
      AND id_empresa = :id_empresa
      AND id_fornecedor IN ($inClause)
");
$sqlVals->execute($params);
$valores = $sqlVals->fetchAll(PDO::FETCH_ASSOC);

// -----------------------------------------------------------
// 5) Mapear valores por fornecedor para preencher os inputs
// -----------------------------------------------------------
$map = [];
foreach ($valores as $v) {
    $map[(int) $v['id_fornecedor']] = [
        'estoque' => $v['estoque'],
        'venda'   => $v['venda'],
    ];
}

// -------------------------------------------
// 6) Montar HTML dos cards para resposta AJAX
// -------------------------------------------
$html = '';
foreach ($fornecedores as $fornecedor) {
    $idFornecedor = (int) $fornecedor['id_compra_fornecedor'];
    $estoqueVal   = isset($map[$idFornecedor]) ? (string) $map[$idFornecedor]['estoque'] : '';
    $vendaVal     = isset($map[$idFornecedor]) ? (string) $map[$idFornecedor]['venda']   : '';

    $html .= '
    <div class="col-4">
        <div class="card p-2 mb-3">
            <b class="text-center">' . htmlspecialchars($fornecedor['fornecedor'], ENT_QUOTES, 'UTF-8') . '</b>
            <div class="row mb-2">
                <input type="hidden" name="id_fornecedor[]" value="' . $idFornecedor . '">
                <div class="col-6 text-center">
                    <label class="form-label">Estoque</label>
                    <input type="text" name="estoque[]" class="form-control" value="' . htmlspecialchars($estoqueVal, ENT_QUOTES, 'UTF-8') . '">
                </div>
                <div class="col-6 text-center">
                    <label class="form-label">Venda</label>
                    <input type="text" name="venda[]" class="form-control" value="' . htmlspecialchars($vendaVal, ENT_QUOTES, 'UTF-8') . '">
                </div>
            </div>
        </div>
    </div>';
}

// ---------------------------------
// 7) Retornar JSON para o frontend
// ---------------------------------
echo json_encode(['html' => $html]);
