<?php 
require 'class/Connection.php';
require 'class/Conexao.php';
require 'class/Conversas.php';
require 'class/Usuario.php';
require 'class/Setor.php';
require 'class/Mensagens.php';  // Incluindo a classe Mensagens

session_start();
$id_usuario = $_SESSION['id_usuario'];

$Chats = new Conversa();
$Usuarios = new Usuario();
$Setores = new Setor();
$Mensagens = new Mensagem();  // Instanciando a classe Mensagem

// Preparando a listagem de conversas
$chats = $Chats->listar($id_usuario);

?>

<div id="chats-list">
    <?php foreach($chats as $chat): ?>
        <?php 
        // Recuperar destinatário
        $destinatario = $Usuarios->mostrar($Chats->destinatario($chat->id_conversa, $id_usuario)->id_usuario); 
        
        // Recuperar última mensagem
        $ultimaMensagem = $Mensagens->ultimaMensagem($chat->id_conversa, $id_usuario);
        
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

        <a href="chat.php?id=<?php echo $chat->id_conversa ?>&id_destinatario=<?php echo $destinatario->id_usuario ?>" class="chat card text-decoration-none">
            <div class="my-3 border-1">
                <div class="row align-items-center">
                    <div class="col-1">
                        <img class="img-profile rounded-circle p-2 ml-3" width="95%" src="img/avatar/<?php echo $destinatario->id_avatar ?>.png">
                    </div>
                    <div class="col-6 text-start">
                        <p class="text-dark mt-3" style="margin-left: 10px; font-size: 18px"><?php echo ($chat->nome ?? $destinatario->nome); ?></p>
                        <p class="text-muted" style="margin-left: 10px;">
                            <?php 
                                if (isset($ultimaMensagem->id_usuario)){
                                    if($ultimaMensagem->id_usuario == $_SESSION['id_usuario']) { 
                                        echo $iconeLeitura.' '; 
                                    } else { 
                                        echo ''; 
                                    } 
                                }else{
                                    echo '';
                                }
                            ?>
                            <?php echo htmlspecialchars($ultimaMensagem->mensagem ?? ''); ?>
                        </p>
                    </div>
                    <div class="col-2 text-center">
                        <p class="text-dark mt-3"><?php echo $Setores->mostrar($destinatario->id_setor)->setor; ?></p>
                    </div>
                    <div class="col-2 offset-1 text-center">
                        <p class="text-dark mt-3"><?php echo $dataEnvio; ?></p>
                    </div>
                </div>
            </div>
        </a>
    <?php endforeach; ?>
</div>
