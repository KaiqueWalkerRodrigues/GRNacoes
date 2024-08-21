<?php 
    require 'class/Connection.php';

    $id_conversa = $_GET['id_conversa'];
    $id_usuario = $_GET['id_usuario'];
    $id_avatar = $_GET['id_avatar'];
    $id_avatar_destinatario = $_GET['id_avatar_destinatario'];

    $sql = $pdo->query("SELECT m.*, DATE_FORMAT(m.created_at, '%H:%i') as hora_envio, DATE_FORMAT(m.created_at, '%d/%m/%Y') as data_envio 
            FROM mensagens m
            INNER JOIN conversas_mensagens cm ON cm.id_mensagem = m.id_mensagem
            WHERE cm.id_conversa = $id_conversa AND m.deleted_at IS NULL
            ORDER BY m.created_at ASC");
    
    if($sql->rowCount() > 0){
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

            $class = $id_usuario == $value['id_usuario'] ? 'eu' : 'outro';
            if($id_usuario == $value['id_usuario']){
                echo '
                    <div class="icon-container '.$class.'">
                        <div class="card p-2 mb-2 position-relative">'
                            .nl2br(htmlspecialchars($value['mensagem'])).'
                            <span class="hora-enviada" style="font-size: 10px; position: absolute; bottom: 5px; right: 10px; color: #ffffff;">'.$hora_envio.'</span>
                        </div>
                        <img class="perfil-icon mb-2" src="img/avatar/'.$id_avatar.'.png" alt="">
                    </div>';
            } else {
                echo '
                    <div class="icon-container '.$class.'">
                        <img class="perfil-icon mb-2" src="img/avatar/'.$id_avatar_destinatario.'.png" alt="">
                        <div class="card p-2 mb-2 position-relative">'
                            .$value['mensagem'].'
                            <span class="hora-enviada" style="font-size: 10px; position: absolute; bottom: 5px; right: 10px; color: #999;">'.$hora_envio.'</span>
                        </div>
                    </div>';
            }
        }
    }
?>
