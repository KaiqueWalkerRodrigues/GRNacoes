<?php 

require_once '../../const.php';

session_start();

$id_usuario = $_SESSION['id_usuario'];
$current_chat = isset($_GET['current_chat']) ? $_GET['current_chat'] : null;

$Setor = new Setor();
$Chats = new Conversa();
$Usuario = new Usuario();
$Setores = new Setor();
$Mensagem = new Mensagem(); 

// Preparando a listagem de conversas
$chats = $Chats->listar($id_usuario);

?>

<?php foreach($chats as $chat): ?>
    <?php 
    $qtdNaoLidas = $Mensagem->contarNaoLidasPorConversa((int)$chat->id_conversa, (int)$id_usuario);
    $mostrarBadge = $qtdNaoLidas > 0;
    $badgeTexto = $qtdNaoLidas > 99 ? '99+' : (string)$qtdNaoLidas;
    
    // Recuperar destinatário
    $destinatario = $Usuario->mostrar($Chats->destinatario($chat->id_conversa, $id_usuario)->id_usuario); 
    
    // Recuperar última mensagem
    $ultimaMensagem = $Mensagem->ultimaMensagem($chat->id_conversa, $id_usuario);
    
    // Definir a data ou hora a ser exibida
    $dataEnvio = '';
    $hoje = date('Y-m-d');
    $ontem = date('Y-m-d', strtotime('-1 day'));
    
    if ($ultimaMensagem) {
        $dataMensagem = date('Y-m-d', strtotime($ultimaMensagem->created_at));
        
        if ($dataMensagem == $hoje) {
            $dataEnvio = date('H:i', strtotime($ultimaMensagem->created_at));
        } elseif ($dataMensagem == $ontem) {
            $dataEnvio = 'Ontem';
        } else {
            $dataEnvio = date('d/m/Y', strtotime($ultimaMensagem->created_at));
        }

        // Mostrar ícone apenas se a última mensagem foi enviada por mim
        if ($ultimaMensagem->id_usuario == $id_usuario) {
            $iconeLeitura = $ultimaMensagem->status_leitura == 'lida' 
                ? '<i class="fa-solid fa-check" style="color: #53bdeb; margin-right: 4px;"></i>' 
                : '<i class="fa-solid fa-check" style="color: #808080; margin-right: 4px;"></i>';
        } else {
            $iconeLeitura = ''; // não mostra nada
        }
    }
    
    // Verificar se é o chat ativo
    $isActive = ($current_chat == $chat->id_conversa) ? 'active' : '';
    ?>

    <a href="javascript:void(0)" 
       class="contact-item <?php echo $isActive ?>"
       onclick="selecionarChat(<?php echo $chat->id_conversa ?>, <?php echo $destinatario->id_usuario ?>, '<?php echo addslashes($destinatario->nome) ?>', '<?php echo $destinatario->id_avatar ?>')"
       data-chat-id="<?php echo $chat->id_conversa ?>"
       data-destinatario-id="<?php echo $destinatario->id_usuario ?>"
       data-destinatario-nome="<?php echo addslashes($destinatario->nome) ?>"
       data-destinatario-avatar="<?php echo $destinatario->id_avatar ?>">
        <div style="display: flex; align-items: center;">
            <div class="contact-avatar-wrap">
                <img class="contact-avatar" src="<?php echo URL_RESOURCES ?>/assets/img/avatars/<?php echo $destinatario->id_avatar ?>.png" alt="">
                <span class="online-dot" id="online-<?php echo (int)$destinatario->id_usuario ?>"></span>
            </div>
            <div class="contact-info">
                <div class="contact-name"><?php echo $destinatario->nome ?></div>
                <div class="contact-last-message">
                    <?php if($ultimaMensagem != false): ?>
                        <?php echo $iconeLeitura; ?>
                        <?php echo mb_strlen($ultimaMensagem->mensagem) > 30 ? mb_substr($ultimaMensagem->mensagem, 0, 30) . '...' : $ultimaMensagem->mensagem; ?>
                    <?php else: ?>
                        <em>Nenhuma mensagem</em>
                    <?php endif; ?>
                </div>
            </div>
            <div class="contact-meta">
                <?php if ($mostrarBadge): ?>
                    <div class="unread-badge"><?php echo $badgeTexto; ?></div>
                <?php endif; ?>
                <div class="contact-time"><?php echo $dataEnvio; ?></div>
            </div>
        </div>
    </a>
    
<?php endforeach; ?>

<?php if(empty($chats)): ?>
    <div style="padding: 40px 20px; text-align: center; color: #667781;">
        <i class="fas fa-comments" style="font-size: 48px; margin-bottom: 16px; opacity: 0.3;"></i>
        <p>Nenhuma conversa encontrada.<br>Clique no botão "+" para iniciar um novo chat.</p>
    </div>
<?php endif; ?>

