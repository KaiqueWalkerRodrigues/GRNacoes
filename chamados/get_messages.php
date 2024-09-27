<?php 
    require '../class/Connection.php';
    require '../class/Conexao.php';
    require '../class/Usuario.php';
    require '../class/Chamados.php';

    $id_chamado = $_GET['id_chamado'];
    $id_usuario = $_GET['id_usuario']; // Usuário logado (quem está vendo o chat)
    $id_avatar = $_GET['id_avatar'];
    $id_destinatario_avatar = $_GET['id_destinatario_avatar'];

    $Chamado = new Chamados();
    $Usuario = new Usuario();

    // Obtem informações do chamado
    $chamado = $Chamado->mostrar($id_chamado);

    // ID do usuário que abriu o chamado
    $id_usuario_dono_chamado = $chamado->id_usuario;

    // Obtem setor do usuário logado
    $id_setor_usuario_logado = $Usuario->mostrar($id_usuario)->id_setor;
    
    // Consultar as mensagens
    $sql = $pdo->query("SELECT m.*, DATE_FORMAT(m.created_at, '%H:%i') as hora_envio, DATE_FORMAT(m.created_at, '%d/%m/%Y') as data_envio 
            FROM mensagens m
            INNER JOIN chamados_mensagens cm ON cm.id_mensagem = m.id_mensagem
            WHERE cm.id_chamado = $id_chamado AND m.deleted_at IS NULL
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

            // Informações do remetente da mensagem
            $id_usuario_remetente = $value['id_usuario'];

            // Nome do remetente
            $mandante = $Usuario->mostrar($id_usuario_remetente);
            $nomeCompleto = $mandante->nome;
            $partesNome = explode(' ', $nomeCompleto);
            $primeiroNome = $partesNome[0];
            $ultimoNome = array_pop($partesNome);

            // Verifica se o usuário logado é o criador do chamado
            if ($id_usuario == $id_usuario_dono_chamado) {
                // Se o usuário logado for o criador do chamado
                if ($id_usuario_remetente == $id_usuario) {
                    // Mensagens do próprio criador do chamado ficam no lado azul
                    $class = 'eu';
                    echo '
                        <div class="icon-container '.$class.'">
                            <div class="card p-2 mb-2 position-relative">'
                                .nl2br(htmlspecialchars($value['mensagem'])).'
                                <span class="hora-enviada" style="font-size: 10px; position: absolute; bottom: 5px; right: 10px;">'.$primeiroNome.' '.$ultimoNome.' - '.$hora_envio.'</span>
                            </div>
                            <img class="perfil-icon mb-2" src="../img/avatar/'.$mandante->id_avatar.'.png" alt="">
                        </div>';
                } else {
                    // Mensagens dos outros usuários ficam no lado cinza
                    $class = 'outro';
                    echo '
                        <div class="icon-container '.$class.'">
                            <img class="perfil-icon mb-2" src="../img/avatar/'.$mandante->id_avatar.'.png" alt="">
                            <div class="card p-2 mb-2 position-relative">'
                                .nl2br(htmlspecialchars($value['mensagem'])).'
                                <span class="hora-enviada" style="font-size: 10px; position: absolute; bottom: 5px; right: 10px;">'.$primeiroNome.' '.$ultimoNome.' - '.$hora_envio.'</span>
                            </div>
                        </div>';
                }
            } else {
                // Se o usuário logado **não** for o criador do chamado
                if ($id_usuario_remetente == $id_usuario_dono_chamado) {
                    // Mensagens do criador do chamado ficam no lado cinza
                    $class = 'outro';
                    echo '
                        <div class="icon-container '.$class.'">
                            <img class="perfil-icon mb-2" src="../img/avatar/'.$mandante->id_avatar.'.png" alt="">
                            <div class="card p-2 mb-2 position-relative">'
                                .nl2br(htmlspecialchars($value['mensagem'])).'
                                <span class="hora-enviada" style="font-size: 10px; position: absolute; bottom: 5px; right: 10px;">'.$primeiroNome.' '.$ultimoNome.' - '.$hora_envio.'</span>
                            </div>
                        </div>';
                } else {
                    // Mensagens dos outros usuários, inclusive o logado, ficam no lado azul
                    $class = 'eu';
                    echo '
                        <div class="icon-container '.$class.'">
                            <div class="card p-2 mb-2 position-relative">'
                                .nl2br(htmlspecialchars($value['mensagem'])).'
                                <span class="hora-enviada" style="font-size: 10px; position: absolute; bottom: 5px; right: 10px;">'.$primeiroNome.' '.$ultimoNome.' - '.$hora_envio.'</span>
                            </div>
                            <img class="perfil-icon mb-2" src="../img/avatar/'.$mandante->id_avatar.'.png" alt="">
                        </div>';
                }
            }
        }
    }
?>
