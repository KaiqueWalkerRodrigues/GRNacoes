<?php 
    require '../../const.php';

    $pdo = Conexao::conexao();

    $id_conversa = $_GET['id_conversa'];
    $id_usuario = $_GET['id_usuario'];
    $id_avatar = $_GET['id_avatar'];
    $id_avatar_destinatario = $_GET['id_avatar_destinatario'];

    // Preparando a consulta para buscar as mensagens e o status de leitura
    $sql = $pdo->prepare("
        SELECT m.*, 
        DATE_FORMAT(m.created_at, '%H:%i') as hora_envio, 
        DATE_FORMAT(m.created_at, '%d/%m/%Y') as data_envio,
        CASE 
            WHEN ml.id_mensagem IS NOT NULL THEN 'lida'
            ELSE 'nao_lida'
        END as status_leitura
        FROM mensagens m
        INNER JOIN conversas_mensagens cm ON cm.id_mensagem = m.id_mensagem
        LEFT JOIN mensagens_lidas ml ON ml.id_mensagem = m.id_mensagem AND ml.id_usuario != :id_usuario
        WHERE cm.id_conversa = :id_conversa AND m.deleted_at IS NULL
        ORDER BY m.created_at ASC
    ");

    // Bind do parâmetro id_conversa e id_usuario
    $sql->bindParam(':id_conversa', $id_conversa, PDO::PARAM_INT);
    $sql->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $sql->execute();

    if($sql->rowCount() > 0) {
        $mensagens = $sql->fetchAll();
        $dia_anterior = '';

        foreach($mensagens as $value){
            $data_envio = $value['data_envio'];
            $hora_envio = $value['hora_envio'];

            // Comparar a data de envio com hoje e ontem
            $data_hoje = date('d/m/Y');
            $data_ontem = date('d/m/Y', strtotime('-1 day'));

            if ($data_envio == $data_hoje) {
                $data_display = 'Hoje';
            } elseif ($data_envio == $data_ontem) {
                $data_display = 'Ontem';
            } else {
                $data_display = $data_envio;
            }

            // Inserir separador de dia se o dia mudou
            if($data_display != $dia_anterior){
                echo '<div class="date-separator"><span>'.$data_display.'</span></div>';
                $dia_anterior = $data_display;
            }

            // Determinar se a mensagem é do usuário ou do destinatário
            $messageType = $id_usuario == $value['id_usuario'] ? 'sent' : 'received';

            // Determinar o ícone de status de visualização
            if ($value['status_leitura'] == 'lida') {
                $icone_leitura = '<i class="fa-solid fa-check" style="color: #53bdeb;"></i>'; // Check azul para lida
            } else {
                $icone_leitura = '<i class="fa-solid fa-check" style="color: #92a3ab;"></i>'; // Check cinza para recebida, mas não lida
            }

            echo '<div class="message-container '.$messageType.'" data-id-msg="'.$value['id_mensagem'].'">';
            echo '    <div class="message-bubble '.$messageType.'">';
            echo '        <div class="message-text">'.nl2br(htmlspecialchars($value['mensagem'])).'</div>';
            echo '        <div class="message-time">';
            echo '            <span>'.$hora_envio.'</span>';
            
            // Mostrar status apenas para mensagens enviadas por mim
            if($id_usuario == $value['id_usuario']){
                echo '            <span class="message-status" id="status-'.$value['id_mensagem'].'">' . $icone_leitura . '</span>';
            }
            
            echo '        </div>';
            echo '    </div>';
            echo '</div>';
        }

        // Agora marcar as mensagens não lidas como lidas
        $sql_lidas = $pdo->prepare("
            INSERT INTO mensagens_lidas (id_usuario, id_mensagem, lida_em)
            SELECT :id_usuario, m.id_mensagem, NOW()
            FROM mensagens m
            INNER JOIN conversas_mensagens cm ON cm.id_mensagem = m.id_mensagem
            LEFT JOIN mensagens_lidas ml ON ml.id_mensagem = m.id_mensagem AND ml.id_usuario = :id_usuario
            WHERE cm.id_conversa = :id_conversa AND ml.id_mensagem IS NULL
        ");
        $sql_lidas->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $sql_lidas->bindParam(':id_conversa', $id_conversa, PDO::PARAM_INT);
        $sql_lidas->execute();
    } else {
        echo '<div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; color: #667781; text-align: center; padding: 40px;">';
        echo '    <i class="fas fa-comments" style="font-size: 60px; margin-bottom: 20px; opacity: 0.3;"></i>';
        echo '    <p>Nenhuma mensagem ainda.<br>Seja o primeiro a enviar uma mensagem!</p>';
        echo '</div>';
    }
?>

