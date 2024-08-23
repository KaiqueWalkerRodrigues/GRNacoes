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

<!-- Nav Item - Pages Collapse Menu -->
<!-- <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
        aria-expanded="true" aria-controls="collapseTwo">
        <i class="fas fa-fw fa-cog"></i>
        <span>Components</span>
    </a>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Custom Components:</h6>
            <a class="collapse-item" href="buttons.html">Buttons</a>
            <a class="collapse-item" href="cards.html">Cards</a>
        </div>
    </div>
</li> -->

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
            <button id="btnAbrirChamado" data-toggle="modal" data-target="#modalAbrirChamado" class="collapse-item botao">Abrir Chamado</button>
            <h6 class="collapse-header">Para:</h6>
            <a class="collapse-item" href="<?php echo URL ?>/chamados">Faturamento</a>
            <a class="collapse-item" href="<?php echo URL ?>/chamados">Financeiro</a>
            <a class="collapse-item" href="<?php echo URL ?>/chamados">RH</a>
            <a class="collapse-item" href="<?php echo URL ?>/chamados">TI</a>
        </div>
    </div>
</li> 

<!-- Nav Item - Tables -->
<li class="nav-item" id="chat">
    <a class="nav-link" href="<?php echo URL ?>/chats">
        <i class="fa-solid fa-comment"></i>
        <span>Chats</span></a>
</li>

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
            <a class="collapse-item" id="compras_notas" href="<?php echo URL ?>/compras/notas">Notas Fiscias</a>
            <a class="collapse-item" id="compras_categorias" href="<?php echo URL ?>/compras/categorias">Gerenciar Categorias</a>
            <a class="collapse-item" id="compras_fornecedores" href="<?php echo URL ?>/compras/fornecedores">Gerenciar Fornecedores</a>
            <a class="collapse-item" id="compras_relatorios" href="<?php echo URL ?>/compras/relatorios">Gerar Relatórios</a>
        </div>
    </div>
</li> 

<li class="nav-item" id="fat">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#faturamento"
        aria-expanded="true" aria-controls="fat">
        <i class="fa-solid fa-dollar-sign"></i>
        <span>Faturamento</span>
    </a> 
    <div id="faturamento" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">    
            <a class="collapse-item" id="" href="<?php echo URL ?>/faturamento/"></a>
        </div>
    </div>
</li> 

<li class="nav-item" id="finan">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#financeiro"
        aria-expanded="true" aria-controls="finan">
        <i class="fa-solid fa-money-bill"></i>
        <span>Financeiro</span>
    </a> 
    <div id="financeiro" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" id="financeiro_campanhas" href="<?php echo URL ?>/financeiro/campanhas">Campanhas</a>
            <a class="collapse-item" id="financeiro_relatorios" href="<?php echo URL ?>/financeiro/relatorios">Relatórios</a>
        </div>
    </div>
</li> 

<li class="nav-item" id="proj">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#projeto"
        aria-expanded="true" aria-controls="finan">
        <i class="fa-solid fa-diagram-project"></i>
        <span>Projetos</span>
    </a> 
    <div id="projeto" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" id="projetos_index" href="<?php echo URL ?>/projetos">Todos Projetos</a>
        </div>
    </div>
</li> 

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

<li class="nav-item" id="logs">
    <a class="nav-link" href="<?php echo URL ?>/logs">
        <i class="fa-solid fa-file-lines"></i>
        <span>Logs</span></a>
</li>

<!-- Divider -->
<hr class="sidebar-divider d-none d-md-block">

<!-- Sidebar Toggler (Sidebar) -->
<div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
</div>

</ul>

<!-- Modal Abrir Chamado -->
<div class="modal fade" id="modalAbrirChamado" tabindex="-1" role="dialog" aria-labelledby="abrirchamadoLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-8 mb-2">
                        <label for="cadastrar_titulo" class="form-label">Título do Chamado *</label>
                        <input type="text" name="cadastrar_titulo" id="cadastrar_titulo" class="form-control" required>
                    </div>
                    <div class="col-4 mb-2">
                        <label for="cadastrar_destinatario" class="form-label">Destinatário *</label>
                        <select name="destinatario" id="cadastrar_destinatario" class="form-control" required>
                            <option value="">Selecione...</option>
                            <option value="compras">Compras</option>
                            <option value="faturamento">Faturamento</option>
                            <option value="financeiro">Financeiro</option>
                            <option value="rh">RH</option>
                            <option value="ti">TI</option>
                        </select>
                    </div>
                    <div class="col-4">
                        <label for="cadastrar_urgencia" class="form-label">Urgência *</label>
                        <select name="cadastrar_urgencia" id="cadastrar_urgencia" class="form-control" required>
                            <option value="">Selecione...</option>
                            <option value="0">Sugestão</option>
                            <option value="1">Baixa</option>
                            <option value="2">Moderada</option>
                            <option value="3">Alta</option>
                            <option value="4">Emergência</option>
                        </select>
                    </div>
                    <div class="col-4">
                        <label for="cadastrar_print" class="form-label">Prints *</label>
                        <input type="file" name="cadastrar-print" multiple accept="image/" id="cadastrar-print" class="form-control">
                    </div>
                    <div class="col-12 mt-1">
                        <label for="cadastrar-descricao" class="form-label">Descreva o Problema *</label>
                        <textarea name="cadastrar-descricao" id="cadastrar-descricao" cols="30" rows="10" class="form-control"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary">Abrir Chamado</button>
            </div>
        </div>
    </div>
</div>
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