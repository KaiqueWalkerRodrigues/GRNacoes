<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="icon" type="image/x-icon" href="/GRNacoes/resources/img/favicon.ico" />

<?php

include_once('const.php');

function verificarSetor(array $setores_necessario){
    foreach($_SESSION['id_setores'] as $setor){
        foreach($setores_necessario as $setor_necessario){
            if($setor == $setor_necessario){
                return true;
                break;
            }
        }
    }
}
function bloquearSetor(array $setores_bloqueado){
    foreach($_SESSION['id_setores'] as $setor){
        foreach($setores_bloqueado as $setor_bloqueado){
            if($setor != $setor_bloqueado){
                return true;
                break;
            }else{
                return false;
            }
        }
    }
}

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
    case 'ajuda':
        $file = 'ajuda/ajuda.php';
        $pageTitle .= "Ajuda";
        $requiredSectors = null;
        $requiredLogin = false;
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
        $requiredSectors = [1,2,12,14,18];
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
        $requiredSectors = [1,2,5,12,13,14];
        $requiredLogin = true;
        break;
    case 'dashboards/catarata':
        $file = 'dashboards/catarata.php';
        $pageTitle .= "Dashboard Catarata";
        $requiredSectors = [1,5,12];
        $requiredLogin = true;
        break;
    case 'dashboards/cobranca':
        $file = 'dashboards/cobranca.php';
        $pageTitle .= "Dashboard Cobrança";
        $requiredSectors = [1,5,12];
        $requiredLogin = true;
        break;
    case 'dashboards/lente_contato':
        $file = 'dashboards/lente_contato.php';
        $pageTitle .= "Dashboard Lente de Contato";
        $requiredSectors = [1,3,5,12];
        $requiredLogin = true;
        break;

    case 'chamados':
        $file = 'chamados/chamados.php';
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
    case 'chamados/test':
        $file = 'chamados/test.php';
        $pageTitle .= "teste";
        $requiredSectors = null;
        $requiredLogin = true;
        break;

    case 'arquivos/arquivos_mortos':
        $file = 'arquivos/arquivos_mortos.php';
        $pageTitle .= "Arquivos Mortos";
        $requiredSectors = [1,5,12,14];
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
    case 'compras/relatorios/estoque_venda':
        $file = 'compras/relatorios/estoque_venda.php';
        $pageTitle .= "Estoque e Venda";
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

    case 'faturamento/competencias':
        $file = 'faturamento/competencias.php';
        $pageTitle .= "Competências";
        $requiredSectors = [1,5,12,14,18];
        $requiredLogin = true;
        break;
    case 'faturamento/competencia':
        $file = 'faturamento/competencia.php';
        $pageTitle .= "Competência";
        $requiredSectors = [1,5,12,14,18];
        $requiredLogin = true;
        break;
    case 'faturamento/relatorios/gerar_relatorio_competencia':
        $file = 'faturamento/relatorios/gerar_relatorio_competencia.php';
        $pageTitle .= "Relatório Competencia";
        $requiredSectors = [1,5,12,14,18];
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
    case 'financeiro/faturas_atrasadas':
        $file = 'financeiro/faturas_atrasadas.php';
        $pageTitle .= "Faturas Atrasadas";
        $requiredSectors = [1,5,12,14];
        $requiredLogin = true;
        break;
    case 'financeiro/contratos':
        $file = 'financeiro/contratos.php';
        $pageTitle .= "Contratos";
        $requiredSectors = [1,5,12,14];
        $requiredLogin = true;
        break;
    case 'financeiro/contrato':
        $file = 'financeiro/documentos/contrato.html';
        $pageTitle .= "Contratos";
        $requiredSectors = [1,5,12,14];
        $requiredLogin = true;
        break;
    case 'financeiro/documentos/gerar_contrato_pdf':
        $file = 'financeiro/documentos/gerar_contrato_pdf.php';
        $pageTitle .= "Gerar PDF do Contrato";
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
    case 'cirurgias/catarata/documentos/gerar_contrato_pdf':
        $file = 'cirurgias/catarata/documentos/gerar_contrato_pdf.php';
        $pageTitle .= "Gerar Contrato PDF";
        $requiredSectors = [1,12,13,15];
        $requiredLogin = true;
        break;
    case 'cirurgias/catarata/documentos/gerar_vale_indicacao':
        $file = 'cirurgias/catarata/documentos/gerar_vale_indicacao.php';
        $pageTitle .= "Gerar Vale indicação PDF";
        $requiredSectors = [1,12,13,15];
        $requiredLogin = true;
        break;
    case 'cirurgias/catarata/agendamento_externo':
        $file = 'cirurgias/catarata/agendamento_externo.php';
        $pageTitle .= "Agendamento Externo Catarata";
        $requiredSectors = [1,12,13,15];
        $requiredLogin = true;
        break;
    case 'cirurgias/catarata/configuracoes/lentes':
        $file = 'cirurgias/catarata/configuracoes/lentes.php';
        $pageTitle .= "Lentes Catarata";
        $requiredSectors = [1,12,13,15];
        $requiredLogin = true;
        break;

    case 'tecnologia/configuracoes/sockets':
        $file = 'tecnologia/configuracoes/sockets.php';
        $pageTitle .= "Sockets Processadores";
        $requiredSectors = [1];
        $requiredLogin = true;
        break;

    case 'lente_contato/testes':
        $file = 'lente_contato/testes.php';
        $pageTitle .= "Orçamentos Lente de Contato";
        $requiredSectors = [1,5,17];
        $requiredLogin = true;
        break;
    case 'lente_contato/orcamentos':
        $file = 'lente_contato/orcamentos.php';
        $pageTitle .= "Orçamentos Lente de Contato";
        $requiredSectors = [1,5,17];
        $requiredLogin = true;
        break;
    case 'lente_contato/configuracoes/fornecedores':
        $file = 'lente_contato/configuracoes/fornecedores.php';
        $pageTitle .= "Orçamentos Lente de Contato";
        $requiredSectors = [1,17];
        $requiredLogin = true;
        break;
    case 'lente_contato/configuracoes/modelos':
        $file = 'lente_contato/configuracoes/modelos.php';
        $pageTitle .= "Modelos Lente de Contato";
        $requiredSectors = [1,17];
        $requiredLogin = true;
        break;

    case 'solicitacao':
        $file = 'solicitacao_cadastro.php';
        $pageTitle .= "Solicitação de Cadastro";
        $requiredSectors = [];
        $requiredLogin = false;
        break;

    //Ajax
    // case 'ajax/buscar_itens':
    //     $file = 'ajax/buscar_itens.php';
    //     $pageTitle .= "Teste de ajax";
    //     $requiredSectors = [1];
    //     $requiredLogin = true;
    //     break;

    //Teste
    // case 'test':
    //     $file = 'test.php';
    //     $pageTitle .= "Teste";
    //     $requiredSectors = [1];
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
// if ($requiredSectors !== null && (!isset($_SESSION['id_setor']) || !in_array($_SESSION['id_setor'], $requiredSectors))) {
//     // Redireciona para a página de erro 401 (Acesso não autorizado) se o setor não corresponde
//     $file = '401.php';
//     $pageTitle = "Acesso não autorizado";
// }
if($requiredSectors != null){
    if(!verificarSetor($requiredSectors)){
        $file = '401.php';
        $pageTitle = "Acesso não autorizado";
    }
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
