<?php
$arquivo = $_GET['file'] ?? '';
$nome_original = $_GET['nome'] ?? basename($arquivo);

$caminho = __DIR__ . '/resources/anexos/chats/' . $arquivo;

if (file_exists($caminho)) {
    header("Content-Type: application/pdf");
    header("Content-Disposition: inline; filename=\"" . basename($nome_original) . "\"");
    readfile($caminho);
    exit;
}
