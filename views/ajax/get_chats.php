<style>
    .time-unread {
    gap: 8px;                   /* espaço entre o badge e a hora */
    min-width: 80px;            /* opcional: ajuda a alinhar a coluna direita */
    }

    .badge-unread {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 20px;
    height: 20px;
    padding: 0 6px;             /* cresce para 2+ dígitos (vira “pílula”) */
    border-radius: 999px;       /* círculo/pílula */
    background: #25D366;        /* verde WhatsApp */
    color: #fff;
    font-weight: 700;
    font-size: 12px;
    line-height: 20px;
    }

    .last-time {
    font-size: 12px;
    color: gray;
    }
</style>

<?php 

require_once '../../const.php';

session_start();

$id_usuario = $_SESSION['id_usuario'];

$Setor = new Setor();
$Chats = new Conversa();
$Usuario = new Usuario();
$Setores = new Setor();
$Mensagem = new Mensagem(); 

// Preparando a listagem de conversas
$chats = $Chats->listar($id_usuario);

?>

<div id="chats-list">
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
                    ? '<i class="fa-solid fa-check" style="color: #00ffb3;"></i>' 
                    : '<i class="fa-solid fa-check" style="color: #808080;"></i>';
            } else {
                $iconeLeitura = ''; // não mostra nada
            }
        }
        ?>

        <div class="card col-12 p-3">
            <a href="<?php echo URL ?>/chat?id=<?php echo $chat->id_conversa ?>&id_destinatario=<?php echo $destinatario->id_usuario ?>" class="text-decoration-none text-dark">
                <div class="row">
                    <div class="col-1 text-center">
                        <img class="btn-icon btn-lg" src="<?php echo URL_RESOURCES ?>/assets/img/avatars/<?php echo $destinatario->id_avatar ?>.png" alt="">
                    </div>
                    <div class="col-9">
                        <b><?php echo $destinatario->nome ?> (<?php echo $Setor->mostrar($Usuario->mostrarSetorPrincipal($destinatario->id_usuario)->id_setor)->setor; ?>)</b>
                        <br>
                        <?php if($ultimaMensagem != false){ echo $iconeLeitura; } ?> <span style="color: gray;"><?php if($ultimaMensagem != false){ echo $ultimaMensagem->mensagem; }else{} ?></span>
                    </div>
                    <div class="col-2 text-center">
                        <br>
                        <div class="time-unread d-inline-flex align-items-center justify-content-end">
                            <?php if ($mostrarBadge): ?>
                                <span class="badge-unread" aria-label="<?php echo $badgeTexto; ?> mensagens não lidas">
                                    <?php echo $badgeTexto; ?>
                                </span>
                            <?php endif; ?>
                            <span class="last-time"><?php echo $dataEnvio; ?></span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        
    <?php endforeach; ?>
</div>
