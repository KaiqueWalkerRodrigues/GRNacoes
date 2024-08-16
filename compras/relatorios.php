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
                <div class="container-fluid">

                    <!-- Page Heading -->

                    <br><br><br><br>

                        <div class="row">

                            <div class="text-center col-4 offset-2">
                                <a href="gerar_relatorio_fornecedor.php" class="btn btn-success">Gerar Relatório Anual por Fornecedor</a>
                            </div>

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

            // Função para remover variáveis GET da URL
            function removeGetParams() {
                var url = window.location.href;
                var index = url.indexOf('?');
                if (index > -1) {
                    url = url.substring(0, index);
                    window.history.replaceState(null, null, url);
                }
            }

            // Remove variáveis GET após o download
            window.addEventListener('load', function() {
                setTimeout(removeGetParams, 2000); // Ajuste o tempo de espera conforme necessário
            });
        });
    </script>

</body>
</html>
