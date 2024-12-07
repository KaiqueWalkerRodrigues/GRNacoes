<?php 
    $Tecnologia_Socket = new Tecnologia_Socket();

    if (isset($_POST['btnCadastrar'])) {
        $Tecnologia_Socket->cadastrar($_POST);
    }
    if (isset($_POST['btnEditar'])) {
        $Tecnologia_Socket->editar($_POST);
    }
    if (isset($_POST['btnDeletar'])) {
        $Tecnologia_Socket->deletar($_POST['id_tecnologia_socket'],$_POST['usuario_logado']);
    }
?>
<!DOCTYPE html>
<html lang="en">

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
                                <span>Categorias</span>
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="container-fluid mt-n10">
                    <div class="card mb-4">
                        <div class="card-header">Categorias
                            <button class="btn btn-datatable btn-icon btn-sm btn-success ml-2" type="button" data-toggle="modal" data-target="#modalCadastrarCargo">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="datatable table-responsive">
                                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Categoria</th>
                                            <th>Quantidade (Fornecedores)</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($Compra_Categoria->listar() as $cc){ ?>
                                        <tr>
                                            <td><?php echo $cc->categoria ?></td>
                                            <td class="text-center"><?php echo $Compra_Categoria->contarFornecedores($cc->id_compra_categoria) ?></td>
                                            <td class="text-center">
                                                <button class="btn btn-datatable btn-icon btn-transparent-dark" data-toggle="modal" data-target="#modalEditarCategoria" class="collapse-item" 
                                                    data-id_categoria="<?php echo $cc->id_compra_categoria ?>" 
                                                    data-categoria="<?php echo $cc->categoria ?>"
                                                    ><i class="fa-solid fa-gear"></i>
                                                </button>
                                                <button class="btn btn-datatable btn-icon btn-transparent-dark" data-toggle="modal" data-target="#modalDeletarCategoria" class="collapse-item" 
                                                    data-id_categoria="<?php echo $cc->id_compra_categoria ?>" 
                                                    data-categoria="<?php echo $cc->categoria ?>"
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

    <!-- Modal Cadastrar Categoria -->
    <div class="modal fade" id="modalCadastrarCategoria" tabindex="1" role="dialog" aria-labelledby="modalCadastrarCategoriasLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cadastrar Nova Categoria</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-10 offset-1">
                                <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                                <label for="categoria" class="form-label">Nome da Categoria *</label>
                                <input type="text" name="categoria" class="form-control" required>
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

    <!-- Modal Editar Categoria -->
    <div class="modal fade" id="modalEditarCategoria" tabindex="1" role="dialog" aria-labelledby="modalEditarCategoriaLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Categoria: <span class="modalEditarCategoriaLabel"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-10 offset-1">
                                <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                                <input type="hidden" name="id_compra_categoria" id="editar_id_compra_categoria">
                                <label for="categoria" class="form-label">Nome da Categoria *</label>
                                <input type="text" name="categoria" id="editar_compra_categoria" class="form-control" required>
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

    <!-- Modal Deletar Categoria-->
    <div class="modal fade" id="modalDeletarCategoria" tabindex="-1" role="dialog" aria-labelledby="modalDeletarCategoriasLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Deletar o Categoria: <span class="modalDeletarCategoriaLabel"></span>?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <input type="hidden" name="id_compra_categoria" id="Deletar_id_compra_categoria">
                        <div class="row">
                            <p>Deseja Deletar a Categoria: <span class="modalDeletarCategoriaLabel"></span>?</p>
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
            $('#compras_configuracoes_categorias').addClass('active')

            $('#preloader').fadeOut('slow', function() { $(this).remove(); });

            $('#modalEditarCategoria').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget)
                let id_categoria = button.data('id_categoria')
                let categoria = button.data('categoria')
                $('.modalEditarCategoriaLabel').empty()
                $('.modalEditarCategoriaLabel').append(categoria)
                $('#editar_id_compra_categoria').empty()
                $('#editar_id_compra_categoria').val(id_categoria)
                $('#editar_compra_categoria').val(categoria)
            })

            $('#modalDeletarCategoria').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget)
                let id_categoria = button.data('id_categoria')
                let categoria = button.data('categoria')
                $('.modalDeletarCategoriaLabel').empty()
                $('.modalDeletarCategoriaLabel').append(categoria)
                $('#Deletar_id_compra_categoria').empty()
                $('#Deletar_id_compra_categoria').val(id_categoria)
            })
        });
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/js/scripts.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo URL_RESOURCES ?>/assets/js/dataTables/datatables-categorias.js"></script>
</body>

</html>