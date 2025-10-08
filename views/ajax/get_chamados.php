<?php

require '../../const.php';
header('Content-Type: application/json; charset=utf-8');

try {
    $pdo = Conexao::conexao();
    $id_setor = isset($_GET['id_setor']) ? (int)$_GET['id_setor'] : 0;
    $id_usuario = isset($_GET['id_usuario']) ? (int)$_GET['id_usuario'] : 0;
    $status = isset($_GET['status']) ? $_GET['status'] : '';

    $statusMap = [
        "Pendentes"    => [1, 2],
        "Em Análise"   => [1],
        "Em Andamento" => [2],
        "Concluído"    => [3],
        "Cancelado"    => [4],
        "Recusado"     => [5]
    ];
    $sql = "SELECT * FROM chamados WHERE deleted_at IS NULL";
    $params = [];
    if ($id_setor > 0) {
        $sql .= " AND id_setor = :id_setor";
        $params[':id_setor'] = $id_setor;
    }
    if ($id_usuario > 0) {
        $sql .= " AND id_usuario = :id_usuario";
        $params[':id_usuario'] = $id_usuario;
    }
    if (!empty($status) && isset($statusMap[$status])) {
        $in = [];
        foreach ($statusMap[$status] as $i => $v) {
            $ph = ":status_$i";
            $in[] = $ph;
            $params[$ph] = $v;
        }
        $sql .= " AND status IN (" . implode(',', $in) . ")";
    }
    $sql .= " ORDER BY created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $chamados = [];
    while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
        // Adapte para trazer nomes de usuário, setor, etc, conforme necessário
        $row->urgencia_texto = Helper::Urgencia($row->urgencia);
        $row->urgencia_texto_input = Helper::TextoUrgencia($row->urgencia);
        $row->status_texto = Helper::statusChamado($row->status);
        $row->status_texto_input = Helper::TextoStatusChamado($row->status);
        $row->created_at_formatado = Helper::formatarData($row->created_at);
        $row->usuario_nome = (new Usuario())->mostrar($row->id_usuario)->nome;
        $row->setor_nome = (new Setor())->mostrar($row->id_setor)->setor;
        $chamados[] = $row;
    }
    echo json_encode(['ok' => true, 'chamados' => $chamados]);
} catch (Exception $e) {
    echo json_encode(['ok' => false, 'message' => $e->getMessage()]);
}
