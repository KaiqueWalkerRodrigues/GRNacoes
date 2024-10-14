<?php 
    include_once('../const.php');  

    $Compras_Fornecedores = new Compras_Fornecedores();

    $Compras_Categorias = new Compras_Categorias();

    if (isset($_POST['btnCadastrar'])) {
        $Compras_Fornecedores->cadastrar($_POST);
    }
    if (isset($_POST['btnEditar'])) {
        $Compras_Fornecedores->editar($_POST);
    }
    if (isset($_POST['btnDesativar'])) {
        $Compras_Fornecedores->desativar($_POST['id_compra_fornecedor'],$_POST['usuario_logado']);
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

    <title>GRNacoes - Gerenciar Setores</title>

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
                            <h6 class="m-0 font-weight-bold text-primary">Fornecedores Ativos | <button class="btn btn-primary" data-toggle="modal" data-target="#modalCadastrarFornecedor" class="collapse-item">Cadastrar Novo Fornecedor</button></h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Fornecedor</th>
                                            <th>Categoria</th>
                                            <th>Quantidade Notas</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($Compras_Fornecedores->listar() as $cf){ ?>
                                        <tr>
                                            <td><?php echo $cf->fornecedor ?></td>
                                            <td><?php echo $Compras_Categorias->nomeCategoria($cf->id_categoria) ?></td>
                                            <td><?php echo $Compras_Fornecedores->contarNotasPorFornecedor($cf->id_compra_fornecedor) ?></td>
                                            <td class="text-center">
                                            <button class="btn btn-secondary" data-toggle="modal" data-target="#modalEditarFornecedor" class="collapse-item"
                                                data-fornecedor="<?php echo $cf->fornecedor ?>"
                                                data-id_fornecedor="<?php echo $cf->id_compra_fornecedor ?>"
                                                data-id_categoria="<?php echo $cf->id_categoria ?>">
                                                    <i class="fa-solid fa-gear"></i>
                                                </button>
                                                <button class="btn btn-danger" data-toggle="modal" data-target="#modalDesativarFornecedor" class="collapse-item"
                                                    data-fornecedor="<?php echo $cf->fornecedor ?>"
                                                    data-id_fornecedor="<?php echo $cf->id_compra_fornecedor ?>"
                                                    data-id_categoria="<?php echo $cf->id_categoria?>">
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
                                <select name="id_categoria" id="cadastrar_id_categoria" class="form-control">
                                    <option value="">Selecione...</option>
                                    <?php foreach($Compras_Categorias->listar() as $cc){ ?>
                                        <option value="<?php echo $cc->id_compra_categoria ?>"><?php echo $cc->categoria ?></option>
                                    <?php } ?>
                                </select>
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
                                <select name="id_categoria" id="editar_id_categoria" class="form-control">
                                    <option value="">Selecione...</option>
                                    <?php foreach($Compras_Categorias->listar() as $cc){ ?>
                                        <option value="<?php echo $cc->id_compra_categoria ?>"><?php echo $cc->categoria ?></option>
                                    <?php } ?>
                                </select>
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

    <!-- Modal Desativar Fornecedor-->
    <div class="modal fade" id="modalDesativarFornecedor" tabindex="-1" role="dialog" aria-labelledby="modalDesativarFornecedorLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Desativar o Fornecedor: <span class="modalDesativarFornecedorLabel"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_compra_fornecedor" id="desativar_id_compra_fornecedor">
                        <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <div class="row">
                            <p>Deseja desativar o Fornecedor: <span class="modalDesativarFornecedorLabel"></span>?</p>
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
    <script src="<?php echo URL ?>/vendor/datatables/jquery.dataTablesFornecedores.min.js"></script>
    <script src="<?php echo URL ?>/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?php echo URL ?>/js/demo/datatables-demo.js"></script>

    <script>
        $(document).ready(function() {
            $('#comp').addClass('active');
            $('#compras_fornecedores').addClass('active');

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

            $('#modalDesativarFornecedor').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget)
                let id_fornecedor = button.data('id_fornecedor')
                let fornecedor = button.data('fornecedor')
                $('.modalDesativarFornecedorLabel').empty()
                $('.modalDesativarFornecedorLabel').append(fornecedor)
                $('#desativar_id_compra_fornecedor').empty()
                $('#desativar_id_compra_fornecedor').val(id_fornecedor)
            })
        });
    </script>

</body>

</html>