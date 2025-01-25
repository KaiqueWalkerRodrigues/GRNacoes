<?php 
    $Lc_fornecedor = new Lente_contato_fornecedor();

    if (isset($_POST['btnCadastrar'])) {
       $Lc_fornecedor->cadastrar($_POST);
    }
    if (isset($_POST['btnEditar'])) {
        $Lc_fornecedor->editar($_POST);
    }
    if (isset($_POST['btnDeletar'])) {
        $Lc_fornecedor->deletar($_POST['id_lente_contato_fornecedor'],$_SESSION['id_usuario']);
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
        <?php include_once('resources/sidebar.php'); ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="page-header pb-10 page-header-dark bg-primary">
                    <div class="container-fluid">
                        <div class="page-header-content">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i class="fa-solid fa-rectangle-history"></i></div>
                                <span>Fornecedores de Lente de Contato</span>
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="container-fluid mt-n10">
                    <div class="card mb-4">
                        <div class="card-header">Fornecedores
                            <button class="btn btn-datatable btn-icon btn-sm btn-success ml-2 mr-2" type="button" data-toggle="modal" data-target="#modalCadastrarFornecedor">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="datatable table-responsive">
                                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Fornecedor</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($Lc_fornecedor->listar() as $fornecedor){ ?>
                                            <tr>
                                                <td><?php echo $fornecedor->id_lente_contato_fornecedor ?></td>
                                                <td><?php echo $fornecedor->fornecedor ?></td>
                                                <td class="text-center">
                                                    <button class="btn btn-datatable btn-icon btn-transparent-dark" type="button" data-toggle="modal" data-target="#modalEditarFornecedor"
                                                        data-fornecedor="<?php echo $fornecedor->fornecedor ?>"
                                                        data-id_lente_contato_fornecedor="<?php echo $fornecedor->id_lente_contato_fornecedor ?>"
                                                        >
                                                        <i class="fa-solid fa-gear"></i>
                                                    </button>
                                                    <button class="btn btn-datatable btn-icon btn-transparent-dark" type="button" data-toggle="modal" data-target="#modalDeletarFornecedor"
                                                        data-fornecedor="<?php echo $fornecedor->fornecedor ?>"
                                                        data-id_lente_contato_fornecedor="<?php echo $fornecedor->id_lente_contato_fornecedor ?>"
                                                    >
                                                        <i class="fa-solid fa-trash"></i>
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
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cadastrar Novo Fornecedor<h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="row col-10 offset-1">
                                <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                                <label for="fornecedor" class="form-label">Nome Fornecedor *</label>
                                <input type="text" name="fornecedor" id="cadastrar_fornecedor" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnCadastrar" class="btn btn-success">Cadastrar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- Modal Editar Fornecedor -->
    <div class="modal fade" id="modalEditarFornecedor" tabindex="1" role="dialog" aria-labelledby="modalEditarFornecedorLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar <span id="editar_fornecedor_nome"></span><h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="row col-10 offset-1">
                                <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                                <input type="hidden" name="id_lente_contato_fornecedor" id="editar_id_lente_contato_fornecedor">
                                <label for="fornecedor" class="form-label">Nome Fornecedor *</label>
                                <input type="text" name="fornecedor" id="editar_fornecedor" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnEditar" class="btn btn-success">Editar</button>
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
                        <input type="hidden" name="id_lente_contato_fornecedor" id="Deletar_id_lente_contato_fornecedor">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>" required>
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
            $('#lent').addClass('active')
            $('#lente_contato_configuracoes').addClass('active')
            $('#lente_contato_configuracoes_fornecedores').addClass('active')

            $('#modalEditarFornecedor').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget);
                let fornecedor = button.data('fornecedor');
                let id_lente_contato_fornecedor = button.data('id_lente_contato_fornecedor');
                $('#editar_fornecedor_nome').text(fornecedor);
                $('#editar_id_lente_contato_fornecedor').val(id_lente_contato_fornecedor);
                $('#editar_fornecedor').val(fornecedor);
            });

            $('#modalDeletarFornecedor').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget)
                let id_lente_contato_fornecedor = button.data('id_lente_contato_fornecedor')
                let fornecedor = button.data('fornecedor')
                $('.modalDeletarFornecedorLabel').empty()
                $('.modalDeletarFornecedorLabel').append(fornecedor)
                $('#Deletar_id_lente_contato_fornecedor').empty()
                $('#Deletar_id_lente_contato_fornecedor').val(id_lente_contato_fornecedor)
            })

            $('#preloader').fadeOut('slow', function() { $(this).remove(); });
        });
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/assets/js/dataTables/datatables-lente_contato_modelos.js"></script>
</body>

</html>
