<?php 
    include_once('../const.php'); 

    $Financeiro_Campanha = new Financeiro_Campanhas();

    if (isset($_POST['btnCadastrar'])) {
        $Financeiro_Campanha->cadastrar($_POST);
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

    <title>GRNacoes - Campanhas</title>

    <!-- Custom fonts for this template-->
    <link href="<?php echo URL ?>/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?php echo URL ?>/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php include_once('../sidebar.php'); ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php include_once('../topbar.php'); ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <br><br><br><br>

                    <div class="row">
                        <div class="col-2 mb-5">
                            <button class="btn btn-success" data-toggle="modal" data-target="#modalCadastrarCampanha" class="collapse-item">Nova Campanha</button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="offset-1">
                            <?php 
                                foreach($Financeiro_Campanha->listar() as $campanha){
                            ?>
                                <a href="campanha?c=<?php echo $campanha->id_financeiro_campanha ?>" class="btn btn-primary"><?php echo $campanha->nome ?></a>
                            <?php 
                                }
                            ?>
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

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>



    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Cadastrar Campanha -->
    <div class="modal fade" id="modalCadastrarCampanha" tabindex="1" role="dialog" aria-labelledby="modalCadastrarCampanhaLabel" aria-hidden="true">
        <form action="?" method="post">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cadastrar Nova Campanha</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-4 offset-1">
                                <input type="hidden" name="usuario_logado" value="<?php echo $_SESSION['id_usuario'] ?>">
                                <label for="nome" class="form-label">Nome da Campanha *</label>
                                <input type="text" name="nome" class="form-control" value="Campanha " required>
                            </div>
                            <div class="col-3">
                                <label for="periodo_inicio" class="form-label">Do dia</label>
                                <input type="date" class="form-control" name="periodo_inicio" id="cadastrar_periodo_inicio" required>
                            </div>
                            <div class="col-3">
                                <label for="periodo_fim" class="form-label">Até o dia</label>
                                <input type="date" class="form-control" name="periodo_fim" id="cadastrar_periodo_fim" required>
                            </div>
                            <div class="col-3 offset-1 mt-2">
                                <label for="data_pagamento" class="form-label">Data do Pagamento</label>
                                <input type="date" name="data_pagamento" id="cadastrar_data_pagamento" class="form-control" required>
                            </div>
                            <div class="col-3 mt-2">
                                <label for="data_pagamento_pos" class="form-label">Data do Pagamento PÓS</label>
                                <input type="date" name="data_pagamento_pos" id="cadastrar_data_pagamento_pos" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btnCadastrar" class="btn btn-success">Cadastrar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $('#finan').addClass('active');
            $('#financeiro_campanhas').addClass('active');

        });
    </script>

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo URL ?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo URL ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo URL ?>/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo URL ?>/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="<?php echo URL ?>/vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?php echo URL ?>/js/demo/chart-area-demo.js"></script>
    <script src="<?php echo URL ?>/js/demo/chart-pie-demo.js"></script>

</body>

</html>