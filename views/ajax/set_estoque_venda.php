<?php
require_once '../../const.php';

header('Content-Type: application/json; charset=utf-8');

$pdo = Conexao::conexao();

// -------------------------
// 1) Validação básica
// -------------------------
if (
    !isset($_POST['id_empresa'], $_POST['id_fornecedor'], $_POST['estoque'], $_POST['venda'], $_POST['mes'], $_POST['ano'])
) {
    echo json_encode(['status' => 'error', 'message' => 'Dados inválidos']);
    exit;
}

$id_empresa = (int) $_POST['id_empresa'];
$ids        = is_array($_POST['id_fornecedor']) ? $_POST['id_fornecedor'] : [];
$estoques   = is_array($_POST['estoque'])       ? $_POST['estoque']       : [];
$vendas     = is_array($_POST['venda'])         ? $_POST['venda']         : [];
$mes        = (int) $_POST['mes'];
$ano        = (int) $_POST['ano'];

if ($id_empresa === 0 || $mes === 0 || $ano === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Empresa, mês e ano são obrigatórios.']);
    exit;
}

if (count($ids) !== count($estoques) || count($ids) !== count($vendas)) {
    echo json_encode(['status' => 'error', 'message' => 'Quantidade de itens inconsistente (id_fornecedor, estoque, venda).']);
    exit;
}

$now = date('Y-m-d H:i:s');

try {
    $pdo->beginTransaction();

    // Preparar statements uma vez (performance)
    $stmtCheck = $pdo->prepare("
        SELECT id_compra_estoque_venda
        FROM compras_estoque_venda
        WHERE id_fornecedor = :id_fornecedor
          AND mes = :mes
          AND ano = :ano
          AND id_empresa = :id_empresa
          AND deleted_at IS NULL
        LIMIT 1
    ");

    $stmtUpdate = $pdo->prepare("
        UPDATE compras_estoque_venda
           SET estoque = :estoque,
               venda   = :venda,
               updated_at = :updated_at
         WHERE id_compra_estoque_venda = :id
    ");

    $stmtInsert = $pdo->prepare("
        INSERT INTO compras_estoque_venda
            (id_empresa, id_fornecedor, estoque, venda, ano, mes, created_at, updated_at)
        VALUES
            (:id_empresa, :id_fornecedor, :estoque, :venda, :ano, :mes, :created_at, :updated_at)
    ");

    foreach ($ids as $i => $idFornecedorRaw) {
        $idFornecedor = (int) $idFornecedorRaw;

        // Normalizar valores: vazio -> NULL, número -> int
        $estoque = null;
        if (isset($estoques[$i]) && trim((string)$estoques[$i]) !== '') {
            $estoque = is_numeric($estoques[$i]) ? (int) $estoques[$i] : null;
        }

        $venda = null;
        if (isset($vendas[$i]) && trim((string)$vendas[$i]) !== '') {
            $venda = is_numeric($vendas[$i]) ? (int) $vendas[$i] : null;
        }

        // Se não houver fornecedor válido, ignora a linha
        if ($idFornecedor <= 0) {
            continue;
        }

        // 1) Existe?
        $stmtCheck->execute([
            ':id_fornecedor' => $idFornecedor,
            ':mes'           => $mes,
            ':ano'           => $ano,
            ':id_empresa'    => $id_empresa,
        ]);
        $existe = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        if ($existe) {
            // 2) UPDATE
            $stmtUpdate->execute([
                ':estoque'    => $estoque,
                ':venda'      => $venda,
                ':updated_at' => $now,
                ':id'         => $existe['id_compra_estoque_venda'],
            ]);
        } else {
            // 3) INSERT
            $stmtInsert->execute([
                ':id_empresa'   => $id_empresa,
                ':id_fornecedor'=> $idFornecedor,
                ':estoque'      => $estoque,
                ':venda'        => $venda,
                ':ano'          => $ano,
                ':mes'          => $mes,
                ':created_at'   => $now,
                ':updated_at'   => $now,
            ]);
        }
    }

    $pdo->commit();
    echo json_encode(['status' => 'success', 'message' => 'Dados salvos com sucesso']);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
