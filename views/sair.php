<?php
// Inicia a sessão
session_start();

// Limpa todas as variáveis de sessão
session_unset();

// Destrói a sessão
session_destroy();

// Redireciona para uma página após remover a sessão, se necessário
header("Location:".URL."/login");
exit();
?>
