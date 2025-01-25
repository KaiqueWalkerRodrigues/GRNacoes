<?php 
    $Compra_Pedido = new Compra_Pedido();
    $Compra_Fornecedor = new Compra_Fornecedor();
    $Compra_Categoria = new Compra_Categoria();
    $Setor = new Setor();

    if (isset($_POST['btnCadastrar'])) {
        $Compra_Pedido->cadastrar($_POST);
    }
    if (isset($_POST['btnEditar'])) {
        $Compra_Pedido->editar($_POST);
    }
    if (isset($_POST['btnConfirmar'])) {
        $Compra_Pedido->confirmarCompra($_POST['id_compra_pedido'], $_POST['usuario_logado']);
    }
    if (isset($_POST['btnNegar'])) {
        $Compra_Pedido->negarCompra($_POST['id_compra_pedido'], $_POST['usuario_logado']);
    }
    if (isset($_POST['btnCancelarCompra'])) {
        $Compra_Pedido->cancelarCompra($_POST['id_compra_pedido'], $_POST['usuario_logado']);
    }
    if (isset($_POST['btnCancelarNegacao'])) {
        $Compra_Pedido->cancelarNegacao($_POST['id_compra_pedido'], $_POST['usuario_logado']);
    }
    if (isset($_POST['btnDeletarPedido'])) {
        $Compra_Pedido->deletar($_POST['id_compra_pedido'], $_SESSION['id_usuario']);
    }
?>

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
    <div id="preloader">
        <div class="spinner"></div>
    </div>
    <?php include_once('resources/topbar.php') ?>
    <div id="layoutSidenav">
        <?php 
            include_once('resources/sidebar.php');
            $id_setor_usuario = $_SESSION['id_setor'];
            $id_usuario_logado = $_SESSION['id_usuario'];
        ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="page-header pb-10 page-header-dark bg-primary">
                    <div class="container-fluid">
                        <div class="page-header-content">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i class="fa-solid fa-rectangle-history"></i></div>
                                <span>Pedidos</span>
                            </h1>
                            <br>
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <label for="filtroEmpresa" class="form-label text-light">Filtrar por Empresa:</label>
                                    <select id="filtroEmpresa" class="form-control">
                                        <option value="">Todos</option>
                                        <option value="Clínica Parque">Clínica Parque</option>
                                        <option value="Clínica Mauá">Clínica Mauá</option>
                                        <option value="Clínica Jardim">Clínica Jardim</option>
                                        <option value="Ótica Matriz">Ótica Matriz</option>
                                        <option value="Ótica Prestigio">Ótica Prestigio</option>
                                        <option value="Ótica Daily">Ótica Daily</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid mt-n10">
                    <div class="card mb-4">
                        <div class="card-header">Pedidos
                            <button class="btn btn-datatable btn-icon btn-sm btn-success ml-2 mr-2" type="button" data-toggle="modal" data-target="#modalCadastrarPedido">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                            |
                            <button class="btn btn-success ml-2 mr-2" data-toggle="modal" data-target="#modalComprados">Pedidos Comprados</button>
                            |
                            <button class="btn btn-danger ml-2" data-toggle="modal" data-target="#modalNegados">Pedidos Negados</button>
                        </div>
                        <div class="card-body">
                            <div class="datatable table-responsive">
                                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th class="d-none">Created_at</th>
                                            <th>Protocolo</th>
                                            <th>Urgência</th>
                                            <th>Título</th>
                                            <th>Empresa</th>
                                            <th>Setor</th>
                                            <th>Link</th>
                                            <th>Data</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        // Se o usuário não for do setor 1 ou 3, mostre apenas os pedidos dele
                                        if ($id_setor_usuario !== 1 && $id_setor_usuario !== 3) {
                                            $pedidos = $Compra_Pedido->listarPedidosDoUsuario($id_usuario_logado);
                                        } else {
                                            $pedidos = $Compra_Pedido->listar();
                                        }
                                        
                                        foreach($pedidos as $cp){ 
                                            if ($cp->status !== 'comprado' && $cp->status !== 'negado') { // Não mostrar pedidos comprados ou negados
                                        ?>
                                        <tr>
                                            <td class="d-none"><?php echo $cp->created_at ?></td>
                                            <td class="text-center"><?php echo $cp->id_compra_pedido ?></td>
                                            <td class="text-center"><?php echo Helper::Urgencia($cp->urgencia); ?></td>
                                            <td><?php echo $cp->titulo ?></td>
                                            <td><?php echo Helper::mostrar_empresa($cp->empresa); ?></td>
                                            <td><?php echo $Setor->mostrar($cp->id_setor)->setor ?></td>
                                            <td>
                                                <?php if (!empty($cp->link)) { ?>
                                                    <a href="<?php echo $cp->link ?>" target="_blank">Ver Link</a>
                                                <?php } ?>
                                            </td>
                                            <td><?php $dt = new DateTime($cp->created_at); echo $dt->format('d/m/Y') ?></td>
                                            <td class="text-center">
                                                <button class="btn btn-datatable btn-icon btn-transparent-dark" data-toggle="modal" data-target="#modalVerDescricao" class="collapse-item"
                                                data-descricao="<?php echo $cp->descricao ?>">
                                                    <i class="fa-solid fa-eye"></i>
                                                </button>
                                                <button class="btn btn-datatable btn-icon btn-transparent-dark" data-toggle="modal" data-target="#modalEditarPedido" class="collapse-item"
                                                    data-id_compra_pedido="<?php echo $cp->id_compra_pedido ?>"
                                                    data-titulo="<?php echo $cp->titulo ?>"
                                                    data-empresa="<?php echo $cp->empresa ?>"
                                                    data-id_setor="<?php echo $cp->id_setor ?>"
                                                    data-link="<?php echo $cp->link ?>"
                                                    data-urgencia="<?php echo $cp->urgencia ?>"
                                                    data-descricao="<?php echo $cp->descricao ?>">
                                                    <i class="fa-solid fa-gear"></i>
                                                </button>
                                                <?php if(verificarSetor([1,3])){ ?>
                                                    <button class="btn btn-datatable btn-icon btn-transparent-dark" data-toggle="modal" data-target="#modalConfirmarCompra" class="collapse-item"
                                                        data-id_compra_pedido="<?php echo $cp->id_compra_pedido ?>"
                                                        data-titulo="<?php echo $cp->titulo ?>">
                                                        <i class="fa-solid fa-check"></i>
                                                    </button>
                                                <?php } ?>
                                                <?php if(verificarSetor([1,3])){ ?>
                                                    <button class="btn btn-datatable btn-icon btn-transparent-dark" data-toggle="modal" data-target="#modalNegarCompra" class="collapse-item"
                                                        data-id_compra_pedido="<?php echo $cp->id_compra_pedido ?>"
                                                        data-titulo="<?php echo $cp->titulo ?>">
                                                        <i class="fa-solid fa-times"></i>
                                                    </button>
                                                <?php } ?>
                                                <?php if($cp->id_usuario == $_SESSION['id_usuario'] or verificarSetor([1])){ ?>
                                                <button class="btn btn-datatable btn-icon btn-transparent-dark" data-toggle="modal" data-target="#modalDeletarPedido" class="collapse-item"
                                                    data-id_compra_pedido="<?php echo $cp->id_compra_pedido ?>"
                                                    data-titulo="<?php echo $cp->titulo ?>">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php } } ?>
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

    <!-- Modal Ver Descrição -->
    <div class="modal fade" id="modalVerDescricao" tabindex="-1" role="dialog" aria-labelledby="modalCancelarCompraLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Descrição do Pedido</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div><p id="descricao"></p></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Cadastrar Pedido -->
    <div class="modal fade" id="modalCadastrarPedido" tabindex="1" role="dialog" aria-labelledby="modalCadastrarPedidoLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cadastrar Novo Pedido</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="usuario_logado" id="id_usuario" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <div class="row">
                            <div class="col-6">
                                <label for="titulo" class="form-label">Título *</label>
                                <input type="text" id="cadastrar_titulo" name="titulo" class="form-control" required>
                            </div>
                            <div class="col-6">
                                <label for="empresa" class="form-label">Empresa *</label>
                                <select name="empresa" id="cadastrar_empresa" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <option value="1">Clínica Parque</option>
                                    <option value="3">Clínica Mauá</option>
                                    <option value="5">Clínica Jardim</option>
                                    <option value="2">Ótica Matriz</option>
                                    <option value="4">Ótica Prestigio</option>
                                    <option value="6">Ótica Daily</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 col-sm-3 mb-2">
                                <label for="cadastrar_id_setor" class="form-label">Setor *</label>
                                <select name="id_setor" id="cadastrar_setor" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <?php 
                                        foreach($Setor->listar() as $s){
                                    ?>
                                        <option value="<?php echo $s->id_setor ?>"><?php echo $s->setor ?></option>
                                    <?php 
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-2 col-sm-3">
                                <label for="cadastrar_urgencia" class="form-label">Urgência *</label>
                                <select name="urgencia" id="cadastrar_urgencia" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <option value="1">Baixa</option>
                                    <option value="2">Média</option>
                                    <option value="3">Alta</option>
                                    <option value="4">Urgente</option>
                                </select>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <label for="link" class="form-label">Link</label>
                                <input type="url" id="cadastrar_link" name="link" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label for="cadastrar_descricao" class="form-label">Descrição</label>
                                <textarea name="descricao" id="cadastrar_descricao" class="form-control" cols="30" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnCadastrar" class="btn btn-primary">Cadastrar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Editar Pedido -->
    <div class="modal fade" id="modalEditarPedido" tabindex="1" role="dialog" aria-labelledby="modalEditarPedidoLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Pedido: <span class="modalEditarPedidoLabel"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <input type="hidden" name="id_compra_pedido" id="editar_id_compra_pedido">
                        <div class="row">
                            <div class="col-6">
                                <label for="titulo" class="form-label">Título *</label>
                                <input type="text" id="editar_titulo" name="titulo" class="form-control" required>
                            </div>
                            <div class="col-6">
                                <label for="empresa" class="form-label">Empresa *</label>
                                <select name="empresa" id="editar_empresa" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <option value="1">Clínica Parque</option>
                                    <option value="3">Clínica Mauá</option>
                                    <option value="5">Clínica Jardim</option>
                                    <option value="2">Ótica Matriz</option>
                                    <option value="4">Ótica Prestigio</option>
                                    <option value="6">Ótica Daily</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3 mb-2">
                                <label for="editar_id_setor" class="form-label">Setor *</label>
                                <select name="id_setor" id="editar_id_setor" class="form-control" required>
                                        <?php 
                                            foreach($Setor->listar() as $s){
                                        ?>
                                            <option value="<?php echo $s->id_setor ?>"><?php echo $s->setor ?></option>
                                        <?php 
                                            }
                                        ?>
                                    </select>
                            </div>
                            <div class="col-2">
                                <label for="editar_urgencia" class="form-label">Urgência *</label>
                                <select name="urgencia" id="editar_urgencia" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <option value="1">Baixa</option>
                                    <option value="2">Média</option>
                                    <option value="3">Alta</option>
                                    <option value="4">Urgente</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label for="link" class="form-label">Link</label>
                                <input type="url" id="editar_link" name="link" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label for="editar_descricao" class="form-label">Descrição</label>
                                <textarea name="descricao" id="editar_descricao" class="form-control" cols="30" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnEditar" class="btn btn-primary">Editar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Confirmar Compra -->
    <div class="modal fade" id="modalConfirmarCompra" tabindex="-1" role="dialog" aria-labelledby="modalConfirmarCompraLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmar Compra do Pedido: <span class="modalConfirmarCompraLabel"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <input type="hidden" name="id_compra_pedido" id="confirmar_id_compra_pedido">
                        <div class="row">
                            <p>Deseja confirmar a compra do pedido: <span class="modalConfirmarCompraLabel"></span>?</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnConfirmar" class="btn btn-success">Confirmar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Negar Compra -->
    <div class="modal fade" id="modalNegarCompra" tabindex="-1" role="dialog" aria-labelledby="modalNegarCompraLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Negar Compra do Pedido: <span class="modalNegarCompraLabel"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <input type="hidden" name="id_compra_pedido" id="negar_id_compra_pedido">
                        <div class="row">
                            <p>Deseja negar a compra do pedido: <span class="modalNegarCompraLabel"></span>?</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnNegar" class="btn btn-danger">Negar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Pedidos Comprados -->
    <div class="modal fade" id="modalComprados" tabindex="-1" role="dialog" aria-labelledby="modalCompradosLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pedidos Comprados</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tableComprados" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th class="d-none">Created_at</th>
                                    <th>Protocolo</th> <!-- Coluna de Protocolo adicionada -->
                                    <th>Urgência</th>
                                    <th>Título</th>
                                    <th>Empresa</th>
                                    <th>Setor</th>
                                    <th>Link</th>
                                    <th>Data</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $comprados = $Compra_Pedido->listarComprados($id_usuario_logado, $id_setor_usuario);
                                foreach($comprados as $cp){ ?>
                                <tr>
                                    <td class="d-none"><?php echo $cp->created_at ?></td>
                                    <td class="text-center"><?php echo $cp->id_compra_pedido ?></td> <!-- Protocolo adicionado -->
                                    <td><?php echo Helper::Urgencia($cp->urgencia); ?></td>
                                    <td><?php echo $cp->titulo ?></td>
                                    <td><?php echo Helper::mostrar_empresa($cp->empresa); ?></td>
                                    <td><?php echo $Setor->mostrar($cp->id_setor)->setor ?></td>
                                    <td><a href="<?php echo $cp->link ?>" target="_blank">Ver Link</a></td>
                                    <td><?php $dt = new DateTime($cp->created_at); echo $dt->format('d/m/Y') ?></td>
                                    <td class="text-center">
                                        <button class="btn btn-datatable btn-icon btn-transparent-dark" data-toggle="modal" data-target="#modalVerDescricao" class="collapse-item"
                                            data-descricao="<?php echo $cp->descricao ?>">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <?php if($_SESSION['id_setor'] == 1 or $_SESSION['id_setor'] == 3){ ?>
                                        <button class="btn btn-datatable btn-icon btn-transparent-dark" data-toggle="modal" data-target="#modalCancelarCompra" class="collapse-item"
                                            data-id_compra_pedido="<?php echo $cp->id_compra_pedido ?>"
                                            data-titulo="<?php echo $cp->titulo ?>">
                                            <i class="fa-solid fa-ban"></i>
                                        </button>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Pedidos Negados -->
    <div class="modal fade" id="modalNegados" tabindex="-1" role="dialog" aria-labelledby="modalNegadosLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pedidos Negados</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tableNegados" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Protocolo</th> <!-- Coluna de Protocolo adicionada -->
                                    <th>Urgência</th>
                                    <th>Título</th>
                                    <th>Empresa</th>
                                    <th>Setor</th>
                                    <th>Link</th>
                                    <th>Data</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $negados = $Compra_Pedido->listarNegados($id_usuario_logado, $id_setor_usuario);
                                foreach($negados as $cp){ ?>
                                <tr>
                                    <td class="text-center"><?php echo $cp->id_compra_pedido ?></td> <!-- Protocolo adicionado -->
                                    <td><?php echo Helper::Urgencia($cp->urgencia); ?></td>
                                    <td><?php echo $cp->titulo ?></td>
                                    <td><?php echo Helper::mostrar_empresa($cp->empresa); ?></td>
                                    <td><?php echo $Setor->mostrar($cp->id_setor)->setor ?></td>
                                    <td><a href="<?php echo $cp->link ?>" target="_blank">Ver Link</a></td>
                                    <td><?php $dt = new DateTime($cp->created_at); echo $dt->format('d/m/Y') ?></td>
                                    <td class="text-center">
                                        <button class="btn btn-datatable btn-icon btn-transparent-dark" data-toggle="modal" data-target="#modalVerDescricao" class="collapse-item"
                                            data-descricao="<?php echo $cp->descricao ?>">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <?php if(verificarSetor([1,3])){ ?>
                                        <button class="btn btn-datatable btn-icon btn-transparent-dark" data-toggle="modal" data-target="#modalCancelarNegacao" class="collapse-item"
                                            data-id_compra_pedido="<?php echo $cp->id_compra_pedido ?>"
                                            data-titulo="<?php echo $cp->titulo ?>">
                                            <i class="fa-solid fa-ban"></i>
                                        </button>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Cancelar Compra -->
    <div class="modal fade" id="modalCancelarCompra" tabindex="-1" role="dialog" aria-labelledby="modalCancelarCompraLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cancelar Compra do Pedido: <span class="modalCancelarCompraLabel"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <input type="hidden" name="id_compra_pedido" id="cancelar_id_compra_pedido">
                        <div class="row">
                            <p>Deseja cancelar a compra do pedido: <span class="modalCancelarCompraLabel"></span>?</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnCancelarCompra" class="btn btn-warning">Cancelar Compra</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Cancelar Negação -->
    <div class="modal fade" id="modalCancelarNegacao" tabindex="-1" role="dialog" aria-labelledby="modalCancelarNegacaoLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cancelar Negação do Pedido: <span class="modalCancelarNegacaoLabel"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <input type="hidden" name="id_compra_pedido" id="cancelar_id_negacao">
                        <div class="row">
                            <p>Deseja cancelar a negação do pedido: <span class="modalCancelarNegacaoLabel"></span>?</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnCancelarNegacao" class="btn btn-warning">Cancelar Negação</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Deletar Pedido -->
    <div class="modal fade" id="modalDeletarPedido" tabindex="-1" role="dialog" aria-labelledby="modalDeletarPedidoLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Deletar o Pedido: <span class="modalDeletarPedidoLabel"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <input type="hidden" name="id_compra_pedido" id="deletar_id_compra_pedido">
                        <div class="row">
                            <p>Deseja Deletar o pedido: <span class="modalDeletarPedidoLabel"></span>?</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnDeletarPedido" class="btn btn-danger">Deletar</button>
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
            $('#comp').addClass('active')
            $('#compra_pedido').addClass('active')

            $('#preloader').fadeOut('slow', function() { $(this).remove(); });

            // Evento de alteração nos filtros
            $('#filtroEmpresa').change(function() {
                var filtroEmpresa = $('#filtroEmpresa').val();

                // Aplicar filtros no DataTable usando a API search
                var table = $('#dataTable').DataTable();
                table.column(4).search(filtroEmpresa);

                // Redesenhar a tabela
                table.draw();
            });

            $('#modalVerDescricao').on('show.bs.modal', function(event) {
                let button = $(event.relatedTarget);
                let descricao = button.data('descricao');

                // Definindo a descrição no modal
                $('#descricao').text(descricao);

                // Ajuste de z-index para que o modal de descrição esteja à frente
                let modalZIndex = 1050 + ($('.modal-backdrop').length * 10); // Calcula um z-index superior
                $(this).css('z-index', modalZIndex);
                
                // Ajuste do backdrop para garantir que o modal de detalhes esteja visível
                setTimeout(function() {
                    $('.modal-backdrop').last().css('z-index', modalZIndex - 1);
                }, 0);
            });

            $('#modalVerDescricao').on('hidden.bs.modal', function() {
                // Redefine o z-index do backdrop ao fechar o modal
                $('.modal-backdrop').css('z-index', 1040);
            });

            $('#modalEditarPedido').on('show.bs.modal', function(event) {
                let button = $(event.relatedTarget);
                let id_compra_pedido = button.data('id_compra_pedido');
                let titulo = button.data('titulo');
                let empresa = button.data('empresa');
                let id_setor = button.data('id_setor');
                let link = button.data('link');
                let urgencia = button.data('urgencia');
                let descricao = button.data('descricao');

                $('#editar_id_compra_pedido').val(id_compra_pedido);
                $('#editar_titulo').val(titulo);
                $('#editar_empresa').val(empresa);
                $('#editar_id_setor').val(id_setor);
                $('#editar_link').val(link);
                $('#editar_urgencia').val(urgencia);
                $('#editar_descricao').val(descricao);
            });

            $('#modalConfirmarCompra').on('show.bs.modal', function(event) {
                let button = $(event.relatedTarget);
                let id_pedido = button.data('id_compra_pedido');
                let titulo = button.data('titulo');
                $('.modalConfirmarCompraLabel').empty().append(titulo);
                $('#confirmar_id_compra_pedido').val(id_pedido);
            });

            $('#modalNegarCompra').on('show.bs.modal', function(event) {
                let button = $(event.relatedTarget);
                let id_pedido = button.data('id_compra_pedido');
                let titulo = button.data('titulo');
                $('.modalNegarCompraLabel').empty().append(titulo);
                $('#negar_id_compra_pedido').val(id_pedido);
            });

            $('#modalCancelarCompra').on('show.bs.modal', function(event) {
                let button = $(event.relatedTarget);
                let id_pedido = button.data('id_compra_pedido');
                let titulo = button.data('titulo');
                $('.modalCancelarCompraLabel').empty().append(titulo);
                $('#cancelar_id_compra_pedido').val(id_pedido);
            });

            $('#modalCancelarNegacao').on('show.bs.modal', function(event) {
                let button = $(event.relatedTarget);
                let id_pedido = button.data('id_compra_pedido');
                let titulo = button.data('titulo');
                $('.modalCancelarNegacaoLabel').empty().append(titulo);
                $('#cancelar_id_negacao').val(id_pedido);
            });

            $('#modalDeletarPedido').on('show.bs.modal', function(event) {
                let button = $(event.relatedTarget);
                let id_pedido = button.data('id_compra_pedido');
                let titulo = button.data('titulo');
                $('.modalDeletarPedidoLabel').empty().append(titulo);
                $('#deletar_id_compra_pedido').val(id_pedido);
            });

            // Quando o modal principal for fechado, esconda todos os modais abertos
            $('.modal').on('hidden.bs.modal', function() {
                if ($('.modal:visible').length > 0) {
                    $('body').addClass('modal-open');
                }
            });
        });
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/assets/js/dataTables/datatables-pedidos.js"></script>
</body>

</html>
