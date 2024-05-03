<?php 
    include_once('../const.php'); 

    $Compras_Categorias = new Compras_Categorias();
    $Compras_Fornecedores = new Compras_Fornecedores();
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

                        <!-- <form action="?" method="get">
                            <div class="row mb-4">
                                <div class="col-3">
                                    <label for="filtroEmpresa" class="form-label">Filtrar por Empresa:</label>
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
                                <div class="col-3">
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
                                <div class="col-3">
                                    <label for="filtroCategoria" class="form-label">Filtrar por Categoria:</label>
                                    <select id="filtroCategoria" class="form-control">
                                        <option value="">Todos</option>
                                        <?php foreach($Compras_Categorias->listar() as $cc){ ?>
                                            <option value="<?php echo $cc->id_compra_categoria ?>"><?php echo $cc->categoria ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="filtroFornecedor" class="form-label">Filtrar por Fornecedor:</label>
                                    <select id="filtroFornecedor" class="form-control">
                                        <option value="">Todos</option>
                                        <?php foreach($Compras_Fornecedores->listar() as $cf){ ?>
                                            <option value="<?php echo $cf->id_compra_fornecedor ?>"><?php echo $cf->fornecedor ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </form>

                        <hr> -->

                        <div class="row">

                            <!-- <div class="text-center col-4 offset-4">
                                <a href="gerar_relatorio_fornecedor.php" class="btn btn-secondary">Gerar Relatório Anual por Fornecedor</a>
                            </div> -->

                            <div class="text-center col-4">
                                <a href="gerar_relatorio_categoria.php" class="btn btn-primary">Gerar Relatório Anual por Categoria</a>
                            </div>

                        </div>

                        <?php 
                            if (isset($_GET['s'])) {
                                // Se estiver presente, exibe o botão para baixar o relatório
                                echo '<a id="downloadButton" href="gerar_relatorio_categorias_2024.xlsx" class="btn btn-success" download style="display:none;">Baixar Relatório</a>';
                            }
                        ?>
                        <?php 
                            if (isset($_GET['f'])) {
                                // Se estiver presente, exibe o botão para baixar o relatório
                                echo '<a id="downloadButton" href="gerar_relatorio_fornecedores_2024.xlsx" class="btn btn-success" download style="display:none;">Baixar Relatório</a>';
                            }
                        ?>

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

            function autoDownload() {
                // Simulando o clique no botão de download
                document.getElementById('downloadButton').click();
            }

            // Chamando a função autoDownload assim que a página for carregada
            window.onload = autoDownload;
    });
    </script>

</body>
</html>