<link rel="stylesheet" href="https://atugatran.github.io/FontAwesome6Pro/css/all.min.css" >
<link rel="icon" type="image/x-icon" href="/GRNacoes/resources/img/favicon.ico" />

<?php

include_once('const.php');

session_start();

// Configurações básicas
$baseProject = URL; // Caminho base do projeto
$baseDir = __DIR__ . '/views/'; // Diretório onde estão as páginas

$pageTitle = 'GRNacoes - ';

// Obter a URL amigável
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remover o caminho base da URL e quaisquer barras extras no início ou final
$route = trim(str_replace($baseProject, '', $requestUri), '/');

// Definir rotas e o arquivo correspondente
switch ($route) {
    case '':
        $file = 'home.php';
        $pageTitle .= "Home";
        $requiredSectors = null; // Acesso liberado para todos
        $requiredLogin = true;
        break;
    case 'login':
        $file = 'login.php';
        $pageTitle .= "Login";
        $requiredSectors = null;
        $requiredLogin = false;
        break;
    case 'logs':
        $file = 'logs.php';
        $pageTitle .= "Logs";
        $requiredSectors = [1];
        $requiredLogin = true;
        break;
    case 'perfil':
        $file = 'perfil.php';
        $pageTitle .= "Perfil";
        $requiredSectors = null;
        $requiredLogin = true;
        break;
    case 'sair':
        $file = 'sair.php';
        $pageTitle .= "Sair";
        $requiredSectors = null;
        $requiredLogin = true;
        break;

    case 'chats':
        $file = 'chats/chats.php';
        $pageTitle .= "Chats";
        $requiredSectors = null;
        $requiredLogin = true;
        break;
    case 'chat':
        $file = 'chats/chat.php';
        $pageTitle .= "Chats";
        $requiredSectors = null;
        $requiredLogin = true;
        break;

    case 'configuracoes/cargos':
        $file = 'configuracoes/cargos.php';
        $pageTitle .= "Gerenciar Cargos";
        $requiredSectors = [1,2,12,14];
        $requiredLogin = true;
        break;
    case 'configuracoes/convenios':
        $file = 'configuracoes/convenios.php';
        $pageTitle .= "Gerenciar Convênios";
        $requiredSectors = [1,2,12,14];
        $requiredLogin = true;
        break;
    case 'configuracoes/setores':
        $file = 'configuracoes/setores.php';
        $pageTitle .= "Gerenciar Setores";
        $requiredSectors = [1,2,12,14];
        $requiredLogin = true;
        break;
    case 'configuracoes/usuarios':
        $file = 'configuracoes/usuarios.php';
        $pageTitle .= "Gerenciar Usuarios";
        $requiredSectors = [1,2,12,14];
        $requiredLogin = true;
        break;
    case 'configuracoes/medicos':
        $file = 'configuracoes/medicos.php';
        $pageTitle .= "Gerenciar Médicos";
        $requiredSectors = [1,2,12,14];
        $requiredLogin = true;
        break;

    case 'dashboards/captacao':
        $file = 'dashboards/captacao.php';
        $pageTitle .= "Dashboard Captação";
        $requiredSectors = [1,2,12,14];
        $requiredLogin = true;
        break;

    case 'chamados':
        $file = 'chamados/index.php';
        $pageTitle .= "Chamados";
        $requiredSectors = null;
        $requiredLogin = true;
        break;
    case 'chamados/meus_chamados':
        $file = 'chamados/meus_chamados.php';
        $pageTitle .= "Meus Chamados";
        $requiredSectors = null;
        $requiredLogin = true;
        break;
    case 'chamados/todos':
        $file = 'chamados/todos.php';
        $pageTitle .= "Meus Chamados";
        $requiredSectors = null;
        $requiredLogin = true;
        break;
    case 'chamados/chat_chamado':
        $file = 'chamados/chat_chamado.php';
        $pageTitle .= "Chat Chamado";
        $requiredSectors = null;
        $requiredLogin = true;
        break;

    case 'compras/pedidos':
        $file = 'compras/pedidos.php';
        $pageTitle .= "Pedidos de Compra";
        $requiredSectors = [1,3,12];
        $requiredLogin = true;
        break;
    case 'compras/notas':
        $file = 'compras/notas.php';
        $pageTitle .= "Notas Fiscais";
        $requiredSectors = [1,3,12];
        $requiredLogin = true;
        break;
    case 'compras/configuracoes/categorias':
        $file = 'compras/configuracoes/categorias.php';
        $pageTitle .= "Gerenciar Categorias";
        $requiredSectors = [1,3,12];
        $requiredLogin = true;
        break;
    case 'compras/configuracoes/fornecedores':
        $file = 'compras/configuracoes/fornecedores.php';
        $pageTitle .= "Gerenciar Fornecedores";
        $requiredSectors = [1,3,12];
        $requiredLogin = true;
        break;
    case 'compras/relatorios/categorias':
        $file = 'compras/relatorios/gerar_categorias.php';
        $pageTitle .= "Relatorio Por Categoria";
        $requiredSectors = [1,3,12];
        $requiredLogin = true;
        break;
    case 'compras/relatorios/fornecedores':
        $file = 'compras/relatorios/gerar_fornecedores.php';
        $pageTitle .= "Relatorio Por Categoria";
        $requiredSectors = [1,3,12];
        $requiredLogin = true;
        break;

    case 'financeiro/campanhas':
        $file = 'financeiro/campanhas.php';
        $pageTitle .= "Campanhas";
        $requiredSectors = [1,5,12,14];
        $requiredLogin = true;
        break;
    case 'financeiro/campanha':
        $file = 'financeiro/campanha.php';
        $pageTitle .= "";
        $requiredSectors = [1,5,12,14];
        $requiredLogin = true;
        break;
    case 'financeiro/pendencias':
        $file = 'financeiro/pendencias.php';
        $pageTitle .= "Pendencias";
        $requiredSectors = [1,5,12,14];
        $requiredLogin = true;
        break;
    case 'financeiro/relatorios/campanha':
        $file = 'financeiro/relatorios/gerar_relatorio_campanha.php';
        $pageTitle .= "Captar";
        $requiredSectors = [1,5,12,14];
        $requiredLogin = true;
        break;
    case 'financeiro/relatorios/vendedor':
        $file = 'financeiro/relatorios/gerar_relatorio_vendedor.php';
        $pageTitle .= "Captar";
        $requiredSectors = [1,5,12,14];
        $requiredLogin = true;
        break;

    case 'captacao/captar':
        $file = 'captacao/captar.php';
        $pageTitle .= "Captar";
        $requiredSectors = [1,8,12,13];
        $requiredLogin = true;
        break;
    case 'captacao/alterar':
        $file = 'captacao/alterar.php';
        $pageTitle .= "Alterar Captação";
        $requiredSectors = [1,8,12,13];
        $requiredLogin = true;
        break;
    case 'captacao/relatorios/geral':
        $file = 'captacao/relatorios/gerar_geral.php';
        $pageTitle .= "Relatório Geral Captação";
        $requiredSectors = [1,8,12,13];
        $requiredLogin = true;
        break;

    case 'cirurgias/catarata/agenda':
        $file = 'cirurgias/catarata/agenda.php';
        $pageTitle .= "Agenda Catarata";
        $requiredSectors = [1,12,13,15];
        $requiredLogin = true;
        break;
    case 'cirurgias/catarata/agendas':
        $file = 'cirurgias/catarata/agendas.php';
        $pageTitle .= "Agendas Catarata";
        $requiredSectors = [1,12,13,15];
        $requiredLogin = true;
        break;
    case 'cirurgias/catarata/agendamento':
        $file = 'cirurgias/catarata/agendamento.php';
        $pageTitle .= "Agendamento Catarata";
        $requiredSectors = [1,12,13,15];
        $requiredLogin = true;
        break;
    case 'cirurgias/catarata/configuracoes/lentes':
        $file = 'cirurgias/catarata/configuracoes/lentes.php';
        $pageTitle .= "Lentes Catarata";
        $requiredSectors = [1,12,13,15];
        $requiredLogin = true;
        break;

    // case 'ajax/get_fornecedores':
    //     $file = 'ajax/get_fornecedores.php';
    //     $pageTitle .= "Notas Fiscais";
    //     $requiredSectors = [1,3,12];
    //     $requiredLogin = true;
    //     break;
    // case 'ajax/check_numero_nota':
        //     $file = 'ajax/check_numero_nota.php';
        //     $pageTitle .= "Verificar Numero Nota Disponivel";
        //     $requiredSectors = [1,3,12];
        //     $requiredLogin = true;
        //     break;
        // case 'ajax/get_lista_vendedores':
    //     $file = 'ajax/get_lista_vendedores.php';
    //     $pageTitle .= "Listar Vendedores";
    //     $requiredSectors = [1,5,12,14];
    //     $requiredLogin = true;
    //     break;
    // case 'ajax/get_chats':
    //     $file = 'ajax/get_chats.php';
    //     $pageTitle .= "Chats";
    //     $requiredSectors = null;
    //     $requiredLogin = true;
    //     break;
    // case 'ajax/check_online':
    //     $file = 'ajax/check_online.php';
    //     $pageTitle .= "Verificar Online";
    //     $requiredSectors = null;
    //     $requiredLogin = true;
    //     break;
    // case 'ajax/get_mensagens':
    //     $file = 'ajax/get_mensagens.php';
    //     $pageTitle .= "Listar Mensagens";
    //     $requiredSectors = null;
    //     $requiredLogin = true;
    //     break;

    default:
        $file = '404.php';
        $pageTitle .= "Erro 404";
        $requiredSectors = null;
        $requiredLogin = true;
        break;
}

// Verificar se o usuário precisa estar logado para acessar a página
if ($requiredLogin && !$_SESSION['logado']) {
    // Redireciona para a página de login se a página exige login e o usuário não está logado
    header("Location: ".URL."/login?falha");
    exit;
}

// Verificar se o setor do usuário tem permissão para acessar a página
if ($requiredSectors !== null && (!isset($_SESSION['id_setor']) || !in_array($_SESSION['id_setor'], $requiredSectors))) {
    // Redireciona para a página de erro 401 (Acesso não autorizado) se o setor não corresponde
    $file = '401.php';
    $pageTitle = "Acesso não autorizado";
}

// Incluir o arquivo correspondente se existir, caso contrário, exibir erro 404
$filePath = $baseDir . $file;

?>
<script>
    function manterOnline() {
        $.ajax({
            url: "/GRNacoes/views/ajax/set_online.php",
        });
    }
    setInterval(manterOnline, 5000);
</script>
<?php

if (file_exists($filePath)) {
    include $filePath;
} else {
    include $baseDir . '404.php';
}
