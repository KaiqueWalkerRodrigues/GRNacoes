<?php
header('Content-Type: application/json');

require_once '../../const.php';

if (isset($_GET['n_nota'])) {
    $n_nota = trim($_GET['n_nota']);

    if ($n_nota === '') {
        echo json_encode(['exists' => false]);
        exit;
    }

    try {
        $db = Conexao::conexao();
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM compras_notas WHERE n_nota = :n_nota AND deleted_at IS NULL");
        $stmt->bindParam(':n_nota', $n_nota, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result['count'] > 0) {
            echo json_encode(['exists' => true]);
        } else {
            echo json_encode(['exists' => false]);
        }
    } catch (PDOException $e) {
        // Em caso de erro, retorne uma mensagem de erro genérica
        echo json_encode(['error' => 'Erro ao verificar a nota.']);
    }
} else {
    echo json_encode(['error' => 'Número da nota não fornecido.']);
}
?>
