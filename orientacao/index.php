<?php 
    include_once('../const.php');

    $Medico = new Medicos();
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>GRNacoes - Lançamento Cirurgia</title>

    <!-- Custom fonts for this template -->
    <link href="<?php echo URL ?>/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo URL ?>/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">

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

                    <br><br><br><br>

                    <form id="dataForm" class="row" method="POST">
                        <input type="hidden" name="id_usuario" value="<?php echo $_SESSION['id_usuario'] ?>">
                        <input type="hidden" name="id_empresa" value="<?php echo $_SESSION['id_empresa'] ?>">
                        <input type="hidden" name="captacao_cadastrar" value="1">
                        <div class="col-3">
                            <label class="form-label" for="name">Nome do Paciente:</label>
                            <input class="form-control" type="text" id="name" name="nome_paciente" required>
                        </div>
                        <div class="col-3">
                            <label class="form-label" for="medico">Médico:</label>
                            <select class="form-control" id="medico" name="id_medico" required>
                                <option value="">Selecione...</option>
                                <?php foreach($Medico->listar() as $medico){ ?>
                                    <option value="<?php echo $medico->id_medico ?>"><?php echo $medico->nome ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-3">
                            <label class="form-label" for="medico">Médico:</label>
                            <select class="form-control" id="medico" name="id_medico" required>
                                <option value="">Selecione...</option>
                                <?php foreach($Medico->listar() as $medico){ ?>
                                    <option value="<?php echo $medico->id_medico ?>"><?php echo $medico->nome ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-3" id="observacaoContainer">
                            <label class="form-label" for="observacao">Observação:</label>
                            <input class="form-control" type="text" id="observacao">
                        </div>
                    </form>


                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Cirurgias</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Hórario</th>
                                            <th>Empresa</th>
                                            <th>Captador</th>
                                            <th>Paciente</th>
                                            <th>Captado</th>
                                            <th>Motivo</th>
                                            <th>Médico</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
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

            <!-- Modal Mostrar Chamado -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        $('#ori').addClass('active');
        $('#orientacao_lanc').addClass('active');

        $(document).ready(function () {
            $('#modalEditar').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget);
                let id = button.data('id');
                
                $('#editar_id_captado').val(id);
            });

            $('#modalExcluir').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Botão que acionou o modal
                var id = button.data('id');

                $('#excluir_id_captado').val(id)
            });

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
    <script src="<?php echo URL ?>/vendor/datatables/jquery.dataTablesChamados.min.js"></script>
    <script src="<?php echo URL ?>/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?php echo URL ?>/js/demo/datatables-demo.js"></script>

</body>

</html>