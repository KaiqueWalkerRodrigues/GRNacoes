<?php 
session_start();

require '../../const.php';

$pdo = Conexao::conexao();

$id_chamado = $_GET['id_chamado'];
$id_usuario_logado = $_GET['id_usuario']; // Usuário logado

$Chamado = new Chamado();

// Obtem informações do chamado
$chamado = $Chamado->mostrar($id_chamado);

// ID do usuário que criou o chamado
$id_usuario_dono_chamado = $chamado->id_usuario;

// Setor do usuário logado
$setor_logado = $_SESSION['id_setor'] ?? null;

/**
 * Agora buscamos:
 * - dados da mensagem (m.*)
 * - nome do remetente, setor e avatar (u.nome, u.id_setor, u.id_avatar)
 * - hora e data formatadas
 */
$sql = $pdo->prepare("
    SELECT 
        m.*,
        u.id_avatar AS avatar_mandante,
        DATE_FORMAT(m.created_at, '%H:%i') AS hora_envio, 
        DATE_FORMAT(m.created_at, '%d/%m/%Y') AS data_envio
    FROM mensagens m
    INNER JOIN chamados_mensagens cm 
        ON cm.id_mensagem = m.id_mensagem
    INNER JOIN usuarios u 
        ON u.id_usuario = m.id_usuario
    WHERE cm.id_chamado = :id_chamado
      AND m.deleted_at IS NULL
    ORDER BY m.created_at ASC
");

$sql->bindParam(':id_chamado', $id_chamado, PDO::PARAM_INT);
$sql->execute();

if ($sql->rowCount() > 0) {
    $mensagens = $sql->fetchAll(PDO::FETCH_ASSOC);
    $dia_anterior = '';

    foreach ($mensagens as $value) {
        $data_envio = $value['data_envio'];
        $hora_envio = $value['hora_envio'];

        // Hoje / Ontem / Data
        $data_hoje  = date('d/m/Y');
        $data_ontem = date('d/m/Y', strtotime('-1 day'));

        if ($data_envio === $data_hoje) {
            $data_display = 'Hoje';
        } elseif ($data_envio === $data_ontem) {
            $data_display = 'Ontem';
        } else {
            $data_display = $data_envio;
        }

        // Separador de dia
        if ($data_display !== $dia_anterior) {
            echo '<div style="text-align: center; margin: 10px 0; color: #000000;">'.$data_display.'</div>';
            $dia_anterior = $data_display;
        }

        // Remetente
        $id_usuario_remetente = (int)$value['id_usuario'];
        $nomeCompleto         = $value['nome_remetente'] ?? '';
        $partesNome           = preg_split('/\s+/', trim($nomeCompleto));
        $primeiroNome         = $partesNome[0] ?? '';
        $ultimoNome           = count($partesNome) > 1 ? end($partesNome) : '';

        // Setor e avatar do remetente vindos do SELECT (corrige o bug de usar o setor da sessão)
        $setor_mandante       = $value['setor_mandante'] ?? null;
        $id_avatar_mandante   = $value['avatar_mandante'] ?? 1; // fallback 1

        // Regras de exibição (mantidas)
        $is_usuario_logado = ($id_usuario_remetente === (int)$id_usuario_logado);
        $is_dono_chamado_mesmo_setor = (
            $id_usuario_remetente === (int)$id_usuario_dono_chamado &&
            $setor_mandante === $setor_logado &&
            !$is_usuario_logado
        );

        if ($is_usuario_logado) {
            $class = 'eu'; // azul
        } elseif ($setor_mandante === $setor_logado && !$is_dono_chamado_mesmo_setor) {
            $class = 'eu'; // mesma cor para quem é do mesmo setor
        } else {
            $class = 'outro'; // cinza
        }

        // Mostre sempre o avatar do REMETENTE da mensagem
        $img_avatar = URL_RESOURCES.'/assets/img/avatars/'.$id_avatar_mandante.'.png';

        // HTML
        if ($class === 'eu') {
            echo '
            <div class="icon-container row eu">
                <div style="width: 95%;">
                    <div class="card p-2 mb-2 position-relative">'.
                        nl2br(htmlspecialchars($value['mensagem'])).
                        '<span class="hora-enviada" style="font-size: 10px; position: absolute; bottom: 5px; right: 10px;">'.
                            $primeiroNome.' '.$ultimoNome.' - '.$hora_envio.
                        '</span>
                    </div>
                </div>
                <div style="width: 5%;">
                    <img class="btn-icon btn-md mb-2" src="'.$img_avatar.'" alt="">
                </div>
            </div>';
        } else {
            echo '
            <div class="icon-container row outro">
                <div style="width: 5%;">
                    <img class="btn-icon btn-md mb-2" src="'.$img_avatar.'" alt="">
                </div>
                <div style="width: 95%;">
                    <div class="card p-2 mb-2 position-relative">'.
                        nl2br(htmlspecialchars($value['mensagem'])).
                        '<span class="hora-enviada" style="font-size: 10px; position: absolute; bottom: 5px; right: 10px;">'.
                            $primeiroNome.' '.$ultimoNome.' - '.$hora_envio.
                        '</span>
                    </div>
                </div>
            </div>';
        }
    }
}
?>
