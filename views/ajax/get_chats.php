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

            // Verificar status de leitura e adicionar o ícone correspondente
            $iconeLeitura = $ultimaMensagem->status_leitura == 'lida' ? 
                '<i class="fa-solid fa-check" style="color: #00ffb3;"></i>' : 
                '<i class="fa-solid fa-check" style="color: #808080;"></i>';
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
                        <span style="color: gray;"><?php echo $dataEnvio ?></span>
                    </div>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
</div>
