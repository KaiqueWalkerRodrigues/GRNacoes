<?php
require_once '../../Const.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $pdo = Conexao::conexao();

    $id_destinatario = isset($_GET['id_destinatario']) ? (int) $_GET['id_destinatario'] : 0;
    if ($id_destinatario <= 0) {
        echo json_encode(['ok' => false, 'error' => 'id_destinatario inválido']); exit;
    }

    $stmt = $pdo->prepare("SELECT online_at FROM usuarios WHERE id_usuario = :id");
    $stmt->bindValue(':id', $id_destinatario, PDO::PARAM_INT);
    $stmt->execute();

    $online_at = $stmt->fetchColumn();
    if (!$online_at) {
        echo json_encode([
            'ok' => true,
            'is_online' => false,
            'status' => 'offline',
            'online_at' => null,
            'visto_ha' => null
        ]); exit;
    }

    $agora = new DateTime('now');
    $ultimaOnline = new DateTime($online_at);

    $diffSec = $agora->getTimestamp() - $ultimaOnline->getTimestamp();
    $isOnline = ($diffSec <= 60); // <= 1 minuto

    // Texto humano SEM a palavra "online" para evitar falso positivo no front
    $intervalo = $agora->diff($ultimaOnline);
    if ($isOnline) {
        $vistoHa = 'agora';
    } elseif ($intervalo->y > 0) {
        $vistoHa = $intervalo->y . ' ano(s)';
    } elseif ($intervalo->m > 0) {
        $vistoHa = $intervalo->m . ' mês(es)';
    } elseif ($intervalo->d > 0) {
        $vistoHa = $intervalo->d . ' dia(s)';
    } elseif ($intervalo->h > 0) {
        $vistoHa = $intervalo->h . ' hora(s)';
    } elseif ($intervalo->i > 0) {
        $vistoHa = $intervalo->i . ' minuto(s)';
    } else {
        $vistoHa = $intervalo->s . ' segundo(s)';
    }

    echo json_encode([
        'ok' => true,
        'is_online' => $isOnline,
        'status' => $isOnline ? 'online' : 'offline',
        'online_at' => $ultimaOnline->format('c'),
        'visto_ha' => $vistoHa
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'server_error']);
}
