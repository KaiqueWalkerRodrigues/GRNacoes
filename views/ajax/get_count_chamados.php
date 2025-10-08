<?php

require '../../const.php';

// Define o cabeçalho da resposta como JSON
header('Content-Type: application/json; charset=utf-8');

// Prepara uma resposta padrão para ser usada em caso de sucesso ou erro
$response = ['ok' => false, 'total' => 0, 'message' => 'Parâmetros inválidos.'];

try {
    $pdo = Conexao::conexao();

    // 1. OBTENÇÃO DOS PARÂMETROS
    // Pega os parâmetros da URL com valores padrão seguros
    $tipo = isset($_GET['tipo']) ? (int)$_GET['tipo'] : 0;
    $status_texto = isset($_GET['status']) ? $_GET['status'] : '';
    $id_setor = isset($_GET['id_setor']) ? (int)$_GET['id_setor'] : 0;
    $id_usuario = isset($_GET['id_usuario']) ? (int)$_GET['id_usuario'] : 0;

    // 2. CONSTRUÇÃO DINÂMICA DA CONSULTA
    // Começa com a base da query. "WHERE 1=1" é um truque para facilitar a adição de cláusulas AND
    $sql = "SELECT count(id_chamado) as total FROM chamados WHERE deleted_at IS NULL";
    $params = []; // Array que guardará os valores para a consulta segura

    // Adiciona o filtro principal (por setor, por usuário ou nenhum)
    if ($tipo == 1 && $id_setor > 0) {
        $sql .= " AND id_setor = :id_setor";
        $params[':id_setor'] = $id_setor;
    } elseif ($tipo == 2 && $id_usuario > 0) {
        $sql .= " AND id_usuario = :id_usuario";
        $params[':id_usuario'] = $id_usuario;
    }
    // Se o tipo for 3 ou outro valor, nenhum filtro extra é adicionado, contando todos os chamados.

    // Mapeamento dos status de texto para os valores numéricos do banco de dados
    $statusMap = [
        "Pendentes"    => [1, 2],
        "Em Análise"   => [1],
        "Em Andamento" => [2],
        "Concluído"    => [3],
        "Cancelado"    => [4],
        "Recusado"     => [5]
    ];

    if (!empty($status_texto) && isset($statusMap[$status_texto])) {
        $status_valores = $statusMap[$status_texto];
        if (!empty($status_valores)) {
            // cria placeholders nomeados únicos para o IN
            $inPH = [];
            foreach ($status_valores as $i => $v) {
                $ph = ":status_$i";
                $inPH[] = $ph;
                $params[$ph] = $v;
            }
            $sql .= " AND status IN (" . implode(',', $inPH) . ")";
        }
    }

    // 3. EXECUÇÃO SEGURA DA CONSULTA
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params); // Usamos array_values para garantir a ordem dos parâmetros para a cláusula IN

    // 4. PREPARAÇÃO DA RESPOSTA
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $response = [
            'ok' => true,
            'total' => (int)$result['total'] // Retorna o total como um número inteiro
        ];
    } else {
        // Caso a consulta não retorne nada (o que é raro para um COUNT)
        $response['message'] = 'Nenhum resultado encontrado.';
    }
} catch (PDOException $e) {
    // Em caso de erro na conexão ou na query, retorna uma mensagem de erro clara
    $response['message'] = 'Erro de banco de dados: ' . $e->getMessage();
    // Em um ambiente de produção, seria bom registrar $e->getMessage() em um log em vez de exibi-lo ao usuário.
}

// 5. ENVIO DA RESPOSTA FINAL
// Converte o array de resposta em uma string JSON e a exibe
echo json_encode($response);
