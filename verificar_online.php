<?php 
date_default_timezone_set('America/Sao_Paulo');

require 'class/Connection.php';

$agora = new DateTime(); 
$id_destinatario = $_GET['id_destinatario'];

$sql = $pdo->query("SELECT online_at FROM usuarios WHERE id_usuario = $id_destinatario");

$online_at = $sql->fetchColumn(); 

if ($online_at) {
    $ultimaOnline = new DateTime($online_at);
    $intervalo = $ultimaOnline->diff($agora);

    $minutosDiferenca = ($intervalo->days * 24 * 60) + ($intervalo->h * 60) + $intervalo->i;

    if ($minutosDiferenca >= 1) {
        if ($intervalo->y > 0) {
            echo "Última vez online há " . $intervalo->y . " ano(s)";
        } elseif ($intervalo->m > 0) {
            echo "Última vez online há " . $intervalo->m . " mês(es)";
        } elseif ($intervalo->d > 0) {
            echo "Última vez online há " . $intervalo->d . " dia(s)";
        } elseif ($intervalo->h > 0) {
            echo "Última vez online há " . $intervalo->h . " hora(s)";
        } elseif ($intervalo->i > 0) {
            echo "Última vez online há " . $intervalo->i . " minuto(s)";
        }
    } else {
        echo "<span class='text-success'>Online</span>";
    }
} else {
    echo "Offline";
}
?>
