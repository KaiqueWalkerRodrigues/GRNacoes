<?php 
    $Usuario = new Usuario();
    $Arquivo_Morto = new Arquivo_Morto();

    if (isset($_POST['btnCadastrar'])) {
        $Arquivo_Morto->cadastrar($_POST);
    }
    if (isset($_POST['btnCadastrarLocal'])) {
        $Arquivo_Morto->cadastrarLocal($_POST);
    }
    if (isset($_POST['btnCadastrarItem'])) {
        $Arquivo_Morto->cadastrarItem($_POST);
    }
    if (isset($_POST['btnEditar'])) {
        $Arquivo_Morto->editar($_POST);
    }
    if (isset($_POST['btnEditarItem'])) {
        $Arquivo_Morto->editarItem($_POST);
    }    
    if (isset($_POST['btnDeletar'])) {
        $Arquivo_Morto->deletar($_POST['id_caixa'],$_POST['usuario_logado']);
    }
    if (isset($_POST['btnDeletarItem'])) {
        $Arquivo_Morto->deletarItem($_POST['id_item'],$_POST['usuario_logado']);
    }    
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title><?php echo $pageTitle ?></title>
    <link href="<?php echo URL_RESOURCES ?>/css/styles.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.24.1/feather.min.js" crossorigin="anonymous"></script>
</head>

<body class="nav-fixed">
        <!-- Tela de Carregamento -->
    <!-- <div id="preloader">
        <div class="spinner"></div>
    </div> -->
    <?php include_once('resources/topbar.php') ?>
    <div id="layoutSidenav">
        <?php include_once('resources/sidebar.php') ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="page-header pb-10 page-header-dark bg-primary">
                    <div class="container-fluid">
                        <div class="page-header-content">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i class="fa-solid fa-archive"></i></div>
                                <span>Arquivo Morto</span>
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="container-fluid mt-n10">
                    <div class="row">
                        <div class="col-3">
                            <div class="card border-top-0 border-bottom-0 border-right-0 border-left-lg border-green">
                                <div class="row p-4">
                                    <div class="col-12 text-center">
                                        <h2><?php echo $Arquivo_Morto->contarCaixas(); ?></h2>
                                    </div>
                                    <div class="col-12 text-center">
                                        <b>TOTAL DE CAIXAS</b>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="card border-top-0 border-bottom-0 border-right-0 border-left-lg border-green">
                                <div class="row p-4">
                                    <div class="col-12 text-center">
                                        <h2><?php echo $Arquivo_Morto->ultimaCaixa(); ?></h2>
                                    </div>
                                    <div class="col-12 text-center">
                                        <b>ÚLTIMA CAIXA</b>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="card border-top-0 border-bottom-0 border-right-0 border-left-lg border-green">
                                <div class="row p-4">
                                    <div class="col-12 text-center">
                                        <h2><?php echo $Arquivo_Morto->contarLocais(); ?></h2>
                                    </div>
                                    <div class="col-12 text-center">
                                        <b>LOCAIS</b>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="card border-top-0 border-bottom-0 border-right-0 border-left-lg border-green">
                                <div class="row p-4">
                                    <div class="col-12 text-center">
                                        <h2><?php echo $Arquivo_Morto->contarItens(); ?></h2>
                                    </div>
                                    <div class="col-12 text-center">
                                        <b>TOTAL DE ITENS</b>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="card mb-4">
                        <div class="card-header">Caixas de Arquivo Morto
                            <button class="btn btn-datatable btn-icon btn-sm btn-success ml-2" type="button" data-toggle="modal" data-target="#modalCadastrarCaixa">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="datatable table-responsive">
                                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Nº Caixa</th>
                                            <th>Local</th>
                                            <th>Itens</th>
                                            <th>Observações</th>
                                            <th>Data Cadastro</th>
                                            <th class="d-none">Descrições Itens</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($Arquivo_Morto->listar() as $caixa){ ?>
                                        <tr>
                                            <td class="text-center"><strong><?php echo $caixa->numero_caixa ?></strong></td>
                                            <td class="text-center"><span class="badge badge-primary"><?php echo $caixa->nome_local ?></span></td>
                                            <td class="text-center">
                                                <?php foreach($Arquivo_Morto->listarItens($caixa->id_caixa) as $item){ echo $item->tipo_documento.";";} ?>
                                            </td>
                                            <td><?php echo $caixa->observacoes ? substr($caixa->observacoes, 0, 50) . '...' : '-' ?></td>
                                            <td class="text-center"><?php echo date('d/m/Y', strtotime($caixa->created_at)) ?></td>
                                            <td class="d-none">
                                                <?php 
                                                    $itens_desc = [];
                                                    foreach($Arquivo_Morto->listarItens($caixa->id_caixa) as $item) {
                                                        $itens_desc[] = $item->tipo_documento . " " . $item->nome_documento . " " . $item->departamento . " " . $item->observacoes_item;
                                                    }
                                                    echo implode(" ", $itens_desc);
                                                ?>
                                            </td>
                                            <td>
                                                <button class="btn btn-datatable btn-icon btn-transparent-dark" type="button" data-toggle="modal" data-target="#modalGerenciarItens"
                                                    data-idcaixa="<?php echo $caixa->id_caixa ?>"
                                                    data-numerocaixa="<?php echo $caixa->numero_caixa ?>"
                                                    title="Gerenciar Itens">
                                                    <i class="fa-solid fa-list"></i>
                                                </button>
                                                <button class="btn btn-datatable btn-icon btn-transparent-dark" type="button" data-toggle="modal" data-target="#modalEditarCaixa"
                                                    data-idcaixa="<?php echo $caixa->id_caixa ?>"
                                                    data-numerocaixa="<?php echo $caixa->numero_caixa ?>" 
                                                    data-idlocal="<?php echo $caixa->id_local ?>"
                                                    data-observacoes="<?php echo $caixa->observacoes ?>"
                                                    ><i class="fa-solid fa-gear"></i></button>
                                                <button class="btn btn-datatable btn-icon btn-transparent-dark" type="button" data-toggle="modal" data-target="#modalDeletarCaixa"
                                                    data-numerocaixa="<?php echo $caixa->numero_caixa ?>" 
                                                    data-idcaixa="<?php echo $caixa->id_caixa ?>"
                                                    ><i class="fa-solid fa-trash"></i></button>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <?php include_once('resources/footer.php') ?>
        </div>
    </div>

    <!-- Modal Cadastrar Caixa -->
    <div class="modal fade" id="modalCadastrarCaixa" tabindex="-1" role="dialog" aria-labelledby="modalCadastrarCaixaLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCadastrarCaixaLabel">Cadastrar Nova Caixa</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <div class="row">
                            <div class="col-6">
                                <label for="numero_caixa" class="form-label">Nº Caixa *</label>
                                <input type="text" class="form-control" name="numero_caixa" id="cadastrar_numero_caixa" required>
                            </div>
                            <div class="col-6">
                                <label for="id_local" class="form-label">Local * 
                                    <button type="button" class="btn btn-sm btn-icon btn-primary" data-toggle="modal" data-target="#modalCadastrarLocal">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                </label>
                                <select name="id_local" id="cadastrar_local" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach($Arquivo_Morto->listarLocais() as $local){ ?>
                                        <option value="<?php echo $local->id_local ?>"><?php echo $local->nome_local ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <label for="observacoes" class="form-label">Observações</label>
                                <textarea class="form-control" name="observacoes" id="cadastrar_observacoes" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-dark" type="button" data-dismiss="modal">Fechar</button>
                        <button class="btn btn-success" type="submit" name="btnCadastrar">Cadastrar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Cadastrar Local -->
    <div class="modal fade" id="modalCadastrarLocal" tabindex="-1" role="dialog" aria-labelledby="modalCadastrarLocalLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCadastrarLocalLabel">Cadastrar Novo Local</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <div class="row">
                            <div class="col-12">
                                <label for="nome_local" class="form-label">Nome do Local *</label>
                                <input type="text" class="form-control" name="nome_local" placeholder="Ex: Sala de Arquivo - Estante A" required>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <label for="descricao_local" class="form-label">Descrição</label>
                                <textarea class="form-control" name="descricao_local" rows="3" placeholder="Descrição do local..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-dark" type="button" data-dismiss="modal">Fechar</button>
                        <button class="btn btn-success" type="submit" name="btnCadastrarLocal">Cadastrar Local</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Editar Caixa -->
    <div class="modal fade" id="modalEditarCaixa" tabindex="-1" role="dialog" aria-labelledby="modalEditarCaixaLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditarCaixaLabel">Editar Caixa: <span id="editar_caixa_nome"></span></h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_caixa" id="editar_id_caixa">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <div class="row">
                            <div class="col-6">
                                <label for="editar_numero_caixa" class="form-label">Nº Caixa *</label>
                                <input type="text" class="form-control" name="numero_caixa" id="editar_numero_caixa" required>
                            </div>
                            <div class="col-6">
                                <label for="editar_id_local" class="form-label">Local *</label>
                                <select name="id_local" id="editar_id_local" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach($Arquivo_Morto->listarLocais() as $local){ ?>
                                        <option value="<?php echo $local->id_local ?>"><?php echo $local->nome_local ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <label for="editar_observacoes" class="form-label">Observações</label>
                                <textarea class="form-control" name="observacoes" id="editar_observacoes" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-dark" type="button" data-dismiss="modal">Fechar</button>
                        <button class="btn btn-primary" type="submit" name="btnEditar">Editar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Deletar Caixa -->
    <div class="modal fade" id="modalDeletarCaixa" tabindex="-1" role="dialog" aria-labelledby="modalDeletarCaixaLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDeletarCaixaLabel">Deletar Caixa: <span class="deletar_caixa_nome"></span></h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body text-center">
                        <input type="hidden" name="id_caixa" id="deletar_id_caixa">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <p>Você tem certeza que deseja Deletar a Caixa <br> <b class="deletar_caixa_nome"></b>? <br> Essa ação é Irreversível.</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-dark" type="button" data-dismiss="modal">Fechar</button>
                        <button class="btn btn-danger" type="submit" name="btnDeletar">Deletar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Gerenciar Itens -->
    <div class="modal fade" id="modalGerenciarItens" tabindex="-1" role="dialog" aria-labelledby="modalGerenciarItensLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalGerenciarItensLabel">Gerenciar Itens da Caixa: <span id="gerenciar_itens_caixa_nome"></span></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <button class="btn btn-success btn-icon" type="button" data-toggle="modal" data-target="#modalCadastrarItem">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="tabelaItens">
                            <thead>
                                <tr>
                                    <th>Tipo Documento</th>
                                    <th>Nome Documento</th>
                                    <th>Departamento</th>
                                    <th>Data Documento</th>
                                    <th>Data Arquivamento</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody id="corpoTabelaItens">
                                <!-- Itens serão carregados via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-dark" type="button" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Cadastrar Item -->
    <div class="modal fade" id="modalCadastrarItem" tabindex="-1" role="dialog" aria-labelledby="modalCadastrarItemLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCadastrarItemLabel">Cadastrar Novo Item</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_caixa" id="cadastrar_item_id_caixa">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <div class="row">
                            <div class="col-6">
                                <label for="tipo_documento" class="form-label">Tipo de Documento *</label>
                                <input type="text" class="form-control" name="tipo_documento" required>
                            </div>
                            <div class="col-6">
                                <label for="nome_documento" class="form-label">Nome do Documento *</label>
                                <input type="text" class="form-control" name="nome_documento" required>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-4">
                                <label for="departamento" class="form-label">Departamento</label>
                                <input type="text" class="form-control" name="departamento">
                            </div>
                            <div class="col-4">
                                <label for="data_documento" class="form-label">Data do Documento</label>
                                <input type="date" class="form-control" name="data_documento">
                            </div>
                            <div class="col-4">
                                <label for="data_arquivamento" class="form-label">Data de Arquivamento</label>
                                <input type="date" class="form-control" name="data_arquivamento" value="<?php echo date("Y-m-d") ?>">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <label for="observacoes_item" class="form-label">Observações do Item</label>
                                <textarea class="form-control" name="observacoes_item" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-dark" type="button" data-dismiss="modal">Fechar</button>
                        <button class="btn btn-success" type="submit" name="btnCadastrarItem">Cadastrar Item</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Editar Item -->
    <div class="modal fade" id="modalEditarItem" tabindex="-1" role="dialog" aria-labelledby="modalEditarItemLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditarItemLabel">Editar Item: <span id="editar_item_nome"></span></h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_item" id="editar_id_item">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <div class="row">
                            <div class="col-6">
                                <label for="editar_tipo_documento" class="form-label">Tipo de Documento *</label>
                                <input type="text" class="form-control" name="tipo_documento" id="editar_tipo_documento" required>
                            </div>
                            <div class="col-6">
                                <label for="editar_nome_documento" class="form-label">Nome do Documento *</label>
                                <input type="text" class="form-control" name="nome_documento" id="editar_nome_documento" required>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-4">
                                <label for="editar_departamento" class="form-label">Departamento</label>
                                <input type="text" class="form-control" name="departamento" id="editar_departamento">
                            </div>
                            <div class="col-4">
                                <label for="editar_data_documento" class="form-label">Data do Documento</label>
                                <input type="date" class="form-control" name="data_documento" id="editar_data_documento">
                            </div>
                            <div class="col-4">
                                <label for="editar_data_arquivamento" class="form-label">Data de Arquivamento</label>
                                <input type="date" class="form-control" name="data_arquivamento" id="editar_data_arquivamento">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <label for="editar_observacoes_item" class="form-label">Observações do Item</label>
                                <textarea class="form-control" name="observacoes_item" id="editar_observacoes_item" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-dark" type="button" data-dismiss="modal">Fechar</button>
                        <button class="btn btn-primary" type="submit" name="btnEditarItem">Editar Item</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Deletar Item -->
    <div class="modal fade" id="modalDeletarItem" tabindex="-1" role="dialog" aria-labelledby="modalDeletarItemLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDeletarItemLabel">Deletar Item: <span class="deletar_item_nome"></span></h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body text-center">
                        <input type="hidden" name="id_item" id="deletar_id_item">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <p>Você tem certeza que deseja Deletar o Item <br> <b class="deletar_item_nome"></b>? <br> Essa ação é Irreversível.</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-dark" type="button" data-dismiss="modal">Fechar</button>
                        <button class="btn btn-danger" type="submit" name="btnDeletarItem">Deletar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script>
        $(window).on('load', function () {
            $('#preloader').fadeOut('slow', function() { $(this).remove(); });
        });

        $(document).ready(function () {
            $('#arquivos').addClass('active')
            $('#arquivos_mortos').addClass('active')

            $('#preloader').fadeOut('slow', function() { $(this).remove(); });

            $('#modalEditarCaixa').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget);
                let numero_caixa = button.data('numerocaixa');
                let id_caixa = button.data('idcaixa');
                let id_local = button.data('idlocal');
                let observacoes = button.data('observacoes');

                $('#editar_caixa_nome').text(numero_caixa);
                $('#editar_id_caixa').val(id_caixa);
                $('#editar_numero_caixa').val(numero_caixa);
                $('#editar_id_local').val(id_local);
                $('#editar_observacoes').val(observacoes);
            });

            $('#modalDeletarCaixa').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget);
                let numero_caixa = button.data('numerocaixa');
                let id_caixa = button.data('idcaixa');

                $('.deletar_caixa_nome').text(numero_caixa);
                $('#deletar_id_caixa').val(id_caixa);
            });

            // Modal Gerenciar Itens
            $('#modalGerenciarItens').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget);
                let id_caixa = button.data('idcaixa');
                let numero_caixa = button.data('numerocaixa');

                $('#gerenciar_itens_caixa_nome').text(numero_caixa);
                $('#cadastrar_item_id_caixa').val(id_caixa);

                // Carregar itens da caixa via AJAX (simulado aqui)
                carregarItens(id_caixa);
            });

            // Modal Editar Item
            $(document).on('click', '.btn-editar-item', function() {
                let id_item = $(this).data('iditem');
                let tipo_documento = $(this).data('tipodocumento');
                let nome_documento = $(this).data('nomedocumento');
                let departamento = $(this).data('departamento');
                let data_documento = $(this).data('datadocumento');
                let data_arquivamento = $(this).data('dataarquivamento');
                let observacoes_item = $(this).data('observacoesitem');

                $('#editar_item_nome').text(nome_documento);
                $('#editar_id_item').val(id_item);
                $('#editar_tipo_documento').val(tipo_documento);
                $('#editar_nome_documento').val(nome_documento);
                $('#editar_departamento').val(departamento);
                $('#editar_data_documento').val(data_documento);
                $('#editar_data_arquivamento').val(data_arquivamento);
                $('#editar_observacoes_item').val(observacoes_item);
            });

            // Modal Deletar Item
            $(document).on('click', '.btn-deletar-item', function() {
                let id_item = $(this).data('iditem');
                let nome_documento = $(this).data('nomedocumento');

                $('.deletar_item_nome').text(nome_documento);
                $('#deletar_id_item').val(id_item);
            });

            // Atualizar select de locais após cadastrar novo local
            $('#modalCadastrarLocal').on('hidden.bs.modal', function () {
                // Recarregar a página para atualizar os selects
                setTimeout(function() {
                    location.reload();
                }, 100);
            });

            function carregarItens(id_caixa) {
                $.ajax({
                    url: '/GRNacoes/views/ajax/get_itens_caixa.php',
                    method: 'GET',
                    data: { id_caixa: id_caixa },
                    dataType: 'json',
                    success: function(itens) {
                        let itensHtml = '';

                        if (itens.length > 0) {
                            itens.forEach(function(item) {
                                const data_documento = item.data_documento ? new Date(item.data_documento).toLocaleDateString('pt-BR') : '-';
                                const data_arquivamento = item.data_arquivamento ? new Date(item.data_arquivamento).toLocaleDateString('pt-BR') : '-';

                                itensHtml += '<tr>';
                                itensHtml += `<td>${item.tipo_documento}</td>`;
                                itensHtml += `<td><strong>${item.nome_documento}</strong></td>`;
                                itensHtml += `<td>${item.departamento}</td>`;
                                itensHtml += `<td class="text-center">${data_documento}</td>`;
                                itensHtml += `<td class="text-center">${data_arquivamento}</td>`;
                                itensHtml += `<td>
                                    <button class="btn btn-datatable btn-icon btn-transparent-dark btn-editar-item" data-toggle="modal" data-target="#modalEditarItem"
                                        data-iditem="${item.id_item}"
                                        data-tipodocumento="${item.tipo_documento}"
                                        data-nomedocumento="${item.nome_documento}"
                                        data-departamento="${item.departamento}"
                                        data-datadocumento="${item.data_documento}"
                                        data-dataarquivamento="${item.data_arquivamento}"
                                        data-observacoesitem="${item.observacoes_item || ''}">
                                        <i class="fa-solid fa-gear"></i>
                                    </button>
                                    <button class="btn btn-datatable btn-icon btn-transparent-dark btn-deletar-item" data-toggle="modal" data-target="#modalDeletarItem"
                                        data-iditem="${item.id_item}"
                                        data-nomedocumento="${item.nome_documento}">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>`;
                                itensHtml += '</tr>';
                            });
                        } else {
                            itensHtml = '<tr><td colspan="6" class="text-center">Nenhum item cadastrado nesta caixa.</td></tr>';
                        }

                        $('#corpoTabelaItens').html(itensHtml);
                    },
                    error: function(xhr, status, error) {
                        console.error('Erro ao carregar itens:', error);
                        $('#corpoTabelaItens').html('<tr><td colspan="6" class="text-center text-danger">Erro ao carregar itens da caixa.</td></tr>');
                    }
                });
            }
        });
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/assets/js/dataTables/datatables-arquivos_morto.js"></script>
</body>

</html>

