<?php 
    include_once('../const.php'); 

    $Captacao = new Captacao();

    if (isset($_GET['btnGerarCaptacaoGeral'])) {
        $data_inicio = $_GET['dataInicio'];
        $data_fim = $_GET['dataFim'];
        
        $url = "gerar_relatorio_captacao_geral.php?inicio=".$data_inicio."&fim=".$data_fim;

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

                        <h4>Relatórios Captação</h4>
                        <br>

                        <div class="row">

                            <div class="text-center offset-2 col-4">
                                <button class="btn btn-primary" data-toggle="modal" data-target="#modalCaptacaoGeral">Captação Geral</button>
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

    <!-- Modal Relatório Captação Geral -->
    <div class="modal fade" id="modalCaptacaoGeral" tabindex="1" role="dialog" aria-labelledby="modalCaptacaoGeralLabel" aria-hidden="true">
        <form action="?" method="get">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Gerar Relatório Geral Captação</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 text-center">
                                <p id="erroDatas" class="text-danger" style="display:none;">A data de fim não pode ser menor que a data de início.</p>
                            </div>
                            <div class="col-4 offset-2">
                                <div class="form-group">
                                    <label for="dataInicio">Data de Início</label>
                                    <input type="date" class="form-control" id="dataInicio" name="dataInicio" required>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="dataFim">Data de Fim</label>
                                    <input type="date" class="form-control" id="dataFim" name="dataFim" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success" id="btnGerar" name="btnGerarCaptacaoGeral" disabled>Gerar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script>
        
    </script>

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
            $('#cap').addClass('active');
            $('#captacao_relatorios').addClass('active');
        });
        document.addEventListener('DOMContentLoaded', function () {
        const dataInicio = document.getElementById('dataInicio');
        const dataFim = document.getElementById('dataFim');
        const btnGerar = document.getElementById('btnGerar');
        const erroDatas = document.getElementById('erroDatas');

        function validarDatas() {
            const inicio = new Date(dataInicio.value);
            const fim = new Date(dataFim.value);

            if (fim < inicio) {
                erroDatas.style.display = 'block';  // Mostrar mensagem de erro
                btnGerar.disabled = true;  // Desativar botão
            } else {
                erroDatas.style.display = 'none';  // Esconder mensagem de erro
                btnGerar.disabled = false;  // Ativar botão
            }
        }

        dataInicio.addEventListener('change', validarDatas);
        dataFim.addEventListener('change', validarDatas);
    });
    </script>

</body>
</html>
