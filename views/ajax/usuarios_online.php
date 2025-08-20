<?php
// ajax/usuarios_online.php
require_once '../../const.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $Usuario = new Usuario(); // tem $pdo público

    // 1) Busca quem está online na última 1 min (como você definiu)
    $usuarios = $Usuario->listarOnlines(1); // retorna OBJ com id_usuario, nome, usuario, id_avatar, empresa, online_at

    // Se não há ninguém online, responda direto
    if (!$usuarios || count($usuarios) === 0) {
        echo json_encode([
            'data' => [],
            'server_time' => date('Y-m-d H:i:s'),
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // 2) Coletar IDs para um JOIN único e obter o nome do setor PRINCIPAL
    $ids = array_map(function($u) { return (int)$u->id_usuario; }, $usuarios);
    $ids = array_values(array_filter($ids, fn($v) => $v > 0));

    $mapSetor = []; // id_usuario => setor_nome

    if (!empty($ids)) {
        // Monta placeholders (:id0, :id1, ...)
        $ph = [];
        foreach ($ids as $i => $id) {
            $ph[] = ":id{$i}";
        }
        $inClause = implode(',', $ph);

        // Busca o setor principal (ou o primeiro ativo) de cada usuário
        // Ajuste nomes de colunas/tabelas se necessário
        $sql = $Usuario->pdo->prepare("
            SELECT u.id_usuario,
                s.setor AS setor_nome
            FROM usuarios u
            LEFT JOIN usuarios_setores us
                ON us.id_usuario = u.id_usuario
                AND us.deleted_at IS NULL
                AND us.principal = 1
            LEFT JOIN setores s
                ON s.id_setor = us.id_setor
            WHERE u.id_usuario IN ($inClause)
            ORDER BY u.nome ASC

        ");

        foreach ($ids as $i => $id) {
            $sql->bindValue(":id{$i}", $id, PDO::PARAM_INT);
        }
        $sql->execute();

        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
            $mapSetor[(int)$row['id_usuario']] = $row['setor_nome'] ?? null;
        }
    }

    // 3) Montar saída com avatar/nome/setor
    $avatarBasePath = URL_RESOURCES . '/img/avatars';            // ex.: /img/avatars/3.png
    $defaultAvatar  = URL_RESOURCES . '/img/avatars/0.png'; // garanta este arquivo

    $saida = [];
    foreach ($usuarios as $u) {
        $id = (int)$u->id_usuario;

        // Avatar por id_avatar (ex.: 3 -> /img/avatars/3.png)
        $idAvatar  = isset($u->id_avatar) ? (int)$u->id_avatar : null;
        $avatarUrl = $idAvatar ? ($avatarBasePath . '/' . $idAvatar . '.png') : $defaultAvatar;

        $saida[] = [
            'nome'   => $u->nome ?? 'Sem nome',
            'setor'  => $mapSetor[$id] ?? '—',
            'avatar' => $avatarUrl,
        ];
    }

    echo json_encode([
        'data' => $saida,
        'server_time' => date('Y-m-d H:i:s'),
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => true, 'message' => $e->getMessage()]);
}
