<?php 
    include_once('const.php');
    
    $Compras_Pedidos = new Compras_Pedidos();
?>
<style>
    #accordionSidebar {
        position: fixed;
        z-index: 1000; /* Garante que a sidebar esteja acima do conteúdo */
    }

    #content-wrapper {
        margin-left: 220px; /* Largura da sidebar */
        transition: margin-left 0.3s ease; /* Adiciona uma transição suave ao abrir/fechar a sidebar */
    }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
    .botao{
        border: none;
        background-color: white;
        width: 90%;
        text-align: left;
    }
    .botao:hover{
        border: none;
        background-color: #f7f8fb;
    }
</style>

<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

<!-- Sidebar - Brand -->
<a class="sidebar-brand d-flex align-items-center justify-content-center" href="/GRNacoes/">
    GRN
</a>

<!-- Divider -->
<hr class="sidebar-divider my-0">

<!-- Nav Item - Dashboard -->
<li class="nav-item" id="painel">
    <a class="nav-link" href="<?php echo URL ?>/">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Painel</span>
    </a>
</li>

<!-- Divider -->
<hr class="sidebar-divider">

<!-- Heading -->
<div class="sidebar-heading">
   Central
</div>

<!-- Nav Item - Utilities Collapse Menu -->
<li class="nav-item" id="cham">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#chamados"
        aria-expanded="true" aria-controls="chamados">
        <i class="fa-solid fa-headset"></i>
        <span>Chamados</span>
    </a> 
    <div id="chamados" class="collapse" aria-labelledby="headingUtilities"
        data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <a href="<?php echo URL ?>/chamados/meus_chamados" id="meus_chamados" class="collapse-item">Meus Chamados</a>
            <a href="<?php echo URL ?>/chamados/" id="chamados_index" class="collapse-item">Chamados</a>
            <?php if($_SESSION['id_setor'] == 1){ ?><h6 class="collapse-header">Admin:</h6><a href="<?php echo URL ?>/chamados/todos" id="todos_chamados" class="collapse-item">Todos Chamados</a><?php } ?>
        </div>
    </div>
</li> 

<!-- Nav Item - Tables -->
<li class="nav-item" id="chat">
    <a class="nav-link" href="<?php echo URL ?>/chats">
        <i class="fa-solid fa-comment"></i>
        <span>Chats</span></a>
</li>

<?php if($_SESSION['id_setor'] == 3 OR $_SESSION['id_setor'] == 1 OR $_SESSION['id_setor'] == 12 OR $_SESSION['id_setor'] == 14) { ?>
<li class="nav-item" id="comp">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#compras"
        aria-expanded="true" aria-controls="comp">
        <i class="fa-solid fa-bag-shopping"></i>
        <span>Compras</span>
    </a> 
    <div id="compras" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" id="compras_pedidos" href="<?php echo URL ?>/compras/pedidos">Pedidos de Compras</a>
            <h6 class="collapse-header">Notas:</h6>
            <a class="collapse-item" id="compras_notas" href="<?php echo URL ?>/compras/notas">Notas Fiscais</a>
            <a class="collapse-item" id="compras_relatorios" href="<?php echo URL ?>/compras/relatorios">Gerar Relatórios</a>
            <h6 class="collapse-header">Configurações:</h6>
            <a class="collapse-item" id="compras_categorias" href="<?php echo URL ?>/compras/categorias">Gerenciar Categorias</a>
            <a class="collapse-item" id="compras_fornecedores" href="<?php echo URL ?>/compras/fornecedores">Gerenciar Fornecedores</a>
        </div>
    </div>
</li> 
<?php } ?>

<?php if($_SESSION['id_setor'] == 3 OR $_SESSION['id_setor'] == 1 OR $_SESSION['id_setor'] == 12) { ?>
<li class="nav-item" id="otic">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#otica"
        aria-expanded="true" aria-controls="otic">
        <i class="fa-solid fa-glasses"></i>
        <span>Ótica</span>
    </a> 
    <div id="otica" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
        <a class="collapse-item" id="otica_estoque" href="<?php echo URL ?>/otica/estoque">Estoque</a>
        <a class="collapse-item" id="otica_vendas" href="<?php echo URL ?>/otica/vendas">Vendas</a>
        </div>
    </div>
</li> 
<?php } ?>

<?php if($_SESSION['id_setor'] == 8 OR $_SESSION['id_setor'] == 1 OR $_SESSION['id_setor'] == 12 OR $_SESSION['id_setor'] == 13 OR $_SESSION['id_setor'] == 14) ?>
<li class="nav-item" id="cap">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#captacao"
        aria-expanded="true" aria-controls="comp">
        <i class="fa-solid fa-people-pulling"></i>
        <span>Captação</span>
    </a> 
    <div id="captacao" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" id="captacao_index" href="<?php echo URL ?>/captacao/">Captar</a>
            <a class="collapse-item" id="captacao_alterar" href="<?php echo URL ?>/captacao/alterar">Alterar Captação</a>
            <?php if($_SESSION['id_setor'] == 1 OR $_SESSION['id_setor'] == 12 OR $_SESSION['id_setor'] == 13 OR $_SESSION['id_setor'] == 14){ ?>
                <h6 class="collapse-header">Relatório:</h6>
                <a class="collapse-item" id="captacao_dashboard" href="<?php echo URL ?>/captacao/dashboard">Dashboard</a>
                <a class="collapse-item" id="captacao_relatorios" href="<?php echo URL ?>/captacao/relatorios">Gerar Relatórios</a>
            <?php } ?>
        </div>
    </div>
</li> 
<?php ?>

<?php if($_SESSION['id_setor'] == 5 OR $_SESSION['id_setor'] == 1 OR $_SESSION['id_setor'] == 12 OR $_SESSION['id_setor'] == 14) { ?>
<li class="nav-item" id="finan">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#financeiro"
        aria-expanded="true" aria-controls="finan">
        <i class="fa-solid fa-money-bill"></i>
        <span>Financeiro</span>
    </a> 
    <div id="financeiro" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" id="financeiro_campanhas" href="<?php echo URL ?>/financeiro/campanhas">Campanhas</a>
            <a class="collapse-item" id="financeiro_atrasos" href="<?php echo URL ?>/financeiro/atrasos">Atrasos</a>
        </div>
    </div>
</li> 
<?php } ?>


<?php if($_SESSION['id_setor'] == 2 OR $_SESSION['id_setor'] == 1 OR $_SESSION['id_setor'] == 14 OR $_SESSION['id_setor'] == 12) { ?>
    
<!-- Divider -->
<hr class="sidebar-divider">

<!-- Heading -->
<div class="sidebar-heading">
   Gerenciamento
</div>

<li class="nav-item" id="config">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#configurações"
        aria-expanded="true" aria-controls="config">
        <i class="fa-solid fa-gear"></i>
        <span>Configurações</span>
    </a> 
    <div id="configurações" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" id="gerenciar_cargos" href="<?php echo URL ?>/configuracoes/cargos">Gerenciar Cargos</a>
            <a class="collapse-item" id="gerenciar_setores" href="<?php echo URL ?>/configuracoes/setores">Gerenciar Setores</a>
            <a class="collapse-item" id="gerenciar_usuarios" href="<?php echo URL ?>/configuracoes/usuarios">Gerenciar Usuários</a>
        </div>
    </div>
</li> 
<?php } ?>

<?php if($_SESSION['id_setor'] == 1) { ?>
<li class="nav-item" id="logs">
    <a class="nav-link" href="<?php echo URL ?>/logs">
        <i class="fa-solid fa-file-lines"></i>
        <span>Logs</span></a>
</li>
<?php } ?>

<!-- Divider -->
<hr class="sidebar-divider d-none d-md-block">

<!-- Sidebar Toggler (Sidebar) -->
<div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
</div>

</ul>

<script>
    //Sistema Online
    {
        function manterOnline() {
            $.ajax({
                url: "/GRNacoes/manter_online.php",
            });
        }
        setInterval(manterOnline, 1000);
    }
</script>