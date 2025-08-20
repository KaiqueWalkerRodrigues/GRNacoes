<?php 
   session_start();
   require '../../const.php';

   $pdo = Conexao::conexao();

   $id_chamado = $_GET['id_chamado'];
   $id_usuario = $_GET['id_usuario']; // Usuário logado
   $id_avatar = $_GET['id_avatar']; // (permanece, mas não será usado no render)
   $id_destinatario_avatar = $_GET['id_destinatario_avatar']; // (permanece, mas não será usado no render)

   $Chamado = new Chamado();
   $Usuario = new Usuario();

   // Obtem informações do chamado
   $chamado = $Chamado->mostrar($id_chamado);

   // ID do usuário que criou o chamado
   $id_usuario_dono_chamado = $chamado->id_usuario;

   // Setor do usuário logado
   $setor_logado = $_SESSION['id_setor'];

   // Consultar as mensagens + avatar do remetente
   $sql = $pdo->prepare("
       SELECT 
           m.*,
           DATE_FORMAT(m.created_at, '%H:%i')   AS hora_envio, 
           DATE_FORMAT(m.created_at, '%d/%m/%Y') AS data_envio,
           u.id_avatar AS avatar_remetente
       FROM mensagens m
       INNER JOIN chamados_mensagens cm ON cm.id_mensagem = m.id_mensagem
       INNER JOIN usuarios u ON u.id_usuario = m.id_usuario
       WHERE cm.id_chamado = :id_chamado AND m.deleted_at IS NULL
       ORDER BY m.created_at ASC
   ");
   $sql->bindParam(':id_chamado', $id_chamado, PDO::PARAM_INT);
   $sql->execute();

   if ($sql->rowCount() > 0) {
       $mensagens = $sql->fetchAll();
       $dia_anterior = '';

       foreach ($mensagens as $value) {
           $data_envio = $value['data_envio'];
           $hora_envio = $value['hora_envio'];

           // Verificar se a data é hoje, ontem ou uma data antiga
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
           if ($data_display != $dia_anterior) {
               echo '<div style="text-align: center; margin: 10px 0; color: #000000;">'.$data_display.'</div>';
               $dia_anterior = $data_display;
           }

           // Informações do remetente da mensagem
           $id_usuario_remetente = $value['id_usuario'];

           // Nome do remetente (mantido como no seu código)
           $mandante = $Usuario->mostrar($id_usuario_remetente);
           $nomeCompleto = $mandante->nome;
           $partesNome = explode(' ', trim($nomeCompleto));
           $primeiroNome = $partesNome[0] ?? '';
           $ultimoNome = count($partesNome) > 1 ? array_pop($partesNome) : '';

           // Setor do remetente (mantido conforme sua lógica atual)
           $setor_mandante = $Usuario->mostrarSetorPrincipal($id_usuario_remetente)->id_setor;

           // Verificações de lado/cor (mantidas)
           $is_usuario_logado = $id_usuario_remetente == $id_usuario;
           $is_dono_chamado_mesmo_setor = $id_usuario_remetente == $id_usuario_dono_chamado && $setor_mandante == $setor_logado && $id_usuario_remetente != $id_usuario;

           if ($is_usuario_logado) {
               $class = 'eu'; // Classe azul para o usuário logado
           } elseif ($setor_mandante == $setor_logado && !$is_dono_chamado_mesmo_setor) {
               $class = 'eu'; // Mesma classe para quem é do mesmo setor
           } else {
               $class = 'outro'; // Classe cinza para qualquer outro usuário
           }

           // Avatar de quem enviou a mensagem (NOVO: vindo do JOIN)
           $avatar_remetente = (int)($value['avatar_remetente'] ?? 1);

           // Exibir a mensagem com os detalhes apropriados
           echo '
            <div class="icon-container row '.$class.'">
                '.($class == 'eu' ? '
                    <div style="width: 95%;">
                        <div class="card p-2 mb-2 position-relative">'.
                            nl2br(htmlspecialchars($value['mensagem'])).
                            '<span class="hora-enviada" style="font-size: 10px; position: absolute; bottom: 5px; right: 10px;">'.
                            $primeiroNome.' '.$ultimoNome.' - '.$hora_envio.
                            '</span>
                        </div>
                    </div>
                    <div style="width: 5%;">
                        <img class="btn-icon btn-md mb-2" src="'.URL_RESOURCES.'/assets/img/avatars/'.$avatar_remetente.'.png" alt="">
                    </div>
                ' : '
                    <div style="width: 5%;">
                        <img class="btn-icon btn-md mb-2" src="'.URL_RESOURCES.'/assets/img/avatars/'.$avatar_remetente.'.png" alt="">
                    </div>
                    <div style="width: 95%;">
                        <div class="card p-2 mb-2 position-relative">'.
                            nl2br(htmlspecialchars($value['mensagem'])).
                            '<span class="hora-enviada" style="font-size: 10px; position: absolute; bottom: 5px; right: 10px;">'.
                            $primeiroNome.' '.$ultimoNome.' - '.$hora_envio.
                            '</span>
                        </div>
                    </div>
                ').'
            </div>';
       }
   }
?>
