<?php 
const URL = "/GRNacoes";

require 'class/Connection.php';
require 'class/Conexao.php';
require 'class/Usuario.php';

$Usuario = new Usuario();

$id_usuario = $_GET['id_usuario'];

// Preparando a consulta SQL para buscar as últimas 4 mensagens não lidas por usuário
$sql = $pdo->prepare("
    SELECT m.*, 
        p.id_usuario, 
        u.nome AS nome_usuario, 
        m.id_usuario AS id_usuario_mandante, 
        cm.id_conversa AS id_conversa,
        DATE_FORMAT(m.created_at, '%Y-%m-%d %H:%i:%s') as created_at_format
    FROM mensagens m
    JOIN conversas_mensagens cm ON cm.id_mensagem = m.id_mensagem
    JOIN conversas c ON c.id_conversa = cm.id_conversa
    JOIN participantes p ON p.id_conversa = c.id_conversa
    JOIN usuarios u ON u.id_usuario = p.id_usuario
    LEFT JOIN mensagens_lidas ml ON ml.id_mensagem = m.id_mensagem AND ml.id_usuario = :id_usuario
    WHERE p.id_usuario = :id_usuario
    AND m.id_usuario != :id_usuario
    AND ml.id_mensagem IS NULL
    AND m.deleted_at IS NULL
    AND p.deleted_at IS NULL
    AND m.created_at = (
        SELECT MAX(m2.created_at)
        FROM mensagens m2
        JOIN conversas_mensagens cm2 ON cm2.id_mensagem = m2.id_mensagem
        WHERE cm2.id_conversa = cm.id_conversa
        AND m2.id_usuario = m.id_usuario
        AND m2.deleted_at IS NULL
    )
    ORDER BY m.created_at DESC
    LIMIT 4;
");

// Bind dos parâmetros
$sql->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
$sql->execute();

if($sql->rowCount() > 0) {
    $mensagens = $sql->fetchAll();

    foreach($mensagens as $value){
        $data_envio = $value['created_at_format'];
        $mensagem = htmlspecialchars($value['mensagem']);
        $id_mandante = $value['id_usuario_mandante'];
        $id_conversa = $value['id_conversa'];
        $nome_usuario = $Usuario->mostrar($id_mandante)->nome;
        $id_avatar = $Usuario->mostrar($id_mandante)->id_avatar;

        // Cálculo de tempo relativo
        $data_envio_timestamp = strtotime($data_envio);
        $agora = time();
        $diferenca = $agora - $data_envio_timestamp;
        
        if ($diferenca < 60) {
            $tempo_relativo = "há " . $diferenca . " seg";
        } elseif ($diferenca < 3600) {
            $minutos = floor($diferenca / 60);
            $tempo_relativo = "há " . $minutos . " min";
        } elseif ($diferenca < 86400) {
            $horas = floor($diferenca / 3600);
            $tempo_relativo = "há " . $horas . " horas";
        } elseif ($diferenca < 604800) { // Menos de 1 semana
            $dias = floor($diferenca / 86400);
            $tempo_relativo = "há " . $dias . " dias";
        } else {
            // Se mais de 1 semana, mostrar data no formato 01/01/2001
            $tempo_relativo = date('d/m/Y', $data_envio_timestamp);
        }

        // Exibir a mensagem com o tempo relativo ou data
        echo '
        <a class="dropdown-item d-flex align-items-center" href="'.URL.'/chat?id='.$id_conversa.'&id_destinatario='.$id_mandante.'">
            <div class="dropdown-list-image mr-3">
                <img class="rounded-circle" src="/GRNacoes/img/avatar/'.$id_avatar.'" alt="...">
            </div>
            <div class="font-weight-bold">
                <div class="text-truncate">'.$mensagem.'</div>
                <div class="small text-gray-500">'.$nome_usuario.' · '.$tempo_relativo.'</div>
            </div>
        </a>';
    }
}
?>
