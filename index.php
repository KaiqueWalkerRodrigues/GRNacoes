<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
<?php
// index.php

// Recupera a URL da variável GET
$url = isset($_GET['url']) ? $_GET['url'] : '/';

// Lista de rotas disponíveis
$rotas = [
    '/' => 'home.php',
    'erro' => 'erro.php',
    'chats' => 'chats.php',
    'chamados' => 'chamados.php',
    'compras/nota' => 'compras/notas',
    'compras/fornecedores' => 'compras/fornecedoress',
    'configuracoes/cargos' => 'configuracoes/cargos',
    'configuracoes/setores' => 'configuracoes/setores',
    'configuracoes/usuarios' => 'configuracoes/usuarios',
    'chamados/chat' => 'chamados/chat',
    'chamados/meus_chamados' => 'chamados/meus_chamados',
];

// Verifica se a rota existe na lista e se o arquivo associado à rota existe
if (array_key_exists($url, $rotas) && file_exists($rotas[$url])) {
    // Inclui o conteúdo da página correspondente à rota
    include $rotas[$url];
} else {
    // Se a rota não existe ou o arquivo correspondente não existe, inclui a página de erro
    include 'erro.php';
}
?>