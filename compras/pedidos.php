<?php 
    include_once('../const.php');  

    $Compras_Pedidos = new Compras_Pedidos();

    $Compras_Fornecedores = new Compras_Fornecedores();

    $Compras_Categorias = new Compras_Categorias();

    $Setor = new Setor();

    if (isset($_POST['btnCadastrar'])) {
        $Compras_Pedidos->cadastrar($_POST);
        header('location:/GRNacoes/compras/pedidos');
    }
    if (isset($_POST['btnEditar'])) {
        $Compras_Pedidos->editar($_POST);
        header('location:/GRNacoes/compras/pedidos');
    }
    if (isset($_POST['btnDesativar'])) {
        $Compras_Pedidos->desativar($_POST['id_compra_pedido'], $_POST['usuario_logado']);
        header('location:/GRNacoes/compras/pedidos');
    }
    if (isset($_POST['btnConfirmar'])) {
        $Compras_Pedidos->confirmarCompra($_POST['id_compra_pedido'], $_POST['usuario_logado']);
        header('location:/GRNacoes/compras/pedidos');
    }
    if (isset($_POST['btnNegar'])) {
        $Compras_Pedidos->negarCompra($_POST['id_compra_pedido'], $_POST['usuario_logado']);
        header('location:/GRNacoes/compras/pedidos');
    }
    if (isset($_POST['btnCancelarCompra'])) {
        $Compras_Pedidos->cancelarCompra($_POST['id_compra_pedido'], $_POST['usuario_logado']);
        header('location:/GRNacoes/compras/pedidos');
    }
    if (isset($_POST['btnCancelarNegacao'])) {
        $Compras_Pedidos->cancelarNegacao($_POST['id_compra_pedido'], $_POST['usuario_logado']);
        header('location:/GRNacoes/compras/pedidos');
    }
    if (isset($_POST['btnDesativarPedido'])) {
        $Compras_Pedidos->desativar($_POST['id_compra_pedido'], $_POST['usuario_logado']);
        header('location:/GRNacoes/compras/pedidos');
    }
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>GRNacoes - Pedidos de Compras</title>

    <!-- Custom fonts for this template -->
    <link href="<?php echo URL ?>/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo URL ?>/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="<?php echo URL ?>/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php 
            include_once('../sidebar.php'); 
            $id_setor_usuario = $_SESSION['id_setor']; // Id do setor do usuário logado
            $id_usuario_logado = $_SESSION['id_usuario']; // Id do usuário logado
        ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include_once('../topbar.php'); ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <br><br><br><br>

                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label for="filtroMes" class="form-label">Filtrar por Mês:</label>
                            <select id="filtroMes" class="form-control">
                                <option value="">Todos</option>
                                <option value="Jan/24">Jan/24</option>
                                <option value="Fev/24">Fev/24</option>
                                <option value="Mar/24">Mar/24</option>
                                <option value="Abr/24">Abr/24</option>
                                <option value="Mai/24">Mai/24</option>
                                <option value="Jun/24">Jun/24</option>
                                <option value="Jul/24">Jul/24</option>
                                <option value="Ago/24">Ago/24</option>
                                <option value="Set/24">Set/24</option>
                                <option value="Out/24">Out/24</option>
                                <option value="Nov/24">Nov/24</option>
                                <option value="Dez/24">Dez/24</option>
                                <option value="Jan/25">Jan/25</option>
                                <option value="Fev/25">Fev/25</option>
                                <option value="Mar/25">Mar/25</option>
                                <option value="Abr/25">Abr/25</option>
                                <option value="Mai/25">Mai/25</option>
                                <option value="Jun/25">Jun/25</option>
                                <option value="Jul/25">Jul/25</option>
                                <option value="Ago/25">Ago/25</option>
                                <option value="Set/25">Set/25</option>
                                <option value="Out/25">Out/25</option>
                                <option value="Nov/25">Nov/25</option>
                                <option value="Dez/25">Dez/25</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filtroEmpresa" class="form-label">Filtrar por Empresa:</label>
                            <select id="filtroEmpresa" class="form-control">
                                <option value="">Todos</option>
                                <option value="Clínica Parque">Clínica Parque</option>
                                <option value="Clínica Mauá">Clínica Mauá</option>
                                <option value="Clínica Jardim">Clínica Jardim</option>
                                <option value="Ótica Matriz">Ótica Matriz</option>
                                <option value="Ótica Prestigio">Ótica Prestigio</option>
                                <option value="Ótica Daily">Ótica Daily</option>
                                <!-- Adicione opções para as Empresas -->
                            </select>
                        </div>
                    </div>

                    <!-- DataTables Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Pedidos de Compras | 
                                <button class="btn btn-primary" data-toggle="modal" data-target="#modalCadastrarPedido" class="collapse-item">Cadastrar Novo Pedido</button>
                                <button class="btn btn-success" data-toggle="modal" data-target="#modalComprados">Pedidos Comprados</button>
                                <button class="btn btn-danger" data-toggle="modal" data-target="#modalNegados">Pedidos Negados</button>
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
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
                                            $pedidos = $Compras_Pedidos->listarPedidosDoUsuario($id_usuario_logado);
                                        } else {
                                            $pedidos = $Compras_Pedidos->listar();
                                        }

                                        foreach($pedidos as $cp){ 
                                            if ($cp->status !== 'comprado' && $cp->status !== 'negado') { // Não mostrar pedidos comprados ou negados
                                        ?>
                                        <tr>
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
                                                <button class="btn btn-info" data-toggle="modal" data-target="#modalVerDescricao" class="collapse-item"
                                                data-descricao="<?php echo $cp->descricao ?>">
                                                    <i class="fa-solid fa-eye"></i>
                                                </button>
                                                <button class="btn btn-secondary" data-toggle="modal" data-target="#modalEditarPedido" class="collapse-item"
                                                    data-id_compra_pedido="<?php echo $cp->id_compra_pedido ?>"
                                                    data-titulo="<?php echo $cp->titulo ?>"
                                                    data-empresa="<?php echo $cp->empresa ?>"
                                                    data-id_setor="<?php echo $cp->id_setor ?>"
                                                    data-link="<?php echo $cp->link ?>"
                                                    data-urgencia="<?php echo $cp->urgencia ?>"
                                                    data-descricao="<?php echo $cp->descricao ?>">
                                                    <i class="fa-solid fa-gear"></i>
                                                </button>
                                                <?php if($_SESSION['id_setor'] == 1 or $_SESSION['id_setor'] == 3){ ?>
                                                    <button class="btn btn-success" data-toggle="modal" data-target="#modalConfirmarCompra" class="collapse-item"
                                                        data-id_compra_pedido="<?php echo $cp->id_compra_pedido ?>"
                                                        data-titulo="<?php echo $cp->titulo ?>">
                                                        <i class="fa-solid fa-check"></i>
                                                    </button>
                                                <?php } ?>
                                                <?php if($_SESSION['id_setor'] == 1 or $_SESSION['id_setor'] == 3){ ?>
                                                    <button class="btn btn-dark" data-toggle="modal" data-target="#modalNegarCompra" class="collapse-item"
                                                        data-id_compra_pedido="<?php echo $cp->id_compra_pedido ?>"
                                                        data-titulo="<?php echo $cp->titulo ?>">
                                                        <i class="fa-solid fa-times"></i>
                                                    </button>
                                                <?php } ?>
                                                <?php if($cp->id_usuario == $_SESSION['id_usuario'] or $_SESSION['id_setor'] == 1){ ?>
                                                <button class="btn btn-danger" data-toggle="modal" data-target="#modalDesativarPedido" class="collapse-item"
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
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Grupo Nações <?php echo date("Y"); ?></span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
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
                            <div class="col-3 mb-2">
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
                            <div class="col-2">
                                <label for="cadastrar_urgencia" class="form-label">Urgência *</label>
                                <select name="urgencia" id="cadastrar_urgencia" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <option value="1">Baixa</option>
                                    <option value="2">Média</option>
                                    <option value="3">Alta</option>
                                    <option value="4">Urgente</option>
                                </select>
                            </div>
                            <div class="col-6">
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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnNegar" class="btn btn-danger">Negar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Pedidos Comprados -->
    <div class="modal fade" id="modalComprados" tabindex="-1" role="dialog" aria-labelledby="modalCompradosLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document"> <!-- Alterado para modal-xl -->
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
                                $comprados = $Compras_Pedidos->listarComprados($id_usuario_logado, $id_setor_usuario);
                                foreach($comprados as $cp){ ?>
                                <tr>
                                    <td><?php echo Helper::Urgencia($cp->urgencia); ?></td>
                                    <td><?php echo $cp->titulo ?></td>
                                    <td><?php echo Helper::mostrar_empresa($cp->empresa); ?></td>
                                    <td><?php echo $Setor->mostrar($cp->id_setor)->setor ?></td>
                                    <td><a href="<?php echo $cp->link ?>" target="_blank">Ver Link</a></td>
                                    <td><?php $dt = new DateTime($cp->created_at); echo $dt->format('d/m/Y') ?></td>
                                    <td class="text-center">
                                        <button class="btn btn-dark" data-toggle="modal" data-target="#modalVerDescricao" class="collapse-item"
                                            data-descricao="<?php echo $cp->descricao ?>">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <?php if($_SESSION['id_setor'] == 1 or $_SESSION['id_setor'] == 3){ ?>
                                        <button class="btn btn-warning" data-toggle="modal" data-target="#modalCancelarCompra" class="collapse-item"
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Pedidos Negados -->
    <div class="modal fade" id="modalNegados" tabindex="-1" role="dialog" aria-labelledby="modalNegadosLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document"> <!-- Alterado para modal-xl -->
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
                                $negados = $Compras_Pedidos->listarNegados($id_usuario_logado, $id_setor_usuario);
                                foreach($negados as $cp){ ?>
                                <tr>
                                    <td><?php echo Helper::Urgencia($cp->urgencia); ?></td>
                                    <td><?php echo $cp->titulo ?></td>
                                    <td><?php echo Helper::mostrar_empresa($cp->empresa); ?></td>
                                    <td><?php echo $Setor->mostrar($cp->id_setor)->setor ?></td>
                                    <td><a href="<?php echo $cp->link ?>" target="_blank">Ver Link</a></td>
                                    <td><?php $dt = new DateTime($cp->created_at); echo $dt->format('d/m/Y') ?></td>
                                    <td class="text-center">
                                        <button class="btn btn-dark" data-toggle="modal" data-target="#modalVerDescricao" class="collapse-item"
                                            data-descricao="<?php echo $cp->descricao ?>">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <?php if($_SESSION['id_setor'] == 1 or $_SESSION['id_setor'] == 3){ ?>
                                        <button class="btn btn-warning" data-toggle="modal" data-target="#modalCancelarNegacao" class="collapse-item"
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnCancelarNegacao" class="btn btn-warning">Cancelar Negação</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Desatiar Pedido -->
    <div class="modal fade" id="modalDesativarPedido" tabindex="-1" role="dialog" aria-labelledby="modalDesativarPedidoLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Excluir o Pedido: <span class="modalDesativarPedidoLabel"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <input type="hidden" name="id_compra_pedido" id="desativar_id_compra_pedido">
                        <div class="row">
                            <p>Deseja Excluir o pedido: <span class="modalDesativarPedidoLabel"></span>?</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnDesativarPedido" class="btn btn-danger">Excluir</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo URL ?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo URL ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo URL ?>/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo URL ?>/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="<?php echo URL ?>/vendor/datatables/jquery.dataTablesPedidos.min.js"></script>
    <script src="<?php echo URL ?>/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?php echo URL ?>/js/demo/datatables-demo.js"></script>

    <script>
        $(document).ready(function() {
        $('#comp').addClass('active');
        $('#compras_pedidos').addClass('active');

        // Inicializar DataTables
        $('#dataTable').DataTable();
        $('#tableComprados').DataTable();
        $('#tableNegados').DataTable();

        // Evento de alteração nos filtros
        $('#filtroMes, #filtroEmpresa').change(function() {
            var filtroMes = $('#filtroMes').val();
            var filtroEmpresa = $('#filtroEmpresa').val();

            // Aplicar filtros no DataTable usando a API search
            var table = $('#dataTable').DataTable();
            table.column(5).search(filtroMes);
            table.column(2).search(filtroEmpresa);

            // Redesenhar a tabela
            table.draw();
        });

        // Inicializar modais e outras funcionalidades
        $('#modalVerDescricao').on('show.bs.modal', function(event) {
            let button = $(event.relatedTarget);
            let descricao = button.data('descricao');

            $('#descricao').text(descricao);

            // Ajuste de z-index para permitir empilhamento de modais
            $(this).css('z-index', parseInt($('.modal-backdrop').css('z-index')) + 1);
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

        $('#modalDesativarPedido').on('show.bs.modal', function(event) {
            let button = $(event.relatedTarget);
            let id_pedido = button.data('id_compra_pedido');
            let titulo = button.data('titulo');
            $('.modalDesativarPedidoLabel').empty().append(titulo);
            $('#desativar_id_compra_pedido').val(id_pedido);
        });

        // Quando o modal principal for fechado, esconda todos os modais abertos
        $('.modal').on('hidden.bs.modal', function() {
            if ($('.modal:visible').length > 0) {
                $('body').addClass('modal-open');
            }
        });
    });
    </script>

</body>

</html>
