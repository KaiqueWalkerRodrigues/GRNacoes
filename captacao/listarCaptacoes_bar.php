<?php
require '../class/Connection.php';
require '../class/Conexao.php';

if (isset($_GET['data']) && isset($_GET['id_empresa'])) {
    $data = $_GET['data'];
    $id_empresa = $_GET['id_empresa'];

    $query = "
        SELECT 
            COUNT(CASE WHEN captado IN (1,2) THEN 1 END) as captados,
            COUNT(CASE WHEN captado = 0 THEN 1 END) as naoCaptados,
            COUNT(CASE WHEN captado IN (2, 3) THEN 1 END) as lentes,
            COUNT(CASE WHEN captado = 4 THEN 1 END) as garantia,
            COUNT(id_captado) as receitas, -- Contagem total independentemente do valor de 'captado'
            COUNT(CASE WHEN captado IN (0, 1, 2) THEN 1 END) as receitasValidas -- Contagem para captados = 0, 1 ou 2
        FROM captados
        WHERE DATE(created_at) = :data
        AND deleted_at IS NULL
    ";
    
    if ($id_empresa != 0) {
        $query .= " AND id_empresa = :id_empresa";
    }

    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':data', $data);
    
    if ($id_empresa != 0) {
        $stmt->bindValue(':id_empresa', $id_empresa);
    }

    $stmt->execute();
    $resultado = $stmt->fetch();
    
    echo json_encode([
        'captados' => $resultado['captados'] ?? 0,
        'naoCaptados' => $resultado['naoCaptados'] ?? 0,
        'lentes' => $resultado['lentes'] ?? 0,
        'garantia' => $resultado['garantia'] ?? 0,
        'receitas' => $resultado['receitas'] ?? 0,
        'receitasValidas' => $resultado['receitasValidas'] ?? 0 // Adiciona as receitas válidas
    ]);
} else {
    echo json_encode(['error' => 'Data ou empresa não fornecida']);
}
