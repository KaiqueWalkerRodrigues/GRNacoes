<?php 
require 'class/Connection.php';

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

        // Inserir separador de dia se o dia mudou
        if($data_envio != $dia_anterior){
            echo '<div style="text-align: center; margin: 10px 0; color: #000000;">'.$data_envio.'</div>';
            $dia_anterior = $data_envio;
        }

        // Determinar se a mensagem é do usuário ou do destinatário
        $class = $id_usuario == $value['id_usuario'] ? 'eu' : 'outro';

        // Determinar o ícone de status de visualização
        if ($value['status_leitura'] == 'lida') {
            $icone_leitura = '<i class="fa-solid fa-check" style="color: #00ffb3;"></i>'; // Check verde para lida
        } else {
            $icone_leitura = '<i class="fa-solid fa-check" style="color: #FFFFFF;"></i>'; // Check branco para recebida, mas não lida
        }

        // Exibir a mensagem com o ícone de visualização
        if($id_usuario == $value['id_usuario']){
            echo '
                <div class="icon-container '.$class.'">
                    <div class="card p-2 mb-2 position-relative">'
                        .nl2br(htmlspecialchars($value['mensagem'])).'
                        <span class="hora-enviada" style="font-size: 10px; position: absolute; bottom: 5px; right: 10px; color: #ffffff;">'
                        .$hora_envio.' '.$icone_leitura.'
                        </span>
                    </div>
                    <img class="perfil-icon mb-2" src="img/avatar/'.$id_avatar.'.png" alt="">
                </div>';
        } else {
            echo '
                <div class="icon-container '.$class.'">
                    <img class="perfil-icon mb-2" src="img/avatar/'.$id_avatar_destinatario.'.png" alt="">
                    <div class="card p-2 mb-2 position-relative">'
                        .nl2br(htmlspecialchars($value['mensagem'])).'
                        <span class="hora-enviada" style="font-size: 10px; position: absolute; bottom: 5px; right: 10px; color: #999;">'.$hora_envio.'</span>
                    </div>
                </div>';
        }
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
}
?>
