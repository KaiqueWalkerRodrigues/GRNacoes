<?php include_once('../const.php');   ?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>GRNacoes - Início</title>

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

                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label for="filtroEmpresa" class="form-label">Filtrar por Empresa:</label>
                            <select id="filtroEmpresa" class="form-control">
                                <option value="">Todos</option>
                                <option value="Clínica Parque">Clínica Parque</option>
                                <option value="Clínica Mauá">Clínica Mauá</option>
                                <option value="Clínica Jardim">Clínica Jardim</option>
                                <option value="Ótica Matriz">Ótica Matriz</option>
                                <option value="Ótica Prestigio">Ótica Prestigio</option>
                                <option value="Ótica Daily">Ótica Daily</option>
                                <!-- Adicione opções para os Empresas -->
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filtroCampanha" class="form-label">Filtrar por Campanha:</label>
                            <select id="filtroCampanha" class="form-control">
                                <option value="">Todos</option>
                                <option value="Campanha57">Campanha 57</option>
                                <option value="Campanha56">Campanha 56</option>
                                <option value="Campanha55">Campanha 55</option>
                                <option value="Campanha54">Campanha 54</option>
                                <option value="Campanha53">Campanha 53</option>
                                <option value="Campanha52">Campanha 52</option>
                                <option value="Campanha51">Campanha 51</option>
                                <!-- Adicione opções para as Campanhas -->
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filtroVendedor" class="form-label">Filtrar por Vendedores:</label>
                            <select id="filtroVendedor" class="form-control">
                                <option value="">Todos</option>
                                <option value="Ana">Ana</option>
                                <option value="Alan">Alan</option>
                                <option value="Suzana">Suzana</option>
                                <!-- Adicione opções para os Vendedores -->
                            </select>
                        </div>
                        <div class="col-2 offset-10 text-center mt-3">
                            <button class="btn btn-primary">Gerar Relatório</button>
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

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $('#finan').addClass('active');
            $('#financeiro_relatorios').addClass('active');
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