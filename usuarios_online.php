<?php
require 'class/Conexao.php';
require 'class/Connection.php';
require 'class/Usuario.php';
const URL = "/GRNacoes";

// Cria uma instância da classe Usuario
$usuario = new Usuario();

// Consulta para buscar os usuários em ordem de online_at
$sql = $usuario->pdo->prepare("SELECT * FROM usuarios ORDER BY online_at DESC LIMIT 8");
$sql->execute();
$usuarios = $sql->fetchAll(PDO::FETCH_OBJ);

// Obtém a data e hora atual
$agora = new DateTime();

foreach ($usuarios as $user) {
    // Verifica se o valor de online_at não é nulo
    if (!is_null($user->online_at)) {
        // Verifica se o usuário está online comparando online_at com a data/hora atual
        $online_at = new DateTime($user->online_at);
        $intervalo = $online_at->diff($agora);
        $minutosDiferenca = ($intervalo->days * 24 * 60) + ($intervalo->h * 60) + $intervalo->i;

        echo '<div class="user d-flex align-items-center">';
        echo '<img src="' . URL . '/img/avatar/' . $user->id_avatar . '.png" alt="User Image" class="user-img">';
        echo '<div class="user-info ml-2">';
        echo '<p class="user-name mb-0">' . htmlspecialchars($user->nome) . '</p>';

        if ($minutosDiferenca >= 1) {
            if ($intervalo->y > 0) {
                echo '<span class="status">Última vez online há ' . $intervalo->y . ' ano(s)</span>';
            } elseif ($intervalo->m > 0) {
                echo '<span class="status">Última vez online há ' . $intervalo->m . ' mês(es)</span>';
            } elseif ($intervalo->d > 0) {
                echo '<span class="status">Última vez online há ' . $intervalo->d . ' dia(s)</span>';
            } elseif ($intervalo->h > 0) {
                echo '<span class="status">Última vez online há ' . $intervalo->h . ' hora(s)</span>';
            } elseif ($intervalo->i > 0) {
                echo '<span class="status">Última vez online há ' . $intervalo->i . ' minuto(s)</span>';
            }
        } else {
            echo '<span class="status online text-success">Online</span>';
        }

        echo '</div>';
        echo '</div>';
        echo '<hr>';
    }
}
?>
