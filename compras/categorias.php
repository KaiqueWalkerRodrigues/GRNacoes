<?php 
    include_once('../const.php'); 
    
    $Compras_Categorias = new Compras_Categorias();

    if (isset($_POST['btnCadastrar'])) {
        $Compras_Categorias->cadastrar($_POST);
    }
    if (isset($_POST['btnEditar'])) {
        $Compras_Categorias->editar($_POST);
    }
    if (isset($_POST['btnDesativar'])) {
        $Compras_Categorias->desativar($_POST['id_compra_categoria'],$_POST['usuario_logado']);
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>GRNacoes - Gerenciar Categorias</title>

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
        <?php include_once('../sidebar.php'); ?>
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

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Categorias Ativas | <button class="btn btn-primary" data-toggle="modal" data-target="#modalCadastrarCategoria" class="collapse-item">Cadastrar Nova Categoria</button></h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Categoria</th>
                                            <th>Quantidade (Fornecedores)</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($Compras_Categorias->listar() as $cc){ ?>
                                        <tr>
                                            <td><?php echo $cc->categoria ?></td>
                                            <td><?php echo $Compras_Categorias->contarFornecedores($cc->id_compra_categoria) ?></td>
                                            <td class="text-center">
                                                <button class="btn btn-secondary" data-toggle="modal" data-target="#modalEditarCategoria" class="collapse-item" 
                                                    data-id_categoria="<?php echo $cc->id_compra_categoria ?>" 
                                                    data-categoria="<?php echo $cc->categoria ?>">
                                                    <i class="fa-solid fa-gear"></i>
                                                </button>
                                                <button class="btn btn-danger" data-toggle="modal" data-target="#modalDesativarCategoria" class="collapse-item" 
                                                    data-id_categoria="<?php echo $cc->id_compra_categoria ?>" 
                                                    data-categoria="<?php echo $cc->categoria ?>">
                                                    <i class="fa-solid fa-power-off"></i>
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


    <!-- Modal Cadastrar Categorias -->
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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnCadastrar" class="btn btn-primary">Cadastrar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Editar Setir -->
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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnEditar" class="btn btn-primary">Editar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Desativar Usuário-->
    <div class="modal fade" id="modalDesativarCategoria" tabindex="-1" role="dialog" aria-labelledby="modalDesativarCategoriasLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Desativar o Categoria: <span class="modalDesativarCategoriaLabel"></span>?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <input type="hidden" name="id_compra_categoria" id="desativar_id_compra_categoria">
                        <div class="row">
                            <p>Deseja desativar a Categoria: <span class="modalDesativarCategoriaLabel"></span>?</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnDesativar" class="btn btn-danger">Desativar</button>
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
    <script src="<?php echo URL ?>/vendor/datatables/jquery.dataTablesCategorias.min.js"></script>
    <script src="<?php echo URL ?>/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?php echo URL ?>/js/demo/datatables-demo.js"></script>

    <script>
       $(document).ready(function() {
            $('#comp').addClass('active');
            $('#compras_categorias').addClass('active');

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

            $('#modalDesativarCategoria').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget)
                let id_categoria = button.data('id_categoria')
                let categoria = button.data('categoria')
                $('.modalDesativarCategoriaLabel').empty()
                $('.modalDesativarCategoriaLabel').append(categoria)
                $('#desativar_id_compra_categoria').empty()
                $('#desativar_id_compra_categoria').val(id_categoria)
            })
        });
    </script>

</body>

</html>