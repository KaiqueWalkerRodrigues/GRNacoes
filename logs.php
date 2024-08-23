<?php 
    include_once('const.php');
    
    $Usuario = new Usuario();
    $Log = new Log();
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>GRNacoes - Chamados</title>

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include_once('sidebar.php'); ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include_once('topbar.php'); ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Logs</h1>
                    </div>

                    <br>

                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label for="filtroData" class="form-label">Filtrar por Data:</label>
                            <input type="date" id="filtroData" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label for="filtroTipo" class="form-label">Filtrar por Tipo:</label>
                            <select id="filtroTipo" class="form-control">
                                <option value="">Todos</option>
                                <option value="Cadastro de Usuário">Cadastro de Usuário</option>
                                <option value="Exclusão de Usuário">Exclusão de Usuário</option>
                                <!-- Adicione opções para os tipos -->
                            </select>
                        </div>
                    </div>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Logs</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Data</th>
                                            <th>Hora</th>
                                            <th>Tipo</th>
                                            <th>Usuário</th>
                                            <td>Descrição</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($Log->listar() as $log){ $data = new DateTime($log->data); ?>
                                        <tr>
                                            <td><?php echo $log->id_log ?></td>
                                            <td><?php $dia = $data->format("d/m/Y"); echo $dia; ?></td>
                                            <td><?php $hora = $data->format("H:i"); echo $hora; ?></td>
                                            <td><?php echo $log->acao ?></td>
                                            <td><?php echo $Usuario->mostrarUsuario($log->id_usuario) ?></td>
                                            <td><?php echo $log->descricao ?></td>
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
                        <span>Copyright &copy; Grupo Nações 2024</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

            <!-- Modal encaminhar -->
            <div class="modal fade" id="modalEncaminhar" tabindex="1" role="dialog" aria-labelledby="modalencaminharLabel" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Encaminhar Chamado</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container">
                                <label for="encaminhar" class="form-label">Encaminhar para:</label>
                                <select name="encaminhar" id="encaminhar" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <option value="ti">TI</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-info">Encaminhar</button>
                        </div>
                    </div>
                </div>
            </div>


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
            $('#logs').addClass('active');


            // Evento de alteração nos filtros
            $('#filtroData, #filtroTipo, #filtroUsuario').change(function() {
                var filtroData = $('#filtroData').val();
                var filtroTipo = $('#filtroTipo').val();

                if(filtroData != ''){
                    var partesData = filtroData.split('-');  // Divide a string de data em partes [aaaa, mm, dd]
                    var dataFormatada = partesData[2] + '/' + partesData[1] + '/' + partesData[0];  // Reorganiza para o formato dd/mm/aaaa
                }

                // Mostrar todas as linhas inicialmente
                $('#dataTable tbody tr').show();

                // Iterar sobre as linhas da tabela para aplicar os filtros
                $('#dataTable tbody tr').each(function() {
                    var Data = $(this).find('td:eq(0)').text();
                    var Tipo = $(this).find('td:eq(2)').text();
                    
                    if(filtroData != ''){
                        // Verificar se a linha atende aos critérios de filtragem
                        if ((dataFormatada && Data !== dataFormatada) ||
                            (filtroTipo && Tipo !== filtroTipo)) {
                            $(this).hide(); // Ocultar a linha se não atender aos critérios
                        }
                    }else{
                        if ((filtroTipo && Tipo !== filtroTipo)) {
                            $(this).hide(); // Ocultar a linha se não atender aos critérios
                        }
                    }
                });
                
            });
        });

    </script>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTablesLogs.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

</body>

</html>