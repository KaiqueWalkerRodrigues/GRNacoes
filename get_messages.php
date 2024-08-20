<?php 
    require 'class/Connection.php';

    $id_conversa = $_GET['id_conversa'];
    $id_usuario = $_GET['id_usuario'];
    $id_avatar = $_GET['id_avatar'];
    $id_avatar_remetente = $_GET['id_avatar_remetente'];

    $sql = $pdo->query("SELECT m.* 
            FROM mensagens m
            INNER JOIN conversas_mensagens cm ON cm.id_mensagem = m.id_mensagem
            WHERE cm.id_conversa = $id_conversa AND m.deleted_at IS NULL
            ORDER BY m.created_at ASC");
    if($sql->rowCount() > 0){
        foreach($sql->fetchAll() as $value){
            $class = $id_usuario == $value['id_usuario'] ? 'eu' : 'outro';
            if($id_usuario == $value['id_usuario']){
                echo '
                    <div class="icon-container '.$class.'">
                        <img class="perfil-icon mb-2" src="img/avatar/'.$id_avatar.'.png" alt="">
                        <div class="card p-2 mb-2">'
                            .$value['mensagem'].'
                        </div>
                    </div>';
            }else{
                echo '
                    <div class="icon-container '.$class.'">
                        <div class="card p-2 mb-2">'
                            .$value['mensagem'].'
                        </div>
                        <img class="perfil-icon mb-2" src="img/avatar/'.$id_avatar_remetente.'.png" alt="">
                    </div>';
            }
        }
    }
?>
