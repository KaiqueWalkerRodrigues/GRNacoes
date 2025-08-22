<?php 
require_once '../../const.php';

session_start();

header('Content-Type: application/json; charset=utf-8');

$id_usuario = $_SESSION['id_usuario'];
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';

$Usuario = new Usuario();
$Setor = new Setor();
$Chat = new Conversa();
$Mensagem = new Mensagem();

try {
    if (empty($search_term)) {
        echo json_encode(['ok' => true, 'users' => []]);
        exit;
    }

    $pdo = Conexao::conexao();

    // Buscar usuários pelo nome (exceto o próprio)
    $sql = $pdo->prepare("
        SELECT u.id_usuario, u.nome, u.id_avatar, s.setor
        FROM usuarios u
        LEFT JOIN usuarios_setores us 
               ON u.id_usuario = us.id_usuario AND us.principal = 1
        LEFT JOIN setores s 
               ON us.id_setor = s.id_setor
        WHERE u.id_usuario != :id_usuario 
          AND u.ativo = 1
          AND u.nome LIKE :search_term
        ORDER BY u.nome ASC
        LIMIT 20
    ");
    
    $search_param = '%' . $search_term . '%';
    $sql->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $sql->bindParam(':search_term', $search_param, PDO::PARAM_STR);
    $sql->execute();
    
    $usuarios = $sql->fetchAll(PDO::FETCH_ASSOC);
    
    $result = [];
    
    foreach ($usuarios as $usuario) {
        $conversa_existente = null;
        $ultima_mensagem = null;
        $qtd_nao_lidas = 0;

        // 1) Encontrar conversa privada existente entre os dois usuários (conversas + participantes)
        $sql_conversa = $pdo->prepare("
            SELECT c.id_conversa
            FROM conversas c
            INNER JOIN participantes p1 
                    ON p1.id_conversa = c.id_conversa 
                   AND p1.id_usuario  = :id_usuario
            INNER JOIN participantes p2 
                    ON p2.id_conversa = c.id_conversa 
                   AND p2.id_usuario  = :id_destinatario
            WHERE c.tipo = 'privado'
              AND (c.deleted_at IS NULL OR c.deleted_at = '0000-00-00 00:00:00')
            LIMIT 1
        ");
        $sql_conversa->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $sql_conversa->bindParam(':id_destinatario', $usuario['id_usuario'], PDO::PARAM_INT);
        $sql_conversa->execute();

        $conversa = $sql_conversa->fetch(PDO::FETCH_ASSOC);

        if ($conversa) {
            $conversa_existente = (int)$conversa['id_conversa'];

            // 2) Última mensagem da conversa (mensagens + conversas_mensagens)
            $sql_ultima = $pdo->prepare("
                SELECT 
                    m.mensagem,
                    m.created_at,
                    m.id_usuario AS author_id,
                    CASE 
                        WHEN ml.id_mensagem IS NOT NULL THEN 'lida'
                        ELSE 'nao_lida'
                    END AS status_leitura
                FROM conversas_mensagens cm
                INNER JOIN mensagens m 
                        ON m.id_mensagem = cm.id_mensagem
                LEFT JOIN mensagens_lidas ml 
                        ON ml.id_mensagem = m.id_mensagem
                       AND ml.id_usuario != :id_usuario
                WHERE cm.id_conversa = :id_conversa
                  AND (m.deleted_at IS NULL OR m.deleted_at = '0000-00-00 00:00:00')
                ORDER BY m.created_at DESC
                LIMIT 1
            ");
            // Observação: se quiser o status de leitura do ponto de vista do usuário atual,
            // troque a condição do LEFT JOIN para `ml.id_usuario = :id_usuario`.
            $sql_ultima->bindParam(':id_conversa', $conversa_existente, PDO::PARAM_INT);
            $sql_ultima->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $sql_ultima->execute();

            $ultima_mensagem = $sql_ultima->fetch(PDO::FETCH_ASSOC);

            // 3) Quantidade de não lidas (sua função de domínio)
            $qtd_nao_lidas = (int)$Mensagem->contarNaoLidasPorConversa($conversa_existente, (int)$id_usuario);
        }
        
        // 4) Formatar data da última mensagem
        $data_envio = '';
        if ($ultima_mensagem) {
            $hoje = date('Y-m-d');
            $ontem = date('Y-m-d', strtotime('-1 day'));
            $data_mensagem = date('Y-m-d', strtotime($ultima_mensagem['created_at']));
            
            if ($data_mensagem == $hoje) {
                $data_envio = date('H:i', strtotime($ultima_mensagem['created_at']));
            } elseif ($data_mensagem == $ontem) {
                $data_envio = 'Ontem';
            } else {
                $data_envio = date('d/m/Y', strtotime($ultima_mensagem['created_at']));
            }
        }
        
        $result[] = [
            'id_usuario'      => (int)$usuario['id_usuario'],
            'nome'            => $usuario['nome'],
            'id_avatar'       => $usuario['id_avatar'],
            'setor'           => $usuario['setor'] ?: 'Sem setor',
            'id_conversa'     => $conversa_existente,
            'tem_conversa'    => $conversa_existente !== null,
            'ultima_mensagem' => $ultima_mensagem ? $ultima_mensagem['mensagem'] : null,
            'data_envio'      => $data_envio,
            'qtd_nao_lidas'   => $conversa_existente ? ($qtd_nao_lidas ?: 0) : 0,
            'status_leitura'  => $ultima_mensagem ? $ultima_mensagem['status_leitura'] : null,
            'author_id'       => $ultima_mensagem ? (int)$ultima_mensagem['author_id'] : null
        ];
    }
    
    echo json_encode(['ok' => true, 'users' => $result]);

} catch (Exception $e) {
    echo json_encode(['ok' => false, 'error' => 'Erro interno: ' . $e->getMessage()]);
}
?>
