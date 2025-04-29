<?php 
    $Compra_Fornecedor = new Compra_Fornecedor();

    $Compra_Categoria = new Compra_Categoria();

    if (isset($_POST['btnCadastrar'])) {
        $Compra_Fornecedor->cadastrar($_POST);
    }
    if (isset($_POST['btnEditar'])) {
        $Compra_Fornecedor->editar($_POST);
    }
    if (isset($_POST['btnDeletar'])) {
        $Compra_Fornecedor->deletar($_POST['id_compra_fornecedor'],$_POST['usuario_logado']);
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
    <div id="preloader">
        <div class="spinner"></div>
    </div>
    <?php include_once('resources/topbar.php') ?>
    <div id="layoutSidenav">
        <?php include_once('resources/sidebar.php') ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="page-header pb-10 page-header-dark bg-primary">
                    <div class="container-fluid">
                        <div class="page-header-content">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i class="fa-solid fa-rectangle-history"></i></div>
                                <span>Fornecedores</span>
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="container-fluid mt-n10">
                    <div class="card mb-4">
                        <div class="card-header">Fornecedores
                            <button class="btn btn-datatable btn-icon btn-sm btn-success ml-2" type="button" data-toggle="modal" data-target="#modalCadastrarFornecedor">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="datatable table-responsive">
                                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Fornecedor</th>
                                            <th>Categoria</th>
                                            <th>Quantidade (Notas)</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($Compra_Fornecedor->listar() as $cf){ ?>
                                        <tr>
                                            <td><?php echo $cf->fornecedor ?></td>
                                            <td><?php echo $Compra_Categoria->nomeCategoria($cf->id_categoria) ?></td>
                                            <td class="text-center"><?php echo $Compra_Fornecedor->contarNotasPorFornecedor($cf->id_compra_fornecedor) ?></td>
                                            <td class="text-center">
                                            <button class="btn btn-datatable btn-icon btn-transparent-dark" data-toggle="modal" data-target="#modalEditarFornecedor" class="collapse-item"
                                                data-fornecedor="<?php echo $cf->fornecedor ?>"
                                                data-id_fornecedor="<?php echo $cf->id_compra_fornecedor ?>"
                                                data-id_categoria="<?php echo $cf->id_categoria ?>"
                                                    ><i class="fa-solid fa-gear"></i>
                                                </button>
                                                <button class="btn btn-datatable btn-icon btn-transparent-dark" data-toggle="modal" data-target="#modalDeletarFornecedor" class="collapse-item"
                                                    data-fornecedor="<?php echo $cf->fornecedor ?>"
                                                    data-id_fornecedor="<?php echo $cf->id_compra_fornecedor ?>"
                                                    data-id_categoria="<?php echo $cf->id_categoria?>"
                                                    ><i class="fa-solid fa-trash"></i></button>
                                            </button>
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

    <!-- Modal Cadastrar Fornecedor -->
    <div class="modal fade" id="modalCadastrarFornecedor" tabindex="1" role="dialog" aria-labelledby="modalCadastrarFornecedorLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cadastrar Novo Fornecedor</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <div class="row">
                            <div class="col-5 offset-1">
                                <label for="fornecedor" class="form-label">Nome do Fornecedor *</label>
                                <input type="text" name="fornecedor" class="form-control" required>
                            </div>
                            <div class="col-5">
                                <label for="id_categoria" class="form-label">Categoria *</label>
                                <select name="id_categoria" id="cadastrar_id_categoria" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach($Compra_Categoria->listar() as $cc){ ?>
                                        <option value="<?php echo $cc->id_compra_categoria ?>"><?php echo $cc->categoria ?></option>
                                    <?php } ?>
                                </select>
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

    <!-- Modal Editar Fornecedor -->
    <div class="modal fade" id="modalEditarFornecedor" tabindex="1" role="dialog" aria-labelledby="modalEditarFornecedorLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Fornecedor: <span class="modalEditarFornecedorLabel"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <input type="hidden" name="id_compra_fornecedor" id="editar_id_compra_fornecedor">
                        <div class="row">
                            <div class="col-5 offset-1">
                                <label for="fornecedor" class="form-label">Nome do Fornecedor *</label>
                                <input type="text" name="fornecedor" id="editar_fornecedor" class="form-control" required>
                            </div>
                            <div class="col-5">
                                <label for="editar_id_categoria" class="form-label">Categoria *</label>
                                <select name="id_categoria" id="editar_id_categoria" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach($Compra_Categoria->listar() as $cc){ ?>
                                        <option value="<?php echo $cc->id_compra_categoria ?>"><?php echo $cc->categoria ?></option>
                                    <?php } ?>
                                </select>
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

    <!-- Modal Deletar Fornecedor-->
    <div class="modal fade" id="modalDeletarFornecedor" tabindex="-1" role="dialog" aria-labelledby="modalDeletarFornecedorLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Deletar o Fornecedor: <span class="modalDeletarFornecedorLabel"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_compra_fornecedor" id="Deletar_id_compra_fornecedor">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <div class="container row">
                            <p>Deseja Deletar o Fornecedor: <span class="modalDeletarFornecedorLabel"></span>?</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnDeletar" class="btn btn-danger">Deletar</button>
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
            $('#compras_configuracoes').addClass('active')
            $('#compras_configuracoes_fornecedores').addClass('active')

            $('#preloader').fadeOut('slow', function() { $(this).remove(); });

            $('#modalEditarFornecedor').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget)
                let id_fornecedor = button.data('id_fornecedor')
                let fornecedor = button.data('fornecedor')
                let id_categoria = button.data('id_categoria')
                $('.modalEditarFornecedorLabel').empty()
                $('.modalEditarFornecedorLabel').append(fornecedor)
                $('#editar_id_compra_fornecedor').empty()
                $('#editar_id_compra_fornecedor').val(id_fornecedor)
                $('#editar_fornecedor').val(fornecedor)
                $('#editar_id_categoria').val(id_categoria)
            })

            $('#modalDeletarFornecedor').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget)
                let id_fornecedor = button.data('id_fornecedor')
                let fornecedor = button.data('fornecedor')
                $('.modalDeletarFornecedorLabel').empty()
                $('.modalDeletarFornecedorLabel').append(fornecedor)
                $('#Deletar_id_compra_fornecedor').empty()
                $('#Deletar_id_compra_fornecedor').val(id_fornecedor)
            })
        });
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/assets/js/dataTables/datatables-fornecedores.js"></script>
</body>

</html>