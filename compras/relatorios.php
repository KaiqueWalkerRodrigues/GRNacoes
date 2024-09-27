<?php 
    include_once('../const.php'); 

    $Compras_Categorias = new Compras_Categorias();
    $Compras_Fornecedores = new Compras_Fornecedores();

    if (isset($_GET['btnGerarPorFornecedor'])) {
        $empresa = isset($_GET['empresa']) ? $_GET['empresa'] : 0;
        
        $url = "gerar_relatorio_por_fornecedor.php?empresa=".$empresa;

        header("Location: $url");
        exit(); 
    }

    if (isset($_GET['btnGerarPorCategoria'])) {
        $empresa = isset($_GET['empresa']) ? $_GET['empresa'] : 0;
        
        $url = "gerar_relatorio_por_categoria.php?empresa=".$empresa;

        header("Location: $url");
        exit(); 
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

    <title>GRNacoes - Gerar Relatórios</title>

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
                <div class="container-fluid text-center">

                    <!-- Page Heading -->

                    <br><br><br><br>

                        <h4>Relatórios Notas Fiscais</h4>
                        <br>

                        <div class="row">

                            <div class="text-center offset-2 col-4">
                                <button class="btn btn-primary" data-toggle="modal" data-target="#modalRelatorioPorFornecedor">Despesas por Fornecedor</button>
                            </div>
                            
                            <div class="text-center col-4">
                                <button class="btn btn-secondary" data-toggle="modal" data-target="#modalRelatorioPorCategoria">Despesas por Categoria</button>
                            </div>

                        </div>

                    <br><br>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Grupo Nações 2024</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Modal Relatório por Fornecedor -->
    <div class="modal fade" id="modalRelatorioPorFornecedor" tabindex="1" role="dialog" aria-labelledby="modalRelatorioPorFornecedorLabel" aria-hidden="true">
        <form action="?" method="get">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Imprimir o Relatório por Fornecedor</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6 offset-3">
                                <label for="empresa" class="form-label">Empresas:</label>
                                <select name="empresa" class="form-control" id="porfornecedor_empresa">
                                    <option value="0">Todas</option>
                                    <option value="1">Clínicas</option>
                                    <option value="2">Óticas</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success" name="btnGerarPorFornecedor">Gerar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Relatório por Categoria -->
    <div class="modal fade" id="modalRelatorioPorCategoria" tabindex="1" role="dialog" aria-labelledby="modalRelatorioPorCategoriaLabel" aria-hidden="true">
        <form action="?" method="get">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Imprimir o Relatório por Categoria</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6 offset-3">
                                <label for="empresa" class="form-label">Empresas:</label>
                                <select name="empresa" class="form-control" id="porcategoria_empresa">
                                    <option value="0">Todas</option>
                                    <option value="1">Clínicas</option>
                                    <option value="2">Óticas</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success" name="btnGerarPorCategoria">Gerar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

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
            $('#compras_relatorios').addClass('active');

            $('#modalRelatorioPorFornecedor').on('submit', 'form', function(event) {
                $('#modalRelatorioPorFornecedor').modal('hide'); // Fecha o modal
            });
        });
    </script>

</body>
</html>
